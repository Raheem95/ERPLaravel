<?php

namespace App\Http\Controllers;

use App\Account;
use App\Currency;
use App\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Expenses = Expense::with("ExpensesAccount")->with("PaymentAccount")->orderBy('ExpensesID', 'desc')->get();
        $TotalExpenses = Expense::with(['ExpensesAccount', 'ExpensesAccount.Currency'])
            ->select('ExpensesAccountID', DB::raw('SUM(ExpensesAmount) as total'))
            ->groupBy('ExpensesAccountID')
            ->orderBy(DB::raw("(SELECT CurrencyID FROM accounts WHERE accounts.AccountID = expenses.ExpensesAccountID)"), 'asc')
            ->orderBy('total', 'desc')
            ->get();
        return view("account_managment.expenses.index")->with(['Expenses' => $Expenses, "TotalExpenses" => $TotalExpenses]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Currencies = Currency::get();
        return view('account_managment.expenses.create')->with("Currencies", $Currencies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ExpensesAccountID' => 'required',
            'PaymentAccountID' => 'required',
            'ExpensesDetails' => 'required',
            'ExpensesAmount' => 'required|numeric|min:0',
        ], [
            'ExpensesAccountID.required' => '  حساب المصروفات مطلوب.',
            'PaymentAccountID.required' => '  حساب الدفع مطلوب.',
            'ExpensesDetails.required' => ' تفاصيل المصروفات مطلوبة.',
            'ExpensesAmount.required' => ' مبلغ المصروفات مطلوب.',
            'ExpensesAmount.numeric' => ' مبلغ المصروفات يجب أن يكون رقمًا.',
            'ExpensesAmount.min' => ' مبلغ المصروفات يجب أن يكون على الأقل 1',
        ]);
        $ExpensesAccountID = $request->input("ExpensesAccountID");
        $PaymentAccountID = $request->input("PaymentAccountID");
        $ExpensesAmount = $request->input("ExpensesAmount");
        $ExpensesDetails = $request->input("ExpensesDetails");
        $ExpensesAccountID = $request->input("ExpensesAccountID");
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($ExpensesDetails, auth()->user()->id, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $PaymentAccountID, $ExpensesAmount, 1, 1, $ExpensesDetails, auth()->user()->id);
            if ($Result == 1)
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $ExpensesAccountID, $ExpensesAmount, 2, 1, $ExpensesDetails, auth()->user()->id);
            else
                $DeleteRestriction = $DailyAccountingEntryController->deleteDaily($RestrictionID);
            if ($Result == 1) {
                $Expense = new Expense;
                $Expense->ExpensesAccountID = $ExpensesAccountID;
                $Expense->PaymentAccountID = $PaymentAccountID;
                $Expense->ExpensesDetails = $ExpensesDetails;
                $Expense->ExpensesAmount = $ExpensesAmount;
                $Expense->RestrictionID = $RestrictionID;
                $Expense->AddedBy = auth()->user()->id;
                if ($Expense->save())
                    return redirect("/AccountManagment/Expenses")->with("success", "تمت اضافة المنصرف  بنجاح");
                else
                    $DeleteRestriction = $DailyAccountingEntryController->deleteDaily($RestrictionID);
            } else
                $DeleteRestriction = $DailyAccountingEntryController->deleteDaily($RestrictionID);
        }
        return redirect("/AccountManagment/Expenses")->with("error", "حدث خطاء في اضافة المنصرف");
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Currencies = Currency::get();
        $Expense = Expense::with("ExpensesAccount")->with("PaymentAccount")->find($id);
        $ExpensesAccounts = Account::where("CurrencyID", "=", $Expense->ExpensesAccount->CurrencyID)->where("AccountTypeID", "=", "2")->where("lastChildNum", "=", "0")->get();
        $PaymentAccounts = Account::where("CurrencyID", "=", $Expense->ExpensesAccount->CurrencyID)->where("AccountTypeID", "=", $Expense->PaymentAccount->AccountTypeID)->where("lastChildNum", "=", "0")->get();
        // return $Expense;
        return view('account_managment.expenses.edit')->with(['Expense' => $Expense, 'Currencies' => $Currencies, 'ExpensesAccounts' => $ExpensesAccounts, 'PaymentAccounts' => $PaymentAccounts]);
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
        $this->validate($request, [
            'ExpensesAccountID' => 'required',
            'PaymentAccountID' => 'required',
            'ExpensesDetails' => 'required',
            'ExpensesAmount' => 'required|numeric|min:0',
        ], [
            'ExpensesAccountID.required' => '  حساب المصروفات مطلوب.',
            'PaymentAccountID.required' => '  حساب الدفع مطلوب.',
            'ExpensesDetails.required' => ' تفاصيل المصروفات مطلوبة.',
            'ExpensesAmount.required' => ' مبلغ المصروفات مطلوب.',
            'ExpensesAmount.numeric' => ' مبلغ المصروفات يجب أن يكون رقمًا.',
            'ExpensesAmount.min' => ' مبلغ المصروفات يجب أن يكون على الأقل 1',
        ]);
        $Expense = Expense::find($id);
        $ExpensesAccountID = $request->input("ExpensesAccountID");
        $PaymentAccountID = $request->input("PaymentAccountID");
        $ExpensesAmount = $request->input("ExpensesAmount");
        $ExpensesDetails = $request->input("ExpensesDetails");
        $ExpensesAccountID = $request->input("ExpensesAccountID");
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        if ($DailyAccountingEntryController->deleteDaily($Expense->RestrictionID)) {
            $RestrictionID = $DailyAccountingEntryController->saveDaily($ExpensesDetails, auth()->user()->id, 1, 0);
            if ($RestrictionID > 0) {
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $PaymentAccountID, $ExpensesAmount, 1, 1, $ExpensesDetails, auth()->user()->id);
                $Result1 = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $ExpensesAccountID, $ExpensesAmount, 2, 1, $ExpensesDetails, auth()->user()->id);
                if ($Result == 1 && $Result1 == 1) {
                    $Expense->ExpensesAccountID = $ExpensesAccountID;
                    $Expense->PaymentAccountID = $PaymentAccountID;
                    $Expense->ExpensesDetails = $ExpensesDetails;
                    $Expense->ExpensesAmount = $ExpensesAmount;
                    $Expense->RestrictionID = $RestrictionID;
                    if ($Expense->save())
                        return redirect("/AccountManagment/Expenses")->with("success", "تمت تعديل المنصرف  بنجاح");
                    else
                        $DeleteRestriction = $DailyAccountingEntryController->deleteDaily($RestrictionID);
                } else
                    $DeleteRestriction = $DailyAccountingEntryController->deleteDaily($RestrictionID);
            }
        }
        return redirect("/AccountManagment/Expenses")->with("error", "حدث خطاء في تعديل المنصرف");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Expense = Expense::find($id);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        if ($DailyAccountingEntryController->deleteDaily($Expense->RestrictionID))
            if ($Expense->delete())
                return redirect("/AccountManagment/Expenses")->with("success", "تم حذف المنصرف بنجاح");
        return redirect("/AccountManagment/Expenses")->with("success", "حدث خطاء في حذف المنصرف");
    }
}
