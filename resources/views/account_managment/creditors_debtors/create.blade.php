<title>اضافة مديونية</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>اضافة مديونية</h1>
        </div>
        <div class="col-md-12 Result" id="Results"></div>
        {!! Form::open(['action' => 'CreditorsDebtorController@store', 'method' => 'post']) !!}
        <div class = "row">
            <div class="form-group col-md-6">
                {!! Form::label('name', 'توع المعاملة', ['class' => 'input_label']) !!}
                {!! Form::select('OprationType', ['' => 'نوع المعاملة', '0' => 'دائن', '1' => 'مدين'], null, [
                    'class' => 'input_style RewriteLabels GetAccount',
                    'id' => 'OprationType',
                    'required' => 'required',
                ]) !!}
            </div>
            <?php
            $Currencies = json_decode($Currencies, true);
            $options = ['' => 'اختر العملة']; // Initialize with default option
            foreach ($Currencies as $Currency) {
                $options[$Currency['CurrencyID']] = $Currency['CurrencyName'];
            }
            ?>

            <div class="form-group col-md-6">
                {!! Form::label('name', 'العملة', ['class' => 'input_label']) !!}
                {!! Form::select('CurrencyID', $options, null, [
                    'class' => 'input_style GetAccount ',
                    'id' => 'CurrencyID',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('AccountID', 'الحساب', ['class' => 'input_label', 'id' => 'AccountLabel']) !!}
                <select id='AccountID' name="AccountID" class='input_style' required>
                </select>

            </div>
            <div class="form-group col-md-6">
                <label class="input_label">طريقة الدفع</label>
                <select id='PaymentType' class='input_style'>
                    <option value=''>اختر طريقة الدفع</option>
                    <option value='1'>نقدا</option>
                    <option value='2'>تحويل</option>
                    <option value='3'>شيك</option>
                </select>
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('PaymentAccountID', 'الحساب', ['class' => 'input_label', 'id' => 'PaymentAccountLabel']) !!}
                <select id='PaymentAccountID' name="PaymentAccountID" class='input_style' required>
                </select>
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('Amount', 'المبلغ', ['class' => 'input_label']) !!}
                {!! Form::text('Amount', null, ['class' => 'input_style', 'placeholder' => 'ادخل المبلغ ', 'required']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('OprationDetails', 'التفاصيل', ['class' => 'input_label']) !!}
                {!! Form::textarea('OprationDetails', null, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل التفاصيل ',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
            </div>
            <div class="form-group col-md-6">
                <a href="{{ url('AccountManagment/CreditorsDebtors') }}">
                    <button type="button" class="btn cancel_button">الغاء</button>
                </a>
            </div>

        </div>
        {!! Form::close() !!}
    </div>
    <script>
        $(document).on('change', '.RewriteLabels', function() {
            $("#AccountID").empty()
            if ($(this).val() == 0) {
                $("#AccountLabel").html("حساب الدائن")
                $("#PaymentAccountLabel").html("الحساب المحول له")
            } else {
                $("#AccountLabel").html("حساب المدين")
                $("#PaymentAccountLabel").html("الحساب المحول منه")
            }
        });
        $(document).on('change', '.GetAccount', function() {
            $("#Results").removeClass("alert-danger").html("")
            $("#AccountID").empty().append($("<option value = ''>اختر الحساب</option>"))
            $("#PaymentAccountID").empty().append($("<option value = ''>اختر الحساب</option>"))
            var CurrencyID = $("#CurrencyID").val()
            var AccountType = 6
            if ($("#OprationType").val() == 1)
                var AccountType = 7
            if (CurrencyID)
                $.ajax({
                    url: '{{ url('get_account') }}/' + CurrencyID + '/' + AccountType,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response != 1 && response != 2) {
                            $("#AccountID").empty().append(
                                "<option value=''>اختر الحساب</option>");
                            for (var i = 0; i < response.length; i++) {
                                $("#AccountID").append("<option value='" + response[i][
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
