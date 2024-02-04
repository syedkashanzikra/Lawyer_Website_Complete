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

class PaystackPaymentController extends Controller
{
    public $secret_key;
    public $public_key;
    public $is_enabled;
    public $currancy;

    public function paymentConfig()
    {

        $payment_setting = DB::table('payment_settings')->get()->pluck('value', 'name')->toArray();

        $this->secret_key = isset($payment_setting['paystack_secret_key']) ? $payment_setting['paystack_secret_key'] : '';
        $this->public_key = isset($payment_setting['paystack_public_key']) ? $payment_setting['paystack_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paystack_enabled']) ? $payment_setting['is_paystack_enabled'] : 'off';

        return $this;
    }

    public function planpaymentSetting()
    {
        $payment_setting = Utility::payment_settings();

        $this->currancy = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $this->secret_key = isset($payment_setting['paystack_secret_key']) ? $payment_setting['paystack_secret_key'] : '';
        $this->public_key = isset($payment_setting['paystack_public_key']) ? $payment_setting['paystack_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paystack_enabled']) ? $payment_setting['is_paystack_enabled'] : 'off';
        return $this;
    }
    public function invoicePayWithPaystack(Request $request)
    {
        try {
            $invoiceID = \Illuminate\Support\Facades\Crypt::decrypt($request->invoice_id);
            $invoice = Bill::find($invoiceID);

            if ($invoice) {
                $price = $request->amount;

                if ($price > 0) {

                    if (!empty($invoice->advocate)) {
                        $advocate = Advocate::find($invoice->advocate);
                        $email = $advocate->email;

                    } else {
                        $email = $invoice->custom_email;
                    }
                    $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);

                    $res_data['email'] = $email;
                    $res_data['total_price'] = (int) $price;
                    $res_data['currency'] = !empty($payment_setting['site_currency']) ? $payment_setting['site_currency'] : 'USD';
                    $res_data['flag'] = 1;

                    return $res_data;
                } else {
                    $res['msg'] = __("Enter valid amount.");
                    $res['flag'] = 2;

                    return $res;
                }
            } else {
                return redirect()->route('bills.index')->with('error', __('Invoice is deleted.'));
            }

        }catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function getInvoicePaymentStatus(Request $request, $invoice_id, $amount, $pay_id)
    {
        try {
            $invoiceID = \Illuminate\Support\Facades\Crypt::decrypt($invoice_id);
            $invoice = Bill::find($invoiceID);

            $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);

            $this->secret_key = isset($payment_setting['paystack_secret_key']) ? $payment_setting['paystack_secret_key'] : '';
            $this->public_key = isset($payment_setting['paystack_public_key']) ? $payment_setting['paystack_public_key'] : '';
            $this->is_enabled = isset($payment_setting['is_paystack_enabled']) ? $payment_setting['is_paystack_enabled'] : 'off';



            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            $result = [];
            if ($invoice) {
                $url = "https://api.paystack.co/transaction/verify/$pay_id";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt(
                    $ch,
                    CURLOPT_HTTPHEADER,
                    [
                        'Authorization: Bearer ' . $this->secret_key,
                    ]
                );
                $responce = curl_exec($ch);
                curl_close($ch);
                if ($responce) {
                    $result = json_decode($responce, true);
                }


                $payments = new BillPayment();
                $payments['bill_id'] = $invoiceID;
                $payments['date'] = date('Y-m-d');
                $payments['amount'] = $amount;
                $payments['method'] = __('PAYSTACK');
                $payments['order_id'] = $orderID;
                $payments['currency'] = $payment_setting['site_currency'];

                $payments['note'] = $invoice->description;
                $payments->save();


                $payment = BillPayment::where('bill_id', $invoice_id)->sum('amount');

                if ($payment >= $invoice->total_amount) {
                    $invoice->status = 'PAID';
                    $invoice->due_amount = 0.00;
                } else {
                    $invoice->status = 'Partialy Paid';
                    $invoice->due_amount = $invoice->due_amount - $amount;
                }

                $invoice->save();


                if (Auth::check()) {
                    return redirect()->route('bills.show', $invoice->id)->with('success', __('Payment successfully added'));
                } else {
                    return redirect()->back()->with('success', __(' Payment successfully added.'));
                }

            } else {
                return redirect()->back()->with('error', __('Invoice is deleted.'));
            }
        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    // plan
    public function planPayWithPaystack(Request $request)
    {

        $this->planpaymentSetting();
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $coupon_id = '';
        if ($plan) {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->price;

            if (isset($request->coupon) && !empty($request->coupon))
            {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();

                if (!empty($coupons)) {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if ($usedCoupun >= $coupons->limit) {
                        return Utility::error_res(__('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;

                    $coupon_id = $coupons->id;
                } else {
                    return Utility::error_res(__('This coupon code is invalid or has expired.'));
                }
            }

            if ($price <= 0) {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);
                if ($assignPlan['is_success'] == true && !empty($plan)) {

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
                            'price' => $price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => 'Paystack',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    return redirect()->back()->with('success',__('Plan activated Successfully!'));
                } else {
                    return redirect()->back()->with('error',__('Plan fail to upgrade.'));
                }
            }
            $res_data['email'] = Auth::user()->email;
            $res_data['total_price'] = $price;
            $res_data['currency'] = $this->currancy;
            $res_data['flag'] = 1;
            $res_data['coupon'] = $coupon_id;
            $res_data['price'] = $price;
            return $res_data;
        } else {
            return redirect()->back()->with('error',__('Plan is deleted.'));
        }
    }

    public function getPaymentStatus(Request $request, $pay_id, $plan)
    {
      
        $this->planpaymentSetting();

        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $user           = Auth::user();
        $result         = array();

        if ($plan) {
            //The parameter after verify/ is the transaction reference to be verified
            $url = "https://api.paystack.co/transaction/verify/$pay_id";
            $ch  = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt(
                $ch,
                CURLOPT_HTTPHEADER,
                [
                    'Authorization: Bearer ' . $this->secret_key,
                ]
            );
            $result = curl_exec($ch);
            curl_close($ch);
            if ($result) {
                $result = json_decode($result, true);
            }
            $orderID = time();
            if (isset($result['status']) && $result['status'] == true) {
                if ($request->has('coupon_id') && $request->coupon_id != '')
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
                $objUser                    = Auth::user();
                $assignPlan = $objUser->assignPlan($plan->id);

                if ($assignPlan['is_success'] == true  && !empty($plan)) {
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
                            'price' => isset($coupons) ? $discounted_price : $plan->price,
                            'price_currency' => !empty(env('CURRENCY_CODE')) ? env('CURRENCY_CODE') : 'usd',
                            'txn_id' => '',
                            'payment_type' => __('paystack'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => Auth::user()->id,
                        ]
                    );

                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()->route('plans.index')->with('error', __('Transaction fail'));
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan not found!'));
        }
    }
}
