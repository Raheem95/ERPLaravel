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

    <a style="width: 20%;" href="/sales/create" class="btn add_button mb-3">اضافة فاتورة</a>

    @if (count($Sales) > 0)
        <table class="table">
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

@endsection
