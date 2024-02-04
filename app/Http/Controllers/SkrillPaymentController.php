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
use Illuminate\Support\Facades\Validator;
use Obydul\LaraSkrill\SkrillRequest;
use Obydul\LaraSkrill\SkrillClient;
use Illuminate\Http\RedirectResponse;

class SkrillPaymentController extends Controller
{
    public $email;
    public $is_enabled;
    public $currancy;

    public function planpaymentSetting()
    {
        $admin_payment_setting = Utility::payment_settings();

        $this->currancy = isset($admin_payment_setting['currency'])?$admin_payment_setting['currency']:'';
        $this->email = isset($admin_payment_setting['skrill_email'])?$admin_payment_setting['skrill_email']:'';
        $this->is_enabled = isset($admin_payment_setting['is_skrill_enabled'])?$admin_payment_setting['is_skrill_enabled']:'off';
    }

    public function invoicePayWithSkrill(Request $request)
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

        $invoiceID = decrypt($request->invoice_id);
        $invoice = Bill::find($invoiceID);

        $data = Utility::getCompanyPaymentSetting($invoice->created_by);

        $this->currancy = isset($data['site_currency']) ? $data['site_currency'] : '';
        $this->currancy = isset($data['currency']) ? $data['currency'] : 'USD';
        $this->email = isset($data['skrill_email']) ? $data['skrill_email'] : '';
        $this->is_enabled = isset($data['is_skrill_enabled']) ? $data['is_skrill_enabled'] : 'off';

        $tran_id = md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id');
        $skill = new SkrillRequest();
        $skill->pay_to_email = $this->email;
        $skill->return_url = route('invoice.skrill', [encrypt($invoice->id), 'tansaction_id=' . MD5($tran_id)]);
        $skill->cancel_url = route('invoice.skrill', encrypt($invoice->id));

        // create object instance of SkrillRequest
        if (!empty($invoice->advocate)) {
            $advocate = Advocate::find($invoice->advocate);
            $email = $advocate->email;

        } else {
            $email = $invoice->custom_email;
        }

        $skill->transaction_id = MD5($tran_id); // generate transaction id
        $skill->amount = $request->amount;
        $skill->currency = $this->currancy;
        $skill->language = 'EN';
        $skill->prepare_only = '1';
        $skill->merchant_fields = 'site_name, customer_email';
        $skill->site_name = env('APP_NAME');
        $skill->customer_email = $email;

        // create object instance of SkrillClient
        $client = new SkrillClient($skill);
        $sid = $client->generateSID(); //return SESSION ID

        // handle error
        $jsonSID = json_decode($sid);
        if ($jsonSID != null && $jsonSID->code == "BAD_REQUEST") {
            return redirect()->back()->with('error', $jsonSID->message);
        }

        // do the payment
        $redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url
        if ($tran_id) {
            $data = [
                'amount' => $request->amount,
                'trans_id' => MD5($request['transaction_id']),
                'currency' => $this->currancy,
            ];
            session()->put('skrill_data', $data);
        }

