<title>المنصرفات</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/Accounts/index.blade.php -->
    <h1>المنصرفات</h1>

    <a style="width: 20%;" href="Expenses/create" class="btn add_button mb-3">اضافة منصرف</a>
    <div class="row">
        @if (count($TotalExpenses) > 0)
            <div class="col-md-8">
            @else
                <div class="col-md-12">
        @endif
        @if (count($Expenses) > 0)
            <table class="table ">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>حساب المنصرف</th>
                        <th>المبلغ</th>
                        <th>التفاصيل</th>
                        <th>البنك\الخزينة</th>
                        <th>المصدر</th>
                        <th>تعديل</th>
                        <th>حذف</th>
                    </tr>
                </thead>
                @foreach ($Expenses as $Expense)
                    <tbody>

                        <tr>
                            <td>{{ $Expense->created_at->format('y-m-d') }}</td>
                            <td>{{ $Expense->ExpensesAccount->AccountName }}</td>
                            <td>{{ number_format($Expense->ExpensesAmount, 2) }}</td>
                            <td>{{ $Expense->ExpensesDetails }}</td>
                            <td>{{ $Expense->PaymentAccount->AccountName }}</td>
                            <td>{{ $Expense->User->name }}</td>
                            <td>
                                <a href="Expenses/{{ $Expense->ExpensesID }}/edit" class="btn edit_button">
                                    <i class='fa-solid fa-file-pen fa-2x'></i></a>
                            </td>
                            <td>
                                {!! Form::open([
                                    'action' => ['ExpenseController@destroy', $Expense->ExpensesID],
                                    'method' => 'post',
                                ]) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                    'type' => 'submit',
                                    'class' => 'btn delete_button',
                                    'onclick' => "return confirm('تاكيد حذف العملة  $Expense->ExpenseName ')",
                                ]) !!}

                                {!! Form::close() !!}

                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-danger Result"> لا توجد منصرفات </div>
        @endif
    </div>
    @if (count($TotalExpenses))
        <div class="col-md-4">
            <table class="table">
                <thead>
                    <th>المنصرف</th>
                    <th>المجمل</th>
                </thead>
                <?php $CurrencyID = 0;
                $CurrencyName = '';
                $TotalAmount = 0; ?>
                @foreach ($TotalExpenses as $Total)
                    @if ($CurrencyID != $Total->ExpensesAccount->CurrencyID)
                        @if ($CurrencyName != '')
                            <tr>
                                <th>مجمل المنصرفات بال{{ $CurrencyName }}</th>
                                <th>{{ number_format($TotalAmount, 2) }}</th>
                            </tr>
                        @endif
                        <tr>
                            <td colspan="2">{{ $Total->ExpensesAccount->Currency->CurrencyName }}</td>
                        </tr>
                        <?php $TotalAmount = 0;
                        $CurrencyName = $Total->ExpensesAccount->Currency->CurrencyName;
                        $CurrencyID = $Total->ExpensesAccount->CurrencyID; ?>
                    @endif
                    <tr>
                        <td>{{ $Total->ExpensesAccount->AccountName }}</td>
                        <td>{{ number_format($Total->total, 2) }}</td>
                    </tr>
                    <?php $TotalAmount += $Total->total; ?>
                @endforeach
                <tr>
                    <th>مجمل المنصرفات بال{{ $CurrencyName }}</th>
                    <th>{{ number_format($TotalAmount, 2) }}</th>
                </tr>
            </table>
        </div>
    @endif
    </div>
@endsection
