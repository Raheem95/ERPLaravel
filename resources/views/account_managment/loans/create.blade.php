<title>اضافة سلفية</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>اضافة قرض لموظف</h1>
        </div>
        <div class="col-md-12 Result" id="Results"></div>
        {!! Form::open(['action' => 'LoanController@store', 'method' => 'post']) !!}
        <div class = "row">
            <?php
            $Employees = json_decode($Employees, true);
            $EmployeesOptions = ['0' => 'اختر الموظف']; // Initialize with default option
            foreach ($Employees as $Employee) {
                $EmployeesOptions[$Employee['EmployeeID']] = $Employee['EmployeeName'];
            }
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الموظف', ['class' => 'input_label']) !!}
                {!! Form::select('EmployeeID', $EmployeesOptions, null, [
                    'class' => 'input_style',
                    'id' => 'EmployeeID',
                ]) !!}
            </div>
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
                    'class' => 'input_style GetLoansAccounts',
                    'id' => 'CurrencyID',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('LoanAccountID', 'حساب القرض', ['class' => 'input_label']) !!}
                <select id='LoanAccountID' name="LoanAccountID" class='input_style' required>
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
                <select id='PaymentAccountID' name="PaymentAccountID" class='input_style' required>
                </select>

            </div>
            <div class="form-group col-md-6">
                {!! Form::label('LoanAmount', 'المبلغ', ['class' => 'input_label']) !!}
                {!! Form::text('LoanAmount', null, ['class' => 'input_style', 'placeholder' => 'ادخل المبلغ ', 'required']) !!}
            </div>
            <div class="form-group col-md-12">
                {!! Form::label('LoanDetails', 'التفاصيل', ['class' => 'input_label']) !!}
                {!! Form::textarea('LoanDetails', null, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل التفاصيل ',
                    'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
            </div>
            <div class="form-group col-md-6">
                <a href="{{ url('AccountManagment/Loans') }}">
                    <button type="button" class="btn cancel_button">الغاء</button>
                </a>
            </div>

        </div>
        {!! Form::close() !!}
    </div>
    <script>
        $(document).on('change', '.GetLoansAccounts', function() {
            $("#Results").removeClass("alert-danger").html("")
            $("#LoanAccountID").empty().append($("<option value = ''>اختر الحساب</option>"))
            $("#PaymentAccountID").empty().append($("<option value = ''>اختر الحساب</option>"))
            var CurrencyID = $("#CurrencyID").val()
            var AccountType = 7
            $.ajax({
                url: '{{ url('get_account') }}/' + CurrencyID + '/' + AccountType,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response != 1 && response != 2) {
                        $("#LoanAccountID").empty().append(
                            "<option value=''>اختر الحساب</option>");
                        for (var i = 0; i < response.length; i++) {
                            $("#LoanAccountID").append("<option value='" + response[i][
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
    </script>
@endsection
