@extends('layouts.app')
<style>
    .select2_style {
        padding: 15px;
        margin-bottom: 10px;
        font-weight: 900;
        color: #4d5961;
        border: none;
    }
</style>
@section('content')
    <div class="MainDiv" style="width:99%">
        <div class="col-md-12 Result" id = "Results"></div>
        {!! Form::open([
            'action' => 'DailyAccountingEntryController@store',
            'method' => 'post',
            'onsubmit' => 'return validateForm()',
        ]) !!}

        {!! Form::hidden('restrictionsNum', '1', ['id' => 'restrictionsNum']) !!}
        <div class="col-md-12" style="text-align:right;">
            <label class="MainLabel" style="font-size:25px;width:20%;"><b>بيان القيد</b></label>
            {!! Form::text('ResDetails', null, [
                'class' => 'input_style ReRight',
                'placeholder' => 'ادخل البيان',
                'id' => 'ResDetails',
            ]) !!}
        </div>
        <br>
        <table class ="table" id="restrictions">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اختر الحساب</th>
                    <th>البيان</th>
                    <th>منه/ له</th>
                    <th>المبلغ</th>
                    <th>ألقيمة المقابلة</th>
                </tr>
            </thead>
            <tr id="Row1">
            <tr id='Row1'>
                <td>
                    <button type='button' name='add' id='add' class='btn addItemRow'><i
                            class="fas fa-plus"></i></button>
                </td>
                <td>
                    <select class="select2_style select2" id="AccountID1" name="AccountID1">
                        <option value="0">اختر الحساب</option>
                        @foreach ($Accounts as $Account)
                            <option value="{{ $Account['AccountID'] }}">{{ $Account['AccountNumber'] }} ||
                                {{ $Account['AccountName'] }}</option>
                        @endforeach
                    </select>
                </td>
                <td>
                    <input type='text' id='details1' name='details1' class='input_style' placeholder='البيان' />
                </td>
                <td>
                    <select class='input_style' id='TransactionType1' name='TransactionType1'>
                        <option value='1'>منه</option>
                        <option value='2'>له</option>
                    </select>
                </td>
                <td>
                    <input type='number' name='amount1' id='amount1' min='0' class='input_style'
                        placeholder='المبلغ' value='0' autocomplete="off">
                </td>
                <td>
                    <input type='number' name='val1' id='val1' min='1' class='input_style'
                        placeholder='القيمة المقابلة' value='1' autocomplete="off">
                </td>
            </tr>

            </tr>
        </table>
        <br>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3" style="text-align:center;">
                {!! Form::submit('حفظ', ['class' => 'btn save_button', 'style' => 'width:100%;padding:15px;']) !!}
            </div>
            <div class="col-md-3">
                <a href="AccountManagment/DailyAccountingEntries">
                    <button class="btn cancel_button" style="width:100%;padding:15px;">رجوع</button>
                </a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        $(document).on('change', '.ReRight', function() {
            var Comment = $(this).val()

            for (var i = 1; i <= $("#restrictionsNum").val(); i++) {
                $("#details" + i).val(Comment)
            }
        });
        $(document).on('click', '.addItemRow', function() {
            var MyAccounts = <?php echo json_encode($Accounts); ?>;
            var table = $("#restrictions")
            var Comment = $("#ResDetails").val()
            var myrowCount = $("#restrictionsNum").val()
            myrowCount++
            $("#restrictionsNum").val(myrowCount)
            var tr = $('<tr id = "Row' + myrowCount + '"></tr>')
            tr.append($('<td><button type = "button" class = "btn " id = "remove' + myrowCount +
                '" onclick = "removeItemRow(' + myrowCount +
                ')"><i class="fas fa-trash-alt fa-2x"></i></button></td>'))
            var Select = $("<select class='select2_style select2' id='AccountID" + myrowCount +
                "'  name='AccountID" + myrowCount + "'></select>")
            Select.append($("<option value = '0'>اختر الحساب</option>"))
            MyAccounts.forEach(function(account) {
                Select.append($("<option value = '" + account["AccountID"] + "'>" + account[
                    "AccountNumber"] + "||" + account["AccountName"] + "</option>"))
            });
            var td = $("<td></td>")
            td.append(Select)
            tr.append(td)
            tr.append($('<td><input type = "text" name = "details' + myrowCount + '" id = "details' + myrowCount +
                '" value = "' + Comment +
                '" class = "input_style" placeholder = "البيان" required = "required" ></td>'))
            tr.append($("<td><select class='input_style' id='TransactionType" + myrowCount +
                "' name='TransactionType" + myrowCount +
                "'><option value='1'>منه</option><option value='2'>له</option></select></td>"))
            tr.append($("<td><input type='number' name='amount" + myrowCount + "' id='amount" + myrowCount +
                "' min='0' class='input_style'placeholder='المبلغ' value='0' autocomplete='off'></td>"))
            tr.append($('<td><input type = "text" value = "1" min= "1" name = "val' + myrowCount + '" id = "val' +
                myrowCount +
                '" class = "input_style" placeholder = "ألقيمة المقابلة" required = "required" ></td>'))
            tr.appendTo(table)
            addSelect2();
        });

        function removeItemRow(ItemId) {
            $("#Row" + ItemId).remove()
            var restrictionsNum = $("#restrictionsNum").val()
            ItemId++
            for (i = ItemId; i <= restrictionsNum; i++) {
                myCurrentID = i
                myCurrentID--

                document.getElementById('Row' + i).id = 'Row' + myCurrentID
                $("#AccountID" + i).attr("id", "AccountID" + myCurrentID)
                $("#AccountID" + myCurrentID).attr("name", "AccountID" + myCurrentID)

                $("#details" + i).attr("id", "details" + myCurrentID)
                $("#details" + myCurrentID).attr("name", "details" + myCurrentID)

                $("#TransactionType" + i).attr("id", "TransactionType" + myCurrentID)
                $("#TransactionType" + myCurrentID).attr("name", "TransactionType" + myCurrentID)

                $("#amount" + i).attr("id", "amount" + myCurrentID)
                $("#amount" + myCurrentID).attr("name", "amount" + myCurrentID)

                $("#val" + i).attr("id", "val" + myCurrentID)
                $("#val" + myCurrentID).attr("name", "val" + myCurrentID)

                $("#add" + i).attr("id", "add" + myCurrentID)
                $("#add" + myCurrentID).attr("onclick", 'SetModelId(' + myCurrentID + ')')

                $("#remove" + i).attr("id", "remove" + myCurrentID)
                $("#remove" + myCurrentID).attr("onclick", 'removeItemRow(' + myCurrentID + ')')

            }
            restrictionsNum--
            $("#restrictionsNum").val(restrictionsNum)

        }

        function validateForm() {
            var myrowCount = $("#restrictionsNum").val()
            var amount = 0
            var flag = true
            if (myrowCount > 1) {
                for (var i = 1; i <= myrowCount; i++) {
                    if ($('#TransactionType' + i).val() == 1)
                        amount = amount - parseInt($('#amount' + i).val())
                    else
                        amount = amount + parseInt($('#amount' + i).val())
                    if ($('#AccountID' + i).val() == "0") {
                        flag = false
                        $("#Results").css("color", "red")
                        $("#Results").html("الرجاء تحديد الحساب في القيد رقم " + i)
                        break
                    }
                }

                if (amount != 0) {
                    $("#Results").css("color", "red")
                    $("#Results").html("خطاء في الموازنة")
                    flag = false
                }
            } else {
                $("#Results").css("color", "red")
                $("#Results").html("يجب ان يحتوي القيد على حسابين على الاقل")
                flag = false
            }
            return flag
        }
    </script>
@endsection
