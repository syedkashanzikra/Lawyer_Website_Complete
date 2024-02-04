<?php

namespace App\Http\Controllers;

use App\Models\Advocate;
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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use PaytmWallet;

class PaytmPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;
    public $currancy;


    public function planpaymentSetting()
    {
        $admin_payment_setting = Utility::payment_settings();
        $this->currancy = isset($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'';
        config(
            [
                'services.paytm-wallet.env' => isset($admin_payment_setting['paytm_mode'])?$admin_payment_setting['paytm_mode']:'',
                'services.paytm-wallet.merchant_id' => isset($admin_payment_setting['paytm_merchant_id'])?$admin_payment_setting['paytm_merchant_id']:'',
                'services.paytm-wallet.merchant_key' =>  isset($admin_payment_setting['paytm_merchant_key'])?$admin_payment_setting['paytm_merchant_key']:'',
                'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                'services.paytm-wallet.channel' => 'WEB',
                'services.paytm-wallet.industry_type' =>isset($admin_payment_setting['paytm_industry_type'])?$admin_payment_setting['paytm_industry_type']:'',
            ]
        );
    }
    public function invoicePayWithPaytm(Request $request)
    {
        $validator = Validator::make(
            $request->all(), [
                'amount' => 'required',
                'invoice_id' => 'required',
                'mobile' => 'required',
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

            config(
                [
                    'services.paytm-wallet.env' => isset($data['paytm_mode']) ? $data['paytm_mode'] : '',
                    'services.paytm-wallet.merchant_id' => isset($data['paytm_merchant_id']) ? $data['paytm_merchant_id'] : '',
                    'services.paytm-wallet.merchant_key' => isset($data['paytm_merchant_key']) ? $data['paytm_merchant_key'] : '',
                    'services.paytm-wallet.merchant_website' => 'WEBSTAGING',
                    'services.paytm-wallet.channel' => 'WEB',
                    'services.paytm-wallet.industry_type' => isset($data['paytm_industry_type']) ? $data['paytm_industry_type'] : '',
                ]
            );
            if (!empty($invoice->advocate)) {
                $advocate = Advocate::find($invoice->advocate);
                $email = $advocate->email;
                $name = $advocate->name;

            } else {
                $email = $invoice->custom_email;
                $name = $invoice->custom_advocate;
            }

            $call_back = route('invoice.paytm', [encrypt($invoice->id)]);
            $payment = PaytmWallet::with('receive');
            $payment->prepare(
                [
                    'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                    'user' => $invoice->created_by,
                    'mobile_number' => $request->mobile,
                    'email' => $email,
                    'amount' => $request->amount,
                    'invoice_id' => $invoice->id,
                    'callback_url' => $call_back,
                ]
            );

            return $payment->receive();

        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }

    }

    public function getInvoicePaymentStatus($invoice_id, Request $request)
    {
        try {
            $invoice_id = decrypt($invoice_id);
            $invoice = Bill::find($invoice_id);

            if ($invoice) {
                $transaction = PaytmWallet::with('receive');
                $response = $transaction->response();

                if ($transaction->isSuccessful()) {

                    $manual_payments = BillPayment::where('bill_id', $invoice->id)->first();
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);

                        $payments = new BillPayment();
                        $payments['bill_id'] = $invoice->id;
                        $payments['date'] = date('Y-m-d');
                        $payments['amount'] = $request->has('amount') ? $request->amount : 0;
                        $payments['method'] = __('PAYTM');
                        $payments['order_id'] = $orderID;
                        $payments['currency'] = $payment_setting['site_currency'];

                        $payments['note'] = $invoice->description;
                        $payments['txn_id'] = isset($request->TXNID) ? $request->TXNID : '';
                        $payments->save();


                    $payment = BillPayment::where('bill_id', $invoice_id)->sum('amount');

                    if ($payment >= $invoice->total_amount) {
                        $invoice->status = 'PAID';
                        $invoice->due_amount = 0.00;
                    } else {
                        $invoice->status = 'Partialy Paid';
                        $invoice->due_amount = $invoice->due_amount - $request->has('amount') ? $request->amount : 0;
                    }


                    $invoice->save();

                    if (Auth::check()) {
                        return redirect()->route('bills.show', $invoice->id)->with('success', __('Payment successfully added'));
                    } else {
                        return redirect()->back()->with('success', __('Invoice paid Successfully!'));
                    }

                } else {
                    if (Auth::check()) {
                        return redirect()->route('bills.show', $invoice_id)->with('error', __('Transaction fail'));
                    } else {
                        return redirect()->back()->with('error', __('Transaction fail'));
                    }

                }

            }
        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function planPayWithPaytm(Request $request)
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
                        return Utility::error_res( __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                }
                else
                {
                    return Utility::error_res( __('This coupon code is invalid or has expired.'));
                }
            }

            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if($assignPlan['is_success'] == true && !empty($plan))
                {
                    if(!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '')
                    {
                        try
                        {
                            $authuser->cancel_subscription($authuser->id);
                        }
                        catch(\Exception $exception)
                        {
                            Log::debug($exception->getMessage());
                        }
                    }

                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
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
                            'payment_type' => 'Paytm',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 2;
                    return $res;
                }
                else
                {
                    return Utility::error_res( __('Plan fail to upgrade.'));
                }
            }


            try{
                $call_back = route('plan.paytm',[$request->plan_id]);

                $payment = PaytmWallet::with('receive');

                $payment->prepare(
                    [
                        'order' => date('Y-m-d') . '-' . strtotime(date('Y-m-d H:i:s')),
                        'user' => Auth::user()->id,
                        'mobile_number' => $request->mobile,
                        'email' => Auth::user()->email,
                        'amount' => $price,
                        'plan' => $plan->id,
                        'callback_url' => $call_back
                    ]
                );

                return $payment->receive();
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        }
        else
        {
            return Utility::error_res( __('Plan is deleted.'));
        }
    }

    public function getPaymentStatus(Request $request,$plan)
    {

        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);

        $plan  = Plan::find($planID);
        $user = Auth::user();

        $orderID = time();
        if($plan)
        {

                $transaction = PaytmWallet::with('receive');

                $response = $transaction->response();

                if($transaction->isSuccessful())
                {

                    if($request->has('coupon_id') && $request->coupon_id != '')
                    {
                        $coupons = Coupon::find($request->coupon_id);
                        if(!empty($coupons))
                        {
                            $userCoupon            = new UserCoupon();
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
                    $order->price          = $plan->price;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($request->TXNID) ? $request->TXNID : '';
                    $order->payment_type   = __('paytm');
                    $order->payment_status = 'success';
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
                else
                {
                    return redirect()->route('plans.index')->with('error', __('Transaction has been failed! '));
                }

        }
    }
}
