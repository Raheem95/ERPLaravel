<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Customer;
use App\Account;
use Illuminate\Validation\Rule;


class CustomerController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Customers = Customer::orderBy('CustomerID', 'desc')->get();
        return view("customers.index")->with('Customers', $Customers);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("customers.create");
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
            'CustomerName' => 'required|unique:customers,CustomerName|unique:accounts,AccountName',

            'CustomerPhone' => 'required|unique:customers,CustomerPhone|min:10',
            'CustomerAddress' => 'required',
        ], [
            'CustomerName.required' => 'يجب إدخال اسم العميل',
            'CustomerName.unique' => 'هذا  الاسم مرتبط بحساب اخر',
            'CustomerPhone.required' => 'يجب إدخال رقم الهاتف',
            'CustomerPhone.unique' => 'رقم الهاتف مسجل مسبقًا',
            'CustomerPhone.min' => 'يجب أن يكون رقم الهاتف على الأقل 10 أرقام',
            'CustomerAddress.required' => 'يجب إدخال عنوان العميل',
        ]);
        $Customer = new Customer;
        $Customer->CustomerName = $request->input('CustomerName');
        $Customer->CustomerPhone = $request->input('CustomerPhone');
        $Customer->CustomerAddress = $request->input('CustomerAddress');
        $Account = new AccountController();
        $Parent = Account::where('AccountTypeID', 5)->orderBy('AccountID', 'ASC')->first();
        $lastChildNum = $Parent->lastChildNum + 1;
        $maxAccountNumber = $Parent->AccountNumber;
        $maxAccountNumber .= $lastChildNum;
        $Parent->lastChildNum = $lastChildNum;
        $Parent->save();
        $AccountID = $Account->CreateAccount($maxAccountNumber, $request->input('CustomerName'), 5, $Parent->CurrencyID, 0, auth()->user()->id, $Parent->AccountID, 0);
        $Customer->AccountID = $AccountID;
        $Customer->isSupplier = $request->has('isSupplier') ? 1 : 0;
        $Customer->AddedBy = auth()->user()->id;
        $Customer->save();
        return redirect("/customers")->with("success", "تمت  اضافة العميل بنجاح");
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
        $Customer = Customer::find($id);
        return view("customers.edit")->with('Customer', $Customer);
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
            'CustomerName' => [
                'required',
                Rule::unique('customers')->ignore($request->CustomerID, 'CustomerID'), // Assuming 'CustomerID' is the name of the input field containing the customer's ID
            ],
            'CustomerPhone' => [
                'required',
                'min:10',
                Rule::unique('customers')->ignore($request->CustomerID, 'CustomerID'),
            ],
            'CustomerAddress' => 'required',
        ], [
            'CustomerName.required' => 'يجب إدخال اسم العميل',
            'CustomerName.unique' => 'هذا العميل مسجل مسبقًا',
            'CustomerPhone.required' => 'يجب إدخال رقم الهاتف',
            'CustomerPhone.unique' => 'رقم الهاتف مسجل مسبقًا',
            'CustomerPhone.min' => 'يجب أن يكون رقم الهاتف على الأقل 10 أرقام',
        ]);

        $Customer = Customer::find($id);
        $Customer->CustomerName = $request->input('CustomerName');
        $Customer->CustomerPhone = $request->input('CustomerPhone');
        $Customer->CustomerAddress = $request->input('CustomerAddress');
        $Customer->isSupplier = $request->has('isSupplier') ? 1 : 0;
        $Customer->AddedBy = auth()->user()->id;
        $Customer->save();
        return redirect("/customers")->with("success", "تمت  تعديل العميل بنجاح");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $Customer = Customer::find($id);
        $Customer->delete();
        return redirect("/customers")->with("success", "تمت  حذف العميل بنجاح");
    }
}
