<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Category;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Items = Item::with('categories')->orderBy('ItemID', 'desc')->get();
        return view("items.index")->with('items', $Items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Categories = Category::orderBy('CategoryID', 'desc')->get();
        return view("items.create")->with('Categories', $Categories);
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
            'ItemName' => 'required|unique:items,ItemName',
            'ItemPrice' => 'required|numeric|min:0',
            'ItemSalePrice' => 'required|numeric|min:0',
            'Minimum' => 'required|numeric|min:0',
            'CategoryID' => 'required',
        ], [
            'ItemName.required' => 'يجب ادخال اسم المنتج',
            'ItemName.unique' => 'هذا المنتج مسجل',
            'ItemPrice.required' => 'يجب ادخال سعر المنتج',
            'ItemPrice.numeric' => 'يجب ادخال قيمة رقمية لسعر المنتج',
            'ItemPrice.min' => 'يجب أن يكون سعر المنتج على الأقل 0',
            'ItemSalePrice.required' => 'يجب ادخال سعر البيع',
            'ItemSalePrice.numeric' => 'يجب ادخال قيمة رقمية لسعر البيع',
            'ItemSalePrice.min' => 'يجب أن يكون سعر البيع على الأقل 0',
            'Minimum.required' => 'يجب ادخال الحد الأدنى',
            'Minimum.numeric' => 'يجب ادخال قيمة رقمية للحد الأدنى',
            'Minimum.min' => 'يجب أن يكون الحد الأدنى على الأقل 0',
            'CategoryID.required' => 'يجب اختيار تصنيف المنتج',
        ]);
        $maxItemPartNumber = Item::max('ItemPartNumber');
        if (!$maxItemPartNumber)
            $maxItemPartNumber = 0;
        $maxItemPartNumber = str_replace('A', '', $maxItemPartNumber);
        $maxItemPartNumber++;
        $maxItemPartNumber = sprintf("A%06d", $maxItemPartNumber);

        $Item = new Item;
        $Item->ItemName = $request->input('ItemName');
        $Item->ItemPrice = $request->input('ItemPrice');
        $Item->ItemSalePrice = $request->input('ItemSalePrice');
        $Item->Minimum = $request->input('Minimum');
        $Item->CategoryID = $request->input('CategoryID');
        $Item->ItemPartNumber = $maxItemPartNumber;
        $Item->AddedBy = auth()->user()->id;
        $Item->save();
        return redirect("/items")->with("success", "تمت اضافة المنتج بنجاح");
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
        $Item = Item::find($id);
        $Categories = Category::orderBy('CategoryID', 'desc')->get();
        return view("items.edit")->with(['Categories' => $Categories, 'Item' => $Item]);
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
            'ItemName' => [
                'required',
                Rule::unique('items')->ignore($request->ItemID, 'ItemID'),
            ],
            'ItemPrice' => 'required|numeric|min:0',
            'ItemSalePrice' => 'required|numeric|min:0',
            'Minimum' => 'required|numeric|min:0',
            'CategoryID' => 'required',
        ], [
            'ItemName.required' => 'يجب ادخال اسم المنتج',
            'ItemName.unique' => 'هذا المنتج مسجل',
            'ItemPrice.required' => 'يجب ادخال سعر المنتج',
            'ItemPrice.numeric' => 'يجب ادخال قيمة رقمية لسعر المنتج',
            'ItemPrice.min' => 'يجب أن يكون سعر المنتج على الأقل 0',
            'ItemSalePrice.required' => 'يجب ادخال سعر البيع',
            'ItemSalePrice.numeric' => 'يجب ادخال قيمة رقمية لسعر البيع',
            'ItemSalePrice.min' => 'يجب أن يكون سعر البيع على الأقل 0',
            'Minimum.required' => 'يجب ادخال الحد الأدنى',
            'Minimum.numeric' => 'يجب ادخال قيمة رقمية للحد الأدنى',
            'Minimum.min' => 'يجب أن يكون الحد الأدنى على الأقل 0',
            'CategoryID.required' => 'يجب اختيار تصنيف المنتج',
        ]);
        $Item = Item::find($id);
        $Item->ItemName = $request->input('ItemName');
        $Item->ItemPrice = $request->input('ItemPrice');
        $Item->ItemSalePrice = $request->input('ItemSalePrice');
        $Item->Minimum = $request->input('Minimum');
        $Item->CategoryID = $request->input('CategoryID');
        $Item->AddedBy = auth()->user()->id;
        $Item->save();
        return redirect("/items")->with("success", "تمت تعديل المنتج بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Item = Item::find($id);
        $Item->delete();
        return redirect("/items")->with("success", "تمت حذف المنتج بنجاح");
    }
}
