<?php

namespace App\Http\Controllers;

use App\Models\BankTransfer;
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
use Illuminate\Support\Facades\Validator;

class BankTransferController extends Controller
{
    public $currancy,$invoiceData;
    public function planPayWithbank(Request $request)
    {

        if ($request->payment_receipt) {


            $validator = Validator::make(
                $request->all(), [
                    'payment_receipt' => 'required|image',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

        }else{
            return redirect()->back()->with('error',__('Payment reciept required.'));
        }

        $planID = \Illuminate\Support\Facades\Crypt::decrypt($request->plan_id);
        $plan = Plan::find($planID);
        $authuser = Auth::user();
        $coupon_id = '';
        $user = Auth::user();
        $orderID = time();
        $order = Order::where('plan_id', $planID)->where('payment_status', 'Pending')->where('user_id', $authuser->id)->first();
        if ($order) {
            return redirect()->route('plans.index')->with('error', __('You already send Payment request to this plans.'));
        }
        if ($request->payment_receipt) {


            $dir = 'uploads/receipt/';
            $filenameWithExt = $request->file('payment_receipt')->getClientOriginalName();
            $path = Utility::upload_file($request, 'payment_receipt', $filenameWithExt, $dir, []);
            if ($path['flag'] == 1) {
                $payment_receipt = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
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
                        if ($usedCoupun >= $coupons->limit) {
                            return Utility::error_res(__('This coupon code has expired.'));
                        }
                        $price = $price - $discount_value;
                        $coupon_id = $coupons->id;
                    } else {
                        return Utility::error_res(__('This coupon code is invalid or has expired.'));
                    }
                }

                if ($request->has('coupon') && $request->coupon != '') {
                    $coupons = Coupon::where('code', $request->coupon)->first();
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
                $order->price = isset($coupons) ? $plan->discounted_price : $plan->price;
                $order->price_currency = !empty($admin_payment_setting['currency']) ? $admin_payment_setting['currency'] : 'USD';
                $order->txn_id = isset($request->TXNID) ? $request->TXNID : '';
                $order->payment_type = __('Bank Transfer');
                $order->payment_status = 'Pending';
                $order->receipt = $payment_receipt;
                $order->user_id = $user->id;
                $order->save();

                return redirect()->route('plans.index')->with('success', __('Plan payment request send successfully!'));
            }
        }
    }
    public function destroy(order $order)
    {

        if ($order) {
            $order->delete();
            return redirect()->back()->with('success', __('Order Successfully Deleted.'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }

    public function show(Order $order, $id)
    {
        $order = Order::find($id);
        $admin_payment_setting = Utility::payment_settings();

        return view('order.show', compact('order', 'admin_payment_setting'));
    }
    public function orderapprove($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order = Order::find($id);
            $user = User::find($order->user_id);
            $plann = Plan::find($order->plan_id);
            $order->payment_status = 'Approved';
            $order->save();
            $assignPlan = $user->assignPlan($order->plan_id, $plann->duration);
            return redirect()->back()->with('success', __('Order Successfully Approved'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }
    public function orderreject($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->payment_status = 'Rejected';
            $order->save();
            return redirect()->back()->with('success', __('Order Successfully Rejected'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong'));
        }
    }
    public function invoicePayWithbank(Request $request)
    {

        $invoice = Bill::find($request->invoice_id);
        $this->invoiceData = $invoice;

        $get_amount = $request->amount;
        $request->validate(['amount' => 'required|numeric|min:0']);
        if ($request->payment_receipt) {

            $validation = [
                'max:' . '20480',
            ];
            $image_size = $request->file('payment_receipt')->getSize();



                $dir = 'uploads/receipt/';
                $filenameWithExt = $request->file('payment_receipt')->getClientOriginalName();
                $path = Utility::upload_file($request, 'payment_receipt', $filenameWithExt, $dir, $validation);
                if ($path['flag'] == 1) {
                    $payment_receipt = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }

        }
        if ($invoice) {
            $orderID = strtoupper(str_replace('.', '', uniqid('', true)));

            $BankTransfer = BankTransfer::create(
                [
                    'invoice_id' => $invoice->id,
                    'order_id' => $orderID,
                    'amount' => $get_amount,
                    'status' => 'Pending',
                    'receipt' => !empty($payment_receipt) ? $payment_receipt : 0,
                    'date' => date('Y-m-d H:i:s'),
                    'created_by' => $invoice->created_by,
                    'type' => __('Invoice'),
                ]
            );

            return redirect()->back()->with('success', __('Payment Successfully Done'));
        }
    }

    public function invoicebankPaymentDestroy($id)
    {
        $payment_show = BankTransfer::find($id);

        $payment_show->delete();
        return redirect()->back()->with('success', __('Payment Successfully Deleted'));
    }

    public function bankpaymentshow(BankTransfer $banktransfer, $invoicepayment_id)
    {
        $banktransfer = BankTransfer::find($invoicepayment_id);

        $payment_setting = Utility::payment_settings();
        return view('bills.view', compact('banktransfer', 'payment_setting'));
    }

    public function invoicebankstatus(Request $request, $banktransfer_id)
    {

        $banktransfer = Banktransfer::find($banktransfer_id);
        if ($banktransfer) {
            $banktransfer->status = $request->status;

            $banktransfer->update();
            if ($request->status == 'Approval') {
                $banktransfer->status = 'Approved';
                $invoice_payment = new BillPayment();
                $invoice_payment->txn_id = $banktransfer->order_id;
                $invoice_payment->bill_id = $banktransfer->invoice_id;
                $invoice_payment->amount = $banktransfer->amount;
                $invoice_payment->date = date('Y-m-d');
                $invoice_payment->method = 'Bank transfer';
                $invoice_payment->note = '';
                $invoice_payment->reciept = ($banktransfer->receipt == '0') ? '-' : $banktransfer->receipt;
                $invoice_payment->save();

                $invoice = Bill::find($banktransfer->invoice_id);
                $payment = BillPayment::where('bill_id', $invoice->id)->sum('amount');

                if ($payment >= $invoice->total_amount) {
                    $invoice->status = 'PAID';
                    $invoice->due_amount = 0.00;
                } else {
                    $invoice->status = 'Partialy Paid';
                    $invoice->due_amount = $invoice->due_amount - $banktransfer->amount;
                }

                $invoice->save();
            }
            $banktransfer->delete();

            return redirect()->back()->with('success', __('Invoice payment successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }
}