        return redirect($redirectUrl);
    }
    public function getInvoicePaymentStatus($invoice_id, Request $request)
    {

        if (!empty($invoice_id)) {
            $invoice_id = decrypt($invoice_id);
            $invoice = Bill::find($invoice_id);

            $data = Utility::getCompanyPaymentSetting($invoice->created_by);

            $this->currancy = isset($data['site_currency']) ? $data['site_currency'] : '';
            $this->currancy = isset($data['currency']) ? $data['currency'] : 'USD';
            $this->email = isset($data['skrill_email']) ? $data['skrill_email'] : '';
            $this->is_enabled = isset($data['is_skrill_enabled']) ? $data['is_skrill_enabled'] : 'off';
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            if ($invoice) {
                try {
                    if (session()->has('skrill_data') && $request->has('tansaction_id')) {
                        $get_data = session()->get('skrill_data');
                        $manual_payments = BillPayment::where('bill_id', $invoice->id)->first();


                            $payments = new BillPayment();
                            $payments['bill_id'] = $invoice->id;
                            $payments['date'] = date('Y-m-d');
                            $payments['amount'] = isset($get_data['amount']) ? $get_data['amount'] : 0;
                            $payments['method'] = __('SKRILL');
                            $payments['order_id'] = $orderID;
                            $payments['currency'] = $data['site_currency'];
                            $payments['status'] = __('PAID');
                            $payments['note'] = $invoice->description;
                            $payments['txn_id'] = $request->input('tansaction_id');
                            $payments->save();


                        $payment = BillPayment::where('bill_id', $invoice_id)->sum('amount');

                        if ($payment >= $invoice->total_amount) {
                            $invoice->status = 'PAID';
                            $invoice->due_amount = 0.00;
                        } else {
                            $invoice->status = 'Partialy Paid';
                            $invoice->due_amount = $invoice->due_amount - isset($get_data['amount']) ? $get_data['amount'] : 0;
                        }

                        $invoice->save();

                        session()->forget('skrill_data');

                        if (Auth::check()) {
                            return redirect()->route('bills.show', $invoice->id)->with('success', __('Payment successfully added'));
                        } else {
                            return redirect()->back()->with('success', __(' Payment successfully added.'));
                        }

                    } else {
                       return redirect()->back()->with('error', __("Payment failed"));


                    }
                } catch (Exception $e) {
            return redirect()->back()->with('error',$e->getMessage());
        }
            } else {

                    return redirect()->back()->with('success', __(' Bill not found.'));


            }
        } else {
            return redirect()->back()->with('success', __(' Bill not found.'));


        }
    }

    public function planPayWithSkrill(Request $request)
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
                        return redirect()->back()->with('error', __('This coupon code has expired.'));
                    }
                    $price = $price - $discount_value;
                }
                else
                {
                    return redirect()->back()->with('error', __('This coupon code is invalid or has expired.'));
                }
            }
            if($price <= 0)
            {
                $authuser->plan = $plan->id;
                $authuser->save();
                $assignPlan = $authuser->assignPlan($plan->id);
                if($assignPlan['is_success'] == true && !empty($plan))
                {
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
                            'price' => $price==null?0:$price,
                            'price_currency' => !empty($this->currancy) ? $this->currancy : 'usd',
                            'txn_id' => '',
                            'payment_type' => __('SKRILL'),
                            'payment_status' => 'succeeded',
                            'receipt' => null,
                            'user_id' => $authuser->id,
                        ]
                    );
                    $assignPlan = $authuser->assignPlan($plan->id, $request->skrill_payment_frequency);
                    return redirect()->route('plans.index')->with('success', __('Plan activated Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __('Plan fail to upgrade.'));
                }
            }
            $tran_id = md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id');
            $skill               = new SkrillRequest();
            $skill->pay_to_email = $this->email;
            $skill->return_url   = route('plan.skrill',[$request->plan_id,'tansaction_id=' . MD5($tran_id),'payment_frequency='.$request->skrill_payment_frequency,'coupon_id='.$coupons_id]);
            $skill->cancel_url   = route('plans.index',[$request->plan_id]);
            // create object instance of SkrillRequest
            $skill->transaction_id  = MD5($tran_id); // generate transaction id
            $skill->amount          = $price;
            $skill->currency        = $this->currancy;
            $skill->language        = 'EN';
            $skill->prepare_only    = '1';
            $skill->merchant_fields = 'site_name, customer_email';
            $skill->site_name       = Auth::user()->name;
            $skill->customer_email  = Auth::user()->email;
            // create object instance of SkrillClient
            $client = new SkrillClient($skill);
            $sid    = $client->generateSID(); //return SESSION ID
            // handle error
            $jsonSID = json_decode($sid);
            if($jsonSID != null && $jsonSID->code == "BAD_REQUEST")
            {
            }
            // do the payment
            $redirectUrl = $client->paymentRedirectUrl($sid); //return redirect url
            if($tran_id)
            {
                $data = [
                    'amount' => $price,
                    'trans_id' => MD5($request['transaction_id']),
                    'currency' =>$this->currancy,
                ];
                session()->put('skrill_data', $data);
            }

            try
            {
                return  new RedirectResponse($redirectUrl);
            }
            catch(\Exception $e)
            {
                return redirect()->route('plans.index')->with('error', __('Transaction has been failed!'));
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Plan is deleted.');
        }
    }

    public function getPaymentStatus(Request $request,$plan)
    {
        $this->planpaymentSetting();
        $planID         = \Illuminate\Support\Facades\Crypt::decrypt($plan);
        $plan           = Plan::find($planID);
        $user = Auth::user();
        $orderID = time();
        if($plan)
        {
            try
            {
                if(session()->has('skrill_data'))
                {
                    $get_data = session()->get('skrill_data');

                    if($request->has('coupon_id') && $request->coupon_id != '')
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
                    $order->price          = isset($coupons) ? $discounted_price : $plan->price;
                    $order->price_currency = $this->currancy;
                    $order->txn_id         = isset($request->transaction_id) ? $request->transaction_id : '';
                    $order->payment_type   = __('Skrill');
                    $order->payment_status = 'Succeeded';
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
                return redirect()->back()->with('error', __('Plan not found!'));
            }
        }
    }
}
