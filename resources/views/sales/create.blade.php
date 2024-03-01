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
            <h1>فاتورة مبيعات</h1>
        </div>
        <div class="col-md-12 Result" id = "Results"></div>

        {!! Form::open([
            'action' => 'SaleController@store',
            'method' => 'post',
            'onsubmit' => 'return validateForm()',
            'style' => 'margin-right:50px;',
        ]) !!}
        <div class = "row">

            <?php
            $Customers = json_decode($Customers, true);
            $options = ['0' => 'اختر العميل']; // Initialize with default option
            foreach ($Customers as $customer) {
                $options[$customer['CustomerID']] = $customer['CustomerName'];
            }
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اختر العميل', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('CustomerID', $options, null, [
                    'class' => 'input_style SetCustomerName',
                    'id' => 'CustomerID',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'ادخل اسم العميل', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('CustomerName', null, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل اسم العميل',
                    'id' => 'CustomerName',
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
                {!! Form::select('StockID', $options, null, [
                    'class' => 'input_style',
                    'id' => 'StockID',
                ]) !!}
            </div>
        </div>
        <div class="PriceDiv">
            {!! Form::label('', 'مجمل الفاتورة', ['class' => 'PriceLabel']) !!}
            {!! Form::label('0', '0', ['class' => 'PriceLabel', 'id' => 'TotalSaleText']) !!}
            {!! Form::hidden('TotalSale', 0, [
                'id' => 'TotalSale',
            ]) !!}
        </div>
        {!! Form::hidden('NumberOfItems', 1, [
            'id' => 'NumberOfItems',
        ]) !!}
        <table class = "table" id = "ItemsTable">
            <tr>
                <th>-</th>
                <th width="30%">المنتج</th>
                <th>الكمية المتوفرة</th>
                <th>الكمية</th>
                <th>السعر</th>
                <th width="10%">المجمل</th>
            </tr>
            <tr id = "Row1">
                <td><button type="button" class = 'btn add_button AddRow'><i class="fas fa-plus"></button></td>
                <td>
                    <?php
                    $Items = json_decode($Items, true);
                    $options = collect(['0' => 'اختر المنتج']); // Creating a collection with default option
                    foreach ($Items as $item) {
                        $options[$item['ItemID']] = $item['ItemName'];
                    } ?>
                    {!! Form::select('ItemID1', $options, null, [
                        'class' => 'input_style GetItemDetails',
                        'id' => 'ItemID1',
                    ]) !!}
                </td>
                <td><label id="AvailableQTY1">0</label></td>
                <td>{!! Form::number('ItemQTY1', 0, [
                    'class' => 'input_style CheckQTY',
                    'placeholder' => 'ادخل الكمية ',
                    'id' => 'ItemQTY1',
                    'oninput' => 'CalculateRow(1)',
                ]) !!}</td>
                <td>{!! Form::number('ItemPrice1', 0, [
                    'class' => 'input_style ',
                    'placeholder' => 'ادخل السعر',
                    'id' => 'ItemPrice1',
                    'oninput' => 'CalculateRow(1)',
                ]) !!}</td>
                <td><label id = "TotalRow1">0</label></td>

            </tr>
        </table>
        <!-- Add more form fields as needed -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">{!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}</div>
            <div class="col-md-3"><a href = "/sales"><button type="button" class="btn cancel_button">رجوع</button></a>
            </div>
        </div>
    </div>
    {!! Form::close() !!}
    </div>
    <script>
        $(document).on('change', '.SetCustomerName', function() {
            $("#CustomerName").val($(this).find('option:selected').text())
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

            var Select = $("<select class='input_style GetItemDetails' id='ItemID" + myrowCount +
                "'  name='ItemID" + myrowCount + "'></select>");
            $.each(Items, function(key, value) {
                Select.append($("<option value='" + key + "'>" + value + "</option>"));
            });
            var td = $("<td></td>")
            td.append(Select)
            tr.append(td)
            tr.append($("<td><label id='AvailableQTY" + myrowCount + "'>0</label></td>"))
            tr.append($(
                "<td><input type = 'number' value = '0' id = 'ItemQTY" + myrowCount +
                "' name = 'ItemQTY" + myrowCount +
                "' placeholder = 'ادخل الكمية' class = 'input_style CheckQTY' value = '0' oninput = (CalculateRow(" +
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



                $("#AvailableQTY" + i).attr("id", "AvailableQTY" + myCurrentID)

                $("#ItemPrice" + i).attr("id", "ItemPrice" + myCurrentID)
                $("#ItemPrice" + myCurrentID).attr("name", "ItemPrice" + myCurrentID)

                $("#TotalRow" + i).attr("id", "TotalRow" + myCurrentID)
                $("#TotalRow" + myCurrentID).attr("name", "TotalRow" + myCurrentID)

                $("#RemoveButton" + i).attr("id", "RemoveButton" + myCurrentID)
                $("#RemoveButton" + myCurrentID).val(myCurrentID)

            }
            NumberOfItems--
            $("#NumberOfItems").val(NumberOfItems)
        });
        $(document).on('change', '.GetItemDetails', function() {
            var ItemID = $(this).val()
            var StockID = $("#StockID").val()
            var RowID = $(this).attr("id").replace("ItemID", "");
            if (StockID > 0) {
                $("#Results").html("")
                var form_data = new FormData();
                form_data.append('ItemID', ItemID);
                form_data.append('StockID', StockID);

                $.ajax({
                    url: "{{ route('get_item_details') }}",
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,
                    type: 'post',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr(
                            'content'));
                    },
                    success: function(result) {
                        $("#ItemPrice" + RowID).val(result.SalesPrice)
                        $("#AvailableQTY" + RowID).html(result.AvailableQTY)
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                    }
                });
            } else {
                $("#Results").html("<div class = 'alert alert-danger Result'> الرجاء تحديد المخزن </div>")
                $(this).val(0)
            }
        });

        $(document).on('change', '.CheckQTY', function() {
            var ItemQTY = $(this).val()
            var RowID = $(this).attr("id").replace("ItemQTY", "");
            if (parseFloat(ItemQTY) > parseFloat($("#AvailableQTY" + RowID).html()))
                $(this).removeClass("right_input_style").addClass("wrong_input_style")
            else
                $(this).removeClass("wrong_input_style").addClass("right_input_style")

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
            $("#TotalSaleText").html(Total.toLocaleString());

            $("#TotalSale").val(Total)
        }

        function validateForm() {
            var flag = true
            var Result = "";
            if ($("#CustomerID").val() == 0) {
                flag = false;
                var Result = Result + "<div class = 'alert alert-danger Result'> الرجاء اختيار العميل </div>"
            }
            if ($("#CustomerName").val() == "") {
                flag = false;
                var Result = Result + "<div class = 'alert alert-danger Result'> الرجاء ادخال اسم العميل </div>"
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

            if (flag) {
                var flag2 = true;
                for (var i = 1; i <= $("#NumberOfItems").val(); i++) {
                    if (parseFloat($("#ItemQTY" + i).val()) > parseFloat($("#AvailableQTY" + i).html()))
                        flag2 = false;
                }
                if (!flag2)
                    if (!confirm("توجد منتجات غير متوقرة الكمية هل تريد المتابعة ؟"))
                        flag = false;
            }
            document.getElementById('Results').scrollIntoView();
            $("#Results").html(Result)
            return flag;
        }
    </script>
@endsection
