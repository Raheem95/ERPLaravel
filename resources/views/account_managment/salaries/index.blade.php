<title>الرواتب</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/Accounts/index.blade.php -->
    <h1>الرواتب</h1>
    <div class="Result" id="Results"></div>
    <input type="hidden" id="LoanID">
    <input type="hidden" id="CurrencyID">
    <a style="width: 20%;" href="Salaries/create" class="btn add_button mb-3">صرف راتب </a>
    <div class="row">
        <div class="col-md-12">
            @if (count($Salaries) > 0)
                <table class="table ">
                    <thead>
                        <tr>
                            <th>الشهر</th>
                            <th>القيمة</th>
                            <th>عددالموظفين بصرف كامل</th>
                            <th>عددالموظفين بصرف ناقص</th>
                            <th>عددالموظفين من غير صرف </th>
                            <th>عرض</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    @foreach ($Salaries as $Salary)
                        <tbody>

                            <tr>
                                <td>{{ $Salary->Month->MonthName }}</td>
                                <td>{{ $Salary->TotalPaidAmount }}</td>
                                <td>{{ $Salary->FullyPaidEmployeeCount }}</td>
                                <td>{{ $Salary->NotFullyPaidEmployeeCount }}</td>
                                <td>{{ $Salary->NotPaidEmployeeCount }}</td>
                                <td>
                                    <a target="_blank" href="Salaries/{{ $Salary->MonthID }}/"> <button
                                            class="btn view_button"><i class="fa-solid fa-dollar-sign fa-2x"></i>
                                        </button>
                                    </a>
                                </td>
                                <td>
                                    {!! Form::open([
                                        'action' => ['SalaryController@destroy', $Salary->Month->MonthID],
                                        'method' => 'post',
                                    ]) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                        'type' => 'submit',
                                        'class' => 'btn delete_button',
                                        'onclick' => "return confirm('تاكيد حذف رواتب شهر   {$Salary->Month->MonthName}')",
                                    ]) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-danger Result"> لا توجد رواتب </div>
            @endif
        </div>
    </div>
@endsection
