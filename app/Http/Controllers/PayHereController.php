<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Lahirulhr\PayHere\PayHere;

class PayHereController extends Controller
{
    public $paymentSetting;
    public function __construct()
    {
        $paymentSetting = Utility::payment_settings();
        $config = [
            'payhere.api_endpoint' => $paymentSetting['payhere_mode'] === 'local'
                ? 'https://sandbox.payhere.lk/'
                : 'https://www.payhere.lk/',
        ];

        $config['payhere.merchant_id'] = $paymentSetting['merchant_id'] ?? '';
        $config['payhere.merchant_secret'] = $paymentSetting['merchant_secret'] ?? '';
        $config['payhere.app_secret'] = $paymentSetting['payhere_app_secret'] ?? '';
        $config['payhere.app_id'] = $paymentSetting['payhere_app_id'] ?? '';

        config($config);

        $this->paymentSetting = $paymentSetting;
    }

    public function planPayWithPayHere(Request $request)
    {
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);

        $authuser = Auth::user();

        if ($plan) {
            $get_amount = $plan->price;

            if (!empty($request->coupon)) {
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $get_amount = $plan->price - $discount_value;
                    $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                    $userCoupon = new UserCoupon();
                    $userCoupon->user = Auth::user()->id;
                    $userCoupon->coupon = $coupons->id;
                    $userCoupon->order = $orderID;
                    $userCoupon->save();
                    if ($coupons->limit == $usedCoupun) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            $hash = strtoupper(
                md5(
                    config('payhere.merchant_id') .
                        $orderID .
                        number_format($get_amount, 2, '.', '') .
                        'LKR' .
                        strtoupper(md5(config('payhere.merchant_secret')))
                )
            );

            $data = [
                'first_name' => $authuser->name,
                'last_name' => '',
                'email' => $authuser->email,
                'address' => '',
                'city' => '',
                'country' => '',
                'order_id' => $orderID,
                'items' => $plan->name,
                'currency' => 'LKR',
                'amount' => $get_amount,
                'hash' => $hash,
            ];

            return PayHere::checkOut()
                ->data($data)
                ->successUrl(route('plan.payhere.status', ['success' => 1, 'data' => $request->all(),'amount'=>$get_amount]))
                ->failUrl(route('plan.payhere.status', ['success' => 0, 'data' => $request->all()]))
                ->renderView();

        } else {
            return redirect()->back()->with('error', __('Plan not found!'));
        }
    }

    public function planGetPayHereStatus(Request $request)
    {
        if ($request->success == 1) {
            $info = PayHere::retrieve()
                ->orderId($request->order_id) // order number that you use to charge from customer
                ->submit();

            if ($info['data'][0]['order_id'] == $request->order_id) {
                if ($info['data'][0]['status'] == "RECEIVED") {

                    $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
                    $plan = Plan::find($planID);

                    if ($request->has('coupon_id') && $request->coupon_id != '') {
                        $coupons = Coupon::find($request->coupon_id);
                        if (!empty($coupons)) {
                            $userCoupon            = new UserCoupon();
                            $userCoupon->user   = Auth::user()->id;
                            $userCoupon->coupon = $coupons->id;
                            $userCoupon->order  = $request->order_id;
                            $userCoupon->save();


                            $usedCoupun = $coupons->used_coupon();
                            if ($coupons->limit <= $usedCoupun) {
                                $coupons->is_active = 0;
                                $coupons->save();
                            }
                        }
                    }
                    $order                 = new Order();
                    $order->order_id       = $request->order_id;
                    $order->name           = Auth::user()->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = isset($request->amount) ? $request->amount / 100 : 0;
                    $order->price_currency = 'LKR';
                    $order->txn_id         = app('App\Http\Controllers\BillController')->transactionNumber(Auth::user()->id);
                    $order->payment_type   = __('PayHere');
                    $order->payment_status = 'success';
                    $order->receipt        = '';
                    $order->user_id        = Auth::user()->id;
                    $order->save();

                    $assignPlan = Auth::user()->assignPlan($plan->id, $request->payment_frequency);

                    if ($assignPlan['is_success']) {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    } else {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }
                }
            }
        } else {
            return redirect()->back()->with('error', __('Oops! Something went wrong.'));
        }
    }

