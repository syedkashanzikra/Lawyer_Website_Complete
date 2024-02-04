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

class RazorpayPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;
    public $currancy;
    public $pay_amount;


    public function planpaymentSetting()
    {
        $payment_setting = Utility::payment_settings();
        $this->currancy = isset($payment_setting['currency'])?$payment_setting['currency']:'';
        $this->secret_key = isset($payment_setting['razorpay_secret_key'])?$payment_setting['razorpay_secret_key']:'';
        $this->public_key = isset($payment_setting['razorpay_public_key'])?$payment_setting['razorpay_public_key']:'';
        $this->is_enabled = isset($payment_setting['is_razorpay_enabled'])?$payment_setting['is_razorpay_enabled']:'off';
    }

    public function invoicePayWithRazorpay(Request $request)
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

            $this->currancy = isset($data['site_currency']) ? $data['site_currency'] : 'USD';
            $this->secret_key = isset($data['razorpay_secret_key']) ? $data['razorpay_secret_key'] : '';
            $this->public_key = isset($data['razorpay_public_key']) ? $data['razorpay_public_key'] : '';
            $this->is_enabled = isset($data['is_razorpay_enabled']) ? $data['is_razorpay_enabled'] : 'off';


            if (!empty($invoice->advocate)) {
                $advocate = Advocate::find($invoice->advocate);
                $email = $advocate->email;

            } else {
                $email = $invoice->custom_email;
            }

            $res_data['email'] = $email;
            $res_data['total_price'] = $request->amount;
            $res_data['currency'] = $this->currancy;
            $res_data['flag'] = 1;
            $res_data['invoice_id'] = $invoice->id;
            $request->session()->put('invoice_data', $res_data);
            $this->pay_amount = $request->amount;
            return $res_data;

        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }

    }

    public function getInvoicePaymentStatus($pay_id, $invoice_id, Request $request)
    {
        try {
            $invoice_id = decrypt($invoice_id);
            $invoice = Bill::find($invoice_id);


                $data = Utility::getCompanyPaymentSetting($invoice->created_by);

                $this->currancy = isset($data['site_currency']) ? $data['site_currency'] : 'USD';
                $this->secret_key = isset($data['razorpay_secret_key']) ? $data['razorpay_secret_key'] : '';
                $this->public_key = isset($data['razorpay_public_key']) ? $data['razorpay_public_key'] : '';
                $this->is_enabled = isset($data['is_razorpay_enabled']) ? $data['is_razorpay_enabled'] : 'off';


            $ch = curl_init('https://api.razorpay.com/v1/payments/' . $pay_id . '');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_USERPWD, $this->public_key . ':' . $this->secret_key); // Input your Razorpay Key Id and Secret Id here
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = json_decode(curl_exec($ch));

            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            $invoice_data = $request->session()->get('invoice_data');

            if ($response->status == 'authorized') {

                    $payments = new BillPayment();
                    $payments['bill_id'] = $invoice->id;
                    $payments['date'] = date('Y-m-d');
                    $payments['amount'] = isset($invoice_data['total_price']) ? $invoice_data['total_price'] : 0;
                    $payments['method'] = __('RAZORPAY');
                    $payments['order_id'] = $orderID;
                    $payments['currency'] = $data['site_currency'];
                 
                    $payments['note'] = $invoice->description;
                    $payments['txn_id'] = isset($response->id) ? $response->id : '';
                    $payments->save();


                $payment = BillPayment::where('bill_id', $invoice_id)->sum('amount');

                if ($payment >= $invoice->total_amount) {
                    $invoice->status = 'PAID';
                    $invoice->due_amount = 0.00;
                } else {
                    $invoice->status = 'Partialy Paid';
                    $invoice->due_amount = $invoice->due_amount - isset($invoice_data['total_price']) ? $invoice_data['total_price'] : 0;
                }

                $invoice->save();

                $request->session()->forget('invoice_data');

                if (Auth::check()) {
                    return redirect()->route('bills.show', $invoice->id)->with('success', __('Payment successfully added'));
                } else {
                    return redirect()->back()->with('success', __(' Payment successfully added.'));
                }

            }

        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    // plan
    public function planPayWithRazorpay(Request $request){

        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $coupon_id = '';
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

                    if($usedCoupun >= $coupons->limit)
                    {
                        return Utility::error_res( __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                    $coupon_id = $coupons->id;
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
                            'payment_type' => 'Razorpay',
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

            $res_data['email'] = Auth::user()->email;
            $res_data['total_price'] = $price;
            $res_data['currency'] = $this->currancy;
            $res_data['flag'] = 1;
            $res_data['payment_frequency'] = $request->razorpay_payment_frequency;
            $res_data['coupon'] = $coupon_id;
            return $res_data;
        }
        else
        {
            return Utility::error_res( __('Plan is deleted.'));
        }
    }

    public function getPaymentStatus(Request $request,$pay_id,$plan){

        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $user = Auth::user();
        if($plan)
        {
            try
            {
                $orderID = time();
                $ch = curl_init('https://api.razorpay.com/v1/payments/' . $pay_id . '');
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                curl_setopt($ch, CURLOPT_USERPWD, $this->public_key . ':' . $this->secret_key); // Input your Razorpay Key Id and Secret Id here
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = json_decode(curl_exec($ch));
                // check that payment is authorized by razorpay or not

                if($response->status == 'authorized')
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
                    $order->price          = isset($response->amount) ? $response->amount/100 : 0;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($response->id) ? $response->id : $pay_id;
                    $order->payment_type   = __('Razorpay');
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
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __('Plan not found!'));
            }
        }
    }
}
