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
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PaypalController extends Controller
{
    protected $invoiceData;
    private $_api_context;
    public $currancy;

    public function paymentConfig($userId)
    {

        $payment_setting = Utility::getCompanyPaymentSetting($userId);

        if ($payment_setting['paypal_mode'] == 'live') {
            config([
                'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        } else {
            config([
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        }

    }

    public function planpaymentSetting()
    {
        $payment_setting = Utility::payment_settings();
        $this->currancy = env('CURRENCY');

        if ($payment_setting['paypal_mode'] == 'live') {
            config([
                'paypal.live.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.live.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        } else {
            config([
                'paypal.sandbox.client_id' => isset($payment_setting['paypal_client_id']) ? $payment_setting['paypal_client_id'] : '',
                'paypal.sandbox.client_secret' => isset($payment_setting['paypal_secret_key']) ? $payment_setting['paypal_secret_key'] : '',
                'paypal.mode' => isset($payment_setting['paypal_mode']) ? $payment_setting['paypal_mode'] : '',
            ]);
        }
        return $payment_setting;
    }

    public function PayWithPaypal(Request $request, $invoice_id)
    {
        try {
            $id = decrypt($invoice_id);
            $invoice = Bill::find($id);

            $this->invoiceData = $invoice;
            $this->paymentConfig($invoice->created_by);

            $settings = DB::table('settings')->where('created_by', '=', $invoice->created_by)->get()->pluck('value', 'name');

            $payment_setting = DB::table('payment_settings')->get()->pluck('value', 'name')->toArray();

            $request->validate(['amount' => 'required|numeric|min:0']);

            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));

            if ($invoice) {

                $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

                $name = Utility::invoiceNumberFormat($settings, $invoice->id);

                $paypalToken = $provider->getAccessToken();

                $response = $provider->createOrder([
                    "intent" => "CAPTURE",
                    "application_context" => [
                        "return_url" => route('get.payment.status', [$invoice->id, $request->amount]),
                        "cancel_url" => route('get.payment.status', [$invoice->id, $request->amount]),
                    ],
                    "purchase_units" => [
                        0 => [
                            "amount" => [
                                "currency_code" => $payment_setting['site_currency'],
                                "value" => $request->amount,
                            ],
                        ],
                    ],
                ]);

                if (isset($response['id']) && $response['id'] != null) {
                    // redirect to approve href
                    foreach ($response['links'] as $links) {
                        if ($links['rel'] == 'approve') {
                            return redirect()->away($links['href']);
                        }
                    }
                    return redirect()
                        ->route('bills.show', encrypt($invoice->id))
                        ->with('error', 'Something went wrong.');
                } else {
                    return redirect()
                        ->route('bills.show', encrypt($invoice->id))
                        ->with('error', $response['message'] ?? 'Something went wrong.');
                }

            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }

        } catch (Exception $e) {

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function GetPaymentStatus(Request $request, $invoice_id, $amount)
    {
        try {
            $invoice = Bill::find($invoice_id);

            $this->paymentConfig($invoice->created_by);

            $provider = new PayPalClient;

            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);
            $payment_id = Session::get('paypal_payment_id');

            if (empty($request->PayerID || empty($request->token))) {
                return redirect()->back()->with('error', __('Payment failed'));
            }

            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));
            $payment_setting = DB::table('payment_settings')->get()->pluck('value', 'name')->toArray();

            $payments = new BillPayment();
            $payments['bill_id'] = $invoice_id;
            $payments['date'] = date('Y-m-d');
            $payments['amount'] = $amount;
            $payments['method'] = __('PAYPAL');
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

        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // plan
    public function planPayWithPaypal(Request $request)
    {
        $this->planpaymentSetting();

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $authuser = Auth::user();
        $coupons_id = '';
        if ($plan) {
            $plan->discounted_price = false;
            $price = $plan->price;
            if (isset($request->coupon) && !empty($request->coupon)) {
                $request->coupon = trim($request->coupon);
                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;
                    $coupons_id = $coupons->id;
                    if ($usedCoupun >= $coupons->limit) {
                        return Utility::error_res(__('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                } else {
                    return Utility::error_res(__('This coupon code is invalid or has expired.'));
                }
            }

            if ($price <= 0) {
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
                            'payment_type' => 'PAYPAL',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 2;
                    
                    return redirect()->route('plans.index')->with('success', $res['msg']);
                } else {
                    return Utility::error_res(__('Plan fail to upgrade.'));
                }
            }


            $paypalToken = $provider->getAccessToken();

            $response = $provider->createOrder([
                "intent" => "CAPTURE",
                "application_context" => [
                    "return_url" => route('plan.get.payment.status', [$plan->id, $price]),
                    "cancel_url" => route('plan.get.payment.status', [$plan->id, $price]),
                ],
                "purchase_units" => [
                    0 => [
                        "amount" => [
                            "currency_code" => !empty($this->currancy) ? $this->currancy : 'usd',
                            "value" => $price,
                        ],
                    ],
                ],
            ]);

            if (isset($response['id']) && $response['id'] != null) {
                // redirect to approve href
                foreach ($response['links'] as $links) {
                    if ($links['rel'] == 'approve') {
                        return redirect()->away($links['href']);
                    }
                }
                return redirect()->back()->with('error', 'Something went wrong.');

            } else {
                return redirect()->back()->with('error', $response['message'] ?? 'Something went wrong.');
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }

    public function planGetPaymentStatus(Request $request, $plan_id, $amount)
    {

        $this->planpaymentSetting();

        $user = Auth::user();
        $plan = Plan::find($plan_id);

        if ($plan) {
            $provider = new PayPalClient;
            $provider->setApiCredentials(config('paypal'));
            $provider->getAccessToken();
            $response = $provider->capturePaymentOrder($request['token']);

            $payment_id = Session::get('paypal_payment_id');
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            if (isset($response['status']) && $response['status'] == 'COMPLETED') {

                if ($response['status'] == 'COMPLETED') {
                    $statuses = 'Succeeded';
                }

                if ($request->has('coupon_id') && $request->coupon_id != '') {
                    $coupons = Coupon::find($request->coupon_id);
                    $discount_value = ($plan->price / 100) * $coupons->discount;
                    $discounted_price = $plan->price - $discount_value;

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
                $order = new Order();
                $order->order_id = $orderID;
                $order->name = $user->name;
                $order->card_number = '';
                $order->card_exp_month = '';
                $order->card_exp_year = '';
                $order->plan_name = $plan->name;
                $order->plan_id = $plan->id;
                $order->price = $amount;

                $order->txn_id = isset($request->TXNID) ? $request->TXNID : '';
                $order->payment_type = __('PAYPAL');
                $order->payment_status = $statuses;
                $order->receipt = '';
                $order->user_id = $user->id;
                $order->save();

                $assignPlan = $user->assignPlan($plan->id, $request->payment_frequency);

                if ($assignPlan['is_success']) {
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                } else {
                    return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
                }
            } else {
                return redirect()
                    ->route('plans.index')
                    ->with('error', $response['message'] ?? 'Something went wrong.');
            }
        } else {
            return redirect()->route('plans.index')->with('error', __('Plan is deleted.'));
        }
    }
}
