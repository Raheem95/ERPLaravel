@extends('layouts.app')
@section('content')
    <div class="maindiv">
        <div class="row">
            <div class="col-md-3" style="float: right">
                <label for="" class="MainLabel">اسم المخزن</label>
                <label for="" class="valueLabel">{{ $Stock->StockName }}</label>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-8">
                <label for="" class="MainLabel">الحركات المخزنية</label>
                @if (count($StockTransactions) > 0)
                    <table class="table">
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>تفاصيل العملية</th>
                            <th>تاريخ العملية</th>
                            <th>نوع العملية</th>
                        </tr>
                        @foreach ($StockTransactions as $Transactios)
                            <tr>
                                <td>{{ $Transaction->item->ItemName }}</td>
                                <td>{{ $Transaction->ItemQTY }}</td>
                                <td>{{ $Transaction->TransactionDetails }}</td>
                                <td>{{ $Transaction->created_at }}</td>
                                <td>
                                    @if ($Transaction->OprationType == 1)
                                        تغذية
                                    @else
                                        صرف
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-danger Result">لا توجد حركات مخزنية</div>
                @endif
            </div>
            <div class="col-md-4">
                <label for="" class="MainLabel">المنتجات</label>
                @if (count($StockItems) > 0)
                    <table class="table">
                        <tr>
                            <th>اسم المنتج</th>
                            <th>الكمية المتوفرة</th>
                        </tr>
                        @foreach ($StockItems as $Item)
                            <tr>
                                <td>{{ $Item->item->ItemName }}</td>
                                <td>{{ $Item->ItemQTY }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-danger Result">لا توجد منتجات</div>
                @endif
            </div>
        </div>
    </div>
@endsection
