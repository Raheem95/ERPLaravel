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
        <div class=" input_label">
            <h1>تحويل مخزني</h1>
        </div>
        <div class="col-md-12 Result" id = "Results"></div>

        {!! Form::open([
            'action' => ['StockTransfareController@update', $Transfare->TransfareID],
            'method' => 'post',
            'onsubmit' => 'return validateForm()',
            'style' => 'margin-right:50px;',
        ]) !!}
        <div class = "row">
            <?php
            $Stocks = json_decode($Stocks, true);
            
            $options = ['0' => 'اختر المخزن'];
            foreach ($Stocks as $Stock) {
                $options[$Stock['StockID']] = $Stock['StockName'];
            }
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'من مخزن', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('FromStockID', $options, $Transfare->FromStockID, [
                    'class' => 'input_style',
                    'id' => 'FromStockID',
                    'onchange' => 'GetAllItemsAvailableQTY()',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الى مخزن', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('ToStockID', $options, $Transfare->ToStockID, [
                    'class' => 'input_style',
                    'id' => 'ToStockID',
                ]) !!}
            </div>

            <div class="form-group col-md-12">
                {!! Form::label('name', 'التعليق', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('Comment', $Transfare->Comment, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل  التعليق',
                ]) !!}
            </div>
        </div>

        {!! Form::hidden('NumberOfItems', count($TransfareDetails), [
            'id' => 'NumberOfItems',
        ]) !!}
        <div class="col-md-2" style="float: right;margin:10px;">
            <button type="button" class="btn add_button AddRow"><i class="fas fa-plus"></i></button>
        </div>
        <table class = "table" id = "ItemsTable">
            <tr>
                <th>-</th>
                <th width="30%">المنتج</th>
                <th>الكمية المتوفرة</th>
                <th>الكمية</th>
            </tr>
            <?php
            $i = 0;
            $Items = json_decode($Items, true);
            $options = collect(['0' => 'اختر المنتج']); // Creating a collection with default option
            foreach ($Items as $item) {
                $options[$item['ItemID']] = $item['ItemName'];
            } ?>
            @foreach ($TransfareDetails as $Details)
                <?php $i++; ?>
                <tr id = "Row{{ $i }}">
                    <td>
                        <button style="display:none" type='button' class='btn delete_button RemoveRow'
                            id='RemoveButton{{ $i }}'value='{{ $i }}'><i
                                class='fa-solid fa-trash-can'></i></button>

                    </td>
                    <td>

                        {!! Form::select('ItemID' . $i, $options, $Details->ItemID, [
                            'class' => 'input_style GetItemDetails',
                            'id' => 'ItemID' . $i,
                        ]) !!}
                    </td>
                    <td><label id='AvailableQTY{{ $i }}'>0</label></td>
                    <td>{!! Form::number('ItemQTY' . $i, $Details->ItemQTY, [
                        'class' => 'input_style CheckQTY',
                        'placeholder' => 'ادخل الكمية ',
                        'id' => 'ItemQTY' . $i,
                    ]) !!}
                    </td>
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
            <div class="col-md-3">
                <a href = "/Transfare"><button type="button" class="btn cancel_button">رجوع</button></a>
            </div>
        </div>
    </div>
    <script>
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
                "' placeholder = 'ادخل الكمية' class = 'input_style CheckQTY' value = '0'></td>"
            ))
            tr.appendTo(table)
            if (myrowCount > 1)
                $("#RemoveButton1").css("display", "contents")
            else
                $("#RemoveButton1").css("display", "none")
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

                $("#AvailableQTY" + i).attr("id", "AvailableQTY" + myCurrentID)

                $("#RemoveButton" + i).attr("id", "RemoveButton" + myCurrentID)
                $("#RemoveButton" + myCurrentID).val(myCurrentID)

            }
            NumberOfItems--
            $("#NumberOfItems").val(NumberOfItems)
            if (NumberOfItems > 1)
                $("#RemoveButton1").css("display", "contents")
            else
                $("#RemoveButton1").css("display", "none")
        });
        $(document).on('change', '.GetItemDetails', function() {
            var ItemID = $(this).val()
            var StockID = $("#FromStockID").val()
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

        function validateForm() {
            var flag = true
            var Result = "";
            if ($("#FromStockID").val() == $("#ToStockID").val()) {
                flag = false;
                Result = Result + "<div class = 'alert alert-danger Result'> لا يمكن التحويل لنفس المخزن</div>"
            }
            if ($("#FromStockID").val() == 0) {
                flag = false;
                Result = Result + "<div class = 'alert alert-danger Result'> الرجاء اختيار المخزن المحول منه </div>"
            }
            if ($("#ToStockID").val() == 0) {
                flag = false;
                Result = Result + "<div class = 'alert alert-danger Result'> الرجاء اختيار المخزن المحول اليه </div>"
            }
            if ($("#NumberOfItems").val() == 0) {
                flag = false;
                Result = Result +
                    "<div class = 'alert alert-danger Result'> يجب ان تحتوي الفاتورة على منتج واحد على الاقل </div>"
            }
            for (var i = 1; i <= $("#NumberOfItems").val(); i++) {
                if ($("#ItemID" + i).val() == 0) {
                    flag = false;
                    Result = Result + "<div class = 'alert alert-danger Result'> الرجاء اختيار المنتج رقم " + i +
                        "</div>"
                }
                var ItemQTY = $("#ItemQTY" + i).val();
                if (isNaN(ItemQTY) || parseFloat(ItemQTY) <= 0) {
                    flag = false;
                    Result = Result +
                        "<div class = 'alert alert-danger Result'> الرجاء ادخال كمية صحيحة للمنتج رقم " + i + "</div>"
                }
                if (parseFloat($("#ItemQTY" + i).val()) > parseFloat($("#AvailableQTY" + i).html())) {
                    flag = false;
                    Result = Result +
                        "<div class = 'alert alert-danger Result'> الكمية غير متوفرة للمنتج رقم " + i + "</div>"
                }
            }
            document.getElementById('Results').scrollIntoView();
            $("#Results").html(Result)
            return flag;
        }

        function GetAllItemsAvailableQTY() {
            var NumberOfItems = $("#NumberOfItems").val()
            var StockID = $("#StockID").val()
            for (var i = 1; i <= NumberOfItems; i++) {
                (function(index) {
                    var ItemID = $("#ItemID" + index).val()
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
                            $("#AvailableQTY" + index).html(result.AvailableQTY);
                            if (parseFloat($("#ItemQTY" + index).val()) > parseFloat(result.AvailableQTY))
                                $("#ItemQTY" + index).removeClass("right_input_style").addClass(
                                    "wrong_input_style")
                            else
                                $("#ItemQTY" + index).removeClass("wrong_input_style").addClass(
                                    "right_input_style")
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                        }
                    });
                })(i);
            }

        }

        function GetAllItemsAvailableQTY() {
            var NumberOfItems = $("#NumberOfItems").val()
            var StockID = $("#FromStockID").val()
            for (var i = 1; i <= NumberOfItems; i++) {
                (function(index) {
                    var ItemID = $("#ItemID" + index).val()
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
                            $("#AvailableQTY" + index).html(result.AvailableQTY);
                            if (parseFloat($("#ItemQTY" + index).val()) > parseFloat(result.AvailableQTY))
                                $("#ItemQTY" + index).removeClass("right_input_style").addClass(
                                    "wrong_input_style")
                            else
                                $("#ItemQTY" + index).removeClass("wrong_input_style").addClass(
                                    "right_input_style")
                        },
                        error: function(xhr, status, error) {
                            // Handle error
                        }
                    });
                })(i);
            }

        }
        GetAllItemsAvailableQTY();
    </script>
@endsection
