<?php

namespace App\Http\Controllers;

use App\Models\Advocate;
use App\Models\group;
use App\Models\Order;
use App\Models\Plan;
use App\Models\PointOfContacts;
use App\Models\User;
use App\Models\Cases;
use App\Models\UserDetail;
use App\Models\Utility;
use Database\Seeders\UserSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id="")
    {

        if (Auth::user()->can('manage member') || Auth::user()->can('manage user')) {


                $employee = User::where('created_by', '=', Auth::user()->creatorId())
                        ->where('super_admin_employee',1)
                        ->get();

                $user_details = UserDetail::get();

            return view('employee.index', compact('employee', 'user_details'));

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
        if (Auth::user()->can('create member') || Auth::user()->can('create user')) {
            $permissions=$this->permission_arr();

            return view('employee.create',compact('permissions'));
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

        $validator = Validator::make(
            $request->all(), [
                'name' => 'required|max:120',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:8',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $permissions=$this->permission_arr();
        $permission_arr=[];

        if($request->permissions){
            foreach($permissions as $key => $permission)
            {
            foreach ($request->permissions as $ke => $value) {
                    if($key==$value)
                    {
                        $permission_arr[$key]=$permission;
                    }
            }
            }
        }else{
            return redirect()->back()->with('error', __('Atleast one permission is required.'));
        }

        $user = new User();
        $user['name'] = $request->name;
        $user['email'] = $request->email;
        $user['password'] = Hash::make($request->password);
        $user['type'] ='superAdminEmployee';
        $user['super_admin_employee'] =1;
        $user['permission_json'] = json_encode($permission_arr);
        $user['lang'] = 'en';
        $user['created_by'] = Auth::user()->creatorId();
        if (Utility::settings()['email_verification'] == 'off') {
           $user['email_verified_at'] = date('Y-m-d H:i:s');
        }
        $user->save();
        $detail = new UserDetail();
        $detail->user_id = $user->id;
        $detail->save();
        return redirect()->route('employee.index')->with('success', __('Employee successfully created.'));
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
    public function permission_arr()
    {
        $arr=[

            1 =>'create user',
            2 =>'edit user',
            3 =>'delete user',
            4 =>'manage user',
            5 =>'manage crm',
            6 =>'manage support ticket'
        ];
        return $arr;
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('create member') || Auth::user()->can('create user')) {
            $permissions=$this->permission_arr();
            $user=User::where('id',$id)->first();
            return view('employee.edit',compact('permissions','user'));
        } else {
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

        $validator = Validator::make(
            $request->all(), [
                'name' => 'required|max:120',
                'email' => 'required|email',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        $permissions=$this->permission_arr();
        $permission_arr=[];

        foreach($permissions as $key => $permission)
        {
           foreach ($request->permissions as $ke => $value) {
                if($key==$value)
                {
                    $permission_arr[$key]=$permission;
                }
           }
        }

        $user =User::where('id',$id)->first();
        $user['name'] = $request->name;
        $user['email'] = $request->email;
        $user['permission_json'] = json_encode($permission_arr);

        $user->save();

        return redirect()->route('employee.index')->with('success', __('Employee successfully Updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $premission=[];
        if(\Auth::user()->super_admin_employee==1)
        {
            $premission=json_decode(\Auth::user()->permission_json);
            $premission_arr = get_object_vars($premission);
        }

        if ((Auth::user()->can('delete member') || Auth::user()->can('delete user')) || (Auth::user()->super_admin_employee==1 && in_array("delete user", $premission_arr))) {

            $user = User::find($id);
            $detail = UserDetail::where('user_id', $user->id)->first();


            if ($user->created_by != Auth::user()->creatorId() && Auth::user()->type!='super admin') {
                return redirect()->back()->with('error', __('You cant delete yourself.'));
            } else {
                if ($user && $detail) {
                    $user->delete();
                    $detail->delete();

                    $data = explode(',', $detail->my_group);
                    $my_groups = group::whereIn('id', $data)->get();

                    foreach ($my_groups as $key => $value) {
                        if (str_contains($value->members, $detail->user_id)) {
                            $value->members = trim($value->members, $detail->user_id);
                            $value->save();
                        }
                    }

                    return redirect()->back()->with('success', __('Employee deleted successfully.'));
                }
            }
        } else {
            return redirect()->back()->with('error', __('Employee not found.'));
        }
    }
}
