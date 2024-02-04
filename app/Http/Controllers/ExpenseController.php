<?php

namespace App\Http\Controllers;

use App\Exports\ExpensesExport;
use App\Models\Cases;
use App\Models\Expense;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use App\Models\Plan;
use Maatwebsite\Excel\Facades\Excel;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->can('manage expense')) {
            $expenses = Expense::where('created_by',Auth::user()->creatorId())->get();

            return view('expense.index',compact('expenses'));

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
        if (Auth::user()->can('create expense')) {
            $cases = Cases::where('created_by',Auth::user()->creatorId())->get()->pluck('title', 'id');
            $members = User::where('created_by',Auth::user()->creatorId())
                        ->where('type','!=','company')
                        ->where('type','!=','super admin')
                        ->where('type','!=','client')
                        ->get()->pluck('name', 'id');

            $payments_data = Utility::getCompanyPaymentSetting(Auth::user()->id);
            $payTypes = [
                'Bank Transfer',
                'Cash',
                'Cheque',
                'Online Payment',
            ];

            return view('expense.create',compact('cases','members','payTypes'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (Auth::user()->can('create expense')) {

            $validator = Validator::make(
                $request->all(), [
                    'case' => 'required',
                    'date' => 'required',
                    'particulars' => 'required',
                    'member' => 'required',
                    'money' => 'required',
                    'method' => 'required',
                    'notes' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $expense = new Expense();
            $expense['case'] = $request->case;
            $expense['date'] = $request->date;
            $expense['particulars'] = $request->particulars;
            $expense['money'] = $request->money;
            $expense['member'] = $request->member;
            $expense['method'] = $request->method;
            $expense['notes'] = $request->notes;
            $expense['created_by'] = Auth::user()->creatorId();
            $expense->save();
            return redirect()->route('expenses.index')->with('success', __('Expense successfully created.'));



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
        if (Auth::user()->can('view expense')) {
            $cases = Cases::get()->pluck('title', 'id');
            $members = User::get()->pluck('name', 'id');
            $expense = Expense::find($id);
            return view('expense.view', compact('cases', 'members', 'expense'));

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
        if (Auth::user()->can('edit expense')) {
            $cases = Cases::where('created_by',Auth::user()->creatorId())->get()->pluck('title', 'id');
            $members = User::where('created_by',Auth::user()->creatorId())
                        ->where('type','!=','company')
                        ->where('type','!=','super admin')
                        ->where('type','!=','client')
                        ->get()->pluck('name', 'id');
            $expense = Expense::find($id);
            $payments_data = Utility::getCompanyPaymentSetting(Auth::user()->id);
            $payTypes = [
                'Bank Transfer',
                'Cash',
                'Cheque',
                'Online Payment',
            ];

            return view('expense.edit', compact('cases', 'members', 'expense','payTypes'));

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
        if (Auth::user()->can('edit expense')) {
            $validator = Validator::make(
                $request->all(), [
                    'case' => 'required',
                    'date' => 'required',
                    'particulars' => 'required',
                    'member' => 'required',
                    'money' => 'required',
                    'method' => 'required',
                    'notes' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $expense = Expense::find($id);
            $expense['case'] = $request->case;
            $expense['date'] = $request->date;
            $expense['particulars'] = $request->particulars;
            $expense['money'] = $request->money;
            $expense['member'] = $request->member;
            $expense['method'] = $request->method;
            $expense['notes'] = $request->notes;
            $expense->save();
            return redirect()->route('expenses.index')->with('success', __('Expense successfully created.'));

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
        if (Auth::user()->can('delete expense')) {
            $expense = Expense::find($id);
            if ($expense) {
                $expense->delete();
            }
            return redirect()->route('expenses.index')->with('success', __('Expense successfully deleted.'));

        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));

        }

    }

    public function exportFile()
    {
        $name = 'expenses_' . date('Y-m-d i:h:s');
        $data = Excel::download(new ExpensesExport(), $name . '.xlsx');
        ob_end_clean();
        return $data;
    }
}
