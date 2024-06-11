<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Employee;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employees = Employee::orderBy('EmployeeSalary', 'desc')->get();
        return view('employees.index')->with('employees', $employees);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('employees.create');
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
            'EmployeeImage' => 'nullable|image',
            'EmployeeName' => 'required|string|max:255',
            'EmployeePhone' => 'required|string|max:15',
            'EmployeeAddress' => 'required|string|max:255',
            'EmployeeSalary' => 'required|numeric|min:0',
            'HireDate' => 'required|date',
            'Suspended' => 'boolean',
        ], [
            'EmployeeName.required' => 'يجب ادخال اسم الموظف',
            'EmployeePhone.required' => 'يجب ادخال رقم هاتف الموظف',
            'EmployeeAddress.required' => 'يجب ادخال عنوان الموظف',
            'EmployeeSalary.required' => 'يجب ادخال راتب الموظف',
            'EmployeeSalary.numeric' => 'يجب ادخال قيمة رقمية لراتب الموظف',
            'EmployeeSalary.min' => 'يجب أن يكون راتب الموظف على الأقل 0',
            'HireDate.required' => 'يجب ادخال تاريخ التوظيف',
            'Suspended.boolean' => 'يجب ادخال حالة الموظف بشكل صحيح',
        ]);

        $employeeImagePath = "EmployeeImages/NoImage.png";
        if ($request->hasFile('EmployeeImage')) {
            $file = $request->file('EmployeeImage');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/EmployeesImages/', $fileName);
            $employeeImagePath = 'storage/EmployeesImages/' . $fileName;
        }
        $employee = new Employee;
        $employee->EmployeeImage = $employeeImagePath;
        $employee->EmployeeName = $request->input('EmployeeName');
        $employee->EmployeePhone = $request->input('EmployeePhone');
        $employee->EmployeeAddress = $request->input('EmployeeAddress');
        $employee->EmployeeSalary = $request->input('EmployeeSalary');
        $employee->HireDate = $request->input('HireDate');
        $employee->Suspended = $request->input('Suspended', false);
        $employee->save();

        return redirect('/Employees')->with('success', 'تمت اضافة الموظف بنجاح');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $employee = Employee::find($id);
        return view('employees.edit')->with('employee', $employee);
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
            'EmployeeName' => 'required|string|max:255',
            'EmployeePhone' => 'required|string|max:15',
            'EmployeeAddress' => 'required|string|max:255',
            'EmployeeSalary' => 'required|numeric|min:0',
            'HireDate' => 'required|date',
            'Suspended' => 'boolean',
        ], [
            'EmployeeName.required' => 'يجب ادخال اسم الموظف',
            'EmployeePhone.required' => 'يجب ادخال رقم هاتف الموظف',
            'EmployeeAddress.required' => 'يجب ادخال عنوان الموظف',
            'EmployeeSalary.required' => 'يجب ادخال راتب الموظف',
            'EmployeeSalary.numeric' => 'يجب ادخال قيمة رقمية لراتب الموظف',
            'EmployeeSalary.min' => 'يجب أن يكون راتب الموظف على الأقل 0',
            'HireDate.required' => 'يجب ادخال تاريخ التوظيف',
            'Suspended.boolean' => 'يجب ادخال حالة الموظف بشكل صحيح',
        ]);

        $employee = Employee::find($id);

        if ($request->hasFile('EmployeeImage')) {
            $file = $request->file('EmployeeImage');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/images/EmployeesImages/', $fileName);
            $employeeImagePath = 'storage/images/EmployeesImages/' . $fileName;
            $employee->EmployeeImage = $employeeImagePath;
        }

        $employee->EmployeeName = $request->input('EmployeeName');
        $employee->EmployeePhone = $request->input('EmployeePhone');
        $employee->EmployeeAddress = $request->input('EmployeeAddress');
        $employee->EmployeeSalary = $request->input('EmployeeSalary');
        $employee->HireDate = $request->input('HireDate');
        $employee->Suspended = $request->input('Suspended', false);
        $employee->save();

        return redirect('/Employees')->with('success', 'تم تعديل الموظف بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);
        $employee->delete();
        return redirect('/Employees')->with('success', 'تم حذف الموظف بنجاح');
    }

    /**
     * Search for employees based on the provided keyword.
     *
     * @param  string  $keyword
     * @return \Illuminate\Http\Response
     */
    public function Search(Request $request)
    {
        if ($request->Keyword == "allEmployees") {
            $employees = Employee::orderBy('EmployeeSalary', 'desc')->get();
        } else {
            $employees = Employee::where("EmployeeName", "like", "%$request->Keyword%")
                ->orderBy('EmployeeSalary', 'desc')
                ->get();
        }

        return response()->json($employees);
    }
}
