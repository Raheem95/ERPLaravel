<title>تعديل منصرف</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class="MainLabel">
            <h1>اضافة منصرف</h1>
        </div>
        <div class="col-md-12 Result" id="Results"></div>
        {!! Form::open(['action' => ['ExpenseController@update', $Expense->ExpensesID], 'method' => 'post']) !!}
        <div class="row">
            @php
                $Currencies = json_decode($Currencies, true);
                $currencyOptions = ['' => 'اختر العملة'];
                foreach ($Currencies as $Currency) {
                    $currencyOptions[$Currency['CurrencyID']] = $Currency['CurrencyName'];
                }
            @endphp
            <div class="form-group col-md-6">
                {!! Form::label('CurrencyID', 'العملة', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('CurrencyID', $currencyOptions, $Expense->ExpensesAccount->CurrencyID, [
                    'class' => 'input_style GetExpensesAccounts',
                    'id' => 'CurrencyID',
                ]) !!}
            </div>

            @php
                $ExpensesAccounts = json_decode($ExpensesAccounts, true);
                $expensesAccountOptions = ['' => 'اختر الحساب'];
                foreach ($ExpensesAccounts as $ExpensesAccount) {
                    $expensesAccountOptions[$ExpensesAccount['AccountID']] = $ExpensesAccount['AccountName'];
                }
            @endphp
            <div class="form-group col-md-6">
                {!! Form::label('ExpensesAccountID', 'نوع المنصرف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('ExpensesAccountID', $expensesAccountOptions, $Expense->ExpensesAccount->AccountID, [
                    'class' => 'input_style',
                    'id' => 'ExpensesAccountID',
                ]) !!}
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('PaymentType', 'طريقة الدفع', ['class' => 'ProceduresLabel']) !!}
                <select id='PaymentType' class='input_style'>
                    <option value='0'>اختر طريقة الدفع</option>
                    <option value='1'>نقدا</option>
                    <option value='2'>تحويل</option>
                    <option value='3'>شيك</option>
                </select>
            </div>

            @php
                $PaymentAccounts = json_decode($PaymentAccounts, true);
                $paymentAccountOptions = ['0' => 'اختر الحساب'];
                foreach ($PaymentAccounts as $PaymentAccount) {
                    $paymentAccountOptions[$PaymentAccount['AccountID']] = $PaymentAccount['AccountName'];
                }
            @endphp
            <div class="form-group col-md-6">
                {!! Form::label('PaymentAccountID', 'الحساب المسدد منه', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('PaymentAccountID', $paymentAccountOptions, $Expense->PaymentAccount->AccountID, [
                    'class' => 'input_style',
                    'id' => 'PaymentAccountID',
                ]) !!}
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('ExpensesAmount', 'المبلغ', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('ExpensesAmount', $Expense->ExpensesAmount, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل المبلغ',
                    'required',
                ]) !!}
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('ExpensesDetails', 'التفاصيل', ['class' => 'ProceduresLabel']) !!}
                {!! Form::textarea('ExpensesDetails', $Expense->ExpensesDetails, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل التفاصيل',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {{ Form::hidden('_method', 'PUT') }}
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
            </div>
            <div class="form-group col-md-6">
                <a href="{{ url('AccountManagment/Expenses') }}">
                    <button type="button" class="btn cancel_button">الغاء</button>
                </a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        $(document).on('change', '.GetExpensesAccounts', function() {
            $("#Results").removeClass("alert-danger").html("")
            $("#ExpensesAccountID").empty().append($("<option value = ''>اختر الحساب</option>"))
            $("#PaymentAccountID").empty().append($("<option value = ''>اختر الحساب</option>"))
            var CurrencyID = $("#CurrencyID").val()
            var AccountType = 2
            $.ajax({
                url: '{{ url('get_account') }}/' + CurrencyID + '/' + AccountType,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response != 1 && response != 2) {
                        $("#ExpensesAccountID").empty().append(
                            "<option value=''>اختر الحساب</option>");
                        for (var i = 0; i < response.length; i++) {
                            $("#ExpensesAccountID").append("<option value='" + response[i][
                                "AccountID"
                            ] + "'>" + response[i]["AccountName"] + "</option>");
                        }
                    } else {
                        $("#Results").addClass("alert-danger")
                            .html("خطاء في النظام");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#Results").addClass("alert-danger").html(
                        "حدث خطأ أثناء الاتصال بالخادم");
                }
            });
        });
        $(document).on('change', '#PaymentType', function() {
            $("#Results").removeClass("alert-danger").html("")
            var CurrencyID = $("#CurrencyID").val()
            var AccountType = 4
            if ($("#PaymentType").val() == 2 || $("#PaymentType").val() == 3)
                AccountType = 3
            $.ajax({
                url: '{{ url('get_account') }}/' + CurrencyID + '/' + AccountType,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response != 1 && response != 2) {
                        $("#PaymentAccountID").empty().append(
                            "<option value=''>اختر الحساب</option>");
                        for (var i = 0; i < response.length; i++) {
                            $("#PaymentAccountID").append("<option value='" + response[i][
                                "AccountID"
                            ] + "'>" + response[i]["AccountName"] + "</option>");
                        }
                    } else {
                        $("#Results").addClass("alert-danger")
                            .html("خطاء في النظام");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#Results").addClass("alert-danger").html(
                        "حدث خطأ أثناء الاتصال بالخادم");
                }
            });
        });
    </script>
@endsection
