@extends('layouts.app')
@section('content')
    <div class="maindiv">
        <button class='btn print_button' onclick="printWithSpecialFileName()">
            طباعة <i class='fa-solid fa-print fa-2x'></i>
        </button>

        <div class="row">
            <div class="col-md-3" style="float: right">
                <label for="" class="input_label">اسم المخزن</label>
                <label for="" class="model_label" id="StockName">{{ $Stock->StockName }}</label>
            </div>
        </div>
        <br>
        <br>
        @if (count($StockItems) > 0)
            <table class="table">
                <tr>
                    <th class="TdStyle">اسم المنتج</th>
                    <th class="TdStyle">الكمية المتوفرة</th>
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
        <label for="" class="input_label">الحركات المخزنية</label>
        @if (count($StockTransactions) > 0)
            <table class="table">
                <tr>
                    <th class="TdStyle">المنتج</th>
                    <th class="TdStyle">الكمية</th>
                    <th class="TdStyle">تفاصيل العملية</th>
                    <th class="TdStyle">تاريخ العملية</th>
                    <th class="TdStyle">نوع العملية</th>
                    <th class="TdStyle">منفذ العملية</th>
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
@endsection
<script>
    function printWithSpecialFileName() {
        document.title = $("#StockName").html() + ".pdf";
        window.print();
    }
</script>
