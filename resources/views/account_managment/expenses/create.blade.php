<title>اضافة منصرف</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>اضافة منصرف</h1>
        </div>
        <div class="col-md-12 Result" id="Results"></div>
        {!! Form::open([
            'action' => 'ExpenseController@store',
            'method' => 'post',
            'onsubmit' => 'return validateForm()',
        ]) !!}
        <div class = "row">

            <?php
            $Currencies = json_decode($Currencies, true);
            $options = ['0' => 'اختر العملة']; // Initialize with default option
            foreach ($Currencies as $Currency) {
                $options[$Currency['CurrencyID']] = $Currency['CurrencyName'];
            }
            ?>

            <div class="form-group col-md-6">
                {!! Form::label('name', 'العملة', ['class' => 'input_label']) !!}
                {!! Form::select('CurrencyID', $options, null, [
                    'class' => 'input_style GetExpensesAccounts',
                    'id' => 'CurrencyID',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('ExpensesAccountID', 'نوع المنصرف', ['class' => 'input_label']) !!}
                <select id='ExpensesAccountID' name="ExpensesAccountID" class='input_style'>
                </select>

            </div>
            <div class="form-group col-md-6">
                <label class="input_label">طريقة الدفع</label>
                <select id='PaymentType' class='input_style'>
                    <option value='0'>اختر طريقة الدفع</option>
                    <option value='1'>نقدا</option>
                    <option value='2'>تحويل</option>
                    <option value='3'>شيك</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('PaymentAccountID', 'الحساب المسدد منه', ['class' => 'input_label']) !!}
                <select id='PaymentAccountID' name="PaymentAccountID" class='input_style'>
                </select>

            </div>
            <div class="form-group col-md-6">
                {!! Form::label('ExpensesAmount', 'المبلغ', ['class' => 'input_label']) !!}
                {!! Form::text('ExpensesAmount', null, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل المبلغ ',
                    'id' => 'ExpensesAmount',
                ]) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('ExpensesDetails', 'التفاصيل', ['class' => 'input_label']) !!}
                {!! Form::textarea('ExpensesDetails', null, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل التفاصيل ',
                    'id' => 'ExpensesDetails',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
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
                            "<option value='0'>اختر الحساب</option>");
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

        function validateForm() {
            var RestrictionsRowsNumber = $("#RestrictionsRowsNumber").val()
            var amount = 0
            $(".error-label").remove();
            $(".error_input").removeClass("error_input");
            var flag = true

            if ($('#CurrencyID').val() == 0) {
                $("#CurrencyID").addClass("error_input");
                CreateErrorLabel("CurrencyID", "الرجاء تحديد العملة  ")
                flag = false
            }
            if ($('#ExpensesAccountID').val() == 0) {
                $("#ExpensesAccountID").addClass("error_input");
                CreateErrorLabel("ExpensesAccountID", "الرجاء تحديد الحساب  ")
                flag = false
            }
            if ($('#PaymentType').val() == 0) {
                $("#PaymentType").addClass("error_input");
                CreateErrorLabel("PaymentType", "الرجاء تحديد طريقة الدفع  ")
                flag = false
            }
            if ($('#PaymentAccountID').val() == 0) {
                $("#PaymentAccountID").addClass("error_input");
                CreateErrorLabel("PaymentAccountID", "الرجاء تحديد الحساب  ")
                flag = false
            }
            if (isNaN($('#ExpensesAmount').val()) || $('#ExpensesAmount').val() < 0) {
                $("#ExpensesAmount").addClass("error_input");
                CreateErrorLabel("ExpensesAmount", "الرجاء ادخال المبلغ بصورة صحيحة  ")
                flag = false
            }
            if ($('#ExpensesDetails').val() == 0) {
                $("#ExpensesDetails").addClass("error_input");
                CreateErrorLabel("ExpensesDetails", "الرجاء كتابة التفاصيل   ")
                flag = false
            }
            return flag
        }
    </script>
@endsection
