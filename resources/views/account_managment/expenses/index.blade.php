<title>المنصرفات</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/Accounts/index.blade.php -->
    <h1>المنصرفات</h1>

    <div class="row">
        <div class="col-md-2">
            <a href="Expenses/create" class="btn add_button mb-3">اضافة منصرف</a>

        </div>
        <div class="col-md-6">

            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    <div class="row">
        @if (count($TotalExpenses) > 0)
            <div class="col-md-8">
            @else
                <div class="col-md-12">
        @endif
        @if (count($Expenses) > 0)
            <table class="table " id="ExpensesTable">
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
                                    'id' => 'deleteForm' . $Expense->ExpensesID,
                                ]) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                    'type' => 'button',
                                    'class' => 'btn delete_button',
                                    'onclick' => "confirmDelete('تاكيد حذف  المنصرف {$Expense->ExpensesDetails}','deleteForm$Expense->ExpensesID')",
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
    <script>
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword) Keyword = 0;
            $.ajax({
                url: '{{ url('expenses_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Assuming you handle the response here to update the table content
                    // Example: Clear existing table rows
                    $('#ExpensesTable tbody').empty();

                    // Iterate over each expense in the response and append rows to table
                    response.forEach(function(expense) {
                        const row = `
                            <tr>
                                <td>${expense.created_at}</td>
                                <td>${expense.expenses_account.AccountName}</td>
                                <td>${number_format(expense.ExpensesAmount)}</td>
                                <td>${expense.ExpensesDetails}</td>
                                <td>${expense.payment_account.AccountName}</td>
                                <td>${expense.user.name}</td>
                                <td>
                                    <a href="expenses/${expense.ExpensesID}/edit" class="btn edit_button">
                                        <i class='fa-solid fa-file-pen fa-2x'></i>
                                    </a>
                                </td>
                                <td>
                                    <form action="expenses/${expense.ExpensesID}" method="post" id="deleteForm${expense.ExpensesID}" style="display: inline;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="button" class="btn delete_button" onclick="confirmDelete('تاكيد حذف المنصرف ${expense.ExpensesDetails}', 'deleteForm${expense.ExpensesID}')" id="DeleteButton${expense.ExpensesID}">
                                            <i class="fas fa-trash-alt fa-2x"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        `;
                        $('#ExpensesTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }


        function number_format(number) {
            var formatter = new Intl.NumberFormat();
            return formatter.format(number)

        }
    </script>
@endsection
