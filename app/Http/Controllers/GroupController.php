<?php

namespace App\Http\Controllers;

use App\Models\group;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage group')) {

            $groups = Group::with('creator')->where('created_by', Auth::user()->creatorId())->get();
            return view('group.index',compact('groups'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('create group')) {
            $users = User::where('created_by',Auth::user()->creatorId())->whereNot('type','client')->get()->pluck('name','id');

            return view('group.create',compact('users'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('create group')) {

            $validator = Validator::make(
                $request->all(), [
                    'name' => 'required|max:120|unique:groups',
                    'members' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $grp = new group();
            $grp['name'] = $request->name;
            $grp['members'] = implode(',',$request->members);
            $grp['created_by'] = Auth::user()->creatorId();
            $grp['assigned_at'] = date('j F, Y H:i');
            $grp->save();

            foreach ($request->members as $key => $member) {
                $user = UserDetail::where('user_id',$member)->first();
                if (!empty($user)) {
                    $user['my_group'] = $user['my_group'] .','. $grp->id;
                    $user->save();
                }
            }

            return redirect()->back()->with('success', __('Group successfully created.'));

        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (Auth::user()->can('show group')) {
            $grp = group::find($id)->members;
            if ($grp) {
                $data = explode(',', $grp);
                $my_members = User::whereIn('id', $data)->get();
                return view('group.view', compact('my_members'));
            }


        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));

        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('edit group')) {
            $grp = group::find($id);
            if ($grp) {

                $data = explode(',', $grp->members);

                $my_members = User::whereIn('id', $data)->get()->pluck('id');

                $users = User::where('created_by', Auth::user()->creatorId())->whereNot('type','client')->get()->pluck('name', 'id');

                return view('group.edit', compact('grp','my_members','users'));
            }

        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit group')) {
            $grp = group::find($id);
            if ($grp) {

                $validator = Validator::make(
                    $request->all(), [
                        'name' => 'required|max:120',
                        'members' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }


                $grp['name'] = $request->name;
                $grp['members'] = implode(',', $request->members);
                $grp['created_by'] = Auth::user()->creatorId();
                $grp['assigned_at'] = date('j F, Y H:i');
                $grp->save();

                foreach ($request->members as $key => $member) {
                    $user = UserDetail::where('user_id', $member)->first();
                    if (!empty($user)) {
                        $user['my_group'] = $user['my_group'] . ',' . $grp->id;
                        $user->save();
                    }
                }
                return redirect()->back()->with('success', __('Group successfully updated.'));

            }else{

                return redirect()->back()->with('error', __('Group not found.'));
            }

        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));

        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('edit group')) {
            $grp = group::find($id);
            if ($grp) {

                $data = explode(',', $grp->members);

                $grp_members = UserDetail::whereIn('user_id', $data)->get();

                foreach ($grp_members as $key => $value) {

                    if (str_contains($value->my_group, $grp->id)) {

                        $value->my_group = trim($value->my_group, $grp->id);
                        $value->my_group = ltrim($value->my_group,',');
                        $value->my_group = rtrim($value->my_group,',');
                        $value->save();
                    }

                }
                $grp->delete();

                return redirect()->back()->with('success', __('Group successfully deleted.'));
            }else{

                return redirect()->back()->with('error', __('Group not found.'));
            }
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
