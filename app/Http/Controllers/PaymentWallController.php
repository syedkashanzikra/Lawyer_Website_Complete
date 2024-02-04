<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaymentWallController extends Controller
{
    public $currancy;
    public $secret_key;
    public $public_key;
    public $is_enabled;

    public function planpay(Request $request)
    {
        $data = $request->all();
        $admin_payment_setting = Utility::payment_settings();
        return view('plan.planpay', compact('data', 'admin_payment_setting'));

    }

    public function planpaymentSetting()
    {
        $payment_setting = Utility::payment_settings();

        $this->currancy = isset($payment_setting['currency']) ? $payment_setting['currency'] : '';

        $this->secret_key = isset($payment_setting['paymentwall_private_key']) ? $payment_setting['paymentwall_private_key'] : '';
        $this->public_key = isset($payment_setting['paymentwall_public_key']) ? $payment_setting['paymentwall_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paymentwall_enabled']) ? $payment_setting['is_paymentwall_enabled'] : 'off';
        return $this;
    }

    public function planPayWithPaymentWall(Request $request, $plan_id)
    {
        $admin_payment_setting = Utility::payment_settings();
        $this->planpaymentSetting();

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($plan_id);
        $plan = Plan::find($planID);

        $authuser = Auth::user();
        $coupon_id = '';
        if ($plan) {

            /* Check for code usage */
            $plan->discounted_price = false;
            $price = $plan->price;

            if (isset($request->coupon) && !empty($request->coupon)) {
                $request->coupon = trim($request->coupon);

                $coupons = Coupon::where('code', strtoupper($request->coupon))->where('is_active', '1')->first();
                if (!empty($coupons)) {
                    $usedCoupun = $coupons->used_coupon();
                    $discount_value = ($price / 100) * $coupons->discount;
                    $plan->discounted_price = $price - $discount_value;

                    if ($usedCoupun >= $coupons->limit) {
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                    $coupon_id = $coupons->id;

                } else {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
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
                            'payment_type' => 'PaymentWall',
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 1;

                    return $res;
                } else {
                    $res['msg'] = __("Plan successfully upgraded.");
                    $res['flag'] = 2;

                    return $res;
                }
            } else {

                \Paymentwall_Config::getInstance()->set(array(

                    'private_key' => $admin_payment_setting['paymentwall_private_key'],
                ));

                $parameters = $request->all();

                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => $price,
                    'currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123',
                );

                $charge = new \Paymentwall_Charge ();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(), true);
                $response = $charge->getPublicData();

                if ($charge->isSuccessful() and empty($responseData['secure'])) {
                    if ($charge->isCaptured()) {
                        if ($request->has('coupon') && $request->coupon != '') {
                            $coupons = Coupon::find($request->coupon);
                            if (!empty($coupons)) {
                                $userCoupon = new UserCoupon();
                                $userCoupon->user = $authuser->id;
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
                        $order->name = $authuser->name;
                        $order->card_number = '';
                        $order->card_exp_month = '';
                        $order->card_exp_year = '';
                        $order->plan_name = $plan->name;
                        $order->plan_id = $plan->id;
                        $order->price = isset($paydata['amount']) ? $paydata['amount'] : $price;
                        $order->price_currency = $this->currancy;
                        $order->txn_id = isset($paydata['txid']) ? $paydata['txid'] : 0;
                        $order->payment_type = __('PaymentWall');
                        $order->payment_status = 'success';
                        $order->receipt = '';
                        $order->user_id = $authuser->id;
                        $order->save();

                        $assignPlan = $authuser->assignPlan($plan->id);

                        if ($assignPlan['is_success']) {

                            $res['flag'] = 1;
                            return $res;

                        }
                    } elseif ($charge->isUnderReview()) {
                        $res['flag'] = 2;
                        return $res;
                    }
                } elseif (!empty($responseData['secure'])) {
                    $response = json_encode(array('secure' => $responseData['secure']));
                } else {
                    $errors = json_decode($response, true);
                    $res['msg'] = __("Trasnsaction has been Fail.");

                    $res['flag'] = 2;
                    return $res;
                }

            }

            $res['flag'] = 2;
            return $res;
        } else {
            $res['flag'] = 2;
            return $res;
        }
    }

    public function planerror(Request $request, $flag)
    {
        if ($flag == 1) {
            return redirect()->route("plans.index")->with('success', __('Plan activated Successfully! '));
        } else {
            return redirect()->route("plans.index")->with('error', __('Transaction has been failed! '));
        }

    }

    public function invoicePayWithPaymentwall(Request $request)
    {
        $data = $request->all();
        $invoice_id = decrypt($data['invoice_id']);

        $invoice = Bill::find($invoice_id);

        $admin_payment_setting = $this->paymentSetting($invoice->created_by);

        return view('bills.paymentwall', compact('data', 'admin_payment_setting'));
    }

    public function paymentSetting($id)
    {
        $payment_setting = Utility::getCompanyPaymentSetting($id);

        $this->currancy = isset($payment_setting['currency']) ? $payment_setting['currency'] : 'USD';
        $this->secret_key = isset($payment_setting['paymentwall_private_key']) ? $payment_setting['paymentwall_private_key'] : '';
        $this->public_key = isset($payment_setting['paymentwall_public_key']) ? $payment_setting['paymentwall_public_key'] : '';
        $this->is_enabled = isset($payment_setting['is_paymentwall_enabled']) ? $payment_setting['is_paymentwall_enabled'] : 'off';
        return $this;
    }

    public function getInvoicePaymentStatus($invoice_id, Request $request)
    {
        if (!empty($invoice_id)) {

            $invoice    = Bill::find($invoice_id);
            $data = Utility::getCompanyPaymentSetting($invoice->created_by);

            if ($invoice) {

                \Paymentwall_Config::getInstance()->set(array(
                    'private_key' => $this->secret_key
                ));

                $parameters = $request->all();

                $chargeInfo = array(
                    'email' => $parameters['email'],
                    'history[registration_date]' => '1489655092',
                    'amount' => isset($request->amount) ? $request->amount : 0,
                    'currency' => !empty($this->currancy) ? $this->currancy : 'USD',
                    'token' => $parameters['brick_token'],
                    'fingerprint' => $parameters['brick_fingerprint'],
                    'description' => 'Order #123'
                );

                $charge = new \Paymentwall_Charge();
                $charge->create($chargeInfo);
                $responseData = json_decode($charge->getRawResponseData(), true);
                $response = $charge->getPublicData();

                if ($charge->isSuccessful() and empty($responseData['secure'])) {

                    if ($charge->isCaptured()) {

                        $new = new BillPayment();
                        $new->bill_id = $invoice_id;
                        $new->txn_id = '';
                        $new->date = Date('Y-m-d');
                        $new->amount = isset($request->amount) ? $request->amount : 0;
                        $new->description = '';
                        $new->payment_method = 'Paymentwall';
                        $new->save();

                        $payment = BillPayment::where('bill_id', $invoice_id)->sum('amount');

                        if ($payment >= $invoice->total_amount) {
                            $invoice->status = 'PAID';
                            $invoice->due_amount = 0.00;
                        } else {
                            $invoice->status = 'Partialy Paid';
                            $invoice->due_amount = $invoice->due_amount-isset($payment->amount->value) ? $payment->amount->value : 0;
                        }

                        $invoice->save();



                        if (Auth::check()) {
                            return redirect()->route('bills.show', $invoice_id)->with('success', __('Invoice paid Successfully!'));
                        } else {
                            return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', __('Invoice paid Successfully!'));
                        }
                    } elseif ($charge->isUnderReview()) {
                        $res['invoice'] = $invoice_id;
                        $res['flag'] = 2;
                        return $res;
                    }
                } else {
                    $errors = json_decode($response, true);
                    $res['invoice'] = $invoice_id;
                    $res['flag'] = 2;
                    return $res;
                }
            } else {
                if (Auth::check()) {
                    return redirect()->route('bills.show', $invoice_id)->with('error', __('Invoice not found.'));
                } else {
                    return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', __('Invoice not found.'));
                }
            }
        } else {
            if (Auth::check()) {
                return redirect()->route('bills.index', $invoice_id)->with('error', __('Oops something went wrong.'));
            } else {
                return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', __('Oops something went wrong.'));
            }
        }
    }


     public function invoiceerror(Request $request, $flag, $invoice_id)
    {

        if (Auth::check()) {
            if ($flag == 1) {
                return redirect()->route('bills.index')->with('success', __('Payment added Successfully'));
            } else {
                return redirect()->route('bills.index')->with('error', __('Transaction has been failed! '));
            }
        } else {
            if ($flag == 1) {
                return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('success', __('Payment added Successfully '));
            } else {
                return redirect()->route('pay.invoice', \Illuminate\Support\Facades\Crypt::encrypt($invoice_id))->with('error', __('Transaction has been failed! '));
            }
        }
    }
}
