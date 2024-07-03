@extends('layouts.app')

@section('content')
    <div class="input_label">
        <h1>فواتير المبيعات</h1>
    </div>

    <input type="hidden" id="SaleID">

    <div class="modal fade" id="PaymentDetailsModel" role="dialog">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="col-md-12 Result alert" id="DeletePaymentResults"></div>
                    <table class="table" id="PaymentDetailsTable">
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
            <div class="modal-content">
                <div class="modal-body">
                    <div class='row'>
                        <div class="col-md-12 Result alert" id="PaymentResults"></div>
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
                                    <option value='{{ $Currency->CurrencyID }}'>{{ $Currency->CurrencyName }}</option>
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

    <div class="col-md-12 alert Result" id="Results"></div>

    <div class="row">
        <div class="col-md-2">
            <a href="/sales/create" class="btn add_button mb-3">اضافة فاتورة</a>
        </div>
        <div class="col-md-8">
            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    @if (count($Sales) > 0)
        <table class="table" id="SalesTable">
            <thead>
                <tr>
                    <th>الرقم</th>
                    <th>العميل</th>
                    <th>القيمة</th>
                    <th>التاريخ</th>
                    <th>عرض</th>
                    <th>صرف</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Sales as $Sale)
                    <tr>
                        <td id="SaleNumber{{ $Sale->SaleID }}">{{ $Sale->SaleNumber }}</td>
                        <td id="CustomerName{{ $Sale->SaleID }}">{{ $Sale->CustomerName }}</td>
                        <td>
                            <label id="TotalSale{{ $Sale->SaleID }}">{{ number_format($Sale->TotalSale) }}</label>
                        </td>
                        <td dir="ltr">{{ date('Y-m-d', strtotime($Sale->created_at)) }}</td>
                        <td>
                            <a target="_blank" href="sales/{{ $Sale->SaleID }}/" class="btn view_button">
                                <i class='fa-solid fa-clipboard-list fa-2x'></i>
                            </a>
                        </td>
                        <td>
                            @if ($Sale->Transfer < 2)
                                <?php
                                $Class = 'UnTransfareButton';
                                $color = 'red';
                                ?>
                                @if ($Sale->Transfer == 0)
                                    <?php
                                    $Class = 'TransfareButton';
                                    $color = 'blue';
                                    ?>
                                @endif
                                <button id="TransfareButton{{ $Sale->SaleID }}"
                                    class="btn view_button Transfare {{ $Class }}"
                                    style="color:{{ $color }}" value='{{ $Sale->SaleID }}'>
                                    <i class="fa-solid fa-shuffle fa-2x"></i>
                                </button>
                            @else
                                تم صرف الفاتورة من المخزن
                            @endif
                            <input type="hidden" id="Transfer{{ $Sale->SaleID }}" value="{{ $Sale->Transfer }}">
                        </td>

                        <?php $display = 'none'; ?>
                        @if ($Sale->Transfer == 0 && $Sale->PaidAmount == 0)
                            <?php $display = 'block'; ?>
                        @endif
                        <td>
                            <a style="display: {{ $display }}" id="EditButton{{ $Sale->SaleID }}"
                                href="sales/{{ $Sale->SaleID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i>
                            </a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['SaleController@destroy', $Sale->SaleID],
                                'method' => 'post',
                                'id' => 'deleteForm' . $Sale->SaleID,
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button', // Use type button to handle click event manually
                                'class' => 'btn delete_button',
                                'style' => 'display:' . $display, // Corrected style assignment
                                'id' => 'DeleteButton' . $Sale->SaleID,
                                'onclick' => "confirmDelete('تاكيد حذف  الفاتورة رقم   {$Sale->SaleNumber}','deleteForm{$Sale->SaleID}')",
                            ]) !!}
                            {!! Form::close() !!}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result">لا يوجد فواتير مبيعات</div>
    @endif

    <script>
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword) Keyword = 0;
            $.ajax({
                url: '{{ url('sales_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table body
                    $('#SalesTable tbody').empty();

                    // Iterate over the response data and create rows
                    response.forEach(function(sale) {
                        var transferButton = '';
                        var editButton = 'style="display: none"';
                        var deleteButton = 'style="display: none"';

                        if (sale.Transfer < 2) {
                            var classType = 'UnTransfareButton';
                            var color = 'red';
                            if (sale.Transfer == 0) {
                                classType = 'TransfareButton';
                                color = 'blue';
                            }
                            transferButton =
                                `<button id="TransfareButton${sale.SaleID}" class="btn view_button Transfare ${classType}" style="color:${color}" value='${sale.SaleID}'><i class="fa-solid fa-shuffle fa-2x "></i></button>`;
                        } else {
                            transferButton = 'تم صرف الفاتورة من المخزن';
                        }

                        if (sale.Transfer == 0 && sale.PaidAmount == 0) {
                            editButton = '';
                            deleteButton = '';
                        }

                        var row = `
                    <tr>
                        <td id="SaleNumber${sale.SaleID}">${sale.SaleNumber}</td>
                        <td id="CustomerName${sale.SaleID}">${sale.CustomerName}</td>
                        <td><label id="TotalSale${sale.SaleID}">${Number(sale.TotalSale).toLocaleString()}</label></td>
                        <td dir="ltr">${new Date(sale.created_at).toISOString().split('T')[0]}</td>
                        <td><a target="_blank" href="sales/${sale.SaleID}/" class="btn view_button"><i class='fa-solid fa-clipboard-list fa-2x'></i></a></td>
                        <td>${transferButton}<input type="hidden" id="Transfer${sale.SaleID}" value="${sale.Transfer}"></td>
                        <td><a id="EditButton${sale.SaleID}" href="sales/${sale.SaleID}/edit" class="btn edit_button" ${editButton}><i class='fa-solid fa-file-pen fa-2x'></i></a></td>
                        <td>
                            <form action="sales/${sale.SaleID}" method="post" id="deleteForm${sale.SaleID}" style="display: inline;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="button" class="btn delete_button" ${deleteButton} onclick="confirmDelete('تاكيد حذف الفاتورة رقم ${sale.SaleNumber}', 'deleteForm${sale.SaleID}')" id="DeleteButton${sale.SaleID}">
                                    <i class="fas fa-trash-alt fa-2x"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                        $('#SalesTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }
        $(document).on('click', '.Transfare', function() {
            var SaleID = $(this).val()
            var SaleNumber = $("#SaleNumber" + SaleID).html()
            var Status = 1;
            var AlertMessage = "صرف"
            if ($(this).hasClass("UnTransfareButton")) {
                Status = 0;
                var AlertMessage = " الغاء صرف"
            }
            customConfirm("تاكيد " + AlertMessage + "  الفاتورة رقم" + SaleNumber, function(result) {
                if (result) {
                    var form_data = new FormData();
                    form_data.append('SaleID', SaleID);
                    form_data.append('Status', Status);
                    $.ajax({
                        url: "{{ route('sale_transfare') }}",
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
                                customAlert("تم  " + AlertMessage + " الفاتورة بنجاح",
                                    "success");
                                Search()
                            } else
                                $("#Results").removeClass("alert-success").addClass(
                                    "alert-danger").html(
                                    result);
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
    </script>
@endsection
