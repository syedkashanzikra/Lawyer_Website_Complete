<?php

namespace App\Http\Controllers;

use App\Exports\BillsExport;
use App\Models\Advocate;
use App\Models\BankTransfer;
use App\Models\Bill;
use App\Models\BillPayment;
use App\Models\InvoiceProduct;
use App\Models\Notification;
use App\Models\Plan;
use App\Models\Tax;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\Utility;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class BillController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage bill')) {
            $bills = Bill::where('created_by', Auth::user()->creatorId())->get();
            return view('bills.index', compact('bills'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->can('create bill')) {
            $advocates = User::where('created_by',Auth::user()->creatorId())->where('type','advocate')->pluck('name', 'id');
            $taxes = Tax::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $invoice_number = Auth::user()->invoiceNumberFormat($this->invoiceNumber());
            $clients = User::where('created_by',Auth::user()->creatorId())->where('type','client')->pluck('name', 'id');

            return view('bills.create', compact('advocates', 'taxes', 'invoice_number','clients'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function invoiceNumber()
    {

        $latest = Bill::where('created_by', '=', Auth::user()->creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->id + 1;
        return $latest;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        if (Auth::user()->can('create bill')) {

            $validator = Validator::make(
                $request->all(), [
                    'bill_from' => 'required',
                    'title' => 'required',
                    'bill_number' => 'required',
                    'due_date' => 'required',
                    'description' => 'required',
                    'items' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            if ($request->bill_from == 'advocate') {
                $validator = Validator::make(
                    $request->all(), [
                        'advocate' => 'required',
                    ]
                );

            }
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $data = $request->items;

            $bill = new Bill();
            $bill['bill_from'] = $request->bill_from;

            if ($request->bill_from == 'advocate') {
                $bill['advocate'] = $request->advocate;
            } else {
                $bill['advocate'] = $request->company;
            }
            $bill['title'] = $request->title;
            $bill['bill_number'] = $request->bill_number;
            $bill['reciept_date'] = $request->reciept_date;
            $bill['due_date'] = $request->due_date;
            $bill['subtotal'] = $request->subtotal;
            $bill['total_tax'] = $request->total_tax;
            $bill['total_amount'] = $request->total_amount;
            $bill['due_amount'] = $request->total_amount;
            $bill['description'] = $request->description;
            $bill['created_by'] = Auth::user()->creatorId();
            $bill['bill_to'] = !empty($request->client) ? $request->client : Auth::user()->id;
            $bill['total_disc'] = $request->total_disc;
            $bill->save();

            $products = $request->items;

            for ($i = 0; $i < count($products); $i++) {
                $invoiceProduct = new InvoiceProduct();
                $invoiceProduct->invoice_id = $bill->id;
                $invoiceProduct->product_name = $products[$i]['particulars'];
                $invoiceProduct->quantity = $products[$i]['numbers'];
                $invoiceProduct->tax = $products[$i]['tax'];
                $invoiceProduct->price = $products[$i]['cost'];
                $invoiceProduct->discount = $products[$i]['discount'];
                $invoiceProduct->save();

                $data[$i]['id'] = $invoiceProduct->id;
            }

            $bill['items'] = json_encode($data);
            $bill->save();

            //notification
            Notification::create([
                'bill_id' => $bill->id,
                'bill_to' => $bill->bill_to,
                'bill_from' => $bill->bill_to,
                'due_date' => $bill->due_date,
            ]);

            return redirect()->route('bills.index')->with('success', __('Bill successfully created.'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        if (Auth::user()->can('view bill')) {

            $bill = Bill::find($id);
            $items = json_decode($bill->items, true);
            $payments = BillPayment::where('bill_id', $id)->get();
            $bankPayments = BankTransfer::where('invoice_id', $bill->id)->get();
            $plan = Plan::find(Auth::user()->plan);

            return view('bills.show', compact('bill', 'items','payments','bankPayments'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (Auth::user()->can('edit bill')) {
            $advocates = User::where('created_by',Auth::user()->creatorId())->where('type','advocate')->pluck('name', 'id');
            $taxes = Tax::where('created_by', Auth::user()->creatorId())->get()->pluck('name', 'id');
            $bill = Bill::find($id);
            $clients = User::where('created_by',Auth::user()->creatorId())->where('type','client')->pluck('name', 'id');

            return view('bills.edit', compact('advocates', 'taxes', 'bill','clients'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (Auth::user()->can('edit bill')) {

            $validator = Validator::make(
                $request->all(), [
                    'bill_from' => 'required',
                    'title' => 'required',
                    'bill_number' => 'required',
                    'due_date' => 'required',
                    'description' => 'required',
                    'items' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            if ($request->bill_from == 'advocate') {
                $validator = Validator::make(
                    $request->all(), [
                        'advocate' => 'required',
                    ]
                );

            }


            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $data = $request->items;


            $bill = Bill::find($id);
            $bill['bill_from'] = $request->bill_from;

            if ($request->bill_from == 'advocate') {
                $bill['advocate'] = $request->advocate;
            } else {
                $bill['advocate'] = $request->company;
            }

            $bill['title'] = $request->title;
            $bill['bill_number'] = $request->bill_number;
            $bill['reciept_date'] = $request->reciept_date;
            $bill['due_date'] = $request->due_date;
            $bill['items'] = json_encode($data);
            $bill['subtotal'] = $request->subtotal;
            $bill['total_tax'] = $request->total_tax;
            $bill['total_amount'] = $request->total_amount;
            $bill['due_amount'] = $request->total_amount;
            $bill['description'] = $request->description;
            $bill['created_by'] = Auth::user()->id;
            $bill['bill_to'] = !empty($request->client) ? $request->client : Auth::user()->id;
            $bill['total_disc'] = $request->total_disc;
            $bill->save();

            $products = $request->items;

            for ($i = 0; $i < count($products); $i++) {
                $invoiceProduct = InvoiceProduct::find($products[$i]['id']);

                if ($invoiceProduct == null) {
                    $invoiceProduct = new InvoiceProduct();
                    $invoiceProduct->invoice_id = $id;
                    $invoiceProduct->product_name = $products[$i]['particulars'];
                    $invoiceProduct->quantity = $products[$i]['numbers'];
                    $invoiceProduct->tax = $products[$i]['tax'];
                    $invoiceProduct->price = $products[$i]['cost'];
                    $invoiceProduct->discount = $products[$i]['discount'];
                    $invoiceProduct->save();

                    $data[$i]['id'] = $invoiceProduct->id;

                }

                $invoiceProduct->product_name = $products[$i]['particulars'];
                $invoiceProduct->quantity = $products[$i]['numbers'];
                $invoiceProduct->tax = $products[$i]['tax'];
                $invoiceProduct->price = $products[$i]['cost'];
                $invoiceProduct->discount = $products[$i]['discount'];
                $invoiceProduct->save();

            }

            $bill['items'] = json_encode($data);
            $bill->save();

            // //notification
            Notification::firstOrCreate([
                'bill_id' => $bill->id
            ], [
                'bill_to' => $bill->bill_to,
                'bill_from' => $bill->bill_to,
                'due_date' => $bill->due_date
            ]);


            return redirect()->route('bills.index')->with('success', __('Bill successfully updated.'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Auth::user()->can('delete bill')) {

            $bill = Bill::find($id);
            if ($bill) {

                $bill->delete();
            }

            return redirect()->route('bills.index')->with('success', __('Bill successfully deleted.'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }

    }

    public function paymentcreate($bill_id)
    {
        $bill = Bill::where('id', $bill_id)->first();

        return view('bills.createpayment', compact('bill'));

    }
    public function paymentstore(Request $request, $bill_id)
    {

        $payment = new BillPayment();
        $payment['bill_id'] = $bill_id;
        $payment['amount'] = $request->amount;
        $payment['method'] = $request->method;
        $payment['date'] = $request->date;
        $payment['note'] = $request->note;
        $payment->save();

        $payment = BillPayment::where('bill_id', $bill_id)->sum('amount');
        $invoice = Bill::find($bill_id);


        if ($payment >= $invoice->total_amount) {
            $invoice->status = 'PAID';
            $invoice->due_amount = 0.00;
        } else {
            $invoice->status = 'Partialy Paid';
            $invoice->due_amount = $invoice->due_amount - $request->amount;
        }

        $invoice->save();


        return redirect()->route('bills.show', $bill_id)->with('success', __('Payment successfully added.'));
    }

    public function payinvoice($invoice_id)
    {
        try {

            $id = decrypt($invoice_id);

            $bill = Bill::find($id);

            $items = json_decode($bill->items, true);

            $company_payment_setting = Utility::getCompanyPaymentSetting($bill->created_by);

            if (Auth::check()) {
                $user_temp = Auth::user();
            } else {
                $user_temp = User::where('id', $bill->created_by)->first();
            }

            if ($user_temp->type != 'company') {
                $user_temp = User::where('id', $user_temp->created_by)->first();
            }

            $company_setting = Utility::settings($user_temp->id);

            $user_id = $user_temp->id;

            $payments = BillPayment::where('bill_id', $id)->get();

            $bankPayments = BankTransfer::where('invoice_id', $bill->id)->get();
            $plan = Plan::find($user_temp->plan);

            return view('bills.billpay', compact('bill', 'items', 'company_payment_setting','company_setting','payments','bankPayments','plan'));

        } catch (Exception $e) {
            return redirect()->route('bills.index')->with('success', $e);
        }

    }

    public function transactionNumber($id)
    {
        $latest = BillPayment::select('bill_payments.*')->join('bills', 'bill_payments.bill_id', '=', 'bills.id')->where('bills.created_by', '=', $id)->latest()->first();
        if ($latest) {
            return $latest->transaction_id + 1;
        }

        return 1;
    }

    public function getadvocateDetail(Request $request){

        $avd = User::find($request->avd_id);

        if ($avd) {
            $avdName = $avd->name;

            $details = Advocate::where('user_id',$avd->id)->first();


            if ($details) {
                $avdContact = $details->phone_number;
                $avdAddress = $details->ofc_address_line_1;
            }

            $html = '<div class="row">
                        <div class="col-md-12">
                            <div class="bill-to">
                                    <label class="col-3 mb-2">Name: </label>
                                    <span>'.$avdName.'</span><br>
                                    <label class="col-3 mb-2">Phone Number: </label>
                                    <span>'.$avdContact.'</span><br>
                                    <label class="col-3 mb-2">Address: </label>
                                    <span>'.$avdAddress.'</span><br>

                            </div>
                        </div>
                    </div>';

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        }else{
            return response()->json([
                'success' => false
            ]);
        }

    }

    public function getClientDetail(Request $request){

        $client = User::find($request->client_id);

        if ($client) {
            $clientName = $client->name;

            $details = UserDetail::where('user_id',$client->id)->first();
            if ($details) {
                $clientContact = $details->mobile_number;
                $clientAddress = $details->address;
            }

            $html = '<div class="row">
                        <div class="col-md-12">
                            <div class="bill-to">
                                    <label class="col-3 mb-2">Name: </label>
                                    <span>'.$clientName.'</span><br>
                                    <label class="col-3 mb-2">Phone Number: </label>
                                    <span>'.$clientContact.'</span><br>
                                    <label class="col-3 mb-2">Address: </label>
                                    <span>'.$clientAddress.'</span><br>

                            </div>
                        </div>
                    </div>';

            return response()->json([
                'success' => true,
                'html' => $html
            ]);

        }else{
            return response()->json([
                'success' => false
            ]);
        }

    }
    public function exportFile()
    {
        $name = 'bills_' . date('Y-m-d i:h:s');
        $data = Excel::download(new BillsExport(), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }

}
