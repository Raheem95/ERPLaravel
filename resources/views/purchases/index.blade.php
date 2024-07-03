@extends('layouts.app')

@section('content')
    <div class="input_label">
        <h1>فواتير مشتريات</h1>
    </div>
    <!-- resources/views/Purchases/index.blade.php -->
    <input type = 'hidden' id = "PurchaseID">
    <div class="col-md-12 alert Result" id = "Results"></div>
    <div class="row">
        <div class="col-md-2">
            <a href="/purchases/create" class="btn add_button mb-3">اضافة فاتورة</a>
        </div>
        <div class="col-md-8">
            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    @if (count($Purchases) > 0)
        <table class="table" id="PurchaseTable">
            <thead>
                <tr>
                    <th>الرقم </th>
                    <th>المورد </th>
                    <th>القيمة</th>
                    <th>التاريخ</th>
                    <th>عرض</th>
                    <th>تغذية المخزن</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Purchases as $Purchase)
                    <tr>
                        <td id = "PurchaseNumber{{ $Purchase->PurchaseID }}">{{ $Purchase->PurchaseNumber }}</td>
                        <td id = "SupplierName{{ $Purchase->PurchaseID }}">{{ $Purchase->SupplierName }}
                        </td>
                        <td>
                            <label
                                id = "TotalPurchase{{ $Purchase->PurchaseID }}">{{ number_format($Purchase->TotalPurchase) }}</label>
                        </td>
                        <td dir="ltr">{{ date('Y-m-d', strtotime($Purchase->created_at)) }}
                        </td>
                        <td>
                            <a target="_blank" href="purchases/{{ $Purchase->PurchaseID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i>
                        </td>
                        <td>
                            @if ($Purchase->Transfer <= 1)
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
                            @else
                                تم تغذية الفاتورة في المخزن
                            @endif
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
                                'id' => 'deleteForm' . $Purchase->PurchaseID,
                                'style' => 'display: inline;',
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button',
                                'class' => 'btn delete_button',
                                'style' => 'display:' . $display, // Corrected style assignment
                                'onclick' => "confirmDelete('تاكيد حذف  الفاتورة رقم   {$Purchase->PurchaseNumber}','deleteForm$Purchase->PurchaseID')",
                                'id' => 'DeleteButton' . $Purchase->PurchaseID,
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
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword)
                Keyword = 0
            $.ajax({
                url: '{{ url('purchase_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table body
                    $('#PurchaseTable tbody').empty();

                    // Iterate over the response data and create rows
                    response.forEach(function(purchase) {
                        var transferButton = '';
                        var editButton = 'style="display: none"';
                        var deleteButton = 'style="display: none"';
                        if (purchase.Transfer <= 1) {
                            var classType = 'UnTransfareButton';
                            var color = 'red';
                            if (purchase.Transfer == 0) {
                                classType = 'TransfareButton';
                                color = 'blue';
                            }
                            transferButton =
                                `<button id="TransfareButton${purchase.PurchaseID}" class="btn view_button Transfare ${classType}" style="color:${color}" value='${purchase.PurchaseID}'><i class="fa-solid fa-shuffle fa-2x "></i></button><input type="hidden" id="Transfer${purchase.PurchaseID}" value="${purchase.Transfer}">`;
                        } else {
                            transferButton = 'تم تغذية الفاتورة في المخزن';
                        }

                        if (purchase.Transfer == 0 && purchase.PaidAmount == 0) {
                            editButton = '';
                            deleteButton = '';
                        }

                        var row = `
                    <tr>
                        <td id="PurchaseNumber${purchase.PurchaseID}">${purchase.PurchaseNumber}</td>
                        <td id="SupplierName${purchase.PurchaseID}">${purchase.SupplierName}</td>
                        <td><label id="TotalPurchase${purchase.PurchaseID}">${Number(purchase.TotalPurchase).toLocaleString()}</label></td>
                        <td dir="ltr">${new Date(purchase.created_at).toISOString().split('T')[0]}</td>
                        <td><a target="_blank" href="purchases/${purchase.PurchaseID}/" class="btn view_button"><i class='fa-solid fa-clipboard-list fa-2x'></i></a></td>
                        <td>${transferButton}</td>
                        <td><a id="EditButton${purchase.PurchaseID}" href="purchases/${purchase.PurchaseID}/edit" class="btn edit_button" ${editButton}><i class='fa-solid fa-file-pen fa-2x'></i></a></td>
                        <td>
                            <form action="purchases/${purchase.PurchaseID}" method="post" id="deleteForm${purchase.PurchaseID}" style="display: inline;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="button" class="btn delete_button" ${deleteButton} onclick="confirmDelete('تاكيد حذف الفاتورة رقم ${purchase.PurchaseNumber}', 'deleteForm${purchase.PurchaseID}')" id="DeleteButton${purchase.PurchaseID}">
                                    <i class="fas fa-trash-alt fa-2x"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                        $('#PurchaseTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }
        $(document).on('click', '.Transfare', function() {
            var PurchaseID = $(this).val()
            var PurchaseNumber = $("#PurchaseNumber" + PurchaseID).html()
            var Status = 1;
            var AlertMessage = "صرف"
            if ($(this).hasClass("UnTransfareButton")) {
                Status = 0;
                var AlertMessage = " الغاء صرف"
            }
            customConfirm("تاكيد " + AlertMessage + "  الفاتورة رقم" + PurchaseNumber, function(result) {
                if (result) {
                    var form_data = new FormData();
                    form_data.append('PurchaseID', PurchaseID);
                    form_data.append('From', "Invoice");
                    form_data.append('Status', Status);
                    $.ajax({
                        url: "{{ route('purchase_transfare') }}",
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
