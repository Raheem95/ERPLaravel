<?php

namespace App\Http\Controllers;

use App\Supplier;
use Illuminate\Http\Request;
use App\Purchase;
use App\Item;
use App\Currency;
use App\PurchaseDetails;
use App\PurchasePayment;

class PurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Supplier = [];
        $Currencies = Currency::orderBy('CurrencyID', 'asc')->get();
        $Purchases = Purchase::orderBy('PurchaseID', 'desc')->get();
        foreach ($Purchases as $purchase) {
            $Supplier = $purchase->supplier;
            // Do something with $Supplier
        }
        return view("purchases.index")->with(['Purchases' => $Purchases, "Supplier" => $Supplier, "Currencies" => $Currencies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Suppliers = Supplier::orderBy('SupplierID', 'asc')->get();
        $Items = Item::orderBy('ItemID', 'asc')->get();
        return view("purchases.create")->with(["Suppliers" => $Suppliers, "Items" => $Items]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $flag = true;
        $maxPurchaseNumber = Purchase::max('PurchaseNumber');
        $UserID = auth()->user()->id;
        if (!$maxPurchaseNumber)
            $maxPurchaseNumber = 0;
        $maxPurchaseNumber = str_replace('P', '', $maxPurchaseNumber);
        $maxPurchaseNumber++;
        $maxPurchaseNumber = sprintf("P%06d", $maxPurchaseNumber);
        $SupplierID = $request->SupplierID;
        $SupplierName = $request->SupplierName;
        $Supplier = Supplier::find($SupplierID);
        $AccountID = $Supplier->AccountID;
        $TotalPurchase = $request->TotalPurchase;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $restrictionDetails = "فاتورة مشتريات بالرقم " . $maxPurchaseNumber . " خاصة بالعميل " . $SupplierName;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($restrictionDetails, $UserID, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $AccountID, $TotalPurchase, 1, 1, $restrictionDetails, $UserID);
            if ($Result == 1)
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, 20, $TotalPurchase, 2, 1, $restrictionDetails, $UserID);
            if ($Result == 1) {
                $Purchace = new Purchase;
                $Purchace->PurchaseNumber = $maxPurchaseNumber;
                $Purchace->SupplierID = $SupplierID;
                $Purchace->SupplierName = $SupplierName;
                $Purchace->AccountID = $AccountID;
                $Purchace->TotalPurchase = $TotalPurchase;
                $Purchace->PurchaseStatus = 0;
                $Purchace->StockID = 0;
                $Purchace->RestrictionID = $RestrictionID;
                $Purchace->Transfer = 0;
                $Purchace->CurrencyID = 0;
                $Purchace->AddedBy = $UserID;
                $Purchace->save();
                $PurchaseID = $Purchace->PurchaseID;
                $NumberOfItems = $request->NumberOfItems;

                for ($i = 1; $i <= $NumberOfItems; $i++) {
                    $PurchaseDetails = new PurchaseDetails;
                    $PurchaseDetails->PurchaseID = $PurchaseID;
                    $PurchaseDetails->ItemID = $request->input("ItemID{$i}");
                    $PurchaseDetails->ItemQTY = $request->input("ItemQTY{$i}");
                    $PurchaseDetails->ItemPrice = $request->input("ItemPrice{$i}");
                    $PurchaseDetails->Transfare = 0;
                    if (!$PurchaseDetails->save())
                        $flag = false;
                }
            } else {
                $flag = false;
            }
        } else {
            $flag = false;
        }
        if ($flag)
            return redirect("/purchases")->with("success", "تمت اضافة  الفاتورة بنجاح");
        else
            return redirect("/purchases")->with("error", "خطاء في حفظ الفاتورة");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Purchase = Purchase::find($id);
        $PurchaseDetails = $Purchase->purchase_details;
        return view("purchases.view")->with(['Purchase' => $Purchase, "PurchaseDetails" => $PurchaseDetails]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Purchase = Purchase::find($id);
        $PurchaseDetails = $Purchase->purchase_details;
        $Suppliers = Supplier::orderBy('SupplierID', 'asc')->get();
        $Items = Item::orderBy('ItemID', 'asc')->get();
        return view("purchases.edit")->with(['Suppliers' => $Suppliers, 'Items' => $Items, 'Purchase' => $Purchase, "PurchaseDetails" => $PurchaseDetails]);
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
        $flag = true;
        $UserID = auth()->user()->id;
        $Purchase = Purchase::find($id);
        $RestrictionID = $Purchase->RestrictionID;
        $Supplier = Supplier::find($request->SupplierID);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $DeletingOldRestrictionIDResult = $DailyAccountingEntryController->deleteDaily($RestrictionID);
        if ($DeletingOldRestrictionIDResult) {
            $DailyAccountingEntryController = new DailyAccountingEntryController;
            $restrictionDetails = "تعديل فاتورة مشتريات بالرقم " . $Purchase->PurchaseNumber . " خاصة بالعميل " . $request->SupplierName;
            $RestrictionID = $DailyAccountingEntryController->saveDaily($restrictionDetails, $UserID, 1, 0);
            if ($RestrictionID > 0) {
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $Supplier->AccountID, $request->TotalPurchase, 1, 1, $restrictionDetails, $UserID);
                if ($Result == 1)
                    $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, 20, $request->TotalPurchase, 2, 1, $restrictionDetails, $UserID);
                if ($Result == 1) {
                    $PurchaseDetails = PurchaseDetails::where("PurchaseID", $id);
                    $PurchaseDetails->delete();
                    $Purchase->SupplierID = $request->SupplierID;
                    $Purchase->SupplierName = $request->SupplierName;
                    $Purchase->AccountID = $Supplier->AccountID;
                    $Purchase->TotalPurchase = $request->TotalPurchase;
                    $Purchase->RestrictionID = $RestrictionID;
                    $Purchase->save();
                    $PurchaseID = $Purchase->PurchaseID;
                    $NumberOfItems = $request->NumberOfItems;

                    for ($i = 1; $i <= $NumberOfItems; $i++) {
                        $PurchaseDetails = new PurchaseDetails;
                        $PurchaseDetails->PurchaseID = $PurchaseID;
                        $PurchaseDetails->ItemID = $request->input("ItemID{$i}");
                        $PurchaseDetails->ItemQTY = $request->input("ItemQTY{$i}");
                        $PurchaseDetails->ItemPrice = $request->input("ItemPrice{$i}");
                        $PurchaseDetails->Transfare = 0;
                        if (!$PurchaseDetails->save())
                            $flag = false;
                    }
                    if ($flag)
                        return redirect("/purchases")->with("success", "تمت تعديل  الفاتورة بنجاح");
                    else
                        return redirect("/purchases")->with("error", "خطاء في حفظ المنتجات");
                } else
                    return redirect("/purchases")->with("error", "خطاء في حفظ تفاصيل القيد");
            } else
                return redirect("/purchases")->with("error", "خطاء في حفظ القيد");
        } else {
            return redirect("/purchases")->with("error", "خطاء في في جذف القيد السابق للفاتورة");
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
        $Purchase = Purchase::find($id);
        $RestrictionID = $Purchase->RestrictionID;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $DeletingOldRestrictionIDResult = $DailyAccountingEntryController->deleteDaily($RestrictionID);
        if ($DeletingOldRestrictionIDResult) {
            $PurchaseDetails = PurchaseDetails::where("PurchaseID", $id);
            $PurchaseDetails->delete();
            if ($Purchase->delete())
                return redirect("/purchases")->with("success", "تمت حذف  الفاتورة بنجاح");
        }
        return redirect("/purchases")->with("error", "خطاء في جذف الفاتورة");
    }

    public function AddPayment(Request $request)
    {
        $PurchaseID = $request->input('PurchaseID');
        $PaidAmount = $request->input('PaidAmount');
        $FromAccount = $request->input('FromAccount');
        $UserID = auth()->user()->id;
        $Purchase = Purchase::find($PurchaseID);
        $AccountID = $Purchase->AccountID;
        $SupplierName = $Purchase->SupplierName;
        $PurchaseNumber = $Purchase->PurchaseNumber;
        $restrictionDetails = "سداد   مبلغ " . $PaidAmount . " للسيد / " . $SupplierName . " لفاتورة المشتريات بالرقم " . $PurchaseNumber;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($restrictionDetails, $UserID, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $FromAccount, $PaidAmount, 1, 1, $restrictionDetails, $UserID);
            if ($Result == 1)
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $AccountID, $PaidAmount, 2, 1, $restrictionDetails, $UserID);
            if ($Result == 1) {
                $PurchasePayment = new PurchasePayment;
                $PurchasePayment->PurchaseID = $PurchaseID;
                $PurchasePayment->PaidAmount = $PaidAmount;
                $PurchasePayment->RestrictionID = $RestrictionID;
                $PurchasePayment->AddedBy = $UserID;
                if ($PurchasePayment->save()) {
                    $Purchase->PaidAmount += $PaidAmount;
                    if ($Purchase->save()) {
                        return response()->json(1);
                    }
                } else
                    return response()->json("خطاء في تعديل الفاتورة بعد السداد");
            } else
                return response()->json("خطاء في تفاصيل القيد ");
        } else
            return response()->json("خطاء في انشاء  القيد ");
    }
    public function payment_details($PurchaseID)
    {
        $PurchasePaymentDetails = PurchasePayment::where('PurchaseID', $PurchaseID)->get();
        return response()->json($PurchasePaymentDetails);
    }
    public function DeletePayment(Request $request)
    {
        $Payment = PurchasePayment::find($request->PaymentID);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $Result = $DailyAccountingEntryController->deleteDaily($Payment->RestrictionID);
        if ($Result) {
            $PaidAmount = $Payment->PaidAmount;
            $Purchase = Purchase::find($Payment->PurchaseID);
            $Purchase->PaidAmount -= $Payment->PaidAmount;
            $Purchase->save();
            $Payment->delete();
            return response()->json($PaidAmount);
        } else
            return response()->json("خطاء في حذف  القيد ");
    }
}
