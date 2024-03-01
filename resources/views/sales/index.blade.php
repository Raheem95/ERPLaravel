@extends('layouts.app')

@section('content')
    <!-- resources/views/Purchases/index.blade.php -->
    <input type = 'hidden' id = "PurchaseID">
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
                            <label class="ProceduresLabel">المبلغ المدفوع</label>
                            <input type='text' name='Amount' id="Amount" class='input_style'
                                placeholder="ألقيمة المدفوعة">
                        </div>
                        <div class='col-md-6' style="text-align:right;">
                            <label class="ProceduresLabel">اختر العملة</label>

                            <select id='CurrencyID' class='input_style'>
                                <option value='0'>اختر العملة</option>
                                @foreach ($Currencies as $Currency)
                                    <option value = '{{ $Currency->CurrencyID }}'>{{ $Currency->CurrencyName }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class='col-md-6'>
                            <label class="ProceduresLabel">طريقة الدفع</label>
                            <select id='PaymentType' class='input_style'>
                                <option value='0'>اختر طريقة الدفع</option>
                                <option value='1'>نقدا</option>
                                <option value='2'>تحويل</option>
                                <option value='3'>شيك</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="ProceduresLabel">اختر الحساب</label>
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
    <h1>فواتير المشتريات</h1>
    <div class="col-md-12 alert Result" id = "Results"></div>
    <a style="width: 20%;" href="/purchases/create" class="btn add_button mb-3">اضافة فاتورة</a>
    @if (count($Purchases) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>الرقم </th>
                    <th>المورد </th>
                    <th>القيمة</th>
                    <th>المسدد</th>
                    <th>التاريخ</th>
                    <th>عرض</th>
                    <th>سداد</th>
                    <th>تغذية المخزن</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Purchases as $Purchase)
                <tbody>

                    <tr>
                        <td id = "PurchaseNumber{{ $Purchase->PurchaseID }}">{{ $Purchase->PurchaseNumber }}</td>
                        <td id = "SupplierName{{ $Purchase->PurchaseID }}">{{ $Purchase->SupplierName }}
                        </td>
                        <td>
                            <label
                                id = "TotalPurchase{{ $Purchase->PurchaseID }}">{{ number_format($Purchase->TotalPurchase) }}</label>
                            <input type = "hidden" id = "TotalPurchaseValue{{ $Purchase->PurchaseID }}"
                                value={{ $Purchase->TotalPurchase }}>
                        </td>
                        <td data-toggle="modal" data-target="#PaymentDetailsModel"
                            onclick="viewPaymentDetails({{ $Purchase->PurchaseID }})">
                            <labe id = "PaidAmount{{ $Purchase->PurchaseID }}"> {{ number_format($Purchase->PaidAmount) }}
                            </labe>
                            <input type="hidden" id = "PaidAmountValue{{ $Purchase->PurchaseID }}"
                                value={{ $Purchase->PaidAmount }}>
                        </td>
                        <td dir="ltr">{{ date('Y-m-d', strtotime($Purchase->created_at)) }}
                        </td>
                        <td>
                            <a href="purchases/{{ $Purchase->PurchaseID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i>
                        </td>
                        <td>
                            <?php $display = 'none'; ?>
                            @if ($Purchase->PaidAmount < $Purchase->TotalPurchase)
                                <?php $display = 'block'; ?>
                            @endif
                            <button style="display:{{ $display }}" class="btn SetID edit_button" data-toggle="modal"
                                data-target="#PaymentModel" id = "PayButton{{ $Purchase->PurchaseID }}"
                                value = "{{ $Purchase->PurchaseID }}"><i
                                    class='fa-solid fa-sack-dollar fa-2x'></i></button>


                        </td>
                        <td>
                            <?php $Class = 'UnTransfareButton';
                            $color = 'red'; ?>
                            @if ($Purchase->Transfer == 0)
                                <?php $Class = 'TransfareButton';
                                $color = 'blue'; ?>
                            @endif
                            <button id = "TransfareButton{{ $Purchase->PurchaseID }}"
                                class="btn view_button Transfare {{ $Class }}"
                                style="color:{{ $color }}"value='{{ $Purchase->PurchaseID }}'><i
                                    class="fa-solid fa-shuffle fa-2x "></i></button>
                            <input type="hidden" id = "Transfer{{ $Purchase->PurchaseID }}"
                                value = "{{ $Purchase->Transfer }}">
                        </td>


                        <?php $display = 'none'; ?>
                        @if ($Purchase->Transfer == 0 && $Purchase->PaidAmount == 0)
                            <?php $display = 'block'; ?>
                        @endif
                        <td>
                            <a style="display: {{ $display }}" id="EditButton{{ $Purchase->PurchaseID }}"
                                href="purchases/{{ $Purchase->PurchaseID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i>
                            </a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['PurchaseController@destroy', $Purchase->PurchaseID],
                                'method' => 'post',
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'submit',
                                'class' => 'btn delete_button',
                                'style' => 'display:' . $display, // Corrected style assignment
                                'id' => 'DeleteButton' . $Purchase->PurchaseID,
                                'onclick' => "return confirm('تاكيد حذف العميل  $Purchase->PurchaseName ')",
                            ]) !!}
                            {!! Form::close() !!}
                        </td>



                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد فواتير مشتريات</div>
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
            $("#PurchaseID").val($(this).val())
        });
        $(document).on('click', '.SavePayment', function() {
            var Amount = parseFloat($("#Amount").val())
            var PurchaseID = $("#PurchaseID").val()
            var PaymentAccountID = $("#PaymentAccountID").val()
            var TotalPurchase = parseFloat($("#TotalPurchaseValue" + PurchaseID).val())
            var PaidAmount = parseFloat($("#PaidAmountValue" + PurchaseID).val())
            if (Amount > (TotalPurchase - PaidAmount))
                $("#Results").removeClass("alert-success").addClass("alert-danger").html(
                    " عذرا لا يمكنك سداد مبلغ اكبر من المبلغ المتبقي ")
            else {
                if (PaymentType != 0) {
                    if (confirm("تأكيد دفع " + $("#SupplierName" + PurchaseID).html() + " لقيمة " + Amount +
                            " جنيه ")) {
                        var form_data = new FormData();
                        form_data.append('PurchaseID', PurchaseID);
                        form_data.append('PaidAmount', Amount);
                        form_data.append('FromAccount', PaymentAccountID);

                        $.ajax({
                            url: "{{ route('pay_purchase') }}",
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
                                if (result == 1) {
                                    $("#Results").removeClass("alert-danger").addClass("alert-success")
                                        .html(
                                            "تم الدفع بنجاح");
                                    PaidAmount = parseFloat($("#PaidAmountValue" + PurchaseID).val()) +
                                        Amount;
                                    $("#PaidAmountValue" + PurchaseID).val(PaidAmount)
                                    var formatter = new Intl.NumberFormat();
                                    $("#PaidAmount" + PurchaseID).html(formatter.format(PaidAmount))
                                    resetButtons(PurchaseID)
                                } else {
                                    $("#Results").removeClass("alert-success").addClass("alert-danger")
                                        .html(result);
                                }
                            },
                            error: function(xhr, status, error) {
                                // Handle error
                            }
                        });

                    }
                } else {
                    $("#Results").removeClass("alert-success").addClass("alert-danger").html(
                        "الرجاء تحديد طريقة الدفع")
                }
            }
        });

        function viewPaymentDetails(PurchaseID) {
            $("#PurchaseID").val(PurchaseID)
            $("#PaymentDetailsTable").empty().append("<tr><th>المبلغ</th><th>تاريخ السداد</th><th>حذف</th></tr>")
            $.ajax({
                url: '{{ url('get_purchase_payment_details') }}/' + PurchaseID,
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
            if (confirm("تاكيد حذف السداد")) {
                var form_data = new FormData();
                var PaymentID = $(this).val();
                form_data.append('PaymentID', PaymentID);
                $.ajax({
                    url: "{{ route('delete_purchase_payment') }}",
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
                        if (!isNaN(result)) {
                            var formatter = new Intl.NumberFormat();
                            var PurchaseID = $("#PurchaseID").val()
                            var formatter = new Intl.NumberFormat();
                            $("#PaidAmount" + PurchaseID).html(formatter.format(parseFloat($(
                                "#PaidAmountValue" +
                                PurchaseID).val()) - parseFloat(result)))
                            $("#PaidAmountValue" + PurchaseID).val(parseFloat($("#PaidAmountValue" +
                                PurchaseID).val()) - parseFloat(result))

                            $("#DeletePaymentResults").removeClass("alert-danger").addClass(
                                    "alert-success")
                                .html(
                                    "تم حذف السداد بنجاح");
                            $("#PaymentNo" + PaymentID).remove()
                            resetButtons(PurchaseID)
                        } else
                            $("#DeletePaymentResults").removeClass("alert-success").addClass(
                                "alert-danger").html(
                                result);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                    }
                });
            }
        });
        $(document).on('click', '.Transfare', function() {
            var PurchaseID = $(this).val()
            var PurchaseNumber = $("#PurchaseNumber" + PurchaseID).html()
            var Status = 1;
            var AlertMessage = "صرف"
            if ($(this).hasClass("UnTransfareButton")) {
                Status = 0;
                var AlertMessage = " الغاء صرف"
            }
            if (confirm("تاكيد " + AlertMessage + "  الفاتورة رقم" + PurchaseNumber)) {
                var form_data = new FormData();
                form_data.append('PurchaseID', PurchaseID);
                form_data.append('Status', Status);
                $.ajax({
                    url: "{{ route('transfare_purchase_payment') }}",
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
                        if (!isNaN(result)) {
                            $("#Results").removeClass("alert-danger").addClass(
                                    "alert-success")
                                .html(
                                    "تم  " + AlertMessage + " الفاتورة بنجاح");
                            $("#Transfer" + PurchaseID).val(Status)

                            resetButtons(PurchaseID)
                        } else
                            $("#Results").removeClass("alert-success").addClass(
                                "alert-danger").html(
                                result);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                    }
                });
            }
        });

        function resetButtons(PurchaseID) {
            $("#PayButton" + PurchaseID).css("display", "none");
            if (parseFloat($("#TotalPurchaseValue" + PurchaseID).val()) > parseFloat($("#PaidAmountValue" + PurchaseID)
                    .val()))
                $("#PayButton" + PurchaseID).css("display", "block");

            $("#EditButton" + PurchaseID).css("display", "block");
            $("#DeleteButton" + PurchaseID).css("display", "block");
            if ($("#PaidAmountValue" + PurchaseID).val() > 0 || $("#Transfer" + PurchaseID).val() == 1) {
                $("#EditButton" + PurchaseID).css("display", "none");
                $("#DeleteButton" + PurchaseID).css("display", "none");
            }
            $("#TransfareButton" + PurchaseID).removeClass("TransfareButton").addClass("UnTransfareButton");
            $("#TransfareButton" + PurchaseID).css("color", "red")
            if ($("#Transfer" + PurchaseID).val() == 0) {
                $("#TransfareButton" + PurchaseID).removeClass("UnTransfareButton").addClass("TransfareButton");
                $("#TransfareButton" + PurchaseID).css("color", "blue")
            }
        }
    </script>
@endsection
