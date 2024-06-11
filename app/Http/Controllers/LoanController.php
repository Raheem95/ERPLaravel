<?php

namespace App\Http\Controllers;

use App\Loan;
use App\Account;
use App\Currency;
use App\Employee;
use App\LoanPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Loans = Loan::with("Employee")->with("PaymentAccount")->orderBy('LoanID', 'desc')->get();
        $TotalLoans = Loan::with(['Employee'])
            ->select('EmployeeID', DB::raw('SUM(LoanAmount-PaidAmount) as total'))
            ->groupBy('EmployeeID')
            ->orderBy(DB::raw("(SELECT CurrencyID FROM accounts WHERE accounts.AccountID = loans.EmployeeID)"), 'asc')
            ->orderBy('total', 'desc')
            ->get();
        return view("account_managment.loans.index")->with(['Loans' => $Loans, "TotalLoans" => $TotalLoans]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Currencies = Currency::get();
        $Employees = Employee::get();
        return view('account_managment.loans.create')->with(["Currencies" => $Currencies, "Employees" => $Employees]);
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
            'EmployeeID' => 'required',
            'LoanAccountID' => 'required',
            'PaymentAccountID' => 'required',
            'LoanAmount' => 'required|numeric|min:0',
            'LoanDetails' => 'required',
        ], [
            'EmployeeID.required' => '  موظف مطلوب.',
            'LoanAmount.required' => ' مبلغ السلفية مطلوب.',
            'LoanAmount.numeric' => ' مبلغ السلفية يجب أن يكون رقمًا.',
            'LoanAmount.min' => ' مبلغ السلفية يجب أن يكون على الأقل 1',
            'LoanDetails.required' => ' تفاصيل السلفية مطلوبة.',
        ]);

        $EmployeeID = $request->input("EmployeeID");
        $LoanAmount = $request->input("LoanAmount");
        $PaymentAccountID = $request->input("PaymentAccountID");
        $LoanAccountID = $request->input("LoanAccountID");
        $LoanDetails = $request->input("LoanDetails");

        $Employee = Employee::find($EmployeeID);
        $RestrictionDetails = " سلفية للموظف " . $Employee->EmployeeName . " بقيمة " . $LoanAmount . "/ " . $LoanDetails;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($RestrictionDetails, auth()->user()->id, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $PaymentAccountID, $LoanAmount, 1, 1, $RestrictionDetails, auth()->user()->id);
            $Result1 = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $LoanAccountID, $LoanAmount, 2, 1, $RestrictionDetails, auth()->user()->id);
            if ($Result == 1 && $Result1 == 1) {
                $Loan = new Loan;
                $Loan->EmployeeID = $EmployeeID;
                $Loan->LoanAmount = $LoanAmount;
                $Loan->LoanDetails = $LoanDetails;
                $Loan->LoanAccountID = $LoanAccountID;
                $Loan->PaymentAccountID = $PaymentAccountID;
                $Loan->RestrictionID = $RestrictionID;
                $Loan->AddedBy = auth()->user()->id;
                if ($Loan->save())
                    return redirect("/AccountManagment/Loans")->with("success", "تمت اضافة السلفية بنجاح");
                else
                    $DailyAccountingEntryController->deleteDaily($RestrictionID);
            } else {
                $DailyAccountingEntryController->deleteDaily($RestrictionID);
            }
        }

        return redirect("/AccountManagment/Loans")->with("error", "حدث خطاء في اضافة السلفية");
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
        $Employees = Employee::get();
        $Loan = Loan::with("Employee")->with("RestrictionDetails")->find($id);
        $PaymentAccounts = Account::where("CurrencyID", "=", $Loan->PaymentAccount->CurrencyID)
            ->where("AccountTypeID", "=", $Loan->PaymentAccount->AccountTypeID)
            ->where("lastChildNum", "=", "0")
            ->get();
        $LoanAccounts = Account::where("CurrencyID", "=", $Loan->LoanAccount->CurrencyID)
            ->where("AccountTypeID", "=", $Loan->LoanAccount->AccountTypeID)
            ->where("lastChildNum", "=", "0")->get();

        return view('account_managment.loans.edit')->with(['Loan' => $Loan, 'Currencies' => $Currencies, "Employees" => $Employees, "LoanAccounts" => $LoanAccounts, 'PaymentAccounts' => $PaymentAccounts]);
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
            'EmployeeID' => 'required',
            'LoanAccountID' => 'required',
            'PaymentAccountID' => 'required',
            'LoanAmount' => 'required|numeric|min:0',
            'LoanDetails' => 'required',
        ], [
            'EmployeeID.required' => '  موظف مطلوب.',
            'LoanAmount.required' => ' مبلغ السلفية مطلوب.',
            'LoanAmount.numeric' => ' مبلغ السلفية يجب أن يكون رقمًا.',
            'LoanAmount.min' => ' مبلغ السلفية يجب أن يكون على الأقل 1',
            'LoanDetails.required' => ' تفاصيل السلفية مطلوبة.',
        ]);
        $Loan = Loan::find($id);
        $EmployeeID = $request->input("EmployeeID");
        $LoanAmount = $request->input("LoanAmount");
        $PaymentAccountID = $request->input("PaymentAccountID");
        $LoanAccountID = $request->input("LoanAccountID");
        $LoanDetails = $request->input("LoanDetails");

        $Employee = Employee::find($EmployeeID);
        $RestrictionDetails = " سلفية للموظف " . $Employee->EmployeeName . " بقيمة " . $LoanAmount . "/ " . $LoanDetails;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        if ($DailyAccountingEntryController->deleteDaily($Loan->RestrictionID)) {
            $RestrictionID = $DailyAccountingEntryController->saveDaily($RestrictionDetails, auth()->user()->id, 1, 0);
            if ($RestrictionID > 0) {
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $PaymentAccountID, $LoanAmount, 1, 1, $RestrictionDetails, auth()->user()->id);
                $Result1 = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $LoanAccountID, $LoanAmount, 2, 1, $RestrictionDetails, auth()->user()->id);
                if ($Result == 1 && $Result1 == 1) {
                    $Loan->EmployeeID = $EmployeeID;
                    $Loan->LoanAmount = $LoanAmount;
                    $Loan->LoanDetails = $LoanDetails;
                    $Loan->LoanAccountID = $LoanAccountID;
                    $Loan->PaymentAccountID = $PaymentAccountID;
                    $Loan->RestrictionID = $RestrictionID;
                    $Loan->AddedBy = auth()->user()->id;
                    if ($Loan->save())
                        return redirect("/AccountManagment/Loans")->with("success", "تم تعديل السلفية بنجاح");
                    else
                        $DailyAccountingEntryController->deleteDaily($RestrictionID);
                } else {
                    $DailyAccountingEntryController->deleteDaily($RestrictionID);
                }
            }
        }

        return redirect("/AccountManagment/Loans")->with("error", "حدث خطاء في تعديل السلفية");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Loan = Loan::find($id);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        if ($DailyAccountingEntryController->deleteDaily($Loan->RestrictionID))
            if ($Loan->delete())
                return redirect("/AccountManagment/Loans")->with("success", "تم حذف السلفية بنجاح");
        return redirect("/AccountManagment/Loans")->with("error", "حدث خطاء في حذف السلفية");
    }
    public function Payment(Request $request)
    {
        $Loan = Loan::find($request->LoanID);
        return response()->json($this->LoanPaymentFunction(
            $request->LoanID,
            $request->input("comment"),
            $request->input("PaymentComment"),
            $request->input("PaymentAccountID"),
            $Loan->LoanAccountID,
            $request->input("PaidAmount"),
            1
        ));
    }
    public function GetPayments($LoanID)
    {
        return $LoanPayment = LoanPayment::where("LoanID", "=", $LoanID)->get();
    }
    public function DeletePayment(Request $request)
    {
        return response()->json($this->DeletePaymentFunction($request->PaymentID));
    }


    public function LoanPaymentFunction($LoanID, $Comment, $PaymentComment, $PaymentAccountID, $LoanAccountID, $Amount, $Deletable)
    {
        $Loan = Loan::find($LoanID);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($Comment, auth()->user()->id, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $PaymentAccountID,  $Amount, 2, 1, $Comment, auth()->user()->id);
            $Result1 = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $LoanAccountID,  $Amount, 1, 1, $Comment, auth()->user()->id);
            if ($Result == 1 && $Result1 == 1) {
                $Loan->PaidAmount += $Amount;
                if ($Loan->save()) {
                    $LoanPayment = new LoanPayment;
                    $LoanPayment->LoanID = $LoanID;
                    $LoanPayment->Amount = $Amount;
                    $LoanPayment->Comment = $PaymentComment;
                    $LoanPayment->RestrictionID = $RestrictionID;
                    $LoanPayment->Deletable = $Deletable;
                    if ($LoanPayment->save())
                        return $RestrictionID;
                }
            }
        }
        $DailyAccountingEntryController->deleteDaily($RestrictionID);
        return response()->json(-1);
    }
    public function DeletePaymentFunction($PaymentID)
    {
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $LoanPayment = LoanPayment::find($PaymentID);
        $Loan = Loan::find($LoanPayment->LoanID);
        $Loan->PaidAmount = $Loan->PaidAmount - $LoanPayment->Amount;
        if ($Loan->save())
            if ($LoanPayment->delete())
                if ($DailyAccountingEntryController->deleteDaily($LoanPayment->RestrictionID))
                    return $LoanPayment;
        return ("-1");
    }
}
