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
use Illuminate\Support\Facades\Crypt;

class MidtransController extends Controller
{
    public function planPayWithMidtrans(Request $request)
    {
        $payment_setting = Utility::payment_settings();

        $midtrans_secret = $payment_setting['midtrans_secret'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        if ($plan) {
            $get_amount = round($plan->price);

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
            // Set your Merchant Server Key
            \Midtrans\Config::$serverKey = $midtrans_secret;
            // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
            \Midtrans\Config::$isProduction = $payment_setting['midtrans_mode'] == 'local' ? false : true;
            // Set sanitization on (default)
            \Midtrans\Config::$isSanitized = true;
            // Set 3DS transaction for credit card to true
            \Midtrans\Config::$is3ds = true;

            $params = array(
                'transaction_details' => array(
                    'order_id' => $orderID,
                    'gross_amount' => $get_amount,
                ),
                'customer_details' => array(
                    'first_name' => Auth::user()->name,
                    'last_name' => '',
                    'email' => Auth::user()->email,
                    'phone' => '8787878787',
                ),
            );
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $authuser = Auth::user();
            $authuser->plan = $plan->id;
            $authuser->save();

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
                    'price_currency' => $currency,
                    'txn_id' => '',
                    'payment_type' => __('Midtrans'),
                    'payment_status' => 'pending',
                    'receipt' => null,
                    'user_id' => $authuser->id,
                ]
            );
            $data = [
                'snap_token' => $snapToken,
                'midtrans_secret' => $midtrans_secret,
                'order_id' => $orderID,
                'plan_id' => $plan->id,
                'amount' => $get_amount,
                'mode' => $payment_setting['midtrans_mode'],
                'fallback_url' => 'plan.get.midtrans.status'
            ];

            return view('midtras.payment', compact('data'));
        }
    }

    public function planGetMidtransStatus(Request $request)
    {
        $response = json_decode($request->json, true);
        if (isset($response['status_code']) && $response['status_code'] == 200) {
            $plan = Plan::find($request['plan_id']);
            $user = auth()->user();
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            try {
                $Order                 = Order::where('order_id', $request['order_id'])->first();
                $Order->payment_status = 'succeeded';
                $Order->save();

                $assignPlan = $user->assignPlan($plan->id);

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
            } catch (\Exception $e) {
                return redirect()->route('plans.index')->with('error', __($e->getMessage()));
            }
        } else {
            return redirect()->back()->with('error', $response['status_message']);
        }

    }

    public function invoicePayWithMidtrans(Request $request)
    {
        $invoice_id = $request->invoice_id;

        $invoice = Bill::find($invoice_id);

        $getAmount = $request->amount;

        $user = User::where('id', $invoice->created_by)->first();

        $payment_setting = Utility::getCompanyPaymentSetting($user->id);

        $midtrans_secret = $payment_setting['midtrans_secret'];
        $currency = isset($payment_setting['site_currency']) ? $payment_setting['site_currency'] : 'RUB';
        $get_amount = round($request->amount);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        try {
            if ($invoice) {

                 // Set your Merchant Server Key
                \Midtrans\Config::$serverKey = $midtrans_secret;
                // Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
                \Midtrans\Config::$isProduction = $payment_setting['midtrans_mode'] == 'local' ? false : true;
                // Set sanitization on (default)
                \Midtrans\Config::$isSanitized = true;
                // Set 3DS transaction for credit card to true
                \Midtrans\Config::$is3ds = true;

                $params = array(
                    'transaction_details' => array(
                        'order_id' => $orderID,
                        'gross_amount' => $get_amount,
                    ),
                    'customer_details' => array(
                        'first_name' => Auth::user()->name ?? 'Test',
                        'last_name' => '',
                        'email' => Auth::user()->email ?? 'test@gmail.com',
                        'phone' => '8787878787',
                    ),
                );
                $snapToken = \Midtrans\Snap::getSnapToken($params);


                $data = [
                    'snap_token' => $snapToken,
                    'midtrans_secret' => $midtrans_secret,
                    'invoice_id'=>$invoice->id,
                    'amount'=>$get_amount,
                    'mode' => $payment_setting['midtrans_mode'],
                    'fallback_url' => 'invoice.midtrans.status'
                ];

                return view('midtras.payment', compact('data'));
            } else {
                return redirect()->back()->with('error', 'Invoice not found.');
            }
        } catch (\Throwable $e) {

            return redirect()->back()->with('error', __($e));
        }
    }
    public function getInvociePaymentStatus(Request $request)
    {
        $get_amount = $request->amount;

        $invoice = Bill::find($request->invoice_id);
        $user = User::where('id', $invoice->created_by)->first();

        $response = json_decode($request->json, true);
        if ($invoice) {
            try {
                if (isset($response['status_code']) && $response['status_code'] == 200) {

                    $user = auth()->user();
                    try {
                        $invoice_payment                 = new BillPayment();
                        $invoice_payment->bill_id     = $request->invoice_id;
                        $invoice_payment->txn_id = app('App\Http\Controllers\BillController')->transactionNumber($user->id);
                        $invoice_payment->amount         = $get_amount;
                        $invoice_payment->date           = date('Y-m-d');
                        $invoice_payment->method   = 'Midtrans';
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
                            return redirect()->route('pay.invoice', Crypt::encrypt($request->invoice_id))->with('success', __('Invoice paid Successfully!'));
                        } else {
                            return redirect()->route('pay.invoice', encrypt($request->invoice_id))->with('ERROR', __('Transaction fail'));
                        }

                    } catch (\Exception $e) {
                        return redirect()->route('pay.invoice')->with('error', __($e->getMessage()));
                    }

                }else{
                    return redirect()->back()->with('error', $response['status_message']);
                }
            } catch (\Exception $e) {
                if (Auth::check()) {
                    return redirect()->route('pay.invoice', $request->invoice_id)->with('error', $e->getMessage());
                } else {
                    return redirect()->route('pay.invoice', encrypt($request->invoice_id))->with('success', $e->getMessage());
                }
            }
        } else {
            if (Auth::check()) {
                return redirect()->route('pay.invoice', $request->invoice_id)->with('error', __('Invoice not found.'));
            } else {
                return redirect()->route('pay.invoice', encrypt($request->invoice_id))->with('success', __('Invoice not found.'));
            }
        }
    }
}
