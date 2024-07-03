@extends('layouts.app')

@section('content')
    <!-- resources/views/sales/index.blade.php -->
    <input type = 'hidden' id = "SaleID">
    <div class="modal fade" id="PaymentDetailsModel" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-md-12 Result alert" id = "DeletePaymentResults"></div>
                    <table class = "table" id = "PaymentDetailsTable">
                    </table>
                    <div class="row">
                        <div class="col-md-3">
                            <button type="button" class="btn cancel_button" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="PaymentModel" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class='row'>
                        <div class="col-md-12 Result alert" id = "PaymentResults"></div>
                        <div class='col-md-6'>
                            <label class="input_label">المبلغ المدفوع</label>
                            <input type='text' name='Amount' id="Amount" class='input_style'
                                placeholder="ألقيمة المدفوعة">
                        </div>
                        <div class='col-md-6' style="text-align:right;">
                            <label class="input_label">اختر العملة</label>

                            <select id='CurrencyID' class='input_style'>
                                <option value='0'>اختر العملة</option>
                                @foreach ($Currencies as $Currency)
                                    <option value = '{{ $Currency->CurrencyID }}'>{{ $Currency->CurrencyName }}</option>
                                @endforeach
                            </select>
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
                        <div class="col-md-6">
                            <label class="input_label">اختر الحساب</label>
                            <select id="PaymentAccountID" class="input_style"></select>
                        </div>
                        <div class="col-md-3"></div>
                        <div class="col-md-3">
                            <button data-dismiss="modal" type='button' class='btn save_button SavePayment'>حفظ</button>
                        </div>
                        <div class="col-md-3">
                            <button type="button" class="btn cancel_button" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <h1>فواتير المبيعات</h1>
    <div class="col-md-12 alert Result" id = "Results"></div>
    @if (count($Sales) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>الرقم </th>
                    <th>العميل </th>
                    <th>القيمة</th>
                    <th>المسدد</th>
                    <th>التاريخ</th>
                    <th>عرض</th>
                    <th>سداد</th>
                </tr>
            </thead>
            @foreach ($Sales as $Sale)
                <tbody>

                    <tr>
                        <td id = "SaleNumber{{ $Sale->SaleID }}">{{ $Sale->SaleNumber }}</td>
                        <td id = "CustomerName{{ $Sale->SaleID }}">{{ $Sale->CustomerName }}
                        </td>
                        <td>
                            <label id = "TotalSale{{ $Sale->SaleID }}">{{ number_format($Sale->TotalSale) }}</label>
                            <input type = "hidden" id = "TotalSaleValue{{ $Sale->SaleID }}" value={{ $Sale->TotalSale }}>
                        </td>
                        <td data-toggle="modal" data-target="#PaymentDetailsModel"
                            onclick="viewPaymentDetails({{ $Sale->SaleID }})">
                            <labe id = "PaidAmount{{ $Sale->SaleID }}"> {{ number_format($Sale->PaidAmount) }}
                            </labe>
                            <input type="hidden" id = "PaidAmountValue{{ $Sale->SaleID }}" value={{ $Sale->PaidAmount }}>
                        </td>
                        <td dir="ltr">{{ date('Y-m-d', strtotime($Sale->created_at)) }}
                        </td>
                        <td>
                            <a href="/sales/{{ $Sale->SaleID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i>
                        </td>
                        <td>
                            <?php $display = 'none'; ?>
                            @if ($Sale->PaidAmount < $Sale->TotalSale)
                                <?php $display = 'block'; ?>
                            @endif
                            <button style="display:{{ $display }}" class="btn SetID edit_button" data-toggle="modal"
                                data-target="#PaymentModel" id = "PayButton{{ $Sale->SaleID }}"
                                value = "{{ $Sale->SaleID }}"><i class='fa-solid fa-sack-dollar fa-2x'></i></button>


                        </td>



                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد فواتير مبيعات</div>
    @endif
    <script>
        $(document).on('change', '#PaymentType', function() {
            $("#PaymentResults").removeClass("alert-danger").html("")
            var CurrencyID = $("#CurrencyID").val()
            var AccountType = 4
            if ($("#PaymentType").val() == 2 || $("#PaymentType").val() == 3)
                AccountType = 3
            if (CurrencyID != '0') {

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

            } else {
                $("#PaymentResults").addClass("alert-danger").html(
                    "حدد نوع العملة اولا")
                $("#PaymentType").val("0")
            }
        });
        $(document).on('click', '.SetID', function() {
            $("#SaleID").val($(this).val())
        });
        $(document).on('click', '.SavePayment', function() {
            var Amount = parseFloat($("#Amount").val())
            var SaleID = $("#SaleID").val()
            var PaymentAccountID = $("#PaymentAccountID").val()
            var TotalSale = parseFloat($("#TotalSaleValue" + SaleID).val())
            var PaidAmount = parseFloat($("#PaidAmountValue" + SaleID).val())
            if (Amount > (TotalSale - PaidAmount))
                customAlert(" عذرا لا يمكنك سداد مبلغ اكبر من المبلغ المتبقي ", "danger")
            else {
                if (PaymentType != 0) {
                    customConfirm("تأكيد دفع " + $("#CustomerName" + SaleID).html() + " لقيمة " + Amount +
                        " جنيه ",
                        function(result) {
                            if (result) {
                                var form_data = new FormData();
                                form_data.append('SaleID', SaleID);
                                form_data.append('PaidAmount', Amount);
                                form_data.append('FromAccount', PaymentAccountID);

                                $.ajax({
                                    url: "{{ route('pay_sale') }}",
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
                                            customAlert("تم الدفع بنجاح", "success");
                                            PaidAmount = parseFloat($("#PaidAmountValue" + SaleID)
                                                    .val()) +
                                                Amount;
                                            $("#PaidAmountValue" + SaleID).val(PaidAmount)
                                            var formatter = new Intl.NumberFormat();
                                            $("#PaidAmount" + SaleID).html(formatter.format(
                                                PaidAmount))
                                            resetButtons(SaleID)
                                        } else {
                                            customAlert(result, "danger");
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        // Handle error
                                    }
                                });
                            } else {
                                customAlert("تم إلغاء العملية", "info");
                            }
                        });
                } else {
                    $("#Results").removeClass("alert-success").addClass("alert-danger").html(
                        "الرجاء تحديد طريقة الدفع")
                }
            }
        });

        function viewPaymentDetails(SaleID) {
            $("#SaleID").val(SaleID)
            $("#PaymentDetailsTable").empty().append(
                "<tr><th>المبلغ</th><th>تاريخ السداد</th><th>حذف</th></tr>")
            $.ajax({
                url: '{{ url('get_sale_payment_details') }}/' + SaleID,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    var formatter = new Intl.NumberFormat();
                    for (var i = 0; i < response.length; i++) {
                        var tr = $("<tr id = 'PaymentNo" + response[i]["PaymentID"] + "'></tr>")
                        tr.append($("<td>" + formatter.format(response[i]["PaidAmount"]) + "</td>"))
                        tr.append($("<td>" + response[i]["created_at"] + "</td>"))
                        tr.append($(
                            "<td><button class = 'btn delete_button DeletePayment' value = '" +
                            response[i]["PaymentID"] +
                            "'><i class='fas fa-trash-alt fa-2x'></i></button></td>"))
                        $("#PaymentDetailsTable").append(tr)
                    }

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $("#PaymentResults").addClass("alert-danger").html(
                        "حدث خطأ أثناء الاتصال بالخادم");
                }
            });
        }
        $(document).on('click', '.DeletePayment', function() {
            customConfirm("تاكيد حذف السداد", function(result) {
                if (result) {
                    var form_data = new FormData();
                    var PaymentID = $(this).val();
                    form_data.append('PaymentID', PaymentID);
                    $.ajax({
                        url: "{{ route('delete_sale_payment') }}",
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        type: 'post',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]')
                                .attr(
                                    'content'));
                        },
                        success: function(result) {
                            if (!isNaN(result)) {
                                var formatter = new Intl.NumberFormat();
                                var SaleID = $("#SaleID").val()
                                var formatter = new Intl.NumberFormat();
                                $("#PaidAmount" + SaleID).html(formatter.format(parseFloat($(
                                    "#PaidAmountValue" +
                                    SaleID).val()) - parseFloat(result)))
                                $("#PaidAmountValue" + SaleID).val(parseFloat($(
                                    "#PaidAmountValue" +
                                    SaleID).val()) - parseFloat(result))

                                customAlert("تم حذف السداد بنجاح", "success");
                                $("#PaymentNo" + PaymentID).remove()
                                resetButtons(SaleID)
                            } else
                                customAlert(result, "danger");
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                        }
                    });
                } else {
                    customAlert("تم إلغاء العملية", "info");
                }
            });
        });


        function resetButtons(SaleID) {
            $("#PayButton" + SaleID).css("display", "none");
            if (parseFloat($("#TotalSaleValue" + SaleID).val()) > parseFloat($("#PaidAmountValue" + SaleID)
                    .val()))
                $("#PayButton" + SaleID).css("display", "block");
        }
    </script>
@endsection
