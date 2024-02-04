<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\BillPayment;
use Illuminate\Http\Request;
use App\Models\Utility;
use App\Models\Plan;
use App\Models\UserCoupon;
use App\Models\User;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Exception;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Catch_;

class IyziPayController extends Controller
{
    public $currancy,$invoiceData,$callBackUrl,$returnUrl;
    public function initiatePayment(Request $request)
    {
        $planID    = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $authuser  = Auth::user();
        $adminPaymentSettings = Utility::payment_settings();
        $iyzipay_key = $adminPaymentSettings['iyzipay_key'];
        $iyzipay_secret = $adminPaymentSettings['iyzipay_secret'];
        $iyzipay_mode = $adminPaymentSettings['iyzipay_mode'];
        $currency = $adminPaymentSettings['currency'];
        $plan = Plan::find($planID);
        $coupon_id = '0';
        $price = $plan->price;
        $coupon_code = null;
        $discount_value = null;
        $coupons = Coupon::where('code', $request->coupon)->where('is_active', '1')->first();
        if ($coupons) {
            $coupon_code = $coupons->code;
            $usedCoupun     = $coupons->used_coupon();
            if ($coupons->limit == $usedCoupun) {
                $res_data['error'] = __('This coupon code has expired.');
            } else {
                $discount_value = ($plan->price / 100) * $coupons->discount;
                $price  = $price - $discount_value;
                if ($price < 0) {
                    $price = $plan->price;
                }
                $coupon_id = $coupons->id;
            }
        }
        $res_data['total_price'] = $price;
        $res_data['coupon']      = $coupon_id;
        // set your Iyzico API credentials
        try {

            $setBaseUrl = ($iyzipay_mode == 'local') ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com';
            $options = new \Iyzipay\Options();
            $options->setApiKey($iyzipay_key);
            $options->setSecretKey($iyzipay_secret);
            $options->setBaseUrl($setBaseUrl); // or "https://api.iyzipay.com" for production
            $ipAddress = Http::get('https://ipinfo.io/?callback=')->json();
            $address = ($authuser->address) ? $authuser->address : 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1';
            // create a new payment request
            $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
            $request->setLocale('en');
            $request->setPrice($res_data['total_price']);
            $request->setPaidPrice($res_data['total_price']);
            $request->setCurrency($currency);
            $request->setCallbackUrl(route('iyzipay.payment.callback',[$plan->id,$price,$coupon_code]));
            $request->setEnabledInstallments(array(1));
            $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
            $buyer = new \Iyzipay\Model\Buyer();
            $buyer->setId($authuser->id);
            $buyer->setName(explode(' ', $authuser->name)[0]);
            $buyer->setSurname(explode(' ', $authuser->name)[0]);
            $buyer->setGsmNumber("+" . $authuser->dial_code . $authuser->phone);
            $buyer->setEmail($authuser->email);
            $buyer->setIdentityNumber(rand(0, 999999));
            $buyer->setLastLoginDate("2023-03-05 12:43:35");
            $buyer->setRegistrationDate("2023-04-21 15:12:09");
            $buyer->setRegistrationAddress($address);
            $buyer->setIp($ipAddress['ip']);
            $buyer->setCity($ipAddress['city']);
            $buyer->setCountry($ipAddress['country']);
            $buyer->setZipCode($ipAddress['postal']);
            $request->setBuyer($buyer);
            $shippingAddress = new \Iyzipay\Model\Address();
            $shippingAddress->setContactName($authuser->name);
            $shippingAddress->setCity($ipAddress['city']);
            $shippingAddress->setCountry($ipAddress['country']);
            $shippingAddress->setAddress($address);
            $shippingAddress->setZipCode($ipAddress['postal']);
            $request->setShippingAddress($shippingAddress);
            $billingAddress = new \Iyzipay\Model\Address();
            $billingAddress->setContactName($authuser->name);
            $billingAddress->setCity($ipAddress['city']);
            $billingAddress->setCountry($ipAddress['country']);
            $billingAddress->setAddress($address);
            $billingAddress->setZipCode($ipAddress['postal']);
            $request->setBillingAddress($billingAddress);
            $basketItems = array();
            $firstBasketItem = new \Iyzipay\Model\BasketItem();
            $firstBasketItem->setId("BI101");
            $firstBasketItem->setName("Binocular");
            $firstBasketItem->setCategory1("Collectibles");
            $firstBasketItem->setCategory2("Accessories");
            $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
            $firstBasketItem->setPrice($res_data['total_price']);
            $basketItems[0] = $firstBasketItem;
            $request->setBasketItems($basketItems);

            $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

            return redirect()->to($checkoutFormInitialize->getpaymentPageUrl());
        } catch (\Exception $e) {

            return redirect()->route('plans.index')->with('errors', $e->getMessage());
        }
    }

