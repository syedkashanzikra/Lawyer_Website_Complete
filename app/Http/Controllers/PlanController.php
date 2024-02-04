<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\PlanRequest;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class PlanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage plan') || Auth::user()->can('buy plan')) {
            $plans = Plan::all();
            $payment_setting = Utility::set_payment_settings();
            $settings = Utility::settings(Auth::user()->id);

            return view('plan.index', compact('plans', 'payment_setting'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('create plan')) {
            $arrDuration = Plan::$arrDuration;

            return view('plan.create', compact('arrDuration'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        if (Auth::user()->can('create plan')) {
            $admin_payment_setting = Utility::set_payment_settings();

            if (!empty($admin_payment_setting) && ($admin_payment_setting['is_stripe_enabled'] == 'on' || $admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on' || $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on' || $admin_payment_setting['is_paytm_enabled'] == 'on' || $admin_payment_setting['is_mollie_enabled'] == 'on' || $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on' || $admin_payment_setting['is_paymentwall_enabled'] == 'on' || $admin_payment_setting['is_manually_enabled'] == 'on' || $admin_payment_setting['is_bank_enabled'] == 'on' || $admin_payment_setting['is_paytab_enabled'] == 'on' ))
            {
                $validator = Validator::make(
                    $request->all(), [
                        'name' => 'required|unique:plans',
                        'price' => 'required|numeric|min:0',
                        'duration' => 'required',
                        'max_users' => 'required|numeric',
                        'max_advocates' => 'required|numeric',
                        'storage_limit'  => 'required|numeric'
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $post = $request->all();

                if (Plan::create($post)) {
                    return redirect()->back()->with('success', __('Plan Successfully created.'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }

            } else {
                return redirect()->back()->with('error', __('Please set stripe or paypal api key & secret key for add new plan.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('edit plan')) {
            $arrDuration = Plan::$arrDuration;
            $plan = Plan::find($id);

            return view('plan.edit', compact('plan', 'arrDuration'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $plan_id)
    {
        if (Auth::user()->can('edit plan')) {
            $payment = Utility::set_payment_settings();

            if (count($payment) > 0) {
                $plan = Plan::find($plan_id);
                if (!empty($plan)) {
                    $validation = [];
                    $validation['name'] = 'required|unique:plans,name,' . $plan_id;
                    $validation['price'] = 'required|numeric|min:0';
                    $validation['duration'] = 'required';
                    $validation['max_users'] = 'required|numeric';
                    $validation['max_advocates'] = 'required|numeric';
                    $validation['storage_limit'] = 'required|numeric';
                    $request->validate($validation);

                    $post = $request->all();

                    if ($plan->update($post)) {
                        return redirect()->back()->with('success', __('Plan Successfully updated.'));
                    } else {
                        return redirect()->back()->with('error', __('Something is wrong.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('Plan not found.'));
                }
            } else {
                return redirect()->back()->with('error', __('Please set payment api key & secret key for update plan'));
            }

        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
        //
    }

    public function payment($code)
    {
        $plan_id = \Illuminate\Support\Facades\Crypt::decrypt($code);
        $plan    = Plan::find($plan_id);
        $planReqs = PlanRequest::where('user_id',Auth::user()->id)->where('plan_id',$plan_id)->first();

        if($plan)
        {
            $admin_payment_setting = Utility::payment_settings();
            return view('payment', compact('plan','admin_payment_setting','planReqs'));
        }
        else
        {
            return redirect()->back()->with('error', __('Plan is deleted.'));
        }
    }
}
