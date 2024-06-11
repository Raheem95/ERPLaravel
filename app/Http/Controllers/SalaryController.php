<?php

namespace App\Http\Controllers;

use App\Salary;
use App\Employee;
use App\Loan;
use App\LoanPayment;
use App\Month;
use App\SalaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalaryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Salaries = Salary::with('Month')
            ->select('MonthID', DB::raw('SUM(PaidAmount) as TotalPaidAmount'))
            ->groupBy('MonthID')
            ->get();
        foreach ($Salaries as $Salary) {
            // Count employees not in salaries for the specific month
            $NotPaidEmployeeCount = Employee::whereNotIn('EmployeeID', function ($query) use ($Salary) {
                $query->select('EmployeeID')
                    ->from('salaries')
                    ->where('MonthID', $Salary->MonthID);
            })->count();

            $Salary->NotPaidEmployeeCount = $NotPaidEmployeeCount;

            // Count employees not fully paid for the specific month
            $NotFullyPaidEmployeeCount = Employee::whereIn('EmployeeID', function ($query) use ($Salary) {
                $query->select('EmployeeID')
                    ->from('salaries')
                    ->where('MonthID', $Salary->MonthID)
                    ->whereColumn('PaidAmount', '<', 'SalaryAmount');
            })->count();
            $Salary->NotFullyPaidEmployeeCount = $NotFullyPaidEmployeeCount;
            $FullyPaidEmployeeCount = Employee::whereIn('EmployeeID', function ($query) use ($Salary) {
                $query->select('EmployeeID')
                    ->from('salaries')
                    ->where('MonthID', $Salary->MonthID)
                    ->whereColumn('PaidAmount', '=', 'SalaryAmount');
            })->count();
            $Salary->FullyPaidEmployeeCount = $FullyPaidEmployeeCount;
        }
        return view('account_managment.salaries.index')->with('Salaries', $Salaries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Months = Month::get();
        $FilteredMonths = collect(); // Initialize an empty collection for filtered months

        foreach ($Months as $Month) {
            // Call the GetNonPaidEmployees function for each month
            $UnpaidEmployees = $this->GetNonPaidEmployees($Month->MonthID);
            // Check if there are any unpaid employees for the month
            if ($UnpaidEmployees) {
                $FilteredMonths->push($Month); // Add the month to the filtered collection
            }
        }

        return view('account_managment.salaries.create')->with(["Months" => $FilteredMonths]);
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
            'EmployeeID' => 'required|exists:employees,EmployeeID',
            'MonthID' => 'required',
            'SalaryAmount' => 'required|numeric',
            'PaidAmount' => 'required|numeric',
        ], [
            'EmployeeID.required' => 'معرف الموظف مطلوب.',
            'EmployeeID.exists' => 'الموظف غير موجود.',
            'MonthID.required' => 'شهر مطلوب.',
            'SalaryAmount.required' => 'مبلغ الراتب مطلوب.',
            'SalaryAmount.numeric' => 'مبلغ الراتب يجب أن يكون رقمًا.',
            'PaidAmount.required' => 'مبلغ المدفوعات مطلوب.',
            'PaidAmount.numeric' => 'مبلغ المدفوعات يجب أن يكون رقمًا.',
        ]);
        $Month = Month::find($request->input("MonthID"));
        $Employee = Employee::find($request->input("EmployeeID"));
        $TotalLoans = Loan::where("EmployeeID", $Employee->EmployeeID)
            ->selectRaw('SUM(LoanAmount - PaidAmount) as TotalLoans')
            ->pluck('TotalLoans')
            ->first();
        if (!$TotalLoans)
            $TotalLoans = 0;
        $PaidLoans  = $request->input("PaidLoans");
        $PaidAmount  = $request->input("PaidAmount");
        $Salary = Salary::where("EmployeeID", "=", $Employee->EmployeeID)
            ->where("MonthID", "=", $Month->MonthID)->first();
        if ($Salary) {
            $SalaryAmount = $Salary->SalaryAmount - $Salary->PaidAmount;
            $Salary->PaidAmount += $PaidAmount + $PaidLoans;
        } else {
            $SalaryAmount = $Employee->EmployeeSalary;
            $Salary = new Salary;
            $Salary->EmployeeID = $Employee->EmployeeID;
            $Salary->MonthID = $Month->MonthID;
            $Salary->SalaryAmount = $Employee->EmployeeSalary;
            $Salary->PaidAmount = $PaidAmount + $PaidLoans;
        }
        if ($PaidLoans > $TotalLoans || $PaidAmount + $PaidLoans > $SalaryAmount)
            return redirect("/AccountManagment/Salaries")->with("error", "لم يتم صرف الراتب ");
        if ($Salary->save()) {
            $EmployeeLoans = Loan::where("EmployeeID", $Employee->EmployeeID)
                ->select('LoanID', 'LoanAmount', 'PaidAmount', DB::raw('LoanAmount - PaidAmount as RemainingAmount'))
                ->having('RemainingAmount', '>', 0)
                ->get();
            $LoanController = new LoanController;
            $flag = true;
            foreach ($EmployeeLoans as $Loan) {
                if ($PaidLoans == 0) {
                    break;
                }
                $RemainingAmount = $Loan->LoanAmount - $Loan->PaidLoans;

                if ($PaidLoans < $RemainingAmount) {
                    $Amount = $PaidLoans;
                    $PaidLoans = 0;
                } else {
                    $Amount = $RemainingAmount;
                    $PaidLoans -= $Amount;
                }
                $Comment = "سداد  " . $Employee->EmployeeName . " مبلغ " . $Amount . " خصما من راتب شهر " . $Month->MonthName;
                $RestrictionID = $LoanController->LoanPaymentFunction($Loan->LoanID, $Comment, $Comment, 15, 13, $Amount, 0);
                $SalaryDetail = new SalaryDetail;
                $SalaryDetail->SalaryID = $Salary->SalaryID; // Make sure to set the SalaryID if you have it
                $SalaryDetail->Amount = $Amount;
                $SalaryDetail->Comment = "سداد مبلغ " . $Amount . " للسلفية بالرقم " . $Loan->LoanID;
                $SalaryDetail->RestrictionID = $RestrictionID;
                $SalaryDetail->Type = 1;

                if (!$SalaryDetail->save()) {
                    $flag = false;
                }
            }
            $SalaryComment = "سداد مبلغ " . $request->input("PaidAmount") . " للسيد " . $Employee->EmployeeName . " راتب شهر " . $Month->MonthName;
            $DailyAccountingEntryController = new DailyAccountingEntryController;
            $RestrictionID = $DailyAccountingEntryController->saveDaily($SalaryComment, auth()->user()->id, 1, 0);
            if ($RestrictionID > 0) {
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $request->input("PaymentAccountID"),  $request->input("PaidAmount"), 1, 1, $SalaryComment, auth()->user()->id);
                $Result1 = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, 15,  $request->input("PaidAmount"), 2, 1, $SalaryComment, auth()->user()->id);
                if ($Result == 1 && $Result1 == 1) {
                    $SalaryDetail = new SalaryDetail;
                    $SalaryDetail->SalaryID = $Salary->SalaryID; // Make sure to set the SalaryID if you have it
                    $SalaryDetail->Amount = $request->input("PaidAmount");
                    $SalaryDetail->Comment = $request->input("Comment");
                    $SalaryDetail->RestrictionID = $RestrictionID;
                    $SalaryDetail->Type = 0;
                    if ($SalaryDetail->save() && $flag)
                        return redirect("/AccountManagment/Salaries/create")->with("success", "تم صرف الراتب للموظف  " . $Employee->EmployeeName . " بنجاح");
                }
            }
        }
        return redirect("/AccountManagment/Salaries")->with("error", "خطاء في صرف  الراتب للموظف  " . $Employee->EmployeeName);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $SalaryDetail = Salary::with('Month')
            ->select('MonthID', DB::raw('SUM(PaidAmount) as TotalPaidAmount'))
            ->where('MonthID', "=", $id)
            ->groupBy('MonthID')
            ->first();
        $Salaries = Salary::with("Month")->with("Employee")->where("MonthID", "=", $id)->get();
        foreach ($Salaries as $Salary) {
            $SalaryDetails = SalaryDetail::where("SalaryID", $Salary->SalaryID)
                ->selectRaw('Type, SUM(Amount) as TotalAmount')
                ->groupBy('Type')
                ->get();
            $totalLoans = 0;
            $totalCash = 0;
            foreach ($SalaryDetails as $detail) {
                if ($detail->Type === 1) { // Assuming Type 0 represents loans
                    $totalLoans += $detail->TotalAmount;
                } elseif ($detail->Type === 0) { // Assuming Type 1 represents cash
                    $totalCash += $detail->TotalAmount;
                }
                $Salary->Loans = $totalLoans;
                $Salary->Cash = $totalCash;
            }
        }
        // Assign the calculated values to the salary object


        return view('account_managment.salaries.show')->with(["SalaryDetail" => $SalaryDetail, "Salaries" => $Salaries]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function edit(Salary $salary)
    {
        $employees = Employee::all();
        return view('account_managment.salaries.edit', compact('salary', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Salary $salary)
    {
        $request->validate([
            'EmployeeID' => 'required|exists:employees,id',
            'MonthID' => 'required|date_format:Y-m',
            'SalaryAmount' => 'required|numeric',
            'PaidAmount' => 'required|numeric',
        ]);

        $salary->update($request->all());

        return redirect()->route('account_managment.salaries.index')->with('success', 'Salary updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Salary  $salary
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $salaries = Salary::with('SalaryDetail')->where('MonthID', $id)->get();
        $flag = true;
        $loanController = new LoanController;
        $dailyAccountingEntryController = new DailyAccountingEntryController;

        foreach ($salaries as $salary) {
            if (!$flag) break; // Exit loop if flag is false

            foreach ($salary->SalaryDetail as $salaryDetail) {
                if ($salaryDetail->Type == 1) {
                    $loanPayment = LoanPayment::where('RestrictionID', $salaryDetail->RestrictionID)->first();

                    if (!$loanPayment || $loanController->DeletePaymentFunction($loanPayment->PaymentID) == "-1") {
                        $flag = false;
                        break; // Exit inner loop if loan payment deletion fails
                    }

                    $salaryDetailRow = SalaryDetail::find($salaryDetail->SalaryDetailsID);
                    if (!$salaryDetailRow || !$salaryDetailRow->delete()) {
                        $flag = false;
                        break; // Exit inner loop if salary detail deletion fails
                    }
                } else {
                    if (!$dailyAccountingEntryController->deleteDaily($salaryDetail->RestrictionID)) {
                        $flag = false;
                        break; // Exit inner loop if daily accounting entry deletion fails
                    }

                    $salaryDetailRow = SalaryDetail::find($salaryDetail->SalaryDetailsID);
                    if (!$salaryDetailRow || !$salaryDetailRow->delete()) {
                        $flag = false;
                        break; // Exit inner loop if salary detail deletion fails
                    }
                }
            }

            if ($flag) {
                $salaryRow = Salary::find($salary->SalaryID);
                if ($salaryRow) {
                    $salaryRow->PaidAmount = 0;
                    if (!$salaryRow->save()) {
                        $flag = false;
                        break; // Exit outer loop if salary save fails
                    }
                } else {
                    $flag = false;
                    break; // Exit outer loop if salary not found
                }
            }
        }

        if ($flag) {
            return redirect("/AccountManagment/Salaries")->with('success', 'تم حذف الراتب بنجاح');
        }

        return redirect("/AccountManagment/Salaries")->with('error', 'خطاء في حذف الراتب');
    }

    public function GetNonPaidEmployees($MonthID)
    {
        // Get employees who are not in the salaries table for the specific month
        $employeesNotInSalaries = Employee::whereNotIn('EmployeeID', function ($query) use ($MonthID) {
            $query->select('EmployeeID')
                ->from('salaries')
                ->where('MonthID', $MonthID);
        })->get();

        $employeesNotFullyPaid = Employee::whereIN('EmployeeID', function ($query) use ($MonthID) {
            $query->select('EmployeeID')
                ->from('salaries')
                ->where('MonthID', $MonthID)
                ->whereColumn('PaidAmount', '<', 'SalaryAmount');
        })->get();
        $employees = $employeesNotInSalaries->merge($employeesNotFullyPaid);
        if (count($employees) > 0)
            return response()->json($employees);
        return false;
    }
    public function getEmployeeSalaryDetails($MonthID, $EmployeeID)
    {
        // Get the total loans
        $TotalLoans = Loan::where("EmployeeID", $EmployeeID)
            ->selectRaw('SUM(LoanAmount - PaidAmount) as TotalLoans')
            ->pluck('TotalLoans')
            ->first();
        if (!$TotalLoans)
            $TotalLoans = 0;

        // Check if the employee is not in the salaries table for the given month
        $employeeNotInSalaries = Employee::whereNotIn('EmployeeID', function ($query) use ($MonthID) {
            $query->select('EmployeeID')
                ->from('salaries')
                ->where('MonthID', $MonthID);
        })->where('EmployeeID', $EmployeeID)->first();
        // If the employee is not found, check if the employee is in the salaries table but not fully paid
        if (!$employeeNotInSalaries) {
            $employeeNotFullyPaid = Employee::whereIn('EmployeeID', function ($query) use ($MonthID, $EmployeeID) {
                $query->select('EmployeeID')
                    ->from('salaries')
                    ->where('MonthID', $MonthID)
                    ->where('EmployeeID', $EmployeeID)
                    ->whereColumn('PaidAmount', '<', 'SalaryAmount');
            })->where('EmployeeID', $EmployeeID)->first();

            // If the employee is found in the salaries table but not fully paid, return their salary details
            if ($employeeNotFullyPaid) {
                $salaryDetails = Salary::where('MonthID', $MonthID)
                    ->where('EmployeeID', $EmployeeID)
                    ->select('SalaryAmount', 'PaidAmount')
                    ->first();

                return response()->json([
                    'success' => [
                        'TotalSalaryAmount' => $salaryDetails->SalaryAmount,
                        'PaidAmount' => $salaryDetails->PaidAmount,
                        'TotalLoans' => $TotalLoans
                    ]
                ]);
            }
        }

        // If the employee is not in the salaries table for the given month, return their default salary details
        return response()->json([
            'success' => [
                'TotalSalaryAmount' => $employeeNotInSalaries ? $employeeNotInSalaries->EmployeeSalary : 0, // Default value if not found in the salaries table
                'PaidAmount' => 0, // Default value if not found in the salaries table
                'TotalLoans' => $TotalLoans
            ]
        ]);
    }
}
