@extends('layouts.app')
@section('content')
    <div class="maindiv">
        <a style="width: 20%;" href="/Stocks/StockManagment" class="btn cancel_button mb-3">رجوع</a>
        <div class="row">
            <div class="col-md-3" style="float: right">
                <label for="" class="MainLabel">اسم المخزن</label>
                <label for="" class="valueLabel">{{ $Stock->StockName }}</label>
            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-9">
                <label for="" class="MainLabel">الحركات المخزنية</label>
                @if (count($StockTransactions) > 0)
                    <table class="table">
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                            <th>تفاصيل العملية</th>
                            <th>تاريخ العملية</th>
                            <th>نوع العملية</th>
                            <th>منفذ العملية</th>
                        </tr>
                        @foreach ($StockTransactions as $Transaction)
                            <tr>
                                <td>{{ $Transaction->item->ItemName }}</td>
                                <td>{{ number_format(abs($Transaction->ItemQTY)) }}</td>
                                <td>{{ $Transaction->TransactionDetails }}</td>
                                <td>{{ date('Y-m-d', strtotime($Transaction->created_at)) }}</td>
                                <td>
                                    @if ($Transaction->ItemQTY > 0)
                                        تغذية
                                    @else
                                        صرف
                                    @endif
                                </td>
                                <td>{{ $Transaction->user->name }}</td>
                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-danger Result">لا توجد حركات مخزنية</div>
                @endif
            </div>
            <div class="col-md-3">
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
                                <td>{{ number_format($Item->ItemQTY) }}</td>
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
