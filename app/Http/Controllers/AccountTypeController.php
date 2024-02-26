<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AccountType;
use Illuminate\Validation\Rule;

class AccountTypeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $AccountTypes = AccountType::orderBy('AccountTypeID', 'asc')->get();
        return view("account_managment.account_types.index")->with('AccountTypes', $AccountTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("account_managment.account_types.create");
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
            'AccountTypeName' => 'required|unique:account_type,AccountTypeName',
        ], [
            'AccountTypeName.required' => 'يجب ادخال النوع المنتج',
            'AccountTypeName.unique' => 'هذا النوع مسجل',
        ]);
        $AccountType = new AccountType;
        $AccountType->AccountTypeName = $request->input('AccountTypeName');
        $AccountType->AccountTypeSource = 1;//$request->input('AccountTypeSource');
        $AccountType->AddedBy = auth()->user()->id;
        $AccountType->save();
        return redirect("/AccountManagment/AccountTypes")->with("success", "تمت اضافة  النوع بنجاح");
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
        $AccountType = AccountType::find($id);
        return view("account_managment.account_types.edit")->with('AccountType', $AccountType);
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
            'AccountTypeName' => [
                'required',
                Rule::unique('account_type')->ignore($request->AccountTypeID, 'AccountTypeID'),
            ],
        ], [
            'AccountTypeName.required' => 'يجب ادخال اسم المنتج',
            'AccountTypeName.unique' => 'هذا المنتج مسجل',
        ]);
        $AccountType = AccountType::find($id);
        $AccountType->AccountTypeName = $request->input('AccountTypeName');
        $AccountType->AccountTypeSource = 1;//$request->input('AccountTypeSource');
        $AccountType->save();
        return redirect("/AccountManagment/AccountTypes")->with("success", "تمت تعديل  النوع بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $AccountType = AccountType::find($id);
        $AccountType->delete();
        return redirect("/AccountManagment/AccountTypes")->with("success", "تمت حذف  النوع بنجاح");
    }
}
