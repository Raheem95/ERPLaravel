<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Stock;
use Illuminate\Validation\Rule;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Stocks = Stock::orderBy('StockID', 'asc')->get();
        return view("stocks.index")->with('Stocks', $Stocks);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("stocks.create");
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
            'StockName' => 'required|unique:stock,StockName',
        ], [
            'StockName.required' => 'يجب إدخال اسم المخزن',
            'StockName.unique' => 'هذا المخزن مسجل مسبقًا',
        ]);
        $Stock = new Stock;
        $Stock->StockName = $request->input('StockName');
        $Stock->AddedBy = auth()->user()->id;
        $Stock->save();
        return redirect("/stocks")->with("success", "تمت  اضافة المخزن بنجاح");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Stock = Stock::find($id);
        $StockItems = $Stock->stock_items;
        $StockTransactions = $Stock->stock_transactions;
        return view("stocks.view")->with(['Stock' => $Stock, "StockItems" => $StockItems, "StockTransactions" => $StockTransactions]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Stock = Stock::find($id);
        return view("stocks.edit")->with('Stock', $Stock);
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
        $this->validate($request, [
            'StockName' => [
                'required',
                Rule::unique('stock')->ignore($request->StockID, 'StockID'),
            ],
        ], [
            'StockName.required' => 'يجب إدخال اسم المخزن',
            'StockName.unique' => 'هذا المخزن مسجل مسبقًا',
        ]);
        $Stock = Stock::find($id);
        $Stock->StockName = $request->input('StockName');
        $Stock->save();
        return redirect("/stocks")->with("success", "تمت  تعديل المخزن بنجاح");
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