    public function iyzipayCallback(Request $request,$planID,$price,$coupanCode = null)
    {
        $plan = Plan::find($planID);
        $user = Auth::user();
        $order = new Order();
        $order->order_id = time();
        $order->name = $user->name;
        $order->card_number = '';
        $order->card_exp_month = '';
        $order->card_exp_year = '';
        $order->plan_name = $plan->name;
        $order->plan_id = $plan->id;
        $order->price = $price;
        $order->price_currency = !empty($this->currancy) ? $this->currancy : 'USD';
        $order->txn_id = time();
        $order->payment_type = __('Iyzipay');
        $order->payment_status = 'succeeded';
        $order->txn_id = '';
        $order->receipt = '';
        $order->user_id = $user->id;
        $order->save();
        $user = User::find($user->id);
        $coupons = Coupon::where('code', $coupanCode)->where('is_active', '1')->first();
        if (!empty($coupons)) {
            $userCoupon         = new UserCoupon();
            $userCoupon->user   = $user->id;
            $userCoupon->coupon = $coupons->id;
            $userCoupon->order  = $order->order_id;
            $userCoupon->save();
            $usedCoupun = $coupons->used_coupon();
            if ($coupons->limit <= $usedCoupun) {
                $coupons->is_active = 0;
                $coupons->save();
            }
        }
        $assignPlan = $user->assignPlan($plan->id);


        if ($assignPlan['is_success']) {
            return redirect()->route('plans.index')->with('success', __('Plan activated Successfully.'));
        } else {
            return redirect()->route('plans.index')->with('error', __($assignPlan['error']));
        }
    }

