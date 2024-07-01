@extends('layouts.app')

@section('content')
    <div class = "maindiv">
        <button class='btn print_button' onclick="printWithSpecialFileName()">
            طباعة <i class='fa-solid fa-print fa-2x'></i>
        </button>
        <div class="row">
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle">رقم الفاتورة</label>
                        <label for="" class="input_style" id="InvoiceNumber">{{ $Purchase->PurchaseNumber }}</label>
                    </div>
                    <div class="col-md-6">
                        <label for="" class="input_label TdStyle"> المورد</label>
                        <label for="" class="input_style">{{ $Purchase->SupplierName }}</label>
                    </div>
                    <div class="col-md-6"><label for="" class="input_label TdStyle">تاريخ الفاتورة </label>
                        <label for="" class="input_style">{{ $Purchase->created_at }}</label>
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
            </tr>
            <?php $Total = 0; ?>
            @foreach ($PurchaseDetails as $RowItem)
                <tr>
                    <td>{{ $RowItem->item->ItemName }}</td>
                    <td>{{ number_format($RowItem->ItemQTY) }}</td>
                </tr>
            @endforeach
        </table>
        <br>
        <div class="footer">
            <label class="FooterLabel">مصدر الفاتورة <span>{{ $Purchase->user->name }}</span></label>
        </div>
    </div>
@endsection

<script>
    function printWithSpecialFileName() {
        document.title = $("#InvoiceNumber").html() + ".pdf";
        window.print();
    }
</script>
