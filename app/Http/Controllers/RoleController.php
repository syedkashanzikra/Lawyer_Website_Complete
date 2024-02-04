<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Validator;
class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage role')) {
            $user = Auth::user();

            // $roles = Role::where('name','!=','company')->where('created_by',$user->id)->get();

            $roles = Role::where('name', '!=', 'company')
                    ->where('created_by', $user->id)
                    ->with('permissions')
                    ->get();

            return view('role.index',compact('roles'));
        }else{
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

        $permissions = Permission::all()->pluck('name', 'id')->toArray();

        return view('role.create',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
         $validator = Validator::make(
            $request->all(), [
                'name' => 'required|unique:roles',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        if (empty($request['permissions'])) {
            return redirect()->back()->with('error', __('Please select atleast one permission.'));
        }

        $role             = new Role();
        $role->name       = $request->name;
        $role->created_by = Auth::user()->id;

        $permissions      = $request['permissions'];
        $role->save();

        foreach($permissions as $permission)
        {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            $role->givePermissionTo($p);
        }


        return redirect()->back()->with('success', __('Role successfully created.'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all()->pluck('name', 'id')->toArray();
        return view('role.edit',compact('permissions','role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        if(Auth::user()->can('edit role')){
            $input       = $request->except(['permissions']);
            $permissions = $request['permissions'];
            $role->fill($input)->save();

            $p_all = Permission::all();

            foreach($p_all as $p)
            {
                $role->revokePermissionTo($p);
            }

            if (!empty($permissions)) {
                foreach($permissions as $permission)
                {
                    $p = Permission::where('id', '=', $permission)->firstOrFail();
                    $role->givePermissionTo($p);
                }
            }
            return redirect()->back()->with('success', __('Role successfully updated.'));
        }else
        {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $users = User::where('type',$role->name)->get();
        if (count($users) > 0) {

            return redirect()->back()->with('error', __('The role is assigned to users.'));
        }else{
            $role->delete();
            return redirect()->back()->with('success', __('Role successfully deleted.'));
        }
    }
}
