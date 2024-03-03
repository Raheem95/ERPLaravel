<?php

namespace App\Http\Controllers;
use App\Sale;
use App\SaleDetails;

use Illuminate\Http\Request;

class StockSaleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Sales = Sale::where("Transfer", ">=","1")->orderBy('Transfer', 'desc')->orderBy('SaleID', 'desc')->get();

        return view("stocks.sales.index")->with(['Sales' => $Sales]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
        $Sale = Sale::find($id);
        $SaleDetails = $Sale->Sale_details;
        return view("stocks.sales.view")->with(['Sale' => $Sale, "SaleDetails" => $SaleDetails]);
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
        $Result = "1";
        $flag = true;
        $Sale = Sale::find($request->SaleID);
        $Sale->Transfer = $request->Status;
        $StockID = $Sale->StockID;
        $Status = $request->Status;
        $StockController = new StockController;
        $SaleDetails = SaleDetails::where("SaleID", $request->SaleID)->get();
        if ($Status == 1)
            foreach ($SaleDetails as $SaleItem) {
                $AvailableQTY = $StockController->getStockItemQTY($StockID, $SaleItem->ItemID);
                if ($AvailableQTY < $SaleItem->ItemQTY) {
                    $Result .= " الكمية غير متوفرة للمنتج " . $SaleItem->item->ItemName . "<br>";
                    $flag = false;
                }
            }
        if ($flag) {
            $Sale->save();
        }
            return response()->json($Result);
    }
}