    public function invoicePayWithPayHere(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Bill::find($invoice_id);
        $user = User::where('id', $invoice->created_by)->first();
        $get_amount = $request->amount;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $authuser = Auth::user();

        try {
            if ($invoice) {
                $payment_setting = Utility::getCompanyPaymentSetting($user->id);
                $config = [
                    'payhere.api_endpoint' => $payment_setting['payhere_mode'] === 'local'
                        ? 'https://sandbox.payhere.lk/'
                        : 'https://www.payhere.lk/',
                ];

                $config['payhere.merchant_id'] = $payment_setting['merchant_id'] ?? '';
                $config['payhere.merchant_secret'] = $payment_setting['merchant_secret'] ?? '';
                $config['payhere.app_secret'] = $payment_setting['payhere_app_secret'] ?? '';
                $config['payhere.app_id'] = $payment_setting['payhere_app_id'] ?? '';

                config($config);

                $hash = strtoupper(
                    md5(
                        config('payhere.merchant_id') .
                            $orderID .
                            number_format($get_amount, 2, '.', '') .
                            'LKR' .
                            strtoupper(md5(config('payhere.merchant_secret')))
                    )
                );

                $data = [
                    'first_name' => $authuser->name ?? '',
                    'last_name' => '',
                    'email' => $authuser->email ?? "",
                    'address' => '',
                    'city' => '',
                    'country' => '',
                    'order_id' => $orderID,
                    'items' => 'Invoice',
                    'currency' => 'LKR',
                    'amount' => $get_amount,
                    'hash' => $hash,
                ];

                return PayHere::checkOut()
                    ->data($data)
                    ->successUrl(route('invoice.payhere.status', ['success' => 1, 'data' => $request->all(),'amount'=>$get_amount]))
                    ->failUrl(route('invoice.payhere.status', ['success' => 0, 'data' => $request->all()]))
                    ->renderView();

            } else {
                return redirect()->back()->with('error', 'Invoice not found.');
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', __($e));
        }
    }

    public function invoiceGetPayHereStatus(Request $request){

        $invoice_id = $request->data['invoice_id'];
        $invoice = Bill::find($invoice_id);
        $user = User::where('id', $invoice->created_by)->first();

        if ($request->success == 1) {
            $info = PayHere::retrieve()
                ->orderId($request->order_id)
                ->submit();
                if ($info['data'][0]['order_id'] == $request->order_id) {
                    if ($info['data'][0]['status'] == "RECEIVED") {
                        $invoice_payment                 = new BillPayment();
                        $invoice_payment->bill_id     = $invoice->id;
                        $invoice_payment->txn_id = app('App\Http\Controllers\BillController')->transactionNumber($user->id);
                        $invoice_payment->amount         = $request->amount;
                        $invoice_payment->date           = date('Y-m-d');
                        $invoice_payment->method   = 'Xendit';
                        $invoice_payment->save();

                        $payment = BillPayment::where('bill_id', $invoice->id)->sum('amount');

                        if ($payment >= $invoice->total_amount) {
                            $invoice->status = 'PAID';
                            $invoice->due_amount = 0.00;
                        } else {
                            $invoice->status = 'Partialy Paid';
                            $invoice->due_amount = $invoice->due_amount - $request->amount;
                        }
                        $invoice->save();

                        if (Auth::check()) {
                            return redirect()->route('pay.invoice', $invoice->id)->with('success', __('Invoice paid Successfully!'));
                        } else {
                            return redirect()->route('pay.invoice', encrypt($invoice->id))->with('success', __('Invoice paid Successfully!'));
                        }
                    }
                }
        }else{
            if (Auth::check()) {
                return redirect()->route('pay.invoice', $invoice->id)->with('success', __('Oops! Something went wrong!'));
            } else {
                return redirect()->route('pay.invoice', encrypt($invoice->id))->with('success', __('Oops! Something went wrong!'));
            }

        }



    }
}
