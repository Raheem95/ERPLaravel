<?php

namespace App\Http\Controllers;
use App\Purchase;
use App\PurchaseDetails;
use App\Item;

use Illuminate\Http\Request;

class StockPurchaseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Purchases = Purchase::where("Transfer", ">=","1")->orderBy('Transfer', 'asc')->orderBy('PurchaseID', 'desc')->get();

        return view("stocks.purchases.index")->with(['Purchases' => $Purchases]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        return view("stocks.purchases.view")->with(['Purchase' => $Purchase, "PurchaseDetails" => $PurchaseDetails]);
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



 public function Transfare(Request $request){
        $Result = "";
        $flag = true;
        $Purchase = Purchase::find($request->PurchaseID);
        $Purchase->Transfer = $request->Status;
        $StockID = $Purchase->StockID;
        $Status = $request->Status;
        $StockController = new StockController;
        $PurchaseDetails = PurchaseDetails::where("PurchaseID", $request->PurchaseID)->get();
        if ($Status == 1)
            foreach ($PurchaseDetails as $PurchaseItem) {
                $AvailableQTY = $StockController->getStockItemQTY($StockID, $PurchaseItem->ItemID);
                if ($AvailableQTY < $PurchaseItem->ItemQTY) {
                    $Result .= " الكمية غير متوفرة للمنتج " . $PurchaseItem->item->ItemName . "<br>";
                    $flag = false;
                }
            }
        if ($flag) {
            foreach ($PurchaseDetails as $PurchaseItem) {
                $PurchaseItemID = $PurchaseItem->ItemID;
                $PurchaseQTY = $PurchaseItem->ItemQTY;
                $ItemName = $PurchaseItem->item->ItemName;
                $TransactionDetails = "تغذية المخزن عن طريق فاتورة المشتريات بالرقم " . $Purchase->PurchaseNumber . " للمنتج " . $ItemName;
                if ($Status == 1) {
                    $PurchaseQTY *= -1;
                    $TransactionDetails = "الغاء تغذية المخزن عن طريق فاتورة المشتريات بالرقم " . $Purchase->PurchaseNumber . " للمنتج " . $ItemName;
                }
                $Result .= $StockController->AddTransaction($StockID, $PurchaseItemID, $PurchaseQTY, $TransactionDetails, $Status);
                if ($Result > 0) {
                    $MyItem = Item::find($PurchaseItemID);
                    $MyItem->ItemQty += $PurchaseQTY;
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
            if($flag)
                $Purchase->save();
        }
            return response()->json($Result);
    }
}