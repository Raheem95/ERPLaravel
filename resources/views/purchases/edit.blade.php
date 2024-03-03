@extends('layouts.app')
<style>
    .PriceDiv {
        width: 140px;
        display: inline-block;
        position: fixed;
        right: 50px;
        top: 200px;
        background: #1aebdd;
        border-radius: 15px;
        padding: 5px;

    }

    .PriceLabel {
        text-align: center;
        color: white;
        width: 100%;
        font-size: 18px;
    }
</style>
@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="maindiv">
        <div class=" MainLabel">
            <h1>فاتورة مشتريات</h1>
        </div>
        <div class="col-md-12 Result" id = "Results"></div>

        {!! Form::open([
            'action' => ['PurchaseController@update', $Purchase->PurchaseID],
            'method' => 'post',
            'onsubmit' => 'return validateForm()',
            'style' => 'margin-right:50px;',
        ]) !!}

        <div class = "row">

            <?php
            $Suppliers = json_decode($Suppliers, true);
            
            $options = ['0' => 'اختر المورد'];
            foreach ($Suppliers as $Supplier) {
                $options[$Supplier['SupplierID']] = $Supplier['SupplierName'];
            }
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اختر المورد', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('SupplierID', $options, $Purchase->SupplierID, [
                    'class' => 'input_style SetSupllierName',
                    'id' => 'SupplierID',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'ادخل اسم المورد', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('SupplierName', $Purchase->SupplierName, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل اسم المورد',
                    'id' => 'SupplierName',
                ]) !!}
            </div>
            <?php
            
            $Stocks = json_decode($Stocks, true);
            
            $options = ['0' => 'اختر المخزن'];
            foreach ($Stocks as $Stock) {
                $options[$Stock['StockID']] = $Stock['StockName'];
            }
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اختر المخزن', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('StockID', $options, $Purchase->StockID, [
                    'class' => 'input_style',
                    'id' => 'StockID',
                ]) !!}
            </div>
        </div>
        <div class="PriceDiv">
            {!! Form::label('', 'مجمل الفاتورة', ['class' => 'PriceLabel']) !!}
            {!! Form::label($Purchase->TotalPurchase, '0', ['class' => 'PriceLabel', 'id' => 'TotalPurchaseText']) !!}
            {!! Form::hidden('TotalPurchase', $Purchase->TotalPurchase, [
                'id' => 'TotalPurchase',
            ]) !!}
        </div>
        {!! Form::hidden('NumberOfItems', count($PurchaseDetails), [
            'id' => 'NumberOfItems',
        ]) !!}
        <div class="col-md-2" style="float: right;margin:10px;"><button type="button" class="btn add_button AddRow"><i
                    class="fas fa-plus"></i></button></div>
        <table class = "table" id = "ItemsTable">
            <tr>
                <th>-</th>
                <th width="40%">المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>المجمل</th>
            </tr>

            <?php
            $i = 0;
            $options = collect(['0' => 'اختر المنتج']); // Creating a collection with default option
            
            foreach ($Items as $item) {
                $options[$item['ItemID']] = $item['ItemName'];
            }
            ?>
            @foreach ($PurchaseDetails as $RowItem)
                {{ $i++ }}
                <tr id="Row{{ $i }}">
                    <td><button type='button' class='btn delete_button RemoveRow' id='RemoveButton1' value='1'><i
                                class='fa-solid fa-trash-can'></i></button></td>
                    <td>

                        {!! Form::select('ItemID' . $i, $options, $RowItem->ItemID, [
                            'class' => 'input_style',
                            'id' => 'ItemID' . $i,
                        ]) !!}
                    </td>
                    <td>{!! Form::number('ItemQTY' . $i, $RowItem->ItemQTY, [
                        'class' => 'input_style',
                        'placeholder' => 'ادخل الكمية',
                        'id' => 'ItemQTY' . $i,
                        'oninput' => 'CalculateRow(' . $i . ')',
                    ]) !!}</td>
                    <td>{!! Form::number('ItemPrice' . $i, $RowItem->ItemPrice, [
                        'class' => 'input_style',
                        'placeholder' => 'ادخل السعر',
                        'id' => 'ItemPrice' . $i,
                        'oninput' => 'CalculateRow(' . $i . ')',
                    ]) !!}</td>
                    <td><label id="TotalRow{{ $i }}">{{ $RowItem->ItemPrice * $RowItem->ItemQTY }}</label></td>
                </tr>
            @endforeach
        </table>
        <!-- Add more form fields as needed -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                {{ Form::hidden('_method', 'PUT') }}
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                {!! Form::close() !!}
            </div>
            <div class="col-md-3"><a href = "/purchases"><button type="button" class="btn cancel_button">رجوع</button></a>
            </div>
        </div>
    </div>
    <script>
        $(document).on('change', '.SetSupllierName', function() {
            $("#SupplierName").val($(this).find('option:selected').text())
        });
        $(document).on('click', '.AddRow', function() {
            var myrowCount = $("#NumberOfItems").val()
            var Items = <?php echo json_encode($options); ?>;
            console.log(Items)
            var table = $("#ItemsTable")
            myrowCount++
            $("#NumberOfItems").val(myrowCount)
            var tr = $('<tr id = "Row' + myrowCount + '"></tr>')
            tr.append($("<td><button type='button' class='btn delete_button RemoveRow' id='RemoveButton" +
                myrowCount + "' value='" + myrowCount + "'>" +
                "<i class='fa-solid fa-trash-can'></i></button></td>"));

            var Select = $("<select class='input_style' id='ItemID" + myrowCount +
                "'  name='ItemID" + myrowCount + "'></select>");
            $.each(Items, function(key, value) {
                Select.append($("<option value='" + key + "'>" + value + "</option>"));
            });
            var td = $("<td></td>")
            td.append(Select)
            tr.append(td)
            tr.append($(
                "<td><input type = 'number' value = '0' id = 'ItemQTY" + myrowCount +
                "' name = 'ItemQTY" + myrowCount +
                "' placeholder = 'ادخل الكمية' class = 'input_style ' value = '0' oninput = (CalculateRow(" +
                myrowCount + "))></td>"
            ))
            tr.append($(
                "<td><input type = 'number' value = '0' id = 'ItemPrice" + myrowCount +
                "' name = 'ItemPrice" + myrowCount +
                "' placeholder = 'ادخل السعر' class = 'input_style ' value = '0' oninput = (CalculateRow(" +
                myrowCount + "))></td>"
            ))
            tr.append($("<td><label id = 'TotalRow" + myrowCount + "'>0</label></td>"))
            tr.appendTo(table)
            if (myrowCount > 1)
                $("#RemoveButton1").show()
            else
                $("#RemoveButton1").hide()

        });
        $(document).on('click', '.RemoveRow', function() {
            var ItemID = $(this).val()
            $("#Row" + ItemID).remove()
            var NumberOfItems = $("#NumberOfItems").val()
            ItemID++
            for (i = ItemID; i <= NumberOfItems; i++) {
                myCurrentID = i
                myCurrentID--

                document.getElementById('Row' + i).id = 'Row' + myCurrentID
                $("#ItemID" + i).attr("id", "ItemID" + myCurrentID)
                $("#ItemID" + myCurrentID).attr("name", "ItemID" + myCurrentID)

                $("#ItemQTY" + i).attr("id", "ItemQTY" + myCurrentID)
                $("#ItemQTY" + myCurrentID).attr("name", "ItemQTY" + myCurrentID)
                $("#ItemQTY" + myCurrentID).on('input', function() {
                    CalculateRow(myCurrentID);
                });

                $("#ItemPrice" + i).attr("id", "ItemPrice" + myCurrentID)
                $("#ItemPrice" + myCurrentID).attr("name", "ItemPrice" + myCurrentID)
                $("#ItemPrice" + myCurrentID).on('input', function() {
                    CalculateRow(myCurrentID);
                });

                $("#TotalRow" + i).attr("id", "TotalRow" + myCurrentID)
                $("#TotalRow" + myCurrentID).attr("name", "TotalRow" + myCurrentID)

                $("#RemoveButton" + i).attr("id", "RemoveButton" + myCurrentID)
                $("#RemoveButton" + myCurrentID).val(myCurrentID)

            }
            NumberOfItems--
            $("#NumberOfItems").val(NumberOfItems)
            if (NumberOfItems > 1)
                $("#RemoveButton1").show()
            else
                $("#RemoveButton1").hide()
        });

        function CalculateRow(RowID) {
            var Total = parseFloat($("#ItemPrice" + RowID).val()) * parseFloat($("#ItemQTY" + RowID)
                .val());
            $("#TotalRow" + RowID).html(Total.toLocaleString())
            CalculateTotal()
        }

        function CalculateTotal() {
            var myrowCount = $("#NumberOfItems").val()
            var Total = 0;
            for (var i = 1; i <= myrowCount; i++)
                Total += parseFloat($("#ItemPrice" + i).val()) * parseFloat($("#ItemQTY" + i)
                    .val());
            $("#TotalPurchaseText").html(Total.toLocaleString());

            $("#TotalPurchase").val(Total)
        }

        function validateForm() {
            var flag = true
            var Result = "";
            if ($("#SupplierID").val() == 0) {
                flag = false;
                var Result = Result + "<div class = 'alert alert-danger Result'> الرجاء اختيار المورد </div>"
            }
            if ($("#SupplierName").val() == "") {
                flag = false;
                var Result = Result + "<div class = 'alert alert-danger Result'> الرجاء ادخال اسم المورد </div>"
            }
            if ($("#StockID").val() == 0) {
                flag = false;
                var Result = Result + "<div class = 'alert alert-danger Result'> الرجاء اختيار المخزن </div>"
            }
            if ($("#NumberOfItems").val() == 0) {
                flag = false;
                var Result = Result +
                    "<div class = 'alert alert-danger Result'> يجب ان تحتوي الفاتورة على منتج واحد على الاقل </div>"
            }
            for (var i = 1; i <= $("#NumberOfItems").val(); i++) {
                if ($("#ItemID" + i).val() == 0) {
                    flag = false;
                    var Result = Result + "<div class = 'alert alert-danger Result'> الرجاء اختيار المنتج رقم " + i +
                        "</div>"
                }
                var ItemQTY = $("#ItemQTY" + i).val();
                if (isNaN(ItemQTY) || parseFloat(ItemQTY) <= 0) {
                    flag = false;
                    var Result = Result +
                        "<div class = 'alert alert-danger Result'> الرجاء ادخال كمية صحيحة للمنتج رقم " + i + "</div>"
                }
                var ItemPrice = $("#ItemPrice" + i).val();
                if (isNaN(ItemPrice) || parseFloat(ItemPrice) <= 0) {
                    flag = false;
                    var Result = Result +
                        "<div class = 'alert alert-danger Result'> الرجاء ادخال سعر صحيح للمنتج رقم " + i + "</div>"
                }
            }
            document.getElementById('Results').scrollIntoView();

            $("#Results").html(Result)
            return flag;
        }
    </script>
@endsection
