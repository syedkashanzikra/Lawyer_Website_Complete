<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PaytrController extends Controller
{
    public function PlanpayWithPaytr(Request $request)
    {
        $payment_setting = Utility::payment_settings();
        $paytr_merchant_id = $payment_setting['paytr_merchant_id'];
        $paytr_merchant_key = $payment_setting['paytr_merchant_key'];
        $paytr_merchant_salt = $payment_setting['paytr_merchant_salt'];
        $currency =isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser = Auth::user();
        $plan = Plan::find($planID);
        if ($plan) {

            $get_amount = $plan->price;

            if(!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $get_amount = $plan->price - $discount_value;

                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    if ($get_amount <= 0) {
                        $authuser = \Auth::user();
                        $authuser->plan = $plan->id;
                        $authuser->save();
                        $assignPlan = $authuser->assignPlan($plan->id);
                        if ($assignPlan['is_success'] == true && !empty($plan)) {
                            if (!empty($authuser->payment_subscription_id) && $authuser->payment_subscription_id != '') {
                                try {
                                    $authuser->cancel_subscription($authuser->id);
                                } catch (\Exception $exception) {
                                    \Log::debug($exception->getMessage());
                                }
                            }
                            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                            $userCoupon = new UserCoupon();
                            $userCoupon->user = $authuser->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order = $orderID;
                            $userCoupon->save();
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
                                    'price' => $get_amount == null ? 0 : $get_amount,
                                    'price_currency' => isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD',
                                    'txn_id' => '',
                                    'payment_type' => 'Paytr',
                                    'payment_status' => 'succeeded',
                                    'receipt' => null,
                                    'user_id' => $authuser->id,
                                ]
                            );
                            $assignPlan = $authuser->assignPlan($plan->id);
                            return redirect()->route('plans.index')->with('success', __('Plan Successfully Activated'));
                        }
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            try {
                $coupon = (empty($request->coupon)) ? "0" : $request->coupon;

                $merchant_id    = $paytr_merchant_id;
                $merchant_key   = $paytr_merchant_key;
                $merchant_salt  = $paytr_merchant_salt;

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                // $store_id = $authuser->current_store;

                // $store = Store::where('id', $store_id)->get()->first();
                $email = $authuser->email;
                $payment_amount = $plan->price;
                $merchant_oid = $orderID;
                $user_name = $authuser->name;
                $user_address =  'no address';
                $user_phone = '0000000000';


                $user_basket = base64_encode(json_encode(array(
                    array("Plan", $payment_amount, 1),
                )));

                if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                    $ip = $_SERVER["HTTP_CLIENT_IP"];
                } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                    $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
                } else {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }

                $user_ip = $ip;
                $timeout_limit = "30";
                $debug_on = 1;
                $test_mode = 0;
                $no_installment = 0;
                $max_installment = 0;
                $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
                $payment_amount = $payment_amount * 100;
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

                $request['orderID'] = $orderID;
                $request['plan_id'] = $plan->id;
                $request['price'] = $get_amount;
                $request['payment_status'] = 'failed';
                $payment_failed = $request->all();
                $request['payment_status'] = 'success';
                $payment_success = $request->all();

                $post_vals = array(
                    'merchant_id' => $merchant_id,
                    'user_ip' => $user_ip,
                    'merchant_oid' => $merchant_oid,
                    'email' => $email,
                    'payment_amount' => $payment_amount,
                    'paytr_token' => $paytr_token,
                    'user_basket' => $user_basket,
                    'debug_on' => $debug_on,
                    'no_installment' => $no_installment,
                    'max_installment' => $max_installment,
                    'user_name' => $user_name,
                    'user_address' => $user_address,
                    'user_phone' => $user_phone,
                    'merchant_ok_url' => route('pay.paytr.success', $payment_success),
                    'merchant_fail_url' => route('pay.paytr.success', $payment_failed),
                    'timeout_limit' => $timeout_limit,
                    'currency' => $currency,
                    'test_mode' => $test_mode
                );

                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);


                $result = @curl_exec($ch);

                if (curl_errno($ch)) {
                    die("PAYTR IFRAME connection error. err:" . curl_error($ch));
                }

                curl_close($ch);

                $result = json_decode($result, 1);

                if ($result['status'] == 'success') {
                    $token = $result['token'];
                } else {
                    return redirect()->route('plans.index')->with('error', $result['reason']);
                }
                return view('paytr_payment.index', compact('token'));
            } catch (\Throwable $th) {
                return redirect()->route('plans.index')->with('error', $th->getMessage());
            }
        }
    }

    public function paytrsuccessCallback(Request $request)
    {

        if ($request->payment_status == "success") {

            $getAmount = $request->price;
            $product_id = $request->plan_id;

            $planId = $request->plan_id;
            $couponCode = $request->coupon;


            if ($couponCode != 0) {
                $coupons = Coupon::where('code', strtoupper($couponCode))->where('is_active', '1')->first();
                $request['coupon_id'] = $coupons->id;
            } else {
                $coupons = null;
            }

            $plan = Plan::find($planId);
            $user = auth()->user();
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                    $order = new Order();
                    $order->order_id = $orderID;
                    $order->name = $user->name;
                    $order->card_number = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year = '';
                    $order->plan_name = $plan->name;
                    $order->plan_id = $plan->id;
                    $order->price = $getAmount;
                    $order->price_currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
                    $order->payment_type = __('PayTR');
                    $order->payment_status = 'succeeded';
                    $order->txn_id = '';
                    $order->receipt = '';
                    $order->user_id = $user->id;

                    $order->save();
                    $assignPlan = $user->assignPlan($plan->id);
                    $coupons = Coupon::find($request->coupon_id);
                    if (!empty($request->coupon_id)) {
                        if (!empty($coupons)) {
                            $userCoupon = new UserCoupon();
                            $userCoupon->user = $user->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order = $orderID;
                            $userCoupon->save();
                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
                    } else {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }

            } catch (Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        }
    }

    public function invoicePayWithpaytr(Request $request)
    {
        $invoice_id = $request->invoice_id;

        $invoice = Bill::find($invoice_id);
        $getAmount = $request->amount;


        $user = User::where('id', $invoice->created_by)->first();

        $payment_setting = Utility::getCompanyPaymentSetting($user->id);
        $paytr_merchant_id = $payment_setting['paytr_merchant_id'];
        $paytr_merchant_key = $payment_setting['paytr_merchant_key'];
        $paytr_merchant_salt = $payment_setting['paytr_merchant_salt'];
        $currency =isset($payment_setting['site_currency']) ? $payment_setting['site_currency'] : 'USD';

        try {

            $merchant_id    = $paytr_merchant_id;
            $merchant_key   = $paytr_merchant_key;
            $merchant_salt  = $paytr_merchant_salt;
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));


            $email = $user->email;
            $payment_amount = $getAmount;
            $merchant_oid = $orderID;
            $user_name = $user->name;
            $user_address =  'no address';
            $user_phone = '0000000000';

            $user_basket = base64_encode(json_encode(array(
                array("Plan", $payment_amount, 1),
            )));

            if (isset($_SERVER["HTTP_CLIENT_IP"])) {
                $ip = $_SERVER["HTTP_CLIENT_IP"];
            } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
                $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            } else {
                $ip = $_SERVER["REMOTE_ADDR"];
            }

            $user_ip = $ip;
            $timeout_limit = "30";
            $debug_on = 1;
            $test_mode = 0;
            $no_installment = 0;
            $max_installment = 0;
            $currency = $currency;
            $payment_amount = $payment_amount * 100;
            $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
            $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));

            $request['orderID'] = $orderID;
            $request['invoice_id'] = $invoice_id;
            $request['amount'] = $getAmount;
            $request['payment_status'] = 'failed';
            $payment_failed = $request->all();
            $request['payment_status'] = 'success';
            $payment_success = $request->all();

            $post_vals = array(
                'merchant_id' => $merchant_id,
                'user_ip' => $user_ip,
                'merchant_oid' => $merchant_oid,
                'email' => $email,
                'payment_amount' => $payment_amount,
                'paytr_token' => $paytr_token,
                'user_basket' => $user_basket,
                'debug_on' => $debug_on,
                'no_installment' => $no_installment,
                'max_installment' => $max_installment,
                'user_name' => $user_name,
                'user_address' => $user_address,
                'user_phone' => $user_phone,
                'merchant_ok_url' => route('invoice.paytr.status', $payment_success),
                'merchant_fail_url' => route('invoice.paytr.status', $payment_failed),
                'timeout_limit' => $timeout_limit,
                'currency' => $currency,
                'test_mode' => $test_mode
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);


            $result = @curl_exec($ch);

            if (curl_errno($ch)) {
                die("PAYTR IFRAME connection error. err:" . curl_error($ch));
            }

            curl_close($ch);

            $result = json_decode($result, 1);

            if ($result['status'] == 'success') {
                $token = $result['token'];
            } else {
                return redirect()->back()->with('error', $result['reason']);
            }
            return view('paytr_payment.index', compact('token'));
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', __($e));
        }
    }
    public function getInvociePaymentStatus(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $get_amount = $request->amount;

        $invoice = Bill::find($invoice_id);
        $user = User::where('id', $invoice->created_by)->first();

        if ($invoice) {
            try {
                if ($request->payment_status == "success") {
                    $invoice_payment                 = new BillPayment();
                    $invoice_payment->bill_id     = $invoice_id;
                    $invoice_payment->txn_id = app('App\Http\Controllers\BillController')->transactionNumber($user->id);
                    $invoice_payment->amount         = $get_amount;
                    $invoice_payment->date           = date('Y-m-d');
                    $invoice_payment->method   = 'PayTR';
                    $invoice_payment->save();

                    $payment = BillPayment::where('bill_id', $invoice->id)->sum('amount');

                    if ($payment >= $invoice->total_amount) {
                        $invoice->status = 'PAID';
                        $invoice->due_amount = 0.00;
                    } else {
                        $invoice->status = 'Partialy Paid';
                        $invoice->due_amount = $invoice->due_amount - $get_amount;
                    }
                    $invoice->save();

                    if (Auth::check()) {
                        return redirect()->route('pay.invoice', Crypt::encrypt($invoice_id))->with('success', __('Invoice paid Successfully!'));
                    } else {
                        return redirect()->route('pay.invoice', encrypt($invoice_id))->with('ERROR', __('Transaction fail'));
                    }
                } else {

                    if (Auth::check()) {
                        return redirect()->route('pay.invoice', Crypt::encrypt($invoice_id))->with('error', __('Transaction fail!'));
                    } else {
                        return redirect()->route('pay.invoice', encrypt($invoice_id))->with('error', __('Transaction fail'));
                    }
                }
            } catch (\Exception $e) {
                if (Auth::check()) {
                    return redirect()->route('pay.invoice', $invoice_id)->with('error', $e->getMessage());
                } else {
                    return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', $e->getMessage());
                }
            }
        } else {
            if (Auth::check()) {
                return redirect()->route('pay.invoice', $invoice_id)->with('error', __('Invoice not found.'));
            } else {
                return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', __('Invoice not found.'));
            }
        }
    }

}
