<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Currency;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Currencies = Currency::orderBy('CurrencyID', 'asc')->get();
        return view("account_managment.currencies.index")->with('Currencies', $Currencies);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("account_managment.currencies.create");
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
            'CurrencyName' => 'required|unique:currencies,CurrencyName',
        ], [
            'CurrencyName.unique' => 'هذه العملة موجوة',
            'CurrencyName.required' => 'يجب ادخال اسم العملة',
        ]);
        $Currency = new Currency;
        $Currency->CurrencyName = $request->input('CurrencyName');
        $Currency->AddedBy = auth()->user()->id;
        $Currency->save();
        return redirect("/AccountManagment/Currencies")->with("success", "تمت اضافة  العملة بنجاح");
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
        $Currency = Currency::find($id);
        return view("account_managment.currencies.edit")->with('Currency', $Currency);
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
            'CurrencyName' => [
                'required',
                Rule::unique('currencies')->ignore($request->CurrencyID, 'CurrencyID'),
            ],
        ], [
            'CurrencyName.unique' => 'هذه العملة موجوة',
            'CurrencyName.required' => 'يجب ادخال اسم العملة',
        ]);
        $Currency = Currency::find($id);
        $Currency->CurrencyName = $request->input('CurrencyName');
        $Currency->AddedBy = auth()->user()->id;
        $Currency->save();
        return redirect("/AccountManagment/Currencies")->with("success", "تمت تعديل  العملة بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Currency = Currency::find($id);
        $Currency->delete();
        return redirect("/AccountManagment/Currencies")->with("success", "تمت حذف  العملة بنجاح");
    }
}
