<?php

namespace App\Http\Controllers;

use App\Http\Controllers\StockController;
use App\Customer;
use Illuminate\Http\Request;
use App\Sale;
use App\Item;
use App\Currency;
use App\SaleDetails;
use App\SalePayment;
use App\Stock;
use App\StockItems;

class SaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Currencies = Currency::orderBy('CurrencyID', 'asc')->get();
        $Sales = Sale::orderBy('SaleID', 'desc')->get();

        return view("sales.index")->with(['Sales' => $Sales, "Currencies" => $Currencies]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Customers = Customer::orderBy('CustomerID', 'asc')->get();
        $Items = Item::orderBy('ItemID', 'asc')->get();
        $Stocks = Stock::orderBy('StockID', 'asc')->get();
        return view("sales.create")->with(["Customers" => $Customers, "Items" => $Items, "Stocks" => $Stocks]);
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
        $maxSaleNumber = Sale::max('SaleNumber');
        $UserID = auth()->user()->id;
        if (!$maxSaleNumber)
            $maxSaleNumber = 0;
        $maxSaleNumber = str_replace('S', '', $maxSaleNumber);
        $maxSaleNumber++;
        $maxSaleNumber = sprintf("S%06d", $maxSaleNumber);
        $CustomerID = $request->CustomerID;
        $CustomerName = $request->CustomerName;
        $Customer = Customer::find($CustomerID);
        $AccountID = $Customer->AccountID;
        $TotalSale = $request->TotalSale;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $restrictionDetails = "فاتورة مبيعات بالرقم " . $maxSaleNumber . " خاصة بالعميل " . $CustomerName;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($restrictionDetails, $UserID, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $AccountID, $TotalSale, 2, 1, $restrictionDetails, $UserID);
            if ($Result == 1)
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, 4, $TotalSale, 1, 1, $restrictionDetails, $UserID);
            if ($Result == 1) {
                $Purchace = new Sale;
                $Purchace->SaleNumber = $maxSaleNumber;
                $Purchace->CustomerID = $CustomerID;
                $Purchace->CustomerName = $CustomerName;
                $Purchace->AccountID = $AccountID;
                $Purchace->TotalSale = $TotalSale;
                $Purchace->SaleStatus = 0;
                $Purchace->StockID = $request->StockID;
                $Purchace->RestrictionID = $RestrictionID;
                $Purchace->Transfer = 0;
                $Purchace->CurrencyID = 0;
                $Purchace->AddedBy = $UserID;
                $Purchace->save();
                $SaleID = $Purchace->SaleID;
                $NumberOfItems = $request->NumberOfItems;

                for ($i = 1; $i <= $NumberOfItems; $i++) {
                    $SaleDetails = new SaleDetails;
                    $SaleDetails->SaleID = $SaleID;
                    $SaleDetails->ItemID = $request->input("ItemID{$i}");
                    $SaleDetails->ItemQTY = $request->input("ItemQTY{$i}");
                    $SaleDetails->ItemPrice = $request->input("ItemPrice{$i}");
                    $SaleDetails->Transfare = 0;
                    if (!$SaleDetails->save())
                        $flag = false;
                }
            } else {
                $flag = false;
            }
        } else {
            $flag = false;
        }
        if ($flag)
            return redirect("/sales")->with("success", "تمت اضافة  الفاتورة بنجاح");
        else
            return redirect("/sales")->with("error", "خطاء في حفظ الفاتورة");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Sale = Sale::find($id);
        $SaleDetails = $Sale->Sale_details;
        return view("sales.view")->with(['Sale' => $Sale, "SaleDetails" => $SaleDetails]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Sale = Sale::find($id);
        $SaleDetails = $Sale->Sale_details;
        $Customers = Customer::orderBy('CustomerID', 'asc')->get();
        $Items = Item::orderBy('ItemID', 'asc')->get();
        $Stocks = Stock::orderBy('StockID', 'asc')->get();
        return view("sales.edit")->with(['Customers' => $Customers, 'Items' => $Items, "Stocks" => $Stocks, 'Sale' => $Sale, "SaleDetails" => $SaleDetails]);
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
        $Sale = Sale::find($id);
        $RestrictionID = $Sale->RestrictionID;
        $Customer = Customer::find($request->CustomerID);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $DeletingOldRestrictionIDResult = $DailyAccountingEntryController->deleteDaily($RestrictionID);
        if ($DeletingOldRestrictionIDResult) {
            $DailyAccountingEntryController = new DailyAccountingEntryController;
            $restrictionDetails = "تعديل فاتورة مبيعات بالرقم " . $Sale->SaleNumber . " خاصة بالعميل " . $request->CustomerName;
            $RestrictionID = $DailyAccountingEntryController->saveDaily($restrictionDetails, $UserID, 1, 0);
            if ($RestrictionID > 0) {
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $Customer->AccountID, $request->TotalSale, 2, 1, $restrictionDetails, $UserID);
                if ($Result == 1)
                    $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, 4, $request->TotalSale, 1, 1, $restrictionDetails, $UserID);
                if ($Result == 1) {
                    $SaleDetails = SaleDetails::where("SaleID", $id);
                    $SaleDetails->delete();
                    $Sale->CustomerID = $request->CustomerID;
                    $Sale->CustomerName = $request->CustomerName;
                    $Sale->AccountID = $Customer->AccountID;
                    $Sale->TotalSale = $request->TotalSale;
                    $Sale->RestrictionID = $RestrictionID;
                    $Sale->StockID = $request->StockID;
                    $Sale->save();
                    $SaleID = $Sale->SaleID;
                    $NumberOfItems = $request->NumberOfItems;

                    for ($i = 1; $i <= $NumberOfItems; $i++) {
                        $SaleDetails = new SaleDetails;
                        $SaleDetails->SaleID = $SaleID;
                        $SaleDetails->ItemID = $request->input("ItemID{$i}");
                        $SaleDetails->ItemQTY = $request->input("ItemQTY{$i}");
                        $SaleDetails->ItemPrice = $request->input("ItemPrice{$i}");
                        $SaleDetails->Transfare = 0;
                        if (!$SaleDetails->save())
                            $flag = false;
                    }
                    if ($flag)
                        return redirect("/sales")->with("success", "تمت تعديل  الفاتورة بنجاح");
                    else
                        return redirect("/sales")->with("error", "خطاء في حفظ المنتجات");
                } else
                    return redirect("/sales")->with("error", "خطاء في حفظ تفاصيل القيد");
            } else
                return redirect("/sales")->with("error", "خطاء في حفظ القيد");
        } else {
            return redirect("/sales")->with("error", "خطاء في في جذف القيد السابق للفاتورة");
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
        $Sale = Sale::find($id);
        $RestrictionID = $Sale->RestrictionID;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $DeletingOldRestrictionIDResult = $DailyAccountingEntryController->deleteDaily($RestrictionID);
        if ($DeletingOldRestrictionIDResult) {
            $SaleDetails = SaleDetails::where("SaleID", $id);
            $SaleDetails->delete();
            if ($Sale->delete())
                return redirect("/sales")->with("success", "تمت حذف  الفاتورة بنجاح");
        }
        return redirect("/sales")->with("error", "خطاء في جذف الفاتورة");
    }

    public function GetItemDetails(Request $request)
    {
        $ItemID = $request->ItemID;
        $StockID = $request->StockID;
        $Item = Item::find($ItemID);
        $StockController = new StockController;
        $AvailableQTY = $StockController->getStockItemQTY($StockID, $ItemID);
        $SalesPrice = $Item->ItemSalePrice;
        return response()->json(["AvailableQTY" => $AvailableQTY, "SalesPrice" => $SalesPrice]);
    }

    public function AddPayment(Request $request)
    {
        $SaleID = $request->input('SaleID');
        $PaidAmount = $request->input('PaidAmount');
        $FromAccount = $request->input('FromAccount');
        $UserID = auth()->user()->id;
        $Sale = Sale::find($SaleID);
        $AccountID = $Sale->AccountID;
        $CustomerName = $Sale->CustomerName;
        $SaleNumber = $Sale->SaleNumber;
        $restrictionDetails = "سداد   مبلغ " . number_format($PaidAmount) . " للسيد / " . $CustomerName . " لفاتورة مبيعات بالرقم " . $SaleNumber;
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $RestrictionID = $DailyAccountingEntryController->saveDaily($restrictionDetails, $UserID, 1, 0);
        if ($RestrictionID > 0) {
            $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $FromAccount, $PaidAmount, 2, 1, $restrictionDetails, $UserID);
            if ($Result == 1)
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, $AccountID, $PaidAmount, 1, 1, $restrictionDetails, $UserID);
            if ($Result == 1) {
                $SalePayment = new SalePayment;
                $SalePayment->SaleID = $SaleID;
                $SalePayment->PaidAmount = $PaidAmount;
                $SalePayment->RestrictionID = $RestrictionID;
                $SalePayment->AddedBy = $UserID;
                if ($SalePayment->save()) {
                    $Sale->PaidAmount += $PaidAmount;
                    if ($Sale->save()) {
                        return response()->json(1);
                    }
                } else
                    return response()->json("خطاء في تعديل الفاتورة بعد السداد");
            } else
                return response()->json("خطاء في تفاصيل القيد ");
        } else
            return response()->json("خطاء في انشاء  القيد ");
    }
    public function payment_details($SaleID){
        $SalePaymentDetails = SalePayment::where('SaleID', $SaleID)->get();
        return response()->json($SalePaymentDetails);
    }
    public function DeletePayment(Request $request)
    {
        $Payment = SalePayment::find($request->PaymentID);
        $DailyAccountingEntryController = new DailyAccountingEntryController;
        $Result = $DailyAccountingEntryController->deleteDaily($Payment->RestrictionID);
        if ($Result) {
            $PaidAmount = $Payment->PaidAmount;
            $Sale = Sale::find($Payment->SaleID);
            $Sale->PaidAmount -= $Payment->PaidAmount;
            $Sale->save();
            $Payment->delete();
            return response()->json($PaidAmount);
        } else
            return response()->json("خطاء في حذف  القيد ");
    }
    public function Transfare(Request $request)
    {
$Result = "";
        $flag = true;
        $Sale = Sale::find($request->SaleID);
        $Sale->Transfer = $request->Status;
        $StockID = $Sale->StockID;
        $StockController = new StockController;
        $SaleDetails = SaleDetails::where("SaleID", $request->SaleID)->get();
         $Status = 0;
        if($request->Status == 0)
            $Status = 1;
        if ($Status == 0 )
            foreach ($SaleDetails as $SaleItem) {
                $AvailableQTY = $StockController->getStockItemQTY($StockID, $SaleItem->ItemID);
                if ($AvailableQTY < $SaleItem->ItemQTY) {
                    $Result .= " الكمية غير متوفرة للمنتج " . $SaleItem->item->ItemName . "<br>";
                    $flag = false;
                }
            }
        if ($flag) {
            
            foreach ($SaleDetails as $SaleItem) {
                $SaleItemID = $SaleItem->ItemID;
                $SaleQTY = $SaleItem->ItemQTY;
                $ItemName = $SaleItem->item->ItemName;
                $TransactionDetails = "الغاء صرف فاتورة مبيعات  بالرقم " . $Sale->SaleNumber . " للمنتج " . $ItemName;
                if ($Status == 0) {
                    $SaleQTY *= -1;
                    $TransactionDetails = "  صرف فاتورة مبيعات  بالرقم " . $Sale->SaleNumber . " للمنتج " . $ItemName;
                }
                
       
                $Result .= $StockController->AddTransaction($StockID, $SaleItemID, $SaleQTY, $TransactionDetails, $Status);
                if ($Result == 1) {
                    $MyItem = Item::find($SaleItemID);
                    $MyItem->ItemQty += $SaleQTY;
                    if (!$MyItem->save()) {
                        $Result .= "خطاء في تعديل  كمية المنتج " . $ItemName . " <br> ";
                        $flag = false;
                    }
                } else if ($Result == -1) {
                    $flag = false;
                    $Result .= "خطاء في تعديل كمية المنتج " . $ItemName . " في المخزن <br> ";
                } else if ($Result == -2) {
                    $flag = false;
                    $Result .= "خطاء في اضافة الحركة المخزنية للمنتج " . $ItemName . " <br> ";
                }
            }
            if ($flag)
                $Sale->save();

        }
        if ($flag)
            return response()->json(1);
        else
            return response()->json($Result);
    }
}
