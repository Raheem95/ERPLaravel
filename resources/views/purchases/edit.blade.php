@extends('layouts.app')
<style>
    .SelectItem {

        width: 400px;
        position: absolute;
        background: #fdfeff;
        padding: 10 30px;
        text-align: left;
        display: none;
        z-index: 3;
        max-height: 300px;
        overflow: scroll;
    }



    .SelectItem li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #3498db;
        text-align: right;
    }

    .SelectItem li:hover {
        background: #3498db;
        color: white;
        font-weight: 600;
    }

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
        <div class=" input_label">
            <h1>فاتورة مشتريات</h1>
        </div>
        <div class="col-md-12 Result" id="Results"></div>

        {!! Form::open([
            'action' => ['PurchaseController@update', $Purchase->PurchaseID],
            'method' => 'post',
            'onsubmit' => 'return validateForm()',
            'style' => 'margin-right:50px;',
        ]) !!}

        <div class="row">
            <?php
            $PurchaseAccounts = json_decode($PurchaseAccounts, true);
            foreach ($PurchaseAccounts as $PurchaseAccount) {
                $PurchaseAccountsOptions[$PurchaseAccount['AccountID']] = $PurchaseAccount['AccountName'];
            }
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اختر الحساب', ['class' => 'input_label']) !!}
                {!! Form::select('PurchaseAccountID', $PurchaseAccountsOptions, $PurchaseAccountID, [
                    'class' => 'input_style',
                    'id' => 'PurchaseAccountID',
                    'required' => 'required',
                ]) !!}
            </div>
            <?php
            $Suppliers = json_decode($Suppliers, true);
            
            $options = ['0' => 'اختر المورد'];
            foreach ($Suppliers as $Supplier) {
                $options[$Supplier['SupplierID']] = $Supplier['SupplierName'];
            }
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اختر المورد', ['class' => 'input_label']) !!}
                {!! Form::select('SupplierID', $options, $Purchase->SupplierID, [
                    'class' => 'input_style SetSupllierName',
                    'id' => 'SupplierID',
                    'required' => 'required',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'ادخل اسم المورد', ['class' => 'input_label']) !!}
                {!! Form::text('SupplierName', $Purchase->SupplierName, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل اسم المورد',
                    'id' => 'SupplierName',
                    'required' => 'required',
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
                {!! Form::label('name', 'اختر المخزن', ['class' => 'input_label']) !!}
                {!! Form::select('StockID', $options, $Purchase->StockID, [
                    'class' => 'input_style',
                    'id' => 'StockID',
                    'required' => 'required',
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
        <button type="button" class="btn add_button AddRow" style="width: 100px;margin: 10px 0;"><i
                class="fas fa-plus"></i></button>
        <table class="table" id="ItemsTable">
            <tr>
                <th>-</th>
                <th width="40%">المنتج</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th>المجمل</th>
            </tr>
            <?php $i = 0; ?>
            @foreach ($PurchaseDetails as $RowItem)
                <?php $i++; ?>
                <tr id="Row{{ $i }}">
                    <td><button type='button' class='btn delete_button RemoveRow' id='RemoveButton{{ $i }}'
                            value='{{ $i }}' style="{{ count($PurchaseDetails) > 1 ? '' : 'display:none' }}"><i
                                class='fa-solid fa-trash-can'></i>
                        </button>
                    </td>
                    <td>
                        {!! Form::text('ItemName' . $i, $RowItem->item->ItemName, [
                            'class' => 'input_style getItems',
                            'id' => 'ItemName' . $i,
                            'placeholder' => 'اختر المنتج',
                            'autocomplete' => 'off',
                            'required' => 'required',
                        ]) !!}
                        <input type="hidden" name="ItemID{{ $i }}" value="{{ $RowItem->ItemID }}"
                            id="ItemID{{ $i }}">
                        <div class="SelectItem" id="SelectItem{{ $i }}">
                        </div>
                    </td>
                    <td>{!! Form::number('ItemQTY' . $i, $RowItem->ItemQTY, [
                        'class' => 'input_style Calculate',
                        'placeholder' => 'ادخل الكمية',
                        'id' => 'ItemQTY' . $i,
                        'required' => 'required',
                    ]) !!}</td>
                    <td>{!! Form::number('ItemPrice' . $i, $RowItem->ItemPrice, [
                        'class' => 'input_style Calculate',
                        'placeholder' => 'ادخل السعر',
                        'id' => 'ItemPrice' . $i,
                        'required' => 'required',
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
            <div class="col-md-3"><a href="/purchases"><button type="button" class="btn cancel_button">رجوع</button></a>
            </div>
        </div>
    </div>
    <script>
        function setItem(RowID, ItemID, ItemName) {
            $("#SelectItem" + RowID).css("display", "none")
            $("#ItemName" + RowID).val(ItemName)
            $("#ItemID" + RowID).val(ItemID)
        }
        $(document).on('input', '.getItems', function() {
            var RowID = $(this).attr('id').replace("ItemName", "")
            $("#ItemID" + RowID).val(0)
            var Keyword = $(this).val()
            if (Keyword) {
                $("#SelectItem" + RowID).empty()
                $("#SelectItem" + RowID).css("display", "block")
                var Items = {!! json_encode($Items) !!};
                var flag = false
                var ul = $("<ul></ul>")
                var CurrentItems = []
                for (var i = 1; i < $("#NumberOfItems").val(); i++) {
                    if ($("#ItemID" + i).val() != 0)
                        CurrentItems.push(parseInt($("#ItemID" + i).val()))
                }
                Items.forEach(function(item) {
                    if (!CurrentItems.includes(item.ItemID) && (item.ItemPartNumber.includes(Keyword) ||
                            item.ItemName.includes(Keyword))) {
                        ul.append("<li onclick='setItem(" + RowID + ", " + item.ItemID +
                            ", \"" + item.ItemName + "\")'>" + item.ItemName +
                            "</li>");
                        flag = true
                    }
                })
                if (flag) {
                    $("#SelectItem" + RowID).append(ul)
                } else {
                    $("#SelectItem" + RowID).append($(
                        "<div class = 'col-md-12 aler alert-daner'>لا توجد منتجات</div>"
                    ))
                }
            } else {
                $("#SelectItem" + RowID).css("display", "none")
            }
        });
        $(document).on('change', '.SetSupllierName', function() {
            $("#SupplierName").val($(this).find('option:selected').text())
        });

        $(document).on('click', '.AddRow', function() {
            $("#RemoveButton1").css("display", "inline")
            var myrowCount = $("#NumberOfItems").val()
            var Items = <?php echo json_encode($options); ?>;
            var table = $("#ItemsTable")
            myrowCount++
            $("#NumberOfItems").val(myrowCount)
            var tr = $('<tr id="Row' + myrowCount + '"></tr>')
            tr.append($("<td><button type='button' class='btn delete_button RemoveRow' id='RemoveButton" +
                myrowCount + "' value='" + myrowCount + "'>" +
                "<i class='fa-solid fa-trash-can'></i></button></td>"));

            tr.append($("<td><input class='input_style getItems' id='ItemName" + myrowCount +
                "' name='ItemName" + myrowCount +
                "' placeholder='اختر المنتج' required autocomplete='off'>" +
                "<input type='hidden' name='ItemID" + myrowCount + "' id='ItemID" + myrowCount + "'>" +
                "<div class='SelectItem' id='SelectItem" + myrowCount + "'>" +
                "</div></td>"));

            tr.append($(
                "<td><input type='number' value='0' id='ItemQTY" + myrowCount +
                "' name='ItemQTY" + myrowCount +
                "' placeholder='ادخل الكمية' class='input_style Calculate' value='0'  required></td>"
            ))
            tr.append($(
                "<td><input type='number' value='0' id='ItemPrice" + myrowCount +
                "' name='ItemPrice" + myrowCount +
                "' placeholder='ادخل السعر' class='input_style Calculate' value='0' required></td>"
            ))
            tr.append($("<td><label id='TotalRow" + myrowCount + "'>0</label></td>"))
            tr.appendTo(table)
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
                $("#ItemID" + i).attr("id", "ItemID" + myCurrentID).attr("name", "ItemID" + myCurrentID)

                $("#ItemName" + i).attr("id", "ItemName" + myCurrentID).attr("name", "ItemName" + myCurrentID)

                $("#SelectItem" + i).attr("id", "SelectItem" + myCurrentID)

                $("#ItemQTY" + i).attr("id", "ItemQTY" + myCurrentID).attr("name", "ItemQTY" + myCurrentID)


                $("#ItemPrice" + i).attr("id", "ItemPrice" + myCurrentID).attr("name", "ItemPrice" + myCurrentID)


                $("#TotalRow" + i).attr("id", "TotalRow" + myCurrentID).attr("name", "TotalRow" + myCurrentID)

                $("#RemoveButton" + i).attr("id", "RemoveButton" + myCurrentID).val(myCurrentID)

            }
            NumberOfItems--
            $("#NumberOfItems").val(NumberOfItems)
            if (NumberOfItems > 1)
                $("#RemoveButton1").css("display", "inline")
            else
                $("#RemoveButton1").css("display", "none")

        });
        $(document).on('change', '.SetSupllierName', function() {
            $("#SupplierName").val($(this).find('option:selected').text())
        });

        $(document).on('input', '.Calculate', function() {
            var NumberOfItems = $("#NumberOfItems").val()
            for (var i = 1; i <= NumberOfItems; i++) {
                if (!$.isNumeric($("#ItemQTY" + i).val()) || $("#ItemQTY" + i).val() < 0) {
                    $("#ItemQTY" + i).val(0);
                } else if (!$.isNumeric($("#ItemPrice" + i).val()) || $("#ItemPrice" + i).val() < 0) {
                    $("#ItemPrice" + i).val(0);
                }
                var QTY = $("#ItemQTY" + i).val();
                var ItemPrice = $("#ItemPrice" + i).val();
                $("#ItemQTY" + i).val(parseInt(QTY))
                $("#ItemPrice" + i).val(parseInt(ItemPrice))
                CalculateRow(i);
            }
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
            var flag = true; // Initialize flag to true

            // Reset existing error labels and remove error classes
            $(".error-label").remove();
            $(".error_input").removeClass("error_input");

            if ($("#SupplierID").val() == 0) {
                flag = false;
                $("#SupplierID").addClass("error_input");
                CreateErrorLabel("SupplierID", "الرجاء اختيار المورد");
            }
            if ($("#SupplierName").val() == "") {
                flag = false;
                $("#SupplierName").addClass("error_input");
                CreateErrorLabel("SupplierName", "الرجاء ادخال اسم المورد");
            }
            if ($("#StockID").val() == 0) {
                flag = false;
                $("#StockID").addClass("error_input");
                CreateErrorLabel("StockID", "الرجاء اختيار المخزن");
            }
            if ($("#NumberOfItems").val() == 0) {
                flag = false;
                CreateErrorLabel("NumberOfItems", "يجب أن تحتوي الفاتورة على منتج واحد على الأقل");
            }
            for (var i = 1; i <= $("#NumberOfItems").val(); i++) {
                if ($("#ItemID" + i).val() == 0) {
                    flag = false;
                    $("#ItemName" + i).addClass("error_input");
                    CreateErrorLabel("ItemID" + i, "الرجاء اختيار المنتج");
                }
                var ItemQTY = $("#ItemQTY" + i).val();
                if (isNaN(ItemQTY) || parseFloat(ItemQTY) <= 0) {
                    flag = false;
                    $("#ItemQTY" + i).addClass("error_input");
                    CreateErrorLabel("ItemQTY" + i, "الرجاء ادخال كمية صحيحة للمنتج");
                }
                var ItemPrice = $("#ItemPrice" + i).val();
                if (isNaN(ItemPrice) || parseFloat(ItemPrice) <= 0) {
                    flag = false;
                    $("#ItemPrice" + i).addClass("error_input");
                    CreateErrorLabel("ItemPrice" + i, "الرجاء ادخال سعر صحيح للمنتج");
                }
            }

            return flag; // Return the flag indicating form validity
        }
    </script>
@endsection
