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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Xendit\Xendit;
use Illuminate\Support\Str;

class XenditPaymentController extends Controller
{
    public function planPayWithXendit(Request $request)
    {
        $payment_setting = Utility::payment_settings();
        $xendit_api = $payment_setting['xendit_api'];
        $currency = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
        $user = Auth::user();
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
            $response = ['orderId' => $orderID, 'user' => $user, 'get_amount' => $get_amount, 'plan' => $plan, 'currency' => $currency];
            Xendit::setApiKey($xendit_api);
            $params = [
                'external_id' => $orderID,
                'payer_email' => Auth::user()->email,
                'description' => 'Payment for order ' . $orderID,
                'amount' => $get_amount,
                'callback_url' =>  route('plan.xendit.status'),
                'success_redirect_url' => route('plan.xendit.status', $response),
                'failure_redirect_url' => route('plans.index'),
            ];

            $invoice = \Xendit\Invoice::create($params);
            Session::put('invoice',$invoice);

            return redirect($invoice['invoice_url']);
        }
    }

    public function planGetXenditStatus(Request $request)
    {
        $payment_setting = Utility::payment_settings();
        $xendit_api = $payment_setting['xendit_api'];
        Xendit::setApiKey($xendit_api);

        $session = Session::get('invoice');
        $getInvoice = \Xendit\Invoice::retrieve($session['id']);

        $authuser = User::find($request->user);
        $plan = Plan::find($request->plan);

        if($getInvoice['status'] == 'PAID'){

            Order::create(
                [
                    'order_id' => $request->orderId,
                    'name' => null,
                    'email' => null,
                    'card_number' => null,
                    'card_exp_month' => null,
                    'card_exp_year' => null,
                    'plan_name' => $plan->name,
                    'plan_id' => $plan->id,
                    'price' => $request->get_amount == null ? 0 : $request->get_amount,
                    'price_currency' => $request->currency,
                    'txn_id' => '',
                    'payment_type' => __('Xendit'),
                    'payment_status' => 'succeeded',
                    'receipt' => null,
                    'user_id' => $request->user,
                ]
            );

            $assignPlan = $authuser->assignPlan($plan->id, $request->payment_frequency);

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

    public function invoicePayWithXendit(Request $request)
    {
        $invoice_id = $request->invoice_id;
        $invoice = Bill::find($invoice_id);
        $user = User::where('id', $invoice->created_by)->first();
        $get_amount = $request->amount;
        $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

        try {
            if ($invoice) {
                $payment_setting = Utility::getCompanyPaymentSetting($user->id);
                $xendit_token = $payment_setting['xendit_token'];
                $xendit_api = $payment_setting['xendit_api'];
                $currency = isset($payment_setting['site_currency']) ? $payment_setting['site_currency'] : 'RUB';
                $response = ['orderId' => $orderID, 'user' => $user, 'get_amount' => $get_amount, 'invoice' => $invoice, 'currency' => $currency];
                Xendit::setApiKey($xendit_api);
                $params = [
                    'external_id' => $orderID,
                    'payer_email' => Auth::user()->email ?? 'Testuser@gmail.com',
                    'description' => 'Payment for order ' . $orderID,
                    'amount' => $get_amount,
                    'callback_url' =>  route('invoice.xendit.status'),
                    'success_redirect_url' => route('invoice.xendit.status', $response),
                ];

                $Xenditinvoice = \Xendit\Invoice::create($params);
                Session::put('invoicepay',$Xenditinvoice);
                return redirect($Xenditinvoice['invoice_url']);

            } else {
                return redirect()->back()->with('error', 'Invoice not found.');
            }
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', __($e));
        }
    }

    public function getInvociePaymentStatus(Request $request){
        $session = Session::get('invoicepay');
        $invoice = Bill::find($request->invoice);
        $user = User::where('id', $invoice->created_by)->first();
        $payment_setting = Utility::getCompanyPaymentSetting($user->id);
        $xendit_api = $payment_setting['xendit_api'];
        Xendit::setApiKey($xendit_api);
        $getInvoice = \Xendit\Invoice::retrieve($session['id']);

        if($getInvoice['status'] == 'PAID'){

            $invoice_payment                 = new BillPayment();
            $invoice_payment->bill_id     = $invoice->id;
            $invoice_payment->txn_id = app('App\Http\Controllers\BillController')->transactionNumber($user->id);
            $invoice_payment->amount         = $request->get_amount;
            $invoice_payment->date           = date('Y-m-d');
            $invoice_payment->method   = 'Xendit';
            $invoice_payment->save();

            $payment = BillPayment::where('bill_id', $invoice->id)->sum('amount');

            if ($payment >= $invoice->total_amount) {
                $invoice->status = 'PAID';
                $invoice->due_amount = 0.00;
            } else {
                $invoice->status = 'Partialy Paid';
                $invoice->due_amount = $invoice->due_amount - $request->get_amount;
            }
            $invoice->save();
        }
        if (Auth::check()) {
            return redirect()->route('pay.invoice', $invoice->id)->with('success', __('Invoice paid Successfully!'));
        } else {
            return redirect()->route('pay.invoice', encrypt($invoice->id))->with('success', __('Invoice paid Successfully!'));
        }
    }
}
