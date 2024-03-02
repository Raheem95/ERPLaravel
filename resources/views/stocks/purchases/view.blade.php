@extends('layouts.app')

@section('content')
    <div class = "maindiv">
        <div class="row">
            <div class="col-md-6">
                <label for="" class="MainLabel">رقم الفاتورة</label>
                <label for="" class="valueLabel">{{ $Purchase->PurchaseNumber }}</label>
                <label for="" class="MainLabel">تاريخ الفاتورة </label>
                <label for="" class="valueLabel">{{ $Purchase->created_at }}</label>
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
            @foreach ($PurchaseDetails as $RowItem)
                <tr>
                    <td>{{ $RowItem->item->ItemName }}</td>
                    <td>{{ $RowItem->ItemQTY }}</td>
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
