<?php

namespace App\Http\Controllers;

use App\Account;
use App\CreditorsDebtor;
use App\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CreditorsDebtorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Creditors = CreditorsDebtor::with('Account')->where("OprationType", 0)->orderBy('OprationID', 'desc')->get();
        $Debtors = CreditorsDebtor::with('Account')->where("OprationType", 1)->orderBy('OprationID', 'desc')->get();
        return view('account_managment.creditors_debtors.index')->with(['Creditors' => $Creditors, 'Debtors' => $Debtors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Currencies = Currency::get();
        return view('account_managment.creditors_debtors.create')->with('Currencies', $Currencies);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'AccountID' => 'required',
                'Amount' => 'required|numeric|min:0',
                'OprationDetails' => 'required',
                'OprationType' => 'required',
            ],
            [
                'AccountID.required' => 'الحساب مطلوب.',
                'Amount.required' => 'المبلغ مطلوب.',
                'Amount.numeric' => 'المبلغ يجب أن يكون رقمًا.',
                'Amount.min' => 'المبلغ يجب أن يكون على الأقل 0.',
                'OprationDetails.required' => 'تفاصيل العملية مطلوبة.',
                'OprationType.required' => 'نوع العملية مطلوب.',
            ]
        );
        $Type1 = 2;
        $Type2 = 1;
        if ($request->input('OprationType') == 1) {
            $Type1 = 1;
            $Type2 = 2;
        }

        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($request->input('OprationDetails'), auth()->user()->id, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $request->input('PaymentAccountID'), $request->input('Amount'), $Type1, 1, $request->input('OprationDetails'), auth()->user()->id);
            $Result1 = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $request->input('AccountID'), $request->input('Amount'), $Type2, 1, $request->input('OprationDetails'), auth()->user()->id);
            if ($Result == 1 && $Result1 == 1) {
                $creditorsDebtor = new CreditorsDebtor();
                $creditorsDebtor->AccountID = $request->input('AccountID');
                $creditorsDebtor->PaymentAccountID = $request->input('PaymentAccountID');
                $creditorsDebtor->Amount = $request->input('Amount');
                $creditorsDebtor->OprationDetails = $request->input('OprationDetails');
                $creditorsDebtor->OprationType = $request->input('OprationType');
                $creditorsDebtor->RestrictionID = $RestrictionID;
                $creditorsDebtor->AddedBy = auth()->user()->id;

                if ($creditorsDebtor->save()) {
                    return redirect("/AccountManagment/CreditorsDebtors")->with('success', 'تم الاضافة العملية بنجاح');
                }
            }
        }
        $DeleteRestriction = $DailyAccountingEntryController->deleteDaily($RestrictionID);


        return redirect("/AccountManagment/CreditorsDebtors")->with('error', 'خطاء في حفظ العملية');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Opration = CreditorsDebtor::find($id);
        $Currencies = Currency::all();
        $Accounts = Account::where("AccountTypeID", $Opration->Account->AccountTypeID)->get();
        $PaymentAccounts  = Account::where("AccountTypeID", $Opration->PaymentAccount->AccountTypeID)->get();
        return view('account_managment.creditors_debtors.edit')->with(['Opration' => $Opration, 'Currencies' => $Currencies, "Accounts" => $Accounts, "PaymentAccounts" => $PaymentAccounts]);
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
        $this->validate(
            $request,
            [
                'AccountID' => 'required',
                'Amount' => 'required|numeric|min:0',
                'OprationDetails' => 'required',
                'OprationType' => 'required',
            ],
            [
                'AccountID.required' => 'الحساب مطلوب.',
                'Amount.required' => 'المبلغ مطلوب.',
                'Amount.numeric' => 'المبلغ يجب أن يكون رقمًا.',
                'Amount.min' => 'المبلغ يجب أن يكون على الأقل 0.',
                'OprationDetails.required' => 'تفاصيل العملية مطلوبة.',
                'OprationType.required' => 'نوع العملية مطلوب.',
            ]
        );
        $Type1 = 2;
        $Type2 = 1;
        if ($request->input('OprationType') == 1) {
            $Type1 = 1;
            $Type2 = 2;
        }

        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $creditorsDebtor = CreditorsDebtor::find($id);
        if ($DailyAccountingEntryController->deleteDaily($creditorsDebtor->RestrictionID)) {
            $RestrictionID = $DailyAccountingEntryController->saveDaily($request->input('OprationDetails'), auth()->user()->id, 1, 0);
            if ($RestrictionID > 0) {
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $request->input('PaymentAccountID'), $request->input('Amount'), $Type1, 1, $request->input('OprationDetails'), auth()->user()->id);
                $Result1 = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $request->input('AccountID'), $request->input('Amount'), $Type2, 1, $request->input('OprationDetails'), auth()->user()->id);
                if ($Result == 1 && $Result1 == 1) {
                    $creditorsDebtor->AccountID = $request->input('AccountID');
                    $creditorsDebtor->PaymentAccountID = $request->input('PaymentAccountID');
                    $creditorsDebtor->Amount = $request->input('Amount');
                    $creditorsDebtor->OprationDetails = $request->input('OprationDetails');
                    $creditorsDebtor->OprationType = $request->input('OprationType');
                    $creditorsDebtor->RestrictionID = $RestrictionID;

                    if ($creditorsDebtor->save()) {
                        return redirect("/AccountManagment/CreditorsDebtors")->with('success', 'تم تعديل العملية بنجاح');
                    }
                }
            }
        }
        return redirect("/AccountManagment/CreditorsDebtors")->with('error', 'خطاء في حفظ العملية');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Ortation = CreditorsDebtor::find($id);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        if ($DailyAccountingEntryController->deleteDaily($Ortation->RestrictionID))
            if ($Ortation->delete())
                return redirect("/AccountManagment/CreditorsDebtors")->with('success', 'تم حذف العملية بنجاح');
        return redirect("/AccountManagment/CreditorsDebtors")->with('error', 'خطاء في حذف العملية');
    }
}
