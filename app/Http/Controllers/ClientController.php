<?php

namespace App\Http\Controllers;

use App\Models\Advocate;
use App\Models\group;
use App\Models\Order;
use App\Models\Plan;
use App\Models\Hearing;
use App\Models\PointOfContacts;
use App\Models\User;
use App\Models\Bill;
use App\Models\Fee;
use App\Models\UserDetail;
use App\Models\Utility;
use Database\Seeders\UserSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use DB;
use Carbon\Carbon;
class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (Auth::user()->can('manage member') || Auth::user()->can('manage user')) {

            $users = User::where('created_by', '=', Auth::user()->creatorId())
                    ->where('type','client')
                    ->get();

            $user_details = UserDetail::get();

            return view('client.index', compact('users', 'user_details'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    public function userList()
    {

        if (Auth::user()->can('manage member') || Auth::user()->can('manage user')) {

            $users = User::where('created_by', '=', Auth::user()->creatorId())->where('type','client')->get();
            $user_details = UserDetail::get();

            return view('client.list', compact('users', 'user_details'));

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

            $roles = Role::where('created_by', Auth::user()->creatorId())->where('id', '!=', Auth::user()->id)->where('name','!=','Advocate')->get()->pluck('name', 'id');

            return view('client.create', compact('roles'));
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
        if (Auth::user()->can('create member') || Auth::user()->can('create user')) {

            if (Auth::user()->type == 'company') {
                $validator = Validator::make(
                    $request->all(), [
                        'name' => 'required|max:120',
                        'email' => 'nullable|email|unique:users',
                        'password' => 'required|min:8',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $user = Auth::user();
                $user = new User();
                $user['name'] = $request->name;
                $user['email'] = $request->email;
                $user['password'] = Hash::make($request->password);
                $user['lang'] = 'en';
                $user['created_by'] = Auth::user()->creatorId();
                $user['email_verified_at'] = date('Y-m-d H:i:s');

                $user->assignRole('client');
                $user['type'] = 'client';

                $user->save();

                $detail = new UserDetail();
                $detail->user_id = $user->id;
                $detail->save();

                return redirect()->route('client.index')->with('success', __('Member successfully created.'));


            } else {

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

                $user = new User();
                $user['name'] = $request->name;
                $user['email'] = $request->email;
                $user['password'] = Hash::make($request->password);
                $user['lang'] = 'en';
                $user['created_by'] = Auth::user()->creatorId();
                $user['plan'] = Plan::first()->id;

                if (Utility::settings()['email_verification'] == 'off') {
                   $user['email_verified_at'] = date('Y-m-d H:i:s');
                }

                $role_r = Role::findByName('company');
                $user->assignRole($role_r);
                $user['type'] = 'company';

                $user->save();

                $detail = new UserDetail();
                $detail->user_id = $user->id;
                $detail->save();

                //create company default roles
                $user->MakeRole($user->id);

                return redirect()->route('users.index')->with('success', __('Member successfully created.'));

            }

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($user_id)
    {


        $user= User::where('id', $user_id)->first();

        if ($user) {

            $case = DB::table("cases")
                        ->select("cases.*")
                        ->get();
            $cases=[];
            foreach($case as $value)
            {
               $data=json_decode($value->your_party_name);
               foreach($data as $key => $val)
               {
                   if(isset($val->clients)&& $val->clients ==$user->id)
                   {
                        $hearings = Hearing::where('case_id',$value->id)->get();
                        $value->hearings=$hearings;
                        $cases[$value->id]=$value;
                   }
               }
            }
            $bills = Bill::where('bill_to',$user->id)->get();
             $fees = Fee::where('member',$user->id)->get();
            return view('client.view', compact('user','cases','bills','fees'));
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

        $user = User::find($id);
        $user_detail = UserDetail::where('user_id', $user->id)->first();
        $roles = Role::where('created_by', '=', $user->creatorId())->get()->pluck('name', 'id');
        $advocate = $contacts = [];

        if(Auth::user()->type == 'advocate'){
            $advocate = Advocate::where('user_id',$user->id)->first();
            $contacts = PointOfContacts::where('advocate_id',$advocate->id)->get();
        }
        return view('users.edit', compact('user', 'roles', 'user_detail','advocate','contacts'));

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
        if (!empty($request->mobile_number)) {

            $validator = Validator::make(
                $request->all(), [
                    'mobile_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
                ]
            );
        }

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        $user = User::find($id);

        if ($user) {
            if (Auth::user()->type == 'advocate') {

                $adv = Advocate::where('user_id',$user->id)->first();

                if ($adv) {


                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();

                        return redirect()->back()->with('error', $messages->first());
                    }

                    $advocate = Advocate::find($adv->id);
                    $userAdd = $advocate->getAdvUser($advocate->user_id);

                    if ($userAdd->email != $request->email) {

                        $users = User::where('email',$request->email)->first();
                        if (!empty($users)) {
                            return redirect()->back()->with('error', __('Email address already exist.'));
                        }
                    }

                    $advocate['phone_number'] = !empty($request->phone_number) ? $request->phone_number : NULL;
                    $advocate['age'] = !empty($request->age) ? $request->age : NULL;
                    $advocate['company_name'] = $request->company_name;
                    $advocate['website'] = $request->website;
                    $advocate['ofc_address_line_1'] = $request->ofc_address_line_1;
                    $advocate['ofc_address_line_2'] = $request->name;
                    $advocate['ofc_country'] = $request->ofc_country;
                    $advocate['ofc_state'] = !empty($request->ofc_state) ? $request->ofc_state : NULL;
                    $advocate['ofc_city'] = $request->ofc_city;
                    $advocate['ofc_zip_code'] = !empty($request->ofc_zip_code) ? $request->ofc_zip_code : NULL;
                    $advocate['home_address_line_1'] = $request->home_address_line_1;
                    $advocate['home_address_line_2'] = $request->home_address_line_2;
                    $advocate['home_country'] = $request->home_country;
                    $advocate['home_state'] = $request->home_state;
                    $advocate['home_city'] = $request->home_city;
                    $advocate['home_zip_code'] = !empty($request->home_zip_code) ? $request->home_zip_code : NULL;
                    $advocate->save();

                    $userAdd->name = $request->name;
                    $userAdd->email = $request->email;

                    if ($request->hasFile('profile')) {
                        $filenameWithExt = $request->file('profile')->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $request->file('profile')->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $dir = 'uploads/profile/';
                        $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->route('users.index', Auth::user()->id)->with('error', __($path['msg']));
                        }

                        $userAdd->avatar = $fileNameToStore;
                    }

                    $userAdd->save();

                    return redirect()->back()->with('success', __('Successfully Updated!'));

                }else{
                    return redirect()->back()->with('error', __('Advocate not found.'));

                }


            }else{
                $user['name'] = $request->name;
                $user['email'] = $request->email;

                if ($request->hasFile('profile')) {
                    if (Auth::user()->type == 'super admin') {
                        $filenameWithExt = $request->file('profile')->getClientOriginalName();
                        $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension = $request->file('profile')->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $settings = Utility::Settings();
                        $url = '';
                        $dir = 'uploads/profile/';
                        $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->route('users.index', Auth::user()->id)->with('error', __($path['msg']));
                        }

                        $user->avatar = $fileNameToStore;
                    }else{
                        $dir        = 'uploads/profile/';
                        $file_path = $dir.$user['avatar'];
                        $image_size = $request->file('profile')->getSize();

                        $result = Utility::updateStorageLimit(Auth::user()->id, $image_size);

                        if($result==1) {

                            Utility::changeStorageLimit(Auth::user()->id, $file_path);
                            $filenameWithExt = $request->file('profile')->getClientOriginalName();
                            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                            $extension = $request->file('profile')->getClientOriginalExtension();
                            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                            $settings = Utility::Settings();
                            $url = '';
                            $dir = 'uploads/profile/';
                            $path = Utility::upload_file($request, 'profile', $fileNameToStore, $dir, []);

                            if ($path['flag'] == 1) {
                                $url = $path['url'];
                            } else {
                                return redirect()->route('users.index', Auth::user()->id)->with('error', __($path['msg']));
                            }

                            $user->avatar = $fileNameToStore;
                        }
                    }


                }

                $user->update();

                $detail = UserDetail::where('user_id', $user->id)->first();

                $detail->mobile_number = !empty($request->mobile_number) ? $request->mobile_number : null;
                $detail->address = $request->address;
                $detail->city = $request->city;
                $detail->state = $request->state;
                $detail->zip_code = !empty($request->zip_code) ? $request->zip_code : null;
                $detail->landmark = $request->landmark;
                $detail->about = $request->about;

                $detail->save();

                return redirect()->route('users.index')->with('success', __('Successfully Updated!'));
            }


        } else {
            return redirect()->back()->with('error', __('Member not found.'));

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
        if (Auth::user()->can('delete member') || Auth::user()->can('delete user')) {
            $user = User::find($id);
            $detail = UserDetail::where('user_id', $user->id)->first();

            if ($user->created_by != Auth::user()->creatorId()) {
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

                    return redirect()->back()->with('success', __('Member deleted successfully.'));
                }
            }
        } else {
            return redirect()->back()->with('error', __('Member not found.'));
        }
    }

    public function changeMemberPassword(Request $request, $id)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required|same:confirm_password',
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $objUser = User::find($id);
        $objUser->password = Hash::make($request->password);
        $objUser->save();

        return redirect()->back()->with('success', __('Password updated successfully.'));

    }

    public function companyPassword($id)
    {
        $eId   = Crypt::decrypt($id);
        $user  = User::find($eId);

        $employee = User::where('id', $eId)->first();

        return view('users.reset', compact('user', 'employee'));
    }

    public function upgradePlan($user_id)
    {
        $user  = User::find($user_id);
        $plans = Plan::get();
        $admin_payment_setting = Utility::settings();
        return view('users.plan', compact('user', 'plans','admin_payment_setting'));
    }

    public function activePlan($user_id, $plan_id)
    {
        $user       = User::find($user_id);
        $user->plan = $plan_id;
        $user->save();
        $assignPlan = $user->assignPlan($plan_id);
        $plan       = Plan::find($plan_id);

        if($assignPlan['is_success'] == true && !empty($plan))
        {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            Order::create(
                [
                    'order_id' => $orderID,
                    'name' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $plan->price,
                    'price_currency' => env('CURRENCY'),
                    'txn_id' => '',
                    'payment_type' => __('Manually Upgrade By Super Admin'),
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $user->id,
                ]
            );
        }

        return redirect()->back()->with('success', __('Plan successfully activated.'));
    }

}
