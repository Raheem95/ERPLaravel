<title> السلفيات</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/Accounts/index.blade.php -->
    <h1>السلفيات</h1>
    <div class="Result" id="Results"></div>
    <input type="hidden" id="LoanID">
    <input type="hidden" id="CurrencyID">
    <div class="modal fade" id="PaymentDetails" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="col-md-12 Result" id="PaymentResults"></div>
                <div class="modal-body" id="PaymentDetailsBody">

                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="PayLoan" role="dialog">
        <div class="modal-dialog modal-xl">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-6' style="text-align:right;">
                            <label class="input_label"> القيمة</label>
                            <input type='number' id='PaidAmount' class="input_style" placeholder="القيمة">
                        </div>

                        <div class='col-md-6'>
                            <label class="input_label">طريقة الدفع</label>
                            <select id='PaymentType' class='input_style'>
                                <option value='0'>اختر طريقة الدفع</option>
                                <option value='1'>نقدا</option>
                                <option value='2'>تحويل</option>
                                <option value='3'>شيك</option>
                            </select>
                        </div>
                        <div class="col-md-6"><br>
                            <input type="hidden" id="OP">
                            <label class="input_label">اختر الحساب</label>
                            <select id="PaymentAccountID" class="input_style"></select>
                        </div>
                        <div class="col-md-12" style="text-align:right;"><br>
                            <label class="input_label">تفاصيل السفلية</label>
                            <textarea cols="5" class="input_style" id="LoanDetails"></textarea>
                        </div>
                    </div>
                    <br>
                    <div class='row'>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <button type="button" class='btn save_button SavePayment' data-dismiss="modal">حفظ</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn cancel_button" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a style="width: 20%;" href="Loans/create" class="btn add_button mb-3">اضافة سلفية</a>
    <div class="row">
        @if (count($TotalLoans) > 0)
            <div class="col-md-8">
            @else
                <div class="col-md-12">
        @endif
        @if (count($Loans) > 0)
            <table class="table ">
                <thead>
                    <tr>
                        <th>التاريخ</th>
                        <th>الموظف</th>
                        <th>المبلغ</th>
                        <th>المسدد</th>
                        <th>التفاصيل</th>
                        <th>المصدر</th>
                        <th>تعديل</th>
                        <th>سداد</th>
                        <th>حذف</th>
                    </tr>
                </thead>
                @foreach ($Loans as $Loan)
                    <tbody>

                        <tr>
                            <td>{{ $Loan->created_at->format('y-m-d') }}</td>
                            <td id="EmployeeName{{ $Loan->LoanID }}">{{ $Loan->Employee->EmployeeName }}</td>
                            <td>{{ number_format($Loan->LoanAmount, 2) }}</td>
                            <td id="PaidAmountText{{ $Loan->LoanID }}" data-toggle='modal' data-target='#PaymentDetails'
                                class="GetPaymentDetails">
                                {{ number_format($Loan->PaidAmount, 2) }}</td>
                            <td>{{ $Loan->LoanDetails }} / تم صرف السلفية من حساب
                                {{ $Loan->PaymentAccount->AccountName }}</td>
                            <td>{{ $Loan->User->name }}</td>
                            <td>
                                <a href="Loans/{{ $Loan->LoanID }}/edit" class="btn edit_button">
                                    <i class='fa-solid fa-file-pen fa-2x'></i></a>
                            </td>
                            <td>
                                <button class="btn view_button setID" data-toggle='modal' data-target='#PayLoan'
                                    value="{{ $Loan->LoanID }}">
                                    <i class="fa-solid fa-dollar-sign fa-2x"></i>
                                </button>
                                <input type="hidden" id="CurrencyID{{ $Loan->LoanID }}"
                                    value="{{ $Loan->PaymentAccount->CurrencyID }}">
                                <input type="hidden" id="LoanAmount{{ $Loan->LoanID }}" value="{{ $Loan->LoanAmount }}">
                                <input type="hidden" id="PaidAmount{{ $Loan->LoanID }}" value="{{ $Loan->PaidAmount }}">
                            </td>
                            <td>
                                {!! Form::open([
                                    'action' => ['LoanController@destroy', $Loan->LoanID],
                                    'method' => 'post',
                                ]) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                    'type' => 'submit',
                                    'class' => 'btn delete_button',
                                    'onclick' => "return confirm('تاكيد حذف السلفية الخاصة  {$Loan->Employee->EmployeeName} بقيمة {$Loan->LoanAmount}')",
                                ]) !!}
                                {!! Form::close() !!}
                            </td>
                        </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="alert alert-danger Result"> لا توجد سلفيات </div>
        @endif
    </div>
    @if (count($TotalLoans))
        <div class="col-md-4">
            <table class="table">
                <thead>
                    <th>الموظف</th>
                    <th>مجمل السلفيات</th>
                </thead>
                @foreach ($TotalLoans as $Total)
                    <tr>
                        <td>{{ $Total->Employee->EmployeeName }}</td>
                        <td>{{ number_format($Total->total, 2) }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    @endif
    </div>
    <script>
        $(document).on('click', '.setID', function() {
            $("#LoanID").val($(this).val())
            $("#CurrencyID").val($("#CurrencyID" + $(this).val()).val())
        });
        $(document).on('change', '#PaymentType', function() {
            $("#PaymentResults").removeClass("alert-danger").html("")
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
                        $("#PaymentResults").addClass("alert-danger")
                            .html("خطاء في النظام");
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#PaymentResults").addClass("alert-danger").html(
                        "حدث خطأ أثناء الاتصال بالخادم");
                }
            });
        });
        $(document).on('click', '.SavePayment', function() {
            var LoanID = $("#LoanID").val()
            var PaidAmount = $("#PaidAmount").val()
            var PaymentComment = $("#LoanDetails").val()
            var PaymentAccountID = $("#PaymentAccountID").val()
            if (parseFloat(PaidAmount) > parseFloat($("#LoanAmount" + LoanID).val()) - parseFloat($("#PaidAmount" +
                    LoanID).val()))
                $("#Results").removeClass("alert-success").addClass(
                    "alert-danger").html(
                    "عذرا لا يمكنك سداد مبلغ اكبر من البلغ المتبقي")
            else {
                var comment = "سداد " + $("#EmployeeName" + LoanID).html() + " قيمة " + PaidAmount +
                    "  سداد للسلفية رقم " + LoanID + " / " + PaymentComment
                if (PaymentAccountID != 0) {
                    if (confirm("تأكيد دفع " + $("#EmployeeName" + LoanID).html() + " لقيمة " + PaidAmount +
                            " جنيه ")) {
                        var form_data = new FormData();
                        form_data.append('LoanID', LoanID);
                        form_data.append('PaidAmount', PaidAmount);
                        form_data.append('PaymentComment', PaymentComment);
                        form_data.append('PaymentAccountID', PaymentAccountID);
                        form_data.append('comment', comment);
                        $.ajax({
                            url: "/AccountManagment/Loan/Payment",
                            dataType: 'json',
                            cache: false,
                            contentType: false,
                            processData: false,
                            data: form_data,
                            type: 'post',
                            beforeSend: function(xhr) {
                                xhr.setRequestHeader('X-CSRF-TOKEN', $(
                                    'meta[name="csrf-token"]').attr(
                                    'content'));
                            },
                            success: function(result) {
                                if (result == 1) {
                                    $("#Results").removeClass("alert-danger").addClass(
                                        "alert-success").html("تم السداد بنجاح")
                                    $("#PaidAmountText" + LoanID).html(parseFloat($("#PaidAmount" +
                                        LoanID).val()) + parseFloat(PaidAmount))
                                    $("#PaidAmount" + LoanID).val(parseFloat($("#PaidAmount" +
                                        LoanID).val()) + parseFloat(PaidAmount))
                                } else {
                                    $("#Results").removeClass("alert-success").addClass(
                                        "alert-danger").html("فشل السداد")
                                }
                            }
                        });
                    }
                } else {
                    $("#Results").css("color", "red")
                    $("#Results").html("الرجاء تحديد طريقة الدفع")
                }
            }
        });
        $(document).on('click', '.GetPaymentDetails', function() {
            $("#PaymentResults").removeClass("alert-success").removeClass(
                "alert-danger").empty()
            var LoanID = $(this).attr("id").replace("PaidAmountText", "");
            $("#PaymentDetailsBody").empty()
            if ($("#PaidAmount" + LoanID).val() > 0)
                $.ajax({
                    url: "/AccountManagment/Loans/GetPayments/" + LoanID,
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response != -1) {
                            if (response.length > 0) {
                                $("#PaymentDetailsBody").append(
                                    `<table id="paymentsTable" class="table"><thead><tr><th>التاريخ </th><th>المبلغ</th><th>التعليق</th><th>حذف</th></tr></thead><tbody></tbody></table>`
                                );
                                response.forEach(payment => {
                                    const date = new Date(payment.created_at);
                                    const formattedDate = date.getFullYear() + '-' +
                                        String(date.getMonth() + 1).padStart(2, '0') + '-' +
                                        String(date.getDate()).padStart(2, '0');
                                    var disabled =
                                        "<td><button class = 'btn delete_button DeletePayment' value = '${payment.PaymentID}'><i class='fa-solid fa-trash-can fa-2x'></i></button></td>"
                                    if (payment.Deletable == 0)
                                        var disabled =
                                            "<td>-</td>"
                                    $("#paymentsTable  tbody").append(
                                        `<tr id = "Payment${payment.PaymentID}">
                                        <td>${formattedDate}</td>
                                        <td>${payment.Amount}</td>
                                        <td>${payment.Comment}</td>
                                        ${disabled}
                                    </tr>`
                                    );
                                });
                            } else
                                $("#PaymentResults").removeClass("alert-success").addClass(
                                    "alert-danger").html("خطاء في تحميل الدفعيات")
                        } else {
                            $("#Results").removeClass("alert-success").addClass(
                                "alert-danger").html("حطاء بالنظام");
                        }
                    },
                    error: function(xhr, status, error) {
                        $("#Results").removeClass("alert-success").addClass(
                            "alert-danger").html(error);
                    }
                });
            else
                $("#PaymentResults").removeClass("alert-success").addClass(
                    "alert-danger").html("لا توجد دفعيات")
        });
        $(document).on('click', '.DeletePayment', function() {
            var PaymentID = $(this).val()
            if (confirm("تأكيد حذف السداد ")) {
                var form_data = new FormData();
                form_data.append('PaymentID', PaymentID);
                $.ajax({
                    url: "/AccountManagment/Loans/DeletePayment",
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    success: function(result) {
                        if (isNaN(result)) {
                            $("#PaymentResults").removeClass("alert-danger").addClass("alert-success")
                                .html("تم  حذف السداد  بنجاح");
                            $("#Payment" + PaymentID).remove()
                            $("#PaidAmountText" + result.LoanID).html(parseFloat($("#PaidAmount" +
                                result.LoanID).val()) - parseFloat(result.Amount))
                            $("#PaidAmount" + result.LoanID).val(parseFloat($("#PaidAmount" +
                                result.LoanID).val()) - parseFloat(result.Amount))
                        } else
                            $("#PaymentResults").removeClass("alert-success").addClass(
                                "alert-danger").html("خطاء في حذف السداد");
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                    }
                });
            }
        });
    </script>
@endsection