    public function invoicepaywithiyzipay(Request $request){
        $invoice_id = $request->input('invoice_id');
        $invoice = Bill::find($invoice_id);
        $this->invoiceData  = $invoice;

        $get_amount = $request->amount;

        $user = User::where('id', $invoice->created_by)->first();


        if ($invoice) {

            if ($get_amount > $invoice->due_amount) {
                return redirect()
                    ->route('bills.show', \Crypt::encrypt($invoice->id))
                    ->with('error', 'Invalid amount.');
            }

            $user      = User::find($invoice->created_by);
            $PaymentSettings = Utility::getCompanyPaymentSetting($invoice->created_by);

            $iyzipay_key = $PaymentSettings['iyzipay_key'];
            $iyzipay_secret = $PaymentSettings['iyzipay_secret'];
            $iyzipay_mode = $PaymentSettings['iyzipay_mode'];

            $currency = $PaymentSettings['site_currency'];

            $res_data['total_price'] = $get_amount;
            // set your Iyzico API credentials

                // set your Iyzico API credentials
            try {
                $setBaseUrl = ($iyzipay_mode == 'local') ? 'https://sandbox-api.iyzipay.com' : 'https://api.iyzipay.com';
                $options = new \Iyzipay\Options();
                $options->setApiKey($iyzipay_key);
                $options->setSecretKey($iyzipay_secret);
                $options->setBaseUrl($setBaseUrl); // or "https://api.iyzipay.com" for production
                $ipAddress = Http::get('https://ipinfo.io/?callback=')->json();
                $address = ($user->address) ? $user->address : 'Nidakule Göztepe, Merdivenköy Mah. Bora Sok. No:1';
                // create a new payment request
                $request = new \Iyzipay\Request\CreateCheckoutFormInitializeRequest();
                $request->setLocale('en');
                $request->setPrice($res_data['total_price']);
                $request->setPaidPrice($res_data['total_price']);
                $request->setCurrency($currency);
                $request->setCallbackUrl(route('invoice.iyzipay.status',[$invoice->id,$get_amount]));
                $request->setEnabledInstallments(array(1));
                $request->setPaymentGroup(\Iyzipay\Model\PaymentGroup::PRODUCT);
                $buyer = new \Iyzipay\Model\Buyer();
                $buyer->setId($user->id);
                $buyer->setName(explode(' ', $user->name)[0]);
                $buyer->setSurname(explode(' ', $user->name)[0]);
                $buyer->setGsmNumber("+" . $user->dial_code . $user->phone);
                $buyer->setEmail($user->email);
                $buyer->setIdentityNumber(rand(0, 999999));
                $buyer->setLastLoginDate("2023-03-05 12:43:35");
                $buyer->setRegistrationDate("2023-04-21 15:12:09");
                $buyer->setRegistrationAddress($address);
                $buyer->setIp($ipAddress['ip']);
                $buyer->setCity($ipAddress['city']);
                $buyer->setCountry($ipAddress['country']);
                $buyer->setZipCode($ipAddress['postal']);
                $request->setBuyer($buyer);
                $shippingAddress = new \Iyzipay\Model\Address();
                $shippingAddress->setContactName($user->name);
                $shippingAddress->setCity($ipAddress['city']);
                $shippingAddress->setCountry($ipAddress['country']);
                $shippingAddress->setAddress($address);
                $shippingAddress->setZipCode($ipAddress['postal']);
                $request->setShippingAddress($shippingAddress);
                $billingAddress = new \Iyzipay\Model\Address();
                $billingAddress->setContactName($user->name);
                $billingAddress->setCity($ipAddress['city']);
                $billingAddress->setCountry($ipAddress['country']);
                $billingAddress->setAddress($address);
                $billingAddress->setZipCode($ipAddress['postal']);
                $request->setBillingAddress($billingAddress);
                $basketItems = array();
                $firstBasketItem = new \Iyzipay\Model\BasketItem();
                $firstBasketItem->setId("BI101");
                $firstBasketItem->setName("Binocular");
                $firstBasketItem->setCategory1("Collectibles");
                $firstBasketItem->setCategory2("Accessories");
                $firstBasketItem->setItemType(\Iyzipay\Model\BasketItemType::PHYSICAL);
                $firstBasketItem->setPrice($res_data['total_price']);
                $basketItems[0] = $firstBasketItem;
                $request->setBasketItems($basketItems);

                $checkoutFormInitialize = \Iyzipay\Model\CheckoutFormInitialize::create($request, $options);

                return redirect()->to($checkoutFormInitialize->getpaymentPageUrl());
            } catch (\Exception $e) {

                return redirect()->route('bills.show')->with('errors', $e->getMessage());
            }

        }
        else{
            return redirect()
            ->route('bills.show', \Crypt::encrypt($invoice->id))
            ->with('error', $response['message'] ?? 'Something went wrong.');
        }

        return redirect()->back()->with('error', __('Unknown error occurred'));
    }
    public function invoiceiyzipaystatus($invoice_id, $amount)  {

        $invoice = Bill::find($invoice_id);

        $user = User::where('id', $invoice->created_by)->first();
        $objUser = $user;

        if ($invoice) {
            try {
                $invoice_payment                 = new BillPayment();
                $invoice_payment->bill_id     = $invoice_id;
                $invoice_payment->amount         = $amount;
                $invoice_payment->date           = date('Y-m-d');
                $invoice_payment->method   = 'IyziPay';
                $invoice_payment->save();

                $payment = BillPayment::where('bill_id', $invoice->id)->sum('amount');

                if ($payment >= $invoice->total_amount) {
                    $invoice->status = 'PAID';
                    $invoice->due_amount = 0.00;
                } else {
                    $invoice->status = 'Partialy Paid';
                    $invoice->due_amount = $invoice->due_amount - $amount;
                }
                $invoice->save();
                if (Auth::user()) {
                    return redirect()->route('bills.show', $invoice_id)->with('success', __('Invoice paid Successfully!!') . ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                } else {
                    $id = Crypt::encrypt($invoice_id);

                    return redirect()->route('pay.invoice', $id)->with('success', __('Invoice paid Successfully!!') . ((isset($msg) ? '<br> <span class="text-danger">' . $msg . '</span>' : '')));
                }

                if (Auth::check()) {
                    return redirect()->route('invoices.show', $invoice_id['invoice_id'])->with('success', __('Invoice paid Successfully!'));
                } else {
                    return redirect()->route('pay.invoice', encrypt($invoice_id['invoice_id']))->with('ERROR', __('Transaction fail'));
                }
            } catch (\Exception $e) {

                if (Auth::check()) {
                    return redirect()->route('bills.show', $invoice_id['invoice_id'])->with('error', $e->getMessage());
                } else {
                    return redirect()->route('pay.invoice', encrypt($invoice_id))->with('success', $e->getMessage());
                }
            }
        }
            else {
                if (Auth::check()) {
                    return redirect()->route('invoices.show', $invoice_id['invoice_id'])->with('error', __('Invoice not found.'));
                } else {
                    return redirect()->route('pay.invoice', encrypt($invoice_id['invoice_id']))->with('success', __('Invoice not found.'));
                }
            }
    }

}


