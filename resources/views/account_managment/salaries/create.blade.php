<title>اضافة راتب</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>صرف الراتب </h1>
        </div>
        <div class="col-md-12 Result" id="Results"></div>
        {!! Form::open(['action' => 'SalaryController@store', 'method' => 'post']) !!}
        <div class = "row">
            <div class="form-group col-md-6">
                <?php
                $Months = json_decode($Months, true);
                $MonthsOptions = ['0' => 'اختر الشهر']; // Initialize with default option
                foreach ($Months as $Month) {
                    $MonthsOptions[$Month['MonthID']] = $Month['MonthName'];
                }
                ?>
                {!! Form::label('name', 'الشهر', ['class' => 'input_label']) !!}
                {!! Form::select('MonthID', $MonthsOptions, null, [
                    'class' => 'input_style GetNonPaidEmployees',
                    'id' => 'MonthID',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الموظف', ['class' => 'input_label']) !!}
                <select name="EmployeeID" id="EmployeeID" class="input_style GetEmployeeSalaryDetails" required>
                    <option value="">اختر الموظف</option>
                </select>
            </div>

            <div class="form-group col-md-6">
                {!! Form::label('name', 'الراتب/متبقي الراتب', ['class' => 'input_label']) !!}
                <input id="SalaryAmount" name="SalaryAmount" type="text" class="input_style" readonly>
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'مجمل السلفيات', ['class' => 'input_label']) !!}
                <input id="TotalLoans" name="TotalLoans" type="text" class="input_style" readonly>
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('name', 'خصومات السلفيات', ['class' => 'input_label']) !!}
                <input id="PaidLoans" name="PaidLoans" type="text" class="input_style CheckPayment">
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('name', ' المبلغ المصروف', ['class' => 'input_label']) !!}
                <input id="PaidAmount" name="PaidAmount" type="text" class="input_style CheckPayment">
            </div>
            <div class="form-group col-md-4">
                {!! Form::label('name', ' المبلغ المتبقي من الراتب', ['class' => 'input_label']) !!}
                <input id="NotPaidAmount" name="NotPaidAmount" type="text" class="input_style CheckPayment" readonly>
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
            <div class="form-group col-md-12">
                {!! Form::label('Comment', 'التفاصيل', ['class' => 'input_label']) !!}
                {!! Form::textarea('Comment', null, [
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
        $(document).on('change', '.GetEmployeeSalaryDetails', function() {
            var MonthID = $("#MonthID").val()
            var EmployeeID = $(this).val()
            $("#SalaryDetails").empty()
            $("#Results").removeClass("alert-danger").html("")
            $.ajax({
                url: '{{ url('Salary/GetEmployeeSalaryDetails') }}/' + MonthID + '/' + EmployeeID,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $("#SalaryAmount").val(response.success.TotalSalaryAmount - response.success
                            .PaidAmount)
                        $("#TotalLoans").val(response.success.TotalLoans)
                        $("#PaidLoans").val(response.success.TotalLoans)
                        $("#PaidAmount").val(response.success.TotalSalaryAmount - response.success
                            .TotalLoans - response.success
                            .PaidAmount)
                        $("#NotPaidAmount").val(0)
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
        $(document).on('change', '.GetNonPaidEmployees', function() {
            var MonthID = $(this).val()
            $("#SalaryAmount").val("")
            $("#TotalLoans").val("")
            $("#PaidLoans").val("")
            $("#PaidLoans").val("")
            $("#Results").removeClass("alert-danger").html("")
            $.ajax({
                url: '{{ url('Salary/GetNonPaidEmployees') }}/' + MonthID,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response != 1 && response != 2) {
                        $("#EmployeeID").empty().append(
                            "<option value=''>اختر الموظف</option>");
                        for (var i = 0; i < response.length; i++) {
                            $("#EmployeeID").append("<option value='" + response[i][
                                "EmployeeID"
                            ] + "'>" + response[i]["EmployeeName"] + "</option>");
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
            var CurrencyID = 1; // $("#CurrencyID").val()
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
        $(document).on('change', '.CheckPayment', function() {
            $("#Results").removeClass("alert-danger")
                .html("");
            var SalaryAmount = parseFloat($("#SalaryAmount").val())
            var TotalLoans = parseFloat($("#TotalLoans").val())
            var PaidLoans = parseFloat($("#PaidLoans").val())
            var PaidAmount = parseFloat($("#PaidAmount").val())
            if (PaidLoans > TotalLoans) {
                $("#Results").addClass("alert-danger")
                    .html("لا يمكن خصم مبلغ اكبر من مجمل السلفيات");
                $("#PaidLoans").val(TotalLoans)
                PaidLoans = TotalLoans
            }
            if (PaidAmount + PaidLoans > SalaryAmount) {
                $("#PaidAmount").val(SalaryAmount - PaidLoans)
                PaidAmount = SalaryAmount - PaidLoans
                $("#Results").addClass("alert-danger")
                    .append("<br> لا يمكن صرف مبلغ اكبر من مجمل الراتب");
            }
            $("#NotPaidAmount").val(SalaryAmount - (PaidAmount + PaidLoans))
        });
    </script>
@endsection
