<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Supplier;
use App\Account;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Suppliers = Supplier::orderBy('SupplierID', 'desc')->get();
        return view("suppliers.index")->with('Suppliers', $Suppliers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("suppliers.create");
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
            'SupplierName' => 'required|unique:suppliers,SupplierName',
            'SupplierPhone' => 'required|unique:suppliers,SupplierPhone|min:10',
            'SupplierAddress' => 'required',
        ], [
            'SupplierName.required' => 'يجب إدخال اسم المورد',
            'SupplierName.unique' => 'هذا المورد مسجل مسبقًا',
            'SupplierPhone.required' => 'يجب إدخال رقم الهاتف',
            'SupplierPhone.unique' => 'رقم الهاتف مسجل مسبقًا',
            'SupplierPhone.min' => 'يجب أن يكون رقم الهاتف على الأقل 10 أرقام',
            'SupplierAddress.required' => 'يجب إدخال عنوان المورد',
        ]);
        $Supplier = new Supplier;
        $Supplier->SupplierName = $request->input('SupplierName');
        $Supplier->SupplierPhone = $request->input('SupplierPhone');
        $Supplier->SupplierAddress = $request->input('SupplierAddress');
        $Account = new AccountController();
        $Parent = Account::where('AccountTypeID', 9)->orderBy('AccountID', 'ASC')->first();
        $lastChildNum = $Parent->lastChildNum + 1;
        $maxAccountNumber = $Parent->AccountNumber;
        $maxAccountNumber .= $lastChildNum;
        $Parent->lastChildNum = $lastChildNum;
        $Parent->save();
        $AccountID = $Account->CreateAccount($maxAccountNumber, $request->input('SupplierName'), 5, $Parent->CurrencyID, 0, auth()->user()->id, $Parent->AccountID, 0);
        $Supplier->AccountID = $AccountID;
        $Supplier->isCustomer = $request->has('isCustomer') ? 1 : 0;
        $Supplier->AddedBy = auth()->user()->id;
        $Supplier->save();
        return redirect("/suppliers")->with("success", "تمت  اضافة المورد بنجاح");
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
        $Supplier = Supplier::find($id);
        return view("suppliers.edit")->with('Supplier', $Supplier);
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
            'SupplierName' => [
                'required',
                Rule::unique('suppliers')->ignore($request->SupplierID, 'SupplierID'), // Assuming 'SupplierID' is the name of the input field containing the Supplier's ID
            ],
            'SupplierPhone' => [
                'required',
                'min:10',
                Rule::unique('suppliers')->ignore($request->SupplierID, 'SupplierID'),
            ],
            'SupplierAddress' => 'required',
        ], [
            'SupplierName.required' => 'يجب إدخال اسم المورد',
            'SupplierName.unique' => 'هذا المورد مسجل مسبقًا',
            'SupplierPhone.required' => 'يجب إدخال رقم الهاتف',
            'SupplierPhone.unique' => 'رقم الهاتف مسجل مسبقًا',
            'SupplierPhone.min' => 'يجب أن يكون رقم الهاتف على الأقل 10 أرقام',
        ]);
        $Supplier = Supplier::find($id);
        $Supplier->SupplierName = $request->input('SupplierName');
        $Supplier->SupplierPhone = $request->input('SupplierPhone');
        $Supplier->SupplierAddress = $request->input('SupplierAddress');
        $Supplier->AccountID = 0;
        $Supplier->isCustomer = $request->has('isCustomer') ? 1 : 0;
        $Supplier->AddedBy = auth()->user()->id;
        $Supplier->save();
        return redirect("/suppliers")->with("success", "تمت  تعديل المورد بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Supplier = Supplier::find($id);
        $Supplier->delete();
        return redirect("/suppliers")->with("success", "تمت  حذف  المورد بنجاح");
    }
}
