@extends('layouts.app')

@section('content')
    <div class = "maindiv">
        <div class="row">
            <div class="col-md-6">
                <label for="" class="MainLabel"> اسم المورد</label>
                <label for="" class="valueLabel">{{ $Purchase->SupplierName }}</label>
                <br><br>
                <div class="col-md-6">
                    <label for="" class="MainLabel">رقم الفاتورة</label>
                    <label for="" class="valueLabel">{{ $Purchase->PurchaseNumber }}</label>

                </div>
                <div class="col-md-6">
                    <label for="" class="MainLabel">تاريخ الفاتورة </label>
                    <label for="" class="valueLabel">{{ date('Y-m-d', strtotime($Purchase->created_at)) }}</label>
                </div>
            </div>
            <div class="col-md-6">

            </div>
        </div>
        <br>
        <table class="table">
            <tr>
                <th>#</th>
                <th>اسم المنتج</th>
                <th>الكمية</th>
            </tr>
            {{ $counter = 0 }}
            @foreach ($PurchaseDetails as $RowItem)
                <tr>
                    <td>{{ ++$counter }}</td>
                    <td>{{ $RowItem->item->ItemName }}</td>
                    <td>{{ number_format($RowItem->ItemQTY) }}</td>
                </tr>
            @endforeach
        </table>
        <br>
        <div class="row">
            <div class="col-md-4">
                <label for="" class="MainLabel">مصدر الفاتورة</label>
                <label for="" class="valueLabel">{{ $Purchase->user->name }}</label>
            </div>
        </div>
    </div>
@endsection
