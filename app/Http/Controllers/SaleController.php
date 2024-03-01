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
                $Result = $DailyAccountingEntryController->saveDailyDetails($RestrictionID, 20, $TotalSale, 1, 1, $restrictionDetails, $UserID);
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

    public function AddPayment()
    {

    }
    public function payment_details()
    {

    }
    public function DeletePayment()
    {

    }
    public function Transfare()
    {

    }
}
