<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use CoinGate\CoinGate;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CoingatePaymentController extends Controller
{
    public $mode;
    public $coingate_auth_token;
    public $is_enabled;
    public $currancy;

    public function planpaymentSetting()
    {
        $admin_payment_setting = Utility::payment_settings();
        $this->currancy =isset($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'';
        $this->coingate_auth_token = isset($admin_payment_setting['coingate_auth_token'])?$admin_payment_setting['coingate_auth_token']:'';
        $this->mode = isset($admin_payment_setting['coingate_mode'])?$admin_payment_setting['coingate_mode']:'off';
        $this->is_enabled = isset($admin_payment_setting['is_coingate_enabled'])?$admin_payment_setting['is_coingate_enabled']:'off';
        return $this;
    }

    public function invoicePayWithCoingate(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'amount' => 'required',
                'invoice_id' => 'required',

            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $invoiceID = decrypt($request->invoice_id);
        $invoice = Bill::find($invoiceID);
        $data =Utility::getCompanyPaymentSetting($invoice->created_by);

        $this->currancy = isset($data['site_currency']) ? $data['site_currency'] : 'USD';
        $this->coingate_auth_token = isset($data['coingate_auth_token']) ? $data['coingate_auth_token'] : '';
        $this->mode = isset($data['coingate_mode']) ? $data['coingate_mode'] : 'off';
        $this->is_enabled = isset($data['is_coingate_enabled']) ? $data['is_coingate_enabled'] : 'off';

        CoinGate::config(
            array(
                'environment' => $this->mode,
                'auth_token' => $this->coingate_auth_token,
                'curlopt_ssl_verifypeer' => false,
            )
        );

        $post_params = array(
            'order_id' => time(),
            'price_amount' => $request->amount,
            'price_currency' => $this->currancy,
            'receive_currency' => $this->currancy,
            'callback_url' => route('invoice.coingate', [encrypt($invoice->id)]),
            'cancel_url' => route('invoice.coingate', [encrypt($invoice->id)]),
            'success_url' => route(
                'invoice.coingate',
                [
                    encrypt($invoice->id),
                    'success=true',
                ]
            ),
            'title' => 'Plan #' . time(),
        );
        $order = \CoinGate\Merchant\Order::create($post_params);
        if ($order) {
            $request->session()->put('invoice_data', $post_params);
            return redirect($order->payment_url);
        } else {
            return redirect()->back()->with('error', __('Opps something wren wrong.'));
        }
    }

    public function getInvoicePaymentStatus($invoice_id, Request $request)
    {
        if (!empty($invoice_id)) {
            $invoice_id = decrypt($invoice_id);
            $invoice = Bill::find($invoice_id);

            $data = Utility::getCompanyPaymentSetting($invoice->created_by);

            $invoice_data = $request->session()->get('invoice_data');
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            if ($invoice && !empty($invoice_data)) {
                try {
                    if ($request->has('success') && $request->success == 'true') {



                            $payments = new BillPayment();
                            $payments['bill_id'] = $invoice->id;
                            $payments['date'] = date('Y-m-d');
                            $payments['amount'] = isset($invoice_data['price_amount']) ? $invoice_data['price_amount'] : 0;
                            $payments['method'] = __('COINGATE');
                            $payments['order_id'] = $orderID;
                            $payments['currency'] = $data['site_currency'];
                            $payments['note'] = $invoice->description;
                            $payments['txn_id'] = isset($request->transaction_id) ? $request->transaction_id : '';
                            $payments->save();


                        $payment = BillPayment::where('bill_id', $invoice_id)->sum('amount');

                        if ($payment >= $invoice->total_amount) {
                            $invoice->status = 'PAID';
                            $invoice->due_amount = 0.00;
                        } else {
                            $invoice->status = 'Partialy Paid';
                            $invoice->due_amount = $invoice->due_amount - isset($invoice_data['price_amount']) ? $invoice_data['price_amount'] : 0;
                        }

                        $invoice->save();

                        $request->session()->forget('invoice_data');
                        if (Auth::check()) {
                            return redirect()->route('invoices.show', $invoice_id)->with('success', __('Invoice paid Successfully!'));
                        } else {
                            return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', __('Invoice paid Successfully!'));
                        }
                    } else {
                        return redirect()->back()->with('error', __('Transaction fail.'));
                    }
                } catch (Exception $e) {
                    return redirect()->back()->with('error', $e->getMessage());
                }
            } else {
                return redirect()->back()->with('error', __('Transaction fail.'));

            }
        } else {
            return redirect()->back()->with('error', __('Transaction fail.'));
        }
    }

    public function planPayWithCoingate(Request $request)
    {
        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $coupons_id ='';

        if($plan)
        {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->price;

            if(isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                if(!empty($coupons))
                {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if($usedCoupun >= $coupons->limit)
                    {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                }
                else
                {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    $orderID = time();
                    Order::create(
                        [
                            'order_id' => $orderID,
                            'name' => null,
                            'email' => null,
                            'card_number' => null,
                            'card_exp_month' => null,
                            'card_exp_year' => null,
                            'plan_name' => $plan->name,
                            'plan_id' => $plan->id,
                            'price' => $price==null?0:$price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => 'coingate',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $assignPlan = $authuser->assignPlan($plan->id, $request->coingate_payment_frequency);
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Plan fail to upgrade.'));
                }
            }
            try{
                CoinGate::config(
                    array(
                        'environment' => $this->mode,
                        'auth_token' => $this->coingate_auth_token,
                        'curlopt_ssl_verifypeer' => FALSE
                    )
                );
                $post_params = array(
                    'order_id' => time(),
                    'price_amount' => $price,
                    'price_currency' => $this->currancy,
                    'receive_currency' => $this->currancy,
                    'callback_url' => route('plan.coingate',[$request->plan_id,'payment_frequency='.$request->coingate_payment_frequency,'coupon_id='.$coupons_id]),
                    'cancel_url' => route('plans.index',[$request->plan_id]),
                    'success_url' => route('plan.coingate',[$request->plan_id,'payment_frequency='.$request->coingate_payment_frequency,'coupon_id='.$coupons_id]),
                    'title' => 'Plan #' . time(),
                );

                $order = \CoinGate\Merchant\Order::create($post_params);

                if($order)
                {
                    return redirect($order->payment_url);
                }
                else
                {
                    return redirect()->back()->with('error', __('opps something wren wrong.'));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }
    }

    public function getPaymentStatus(Request $request,$plan)
    {
        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $price                  = $plan->{$request->payment_frequency . '_price'};
        $user = Auth::user();
        $orderID = time();
        if($plan)
        {
            if($request->has('coupon_id') && $request->coupon_id != '')
            {
                $coupons = Coupon::find($request->coupon_id);
                $discount_value         = ($plan->price / 100) * $coupons->discount;
                $discounted_price = $plan->price - $discount_value;

                if(!empty($coupons))
                {
                    $userCoupon         = new UserCoupon();
                    $userCoupon->user   = $user->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order  = $orderID;
                    $userCoupon->save();

                    $usedCoupun = $coupons->used_coupon();
                    if($coupons->limit <= $usedCoupun)
                    {
                        $coupons->is_active = 0;
                        $coupons->save();
                    }
                }
            }

            $order                 = new Order();
            $order->order_id       = $orderID;
            $order->name           = $user->name;
            $order->card_number    = '';
            $order->card_exp_month = '';
            $order->card_exp_year  = '';
            $order->plan_name      = $plan->name;
            $order->plan_id        = $plan->id;
            $order->price          = isset($coupons) ? $discounted_price : $plan->price;
            $order->price_currency = $this->currancy;
            $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
            $order->payment_type   = __('Coingate');
            $order->payment_status = 'Succeeded';
            $order->receipt        = '';
            $order->user_id        = $user->id;
            $order->save();


            $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);

            if($assignPlan['is_success'])
            {
                return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
            }
            else
            {
                return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
            }

        }
    }
}
