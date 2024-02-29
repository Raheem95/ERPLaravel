<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Account;
use App\AccountType;
use App\Currency;

class AccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Accounts = Account::selectRaw('accounts.*, CAST(`AccountNumber` AS SIGNED) AS integer_value')
            ->orderBy('CurrencyID')
            ->orderByRaw('integer_value')
            ->get();
        $Currencies = Currency::whereIn('CurrencyID', function ($query) {
            $query->select('CurrencyID')
                ->from('accounts')
                ->groupBy('CurrencyID');
        })
            ->get();
        return view("account_managment.accounts.index")->with(['Accounts' => $Accounts, 'Currencies' => $Currencies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $AccountTypes = AccountType::orderBy('AccountTypeID', 'desc')->get();
        $Currencies = Currency::orderBy('CurrencyID', 'asc')->get();
        return view("account_managment.accounts.create")->with(['AccountTypes' => $AccountTypes, 'Currencies' => $Currencies]);
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
            'AccountName' => 'required|unique:accounts,AccountName',
        ], [
            'AccountName.unique' => 'هذا الحساب موجود',
            'AccountName.required' => 'يجب ادخال اسم الحساب',
        ]);
        $AccountName = $request->input('AccountName');
        $AccountTypeID = $request->input('AccountTypeID');
        $CurrencyID = $request->input('CurrencyID');
        $lastChildNum = 0;
        $AddedBy = auth()->user()->id;
        $Balance = 0;
        if ($request->has('AccountParent')) {
            $AccountParent = $request->input('AccountParent');

            $Parent = Account::find($request->input('AccountParent'));
            $lastChildNum = $Parent->lastChildNum + 1;
            $maxAccountNumber = $Parent->AccountNumber;
            $maxAccountNumber .= $lastChildNum;
            $AccountNumber = $maxAccountNumber;
            $Parent->lastChildNum = $lastChildNum;
            $Parent->save();
        } else {
            $AccountParent = 0;
            $Balance = $request->input('Balance');
            $maxAccountNumber = Account::where('CurrencyID', $request->input('CurrencyID'))->where('lastChildNum', 0)->max('AccountNumber');
            if (!$maxAccountNumber)
                $maxAccountNumber = 0;
            $maxAccountNumber++;
            $maxAccountNumber = sprintf("%06d", $maxAccountNumber);
            $AccountNumber = $maxAccountNumber;
        }
        $Result = $this->CreateAccount($maxAccountNumber, $AccountName, $AccountTypeID, $CurrencyID, $Balance, $AddedBy, $AccountParent, 0);
        if ($Result > 0) {
            return redirect("/AccountManagment/Accounts")->with("success", "تمت اضافة الحساب بنجاح");
        } else {
            return redirect()->back()->with("error", "حدث خطأ أثناء إضافة الحساب");
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function CreateAccount($AccountNumbert, $AccountName, $AccountTypeID, $CurrencyID, $Balance, $AddedBy, $AccountParent, $lastChildNum)
    {
        $Account = new Account;
        $Account->AccountNumber = $AccountNumbert;
        $Account->AccountName = $AccountName;
        $Account->AccountTypeID = $AccountTypeID;
        $Account->CurrencyID = $CurrencyID;
        $Account->Balance = $Balance;
        $Account->AddedBy = $AddedBy;
        $Account->AccountParent = $AccountParent;
        $Account->lastChildNum = $lastChildNum;
        if ($Account->save()) {
            return $Account->AccountID;
        } else {
            return -1;
        }
    }
    public function getAccount($CurrencyID, $AccountType)
    {
        $accounts = Account::where('CurrencyID', $CurrencyID)
            ->where('AccountTypeID', $AccountType)
            ->where('lastChildNum', 0)
            ->get(); // Execute the query and retrieve results

        return response()->json($accounts);
    }
}
