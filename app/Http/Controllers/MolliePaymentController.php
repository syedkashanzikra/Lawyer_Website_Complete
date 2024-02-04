<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mollie\Api\MollieApiClient;

class MolliePaymentController extends Controller
{
    public $api_key;
    public $profile_id;
    public $partner_id;
    public $is_enabled;
    public $currancy;

    public function planpaymentSetting()
    {
        $admin_payment_setting = Utility::payment_settings();

        $this->currancy =isset($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'';

        $this->api_key = isset($admin_payment_setting['mollie_api_key'])?$admin_payment_setting['mollie_api_key']:'';
        $this->profile_id = isset($admin_payment_setting['mollie_profile_id'])?$admin_payment_setting['mollie_profile_id']:'';
        $this->partner_id = isset($admin_payment_setting['mollie_partner_id'])?$admin_payment_setting['mollie_partner_id']:'';
        $this->is_enabled = isset($admin_payment_setting['is_mollie_enabled'])?$admin_payment_setting['is_mollie_enabled']:'off';
    }

    public function invoicePayWithMollie(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
                'amount' => 'required',
                'invoice_id' => 'required',

            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        try {
            $invoiceID = decrypt($request->invoice_id);
            $invoice = Bill::find($invoiceID);

            $data = Utility::getCompanyPaymentSetting($invoice->created_by);

            $this->currancy = isset($data['site_currency']) ? $data['site_currency'] : '';
            $this->api_key = isset($data['mollie_api_key']) ? $data['mollie_api_key'] : '';
            $this->profile_id = isset($data['mollie_profile_id']) ? $data['mollie_profile_id'] : '';
            $this->partner_id = isset($data['mollie_partner_id']) ? $data['mollie_partner_id'] : '';
            $this->is_enabled = isset($data['is_mollie_enabled']) ? $data['is_mollie_enabled'] : 'off';

            $mollie = new MollieApiClient();
            $mollie->setApiKey($this->api_key);
            $payment = $mollie->payments->create(
                [
                    "amount" => [
                        "currency" => $this->currancy,
                        "value" => number_format($request->amount, 2),
                    ],
                    "description" => "payment for invoice",
                    "redirectUrl" => route('invoice.mollie', encrypt($invoice->id)),
                ]
            );

            session()->put('mollie_payment_id', $payment->id);
            return redirect($payment->getCheckoutUrl())->with('payment_id', $payment->id);
        } catch (Exception $e) {

            return redirect()->back()->with('error',$e->getMessage());
        }

    }

    public function getInvoicePaymentStatus($invoice_id, Request $request)
    {
        try {

            $invoiceID = decrypt($invoice_id);
            $invoice = Bill::find($invoiceID);

            $data = Utility::getCompanyPaymentSetting($invoice->created_by);
            $manual_payments = BillPayment::where('bill_id', $invoice->id)->first();
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            $this->currancy = isset($data['site_currency']) ? $data['site_currency'] : '';
            $this->api_key = isset($data['mollie_api_key']) ? $data['mollie_api_key'] : '';
            $this->profile_id = isset($data['mollie_profile_id']) ? $data['mollie_profile_id'] : '';
            $this->partner_id = isset($data['mollie_partner_id']) ? $data['mollie_partner_id'] : '';
            $this->is_enabled = isset($data['is_mollie_enabled']) ? $data['is_mollie_enabled'] : 'off';

            $mollie = new \Mollie\Api\MollieApiClient ();
            $mollie->setApiKey($this->api_key);

            if ($invoice && session()->has('mollie_payment_id')) {
                $payment = $mollie->payments->get(session()->get('mollie_payment_id'));

                if ($payment->isPaid()) {

                        $payments = new BillPayment();
                        $payments['bill_id'] = $invoice->id;
                        $payments['date'] = date('Y-m-d');
                        $payments['amount'] = isset($payment->amount->value) ? $payment->amount->value : 0;
                        $payments['method'] = __('MOLLIE');
                        $payments['order_id'] = $orderID;
                        $payments['currency'] = $data['site_currency'];
                        $payments['note'] = $invoice->description;
                        $payments['txn_id'] = isset($payment->id) ? $payment->id : '';
                        $payments->save();


                    $payment = BillPayment::where('bill_id', $invoiceID)->sum('amount');

                    if ($payment >= $invoice->total_amount) {
                        $invoice->status = 'PAID';
                        $invoice->due_amount = 0.00;
                    } else {
                        $invoice->status = 'Partialy Paid';
                        $invoice->due_amount = $invoice->due_amount - isset($payment->amount->value) ? $payment->amount->value : 0;
                    }

                    $invoice->save();

                    if (Auth::check()) {
                        return redirect()->route('bills.show', $invoice->id)->with('success', __('Payment successfully added'));
                    } else {
                        return redirect()->back()->with('success', __('Invoice paid Successfully!'));
                    }

                }
            } else {
                if (Auth::check()) {
                    return redirect()->route('bills.show', $invoice_id)->with('error', __('Transaction fail'));
                } else {
                    return redirect()->back()->with('error', __('Transaction fail'));
                }

            }

        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function planPayWithMollie(Request $request)
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
                            'payment_type' => 'Paystack',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $assignPlan = $authuser->assignPlan($plan->id, $request->mollie_payment_frequency);
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Plan fail to upgrade.'));
                }
            }

            try{

                $mollie  = new \Mollie\Api\MollieApiClient();
                $mollie->setApiKey($this->api_key);

                $payment = $mollie->payments->create(
                    [
                        "amount" => [
                            "currency" => $this->currancy,
                            "value" => number_format((float)$price, 2, '.', ''),
                        ],
                        "description" => "payment for product",
                        "redirectUrl" => route('plan.mollie', [$request->plan_id,'payment_frequency='.$request->mollie_payment_frequency,'coupon_id='.$coupons_id]),
                    ]
                );

                session()->put('mollie_payment_id', $payment->id);
                return redirect($payment->getCheckoutUrl())->with('payment_id', $payment->id);
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
        $user = Auth::user();
        $orderID = time();
        if($plan)
        {
            try
            {
                $mollie = new \Mollie\Api\MollieApiClient();
                $mollie->setApiKey($this->api_key);

                if(session()->has('mollie_payment_id'))
                {
                    $payment = $mollie->payments->get(session()->get('mollie_payment_id'));

                    if($payment->isPaid())
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
                        $order->txn_id         = isset($request->TXNID) ? $request->TXNID : '';
                        $order->payment_type   = __('Mollie');
                        $order->payment_status = 'succeeded';
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
                    }else{
                        return redirect()->route('plans.index')->with('error', __('Transaction has been failed! '));
                    }
                }
                else
                {
                    return redirect()->route('plans.index')->with('error', __('Transaction has been failed! '));
                }
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __('Plan not found!'));
            }
        }
    }
}
