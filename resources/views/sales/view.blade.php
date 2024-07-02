@extends('layouts.app')

@section('content')
    <div class = "maindiv">

        <button class='btn print_button' onclick="printWithSpecialFileName()">
            طباعة <i class='fa-solid fa-print fa-2x'></i>
        </button>
        <div class="input_label">
            <h1>فاتورة مبيعات </h1>
        </div>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle">رقم الفاتورة</label>
                        <label for="" class="input_style" id="InvoiceNumber">{{ $Sale->SaleNumber }}</label>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle"> اسم العميل</label>
                        <label for="" class="input_style">{{ $Sale->CustomerName }}</label>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle">تاريخ الفاتورة </label>
                        <label for="" class="input_style">{{ date('Y-m-d', strtotime($Sale->created_at)) }}</label>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle">قيمة الفاتورة </label>
                        <label for="" class="input_style">{{ number_format($Sale->TotalSale) }}</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4 text-left">
                <img src="/images/logo.jpg"
                    style="width: 300px;height: 300px;border: 2px solid #e5dfdf;border-radius: 10px;" alt="">
            </div>
        </div>
        <br>
        <table class="table">
            <tr>
                <th class="TdStyle">اسم المنتج</th>
                <th class="TdStyle">الكمية</th>
                <th class="TdStyle">سعر الوحدة</th>
                <th class="TdStyle">المجمل</th>
            </tr>
            <?php $Total = 0; ?>
            @foreach ($SaleDetails as $RowItem)
                <tr>
                    <td>{{ $RowItem->item->ItemName }}</td>
                    <td>{{ number_format($RowItem->ItemQTY) }}</td>
                    <td>{{ number_format($RowItem->ItemPrice) }}</td>
                    <td>{{ number_format($RowItem->ItemQTY * $RowItem->ItemPrice) }}</td>
                </tr>
                <?php $Total += $RowItem->ItemQTY * $RowItem->ItemPrice; ?>
            @endforeach
            <tr>
                <th class="TdStyle" colspan="3">المجمل</th>
                <th class="TdStyle">{{ number_format($Total) }}</th>
            </tr>
            <tr>
                <th class="TdStyle" colspan="3">التخفيض</th>
                <th class="TdStyle">{{ number_format(0, 2) }}</b></th>
            </tr>
            </tr>
            <tr>
                <th class="TdStyle" colspan="3">Vat</th>
                <th class="TdStyle">{{ number_format(($Total * 15) / 100) }}</th>
            </tr>
            <tr>
                <th class="TdStyle" colspan="3">المجمل النهائي</th>
                <th class="TdStyle">{{ number_format($Total + ($Total * 15) / 100) }}</th>
            </tr>
        </table>
        <br>
        <div class="footer">
            <label class="FooterLabel">مصدر الفاتورة <span>{{ $Sale->user->name }}</span></label>
        </div>
    </div>
@endsection
<script>
    function printWithSpecialFileName() {
        document.title = $("#InvoiceNumber").html() + ".pdf";
        window.print();
    }
</script>
