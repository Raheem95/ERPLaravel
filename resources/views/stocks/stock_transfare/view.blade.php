@extends('layouts.app')
@section('content')
    <div class="maindiv">
        <a style="width: 20%;" href="/Stocks/Transfare" class="btn cancel_button mb-3">رجوع</a>
        <div class="row">
            <div class="col-md-6" style="float: right">
                <label for="" class="MainLabel">من مخزن</label>
                <label for="" class="valueLabel">{{ $StockTransfare->from_stock->StockName }}</label>
                <label for="" class="MainLabel">الى مخزن</label>
                <label for="" class="valueLabel">{{ $StockTransfare->to_stock->StockName }}</label>

                <label for="" class="MainLabel">التعليق</label>
                <label for="" class="valueLabel">{{ $StockTransfare->Comment }}</label>

            </div>
        </div>
        <br>
        <br>
        <div class="row">
            <div class="col-md-9">
                <label for="" class="MainLabel">الحركات المخزنية</label>
                @if (count($StockTransfareDetails) > 0)
                    <table class="table">
                        <tr>
                            <th>المنتج</th>
                            <th>الكمية</th>
                        </tr>
                        @foreach ($StockTransfareDetails as $Details)
                            <tr>
                                <td>{{ $Details->item->ItemName }}</td>
                                <td>{{ number_format($Details->ItemQTY) }}</td>

                            </tr>
                        @endforeach
                    </table>
                @else
                    <div class="alert alert-danger Result">لا توجد حركات مخزنية</div>
                @endif
            </div>
        </div>
    </div>
@endsection
