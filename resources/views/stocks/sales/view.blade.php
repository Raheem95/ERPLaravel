@extends('layouts.app')

@section('content')
    <div class = "maindiv">
        <div class="row">
            <div class="col-md-3">
                <label for="" class="MainLabel">رقم الفاتورة</label>
                <label for="" class="valueLabel">{{ $Sale->SaleNumber }}</label>
                <label for="" class="MainLabel"> اسم العميل</label>
                <label for="" class="valueLabel">{{ $Sale->CustomerName }}</label>
            </div>
            <div class="col-md-3">
                <label for="" class="MainLabel">تاريخ الفاتورة </label>
                <label for="" class="valueLabel">{{ date('Y-m-d', strtotime($Sale->created_at)) }}</label>

            </div>
            <div class="col-md-6">

            </div>
        </div>
        <br>
        <table class="table">
            <tr>
                <th>اسم المنتج</th>
                <th>الكمية</th>
            </tr>
            @foreach ($SaleDetails as $RowItem)
                <tr>
                    <td>{{ $RowItem->item->ItemName }}</td>
                    <td>{{ number_format($RowItem->ItemQTY) }}</td>
                </tr>
            @endforeach
        </table>
        <br>
        <div class="row">
            <div class="col-md-4">
                <label for="" class="MainLabel">مصدر الفاتورة</label>
                <label for="" class="valueLabel">{{ $Sale->user->name }}</label>
            </div>
        </div>
    </div>
@endsection
