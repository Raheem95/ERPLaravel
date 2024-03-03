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
}
