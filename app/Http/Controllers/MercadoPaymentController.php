<?php

namespace App\Http\Controllers;

use App\Models\Advocate;
use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\Utility;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MercadoPaymentController extends Controller
{
    public $token;
    public $is_enabled;
    public $currancy;
    public $mode;

    public function planpaymentSetting()
    {
        $admin_payment_setting = Utility::payment_settings();
        $this->token = isset($admin_payment_setting['mercado_access_token'])?$admin_payment_setting['mercado_access_token']:'';
        $this->mode = isset($admin_payment_setting['mercado_mode'])?$admin_payment_setting['mercado_mode']:'';
        $this->is_enabled = isset($admin_payment_setting['is_mercado_enabled'])?$admin_payment_setting['is_mercado_enabled']:'off';
        $this->currancy = isset($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'';
        return;
    }

    public function invoicePayWithMercado(Request $request)
    {
        try {
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

            $invoiceID = decrypt($request->invoice_id);
            $invoice = Bill::find($invoiceID);

            $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);

            $this->token = isset($payment_setting['mercado_access_token']) ? $payment_setting['mercado_access_token'] : '';
            $this->mode = isset($payment_setting['mercado_mode']) ? $payment_setting['mercado_mode'] : '';
            $this->is_enabled = isset($payment_setting['is_mercado_enabled']) ? $payment_setting['is_mercado_enabled'] : 'off';
            $this->currancy = $payment_setting['site_currency'];

            $preference_data = array(
                "items" => array(
                    array(
                        "title" => "Invoice : " . $request->invoice_id,
                        "quantity" => 1,
                        "currency_id" => $this->currancy,
                        "unit_price" => (float) $request->amount,
                    ),
                ),
            );

            \MercadoPago\SDK::setAccessToken($this->token);

            $preference = new \MercadoPago\Preference ();

            $item = new \MercadoPago\Item ();
            $item->title = "Invoice : " . $request->invoice_id;
            $item->quantity = 1;
            $item->unit_price = (float) $request->amount;
            $preference->items = array($item);

            $success_url = route('invoice.mercado', [encrypt($invoice->id), 'amount' => (float) $request->amount, 'flag' => 'success']);
            $failure_url = route('invoice.mercado', [encrypt($invoice->id), 'flag' => 'failure']);
            $pending_url = route('invoice.mercado', [encrypt($invoice->id), 'flag' => 'pending']);
            $preference->back_urls = array(
                "success" => $success_url,
                "failure" => $failure_url,
                "pending" => $pending_url,
            );
            $preference->auto_return = "approved";
            $preference->save();

            $payer = new \MercadoPago\Payer ();

            if (!empty($invoice->advocate)) {
                $advocate = Advocate::find($invoice->advocate);
                $email = $advocate->email;
                $name = $advocate->name;

            } else {
                $email = $invoice->custom_email;
                $name = $invoice->custom_advocate;
            }

            $payer->name = $name;
            $payer->email = $email;
            $payer->address = array(
                "street_name" => '',
            );

            if ($this->mode == 'live') {
                $redirectUrl = $preference->init_point;
            } else {
                $redirectUrl = $preference->sandbox_init_point;
            }
            return redirect($redirectUrl);

        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function getInvoicePaymentStatus($invoice_id, Request $request)
    {
        try {
            $invoice_id = decrypt($invoice_id);
            $invoice = Bill::find($invoice_id);


            if ($request->status == 'approved' && $request->flag == 'success') {

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
                $payment_setting = Utility::getCompanyPaymentSetting($invoice->created_by);

                    $payments = new BillPayment();
                    $payments['bill_id'] = $invoice->id;
                    $payments['date'] = date('Y-m-d');
                    $payments['amount'] = $request->has('amount') ? $request->amount : 0;
                    $payments['method'] = __('Mercado Pago');
                    $payments['order_id'] = $orderID;
                    $payments['currency'] = $payment_setting['site_currency'];
                    $payments['note'] = $invoice->description;
                    $payments['txn_id'] = $request->has('preference_id') ? $request->preference_id : '';
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

        } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    // plan
    public function planPayWithMercado(Request $request)
    {

        $this->planpaymentSetting();
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan           = Plan::find($planID);
        $authuser       = Auth::user();
        $coupons_id = '';
        if ($plan) {
            /* Check for code usage */
            $plan->discounted_price = false;
            $price                  = $plan->price;
            if (isset($request->coupon) && !empty($request->coupon)) {
                $request->coupon = trim($request->coupon);
                $coupons         = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun             = $coupons->used_coupon();
                    $discount_value         = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if ($usedCoupun >= $coupons->limit) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }

            if ($price <= 0) {
                $authuser->plan = $plan->id;
                $authuser->save();

                $assignPlan = $authuser->assignPlan($plan->id);

                if ($assignPlan['is_success'] == true && !empty($plan)) {

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
                            'price' => $price == null ? 0 : $price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => 'Mercado Pago',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully'));
                } else {
                    return Utility::error_res(__('Plan fail to upgrade.'));
                }
            }

            \MercadoPago\SDK::setAccessToken($this->token);
            try {

                // Create a preference object
                $preference = new \MercadoPago\Preference();
                // Create an item in the preference
                $item = new \MercadoPago\Item();
                $item->title = "Plan : " . $plan->name;
                $item->quantity = 1;
                $item->unit_price = (float)$price;
                $preference->items = array($item);

                $success_url = route('plan.mercado', [$request->plan_id, 'payment_frequency=' . $request->mercado_payment_frequency, 'coupon_id=' . $coupons_id, 'flag' => 'success', 'amount'=>$price]);
                $failure_url = route('plan.mercado', [$request->plan_id, 'flag' => 'failure', 'amount'=>$price]);
                $pending_url = route('plan.mercado', [$request->plan_id, 'flag' => 'pending', 'amount'=>$price]);

                $preference->back_urls = array(
                    "success" => $success_url,
                    "failure" => $failure_url,
                    "pending" => $pending_url
                );

                $preference->auto_return = "approved";
                $preference->save();

                // Create a customer object
                $payer = new \MercadoPago\Payer();
                // Create payer information
                $payer->name = Auth::user()->name;
                $payer->email = Auth::user()->email;
                $payer->address = array(
                    "street_name" => ''
                );
                if ($this->mode == 'live') {
                    $redirectUrl = $preference->init_point;
                } else {
                    $redirectUrl = $preference->sandbox_init_point;
                }
                return redirect($redirectUrl);
            } catch (Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            // callback url :  domain.com/plan/mercado

        } else {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }
    }

    public function getPaymentStatus(Request $request, $plan, $amount)
    {
        $this->planpaymentSetting();
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);

        $user = Auth::user();
        $orderID = time();

        if ($plan) {

            if ($plan && $request->has('status'))
            {

                    if (!empty($user->payment_subscription_id) && $user->payment_subscription_id != '') {
                        try {
                            $user->cancel_subscription($user->id);
                        } catch (\Exception $exception) {
                            \Log::debug($exception->getMessage());
                        }
                    }

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

                    $order                 = new Order();
                    $order->order_id       = $orderID;
                    $order->name           = $user->name;
                    $order->card_number    = '';
                    $order->card_exp_month = '';
                    $order->card_exp_year  = '';
                    $order->plan_name      = $plan->name;
                    $order->plan_id        = $plan->id;
                    $order->price          = $amount;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = $request->has('preference_id') ? $request->preference_id : '';
                    $order->payment_type   = __('Mercado Pago');
                    $order->payment_status = 'succeeded';
                    $order->receipt        = '';
                    $order->user_id        = $user->id;
                    $order->save();
                    $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);

                    if ($assignPlan['is_success'])
                    {
                        return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                    }
                    else
                    {
                        return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                    }
            } else {
                return redirect()->route('plans.index')->with('error', __('Transaction has been failed! '));
            }
        }
    }
}
