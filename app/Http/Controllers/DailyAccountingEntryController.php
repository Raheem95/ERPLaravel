<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\DailyAccountingEntry;
use App\DailyAccountingEntryDetails;
use App\Account;
use Illuminate\Support\Facades\DB;

class DailyAccountingEntryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $DailyAccountingEntries = DailyAccountingEntry::where("Deleted", "0")->orderBy('RestrictionID', 'desc')->take(100)->get();
        return view("account_managment.daily_accounting_entry.index")->with('DailyAccountingEntries', $DailyAccountingEntries);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $accounts = Account::where('lastChildNum', 0)->orderBy('AccountID', 'ASC')->get();
        return view("account_managment.daily_accounting_entry.create")->with('Accounts', $accounts);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $RestrictionDetails = $request->ResDetails;
        $UserID = auth()->user()->id;
        $RestrictionID = $this->saveDaily($RestrictionDetails, $UserID, 0, 0);
        for ($i = 1; $i <= $request->restrictionsNum; $i++) {
            $AccountID = $request->input("AccountID{$i}");
            $TransactionDetails = $request->input("details{$i}");
            $TransactionAmount = $request->input("amount{$i}");
            $TransactionType = $request->input("TransactionType{$i}");
            $CurrencyValue = $request->input("val{$i}");
            $Result = $this->saveDailyDetails($RestrictionID, $AccountID, $TransactionAmount, $TransactionType, $CurrencyValue, $TransactionDetails, $UserID);
        }
        return redirect("/AccountManagment/DailyAccountingEntries")->with("success", "تم اضافة  القيد بنجاح");
        // return $Rest;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $dailyEntry = DailyAccountingEntry::find($id);

        // Check if daily entry exists
        if (!$dailyEntry) {
            // Handle daily entry not found
            return response()->json(['error' => 'Daily accounting entry not found'], 404);
        }
        $user = $dailyEntry->user;

        // Retrieve the details related to the daily entry
        $resource = DailyAccountingEntryDetails::where('RestrictionID', $id)->get();

        // Check if details exist
        if ($resource->isEmpty()) {
            // Handle details not found
            return response()->json(['error' => 'Details not found'], 404);
        }

        // Initialize an array to store associated accounts
        $accounts = [];

        // Retrieve the associated account for each detail
        foreach ($resource as $detail) {
            // Retrieve the account for the current detail
            $account = $detail->account;

            // Add the account to the array
            $accounts[] = $account;
        }

        // Return data as JSON response
        return response()->json([
            'daily_entry' => $dailyEntry,
            'accounts' => $accounts,
            'details' => $resource,
            'user' => $user
        ]);
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
        $Result = $this->deleteDaily($id);
        if ($Result)
            return redirect("/AccountManagment/DailyAccountingEntries")->with("success", "تم حذف  القيد بنجاح");
        else
            return redirect("/AccountManagment/DailyAccountingEntries")->with("success", "خطاء في الحذف");
    }

    public function restriction_search($Keyword)
    {
        if ($Keyword == "0")
            return response()->json(DailyAccountingEntry::where("Deleted", "0")->orderBy('RestrictionID', 'desc')->get());
        return response()->json(DailyAccountingEntry::where("Deleted", "0")
            ->where('RestrictionDetails', 'like', "%$Keyword%")
            ->get());
    }

    public function saveDaily($restrictionDetails, $userID, $Deletable, $Deleted)
    {
        $inserted = DailyAccountingEntry::create([
            'RestrictionDetails' => $restrictionDetails,
            'AddedBy' => $userID,
            'Deletable' => $Deletable,
            'Deleted' => $Deleted,
        ]);

        // Retrieve the ID of the newly inserted record
        $RestrictionID = $inserted->RestrictionID;

        return $RestrictionID ? $RestrictionID : -1;
    }
    public function saveDailyDetails($RestrictionID, $AccountID, $TransactionAmount, $TransactionType, $CurrencyValue, $TransactionDetails, $UserID)
    {
        $flag = true;
        $CurrentBalance = DB::table('accounts')->where('AccountID', $AccountID)->value('Balance');
        if ($TransactionType == 1) {
            $CurrentBalance += $TransactionAmount * -1;
        } else {
            $CurrentBalance += $TransactionAmount;
        }
        $DailyAccountingEntryDetails = new DailyAccountingEntryDetails;
        $DailyAccountingEntryDetails->RestrictionID = $RestrictionID;
        $DailyAccountingEntryDetails->AccountID = $AccountID;
        $DailyAccountingEntryDetails->TransactionAmount = $TransactionAmount;
        $DailyAccountingEntryDetails->TransactionType = $TransactionType;
        $DailyAccountingEntryDetails->CurrencyValue = $CurrencyValue;
        $DailyAccountingEntryDetails->TransactionDetails = $TransactionDetails;
        $DailyAccountingEntryDetails->CurrentBalance = $CurrentBalance;
        $DailyAccountingEntryDetails->AddedBy = $UserID;
        $inserted = $DailyAccountingEntryDetails->save();
        if ($inserted) {
            $flag = $this->updateAccountBalances($AccountID, $TransactionAmount, $TransactionType);
        } else {
            $flag = false;
        }
        return $flag ? 1 : -1;
    }

    private function updateAccountBalances($AccountID, $TransactionAmount, $TransactionType)
    {
        $flag = true;
        if ($TransactionType == 1) {
            $TransactionAmount *= -1;
        }
        while ($AccountID != 0) {
            $accountBalance = DB::table('accounts')->where('AccountID', $AccountID)->value('Balance');

            $updated = DB::table('accounts')->where('AccountID', $AccountID)
                ->update(['Balance' => $accountBalance + $TransactionAmount]);
            $AccountID = DB::table('accounts')->where('AccountID', $AccountID)->value('AccountParent');
        }

        return $flag;
    }

    public function deleteDaily($RestrictionID)
    {
        $flag = true;

        // Create a new daily accounting entry as a record of deletion
        $restrictionDetails = " فيد عكسي للقيد رقم" . $RestrictionID;
        $userID = auth()->user()->id;
        $this->saveDaily($restrictionDetails, $userID, '1', '1');

        // Retrieve and process daily accounting entry details
        $restrictions = DailyAccountingEntryDetails::where('RestrictionID', $RestrictionID)->get();
        foreach ($restrictions as $detail) {
            $accountID = $detail->AccountID;
            $transactionAmount = $detail->TransactionAmount;

            // Invert the TransactionType
            $transactionType = ($detail->TransactionType == 1) ? 2 : 1;
            $currencyValue = $detail->CurrencyValue;

            // Save new daily accounting entry details as a record of deletion
            $result = $this->saveDailyDetails($RestrictionID, $accountID, $transactionAmount, $transactionType, $currencyValue, $restrictionDetails, $userID);

            if ($result == -1) {
                $flag = false;
            }
        }

        // If all detail records were successfully created, update the 'Deleted' flag in the main daily accounting entry
        if ($flag) {
            $restriction = DailyAccountingEntry::find($RestrictionID);
            $restriction->Deleted = 1;
            $restriction->save();
        }

        return $flag;
    }
}
