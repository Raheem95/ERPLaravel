@extends('layouts.app')
@section('content')
    <div class="maindiv">
        <button class='btn print_button' onclick="printWithSpecialFileName()">
            طباعة <i class='fa-solid fa-print fa-2x'></i>
        </button>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle">من مخزن</label>
                        <label for="" class="input_style">{{ $StockTransfare->from_stock->StockName }}</label>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle">الى مخزن</label>
                        <label for="" class="input_style">{{ $StockTransfare->to_stock->StockName }}</label>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle">التعليق</label>
                        <label for="" class="input_style">{{ $StockTransfare->Comment }}</label>
                    </div>
                </div>
            </div>

            <div class="col-md-4 text-left">
                <img src="/images/logo.jpg"
                    style="width: 200px;height: 200px;border: 2px solid #e5dfdf;border-radius: 10px;" alt="">
            </div>
        </div>
        <br>
        @if (count($StockTransfareDetails) > 0)
            <table class="table">
                <tr>
                    <th class="TdStyle">المنتج</th>
                    <th class="TdStyle">الكمية</th>
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
@endsection

<script>
    function printWithSpecialFileName() {
        document.title = "تحويل مخزني.pdf";
        window.print();
    }
</script>
