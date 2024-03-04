<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\StockTransfare;
use App\StockTransfareDetails;
use App\Item;
use App\Stock;

class StockTransfareController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Transfares = StockTransfare::orderBy('TransfareID', 'desc')->get();
        return view("stocks.stock_transfare.index")->with(['Transfares' => $Transfares]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Items = Item::orderBy('ItemID', 'asc')->get();
        $Stocks = Stock::orderBy('StockID', 'asc')->get();
        return view("stocks.stock_transfare.create")->with(["Items" => $Items, "Stocks" => $Stocks]);
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
        $StockTransfare = new StockTransfare;
        $StockTransfare->FromStockID = $request->input("FromStockID");
        $StockTransfare->ToStockID = $request->input("ToStockID");
        $StockTransfare->Comment = $request->input("Comment");
        $StockTransfare->AddedBy = auth()->user()->id;
        $StockTransfare->save();
        $TransfareID = $StockTransfare->TransfareID;
        $NumberOfItems = $request->input("NumberOfItems");
        for ($i = 1; $i <= $NumberOfItems; $i++) {
            $StockTransfareDetails = new StockTransfareDetails;
            $StockTransfareDetails->TransfareID = $TransfareID;
            $StockTransfareDetails->ItemID = $request->input("ItemID{$i}");
            $StockTransfareDetails->ItemQTY = $request->input("ItemQTY{$i}");
            if (!$StockTransfareDetails->save())
                $flag = false;
        }
        if ($flag)
            return redirect("/Stocks/Transfare")->with("success", "تمت حفظ طلب التحويل  بنجاح");
        else
            return redirect("/Stocks/Transfare")->with("error", "خطاء في حفظ طلب التحويل");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $StockTransfare = StockTransfare::find($id);
        $StockTransfareDetails = StockTransfareDetails::where("TransfareID", $id)->get();
        return view("stocks.stock_transfare.view")->with(['StockTransfare' => $StockTransfare, "StockTransfareDetails" => $StockTransfareDetails]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $StockTransfare = StockTransfare::find($id);
        $StockTransfareDetails = StockTransfareDetails::where("TransfareID", $id)->get();
        $Items = Item::orderBy('ItemID', 'asc')->get();
        $Stocks = Stock::orderBy('StockID', 'asc')->get();
        return view("stocks.stock_transfare.edit")->with(["Transfare" => $StockTransfare, "TransfareDetails" => $StockTransfareDetails, "Items" => $Items, "Stocks" => $Stocks]);
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
        $StockTransfare = StockTransfare::find($id);
        $StockTransfare->FromStockID = $request->input("FromStockID");
        $StockTransfare->ToStockID = $request->input("ToStockID");
        $StockTransfare->Comment = $request->input("Comment");
        if ($StockTransfare->save()) {
            $TransfareID = $StockTransfare->TransfareID;
            $StockTransfareDetails = StockTransfareDetails::where("TransfareID", $id);
            $StockTransfareDetails->delete();
            $NumberOfItems = $request->input("NumberOfItems");
            for ($i = 1; $i <= $NumberOfItems; $i++) {
                $StockTransfareDetails = new StockTransfareDetails;
                $StockTransfareDetails->TransfareID = $TransfareID;
                $StockTransfareDetails->ItemID = $request->input("ItemID{$i}");
                $StockTransfareDetails->ItemQTY = $request->input("ItemQTY{$i}");
                if (!$StockTransfareDetails->save())
                    $flag = false;
            }
        } else
            $flag = false;

        if ($flag)
            return redirect("/Stocks/Transfare")->with("success", "تمت تعديل طلب التحويل  بنجاح");
        else
            return redirect("/Stocks/Transfare")->with("error", "خطاء في تعديل طلب التحويل");
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


    public function Transfare(Request $request)
    {
        $Result = "";
        $flag = true;
        $StockTransfare = StockTransfare::find($request->TransfareID);
        $StockTransfare->Transfare = $request->Status;
        $FromStockID = $StockTransfare->FromStockID;
        $ToStockID = $StockTransfare->ToStockID;
        $Status = $request->Status;
        $StockController = new StockController;
        $StockTransfareDetails = StockTransfareDetails::where("TransfareID", $request->TransfareID)->get();
        if ($Status == 1) {
            foreach ($StockTransfareDetails as $StockTransfareItem) {
                $AvailableQTY = $StockController->getStockItemQTY($FromStockID, $StockTransfareItem->ItemID);
                if ($AvailableQTY < $StockTransfareItem->ItemQTY) {
                    $Result .= " الكمية غير متوفرة للمنتج " . $StockTransfareItem->item->ItemName . "<br>";
                    $flag = false;
                }
            }
            if ($flag) {
                foreach ($StockTransfareDetails as $StockTransfareItem) {
                    $StockTransfareItemID = $StockTransfareItem->ItemID;
                    $StockTransfareQTY = $StockTransfareItem->ItemQTY;
                    $ItemName = $StockTransfareItem->item->ItemName;
                    $TransactionDetails = "صرف عدد " . abs($StockTransfareQTY) . " من المنتج  " . $ItemName . " لتغذية  " . $StockTransfare->to_stock->StockName;
                    $StockTransfareQTY *= -1;
                    $Result .= $StockController->AddTransaction($FromStockID, $StockTransfareItemID, $StockTransfareQTY, $TransactionDetails, $Status);
                    $TransactionDetails = "استقبال عدد " . abs($StockTransfareQTY) . " من المنتج  " . $ItemName . " محولة  من " . $StockTransfare->from_stock->StockName;
                    $StockTransfareQTY *= -1;
                    $Result .= $StockController->AddTransaction($ToStockID, $StockTransfareItemID, $StockTransfareQTY, $TransactionDetails, $Status);
                }
            }
        } else {
            foreach ($StockTransfareDetails as $StockTransfareItem) {
                $AvailableQTY = $StockController->getStockItemQTY($ToStockID, $StockTransfareItem->ItemID);
                if ($AvailableQTY < $StockTransfareItem->ItemQTY) {
                    $Result .= " الكمية غير متوفرة للمنتج " . $StockTransfareItem->item->ItemName . "<br>";
                    $flag = false;
                }
            }
            if ($flag) {
                foreach ($StockTransfareDetails as $StockTransfareItem) {
                    $StockTransfareItemID = $StockTransfareItem->ItemID;
                    $StockTransfareQTY = $StockTransfareItem->ItemQTY;
                    $ItemName = $StockTransfareItem->item->ItemName;
                    $TransactionDetails = "ارجاع عدد " . abs($StockTransfareQTY) . " من المنتج  " . $ItemName . " لل" . $StockTransfare->from_stock->StockName;
                    $StockTransfareQTY *= -1;
                    $Result .= $StockController->AddTransaction($ToStockID, $StockTransfareItemID, $StockTransfareQTY, $TransactionDetails, $Status);
                    $TransactionDetails = "الغاء صرف عدد " . abs($StockTransfareQTY) . " من المنتج  " . $ItemName . " محولة  الى " . $StockTransfare->to_stock->StockName;
                    $StockTransfareQTY *= -1;
                    $Result .= $StockController->AddTransaction($FromStockID, $StockTransfareItemID, $StockTransfareQTY, $TransactionDetails, $Status);
                }
            }
        }
        if ($flag)
            $StockTransfare->save();
        return response()->json($Result);
    }
}
