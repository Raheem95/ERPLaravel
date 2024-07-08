@extends('layouts.app')
<style>
    .SelectAccount {

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



    .SelectAccount li {
        list-style: none;
        padding: 5px;
        border-bottom: 1px solid #3498db;
        text-align: right;
    }

    .SelectAccount li:hover {
        background: #3498db;
        color: white;
        font-weight: 600;
    }
</style>
@section('content')
    <div class="MainDiv" style="width:99%">
        <div class=" input_label">
            <h1>قيد يومية</h1>
        </div>
        <div class="col-md-12 alert Result" id = "Results"></div>
        {!! Form::open([
            'action' => 'DailyAccountingEntryController@store',
            'method' => 'post',
            'onsubmit' => 'return validateForm()',
        ]) !!}

        {!! Form::hidden('RestrictionsRowsNumber', '1', ['id' => 'RestrictionsRowsNumber']) !!}
        <div class="col-md-12" style="text-align:right;">
            <label class="input_label"><b>بيان القيد</b></label>
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
                    <input type="text" class="input_style getAccount" id="AccountName1" name="AccountName1"
                        placeholder="اختر احساب" autocomplete = "off">
                    <div class="SelectAccount" id="SelectAccount1"></div>
                    <input type="hidden" name="AccountID1" id="AccountID1">
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
                <a href="/AccountManagment/DailyAccountingEntries">
                    <button type="button" class="btn cancel_button" style="width:100%;padding:15px;">رجوع</button>
                </a>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <script>
        $(document).on('input', '.getAccount', function() {
            var RowID = $(this).attr('id').replace("AccountName", "")
            $("#AccountID" + RowID).val(0)
            var Keyword = $(this).val()
            if (Keyword) {
                $("#SelectAccount" + RowID).empty()
                $("#SelectAccount" + RowID).css("display", "block")
                var Accounts = {!! json_encode($Accounts) !!};
                var flag = false
                var ul = $("<ul></ul>")
                var CurrentAccounts = []
                for (var i = 1; i < $("#RestrictionsRowsNumber").val(); i++) {
                    if ($("#AccountID" + i).val() != 0)
                        CurrentAccounts.push(parseInt($("#AccountID" + i).val()))
                }
                Accounts.forEach(function(Account) {
                    var Refrance = Account.AccountName + " || " + Account.AccountNumber
                    if (!CurrentAccounts.includes(Account.AccountID) && Refrance.includes(Keyword)) {
                        ul.append("<li onclick='setAccount(" + RowID + ", " + Account.AccountID +
                            ", \"" + Refrance + "\")'>" + Refrance + "</li>");
                        flag = true
                    }
                })
                if (flag) {
                    $("#SelectAccount" + RowID).append(ul)
                } else {
                    $("#SelectAccount" + RowID).append($(
                        "<div class = 'col-md-12 aler alert-daner'>لا توجد حساب</div>"
                    ))
                }
            } else {
                $("#SelectAccount" + RowID).css("display", "none")
            }
        });

        function setAccount(RowID, AccountID, AccountName) {
            $("#SelectAccount" + RowID).css("display", "none")
            $("#AccountName" + RowID).val(AccountName)
            $("#AccountID" + RowID).val(AccountID)
        }
        $(document).on('change', '.ReRight', function() {
            var Comment = $(this).val()

            for (var i = 1; i <= $("#RestrictionsRowsNumber").val(); i++) {
                $("#details" + i).val(Comment)
            }
        });
        $(document).on('click', '.addItemRow', function() {
            var MyAccounts = <?php echo json_encode($Accounts); ?>;
            var table = $("#restrictions")
            var Comment = $("#ResDetails").val()
            var myrowCount = $("#RestrictionsRowsNumber").val()
            myrowCount++
            $("#RestrictionsRowsNumber").val(myrowCount)
            var tr = $('<tr id = "Row' + myrowCount + '"></tr>')
            tr.append($('<td><button type = "button" class = "btn " id = "remove' + myrowCount +
                '" onclick = "removeItemRow(' + myrowCount +
                ')"><i class="fas fa-trash-alt fa-2x"></i></button></td>'))
            tr.append($('<td></td>').html(
                '<input type="text" class="input_style getAccount" id="AccountName' + myrowCount +
                '" name="AccountName' + myrowCount + '" placeholder="اختر احساب" autocomplete = "off">' +
                '<div class="SelectAccount" id="SelectAccount' + myrowCount + '"></div>' +
                '<input type="hidden" name="AccountID' + myrowCount + '" id="AccountID' + myrowCount + '">'
            ));
            tr.append($('<td><input type = "text" name = "details' + myrowCount + '" id = "details' + myrowCount +
                '" value = "' + Comment +
                '" class = "input_style" placeholder = "البيان"></td>'))
            tr.append($("<td><select class='input_style' id='TransactionType" + myrowCount +
                "' name='TransactionType" + myrowCount +
                "'><option value='1'>منه</option><option value='2'>له</option></select></td>"))
            tr.append($("<td><input type='number' name='amount" + myrowCount + "' id='amount" + myrowCount +
                "' min='0' class='input_style'placeholder='المبلغ' value='0' autocomplete='off'></td>"))
            tr.append($('<td><input type = "text" value = "1" min= "1" name = "val' + myrowCount + '" id = "val' +
                myrowCount +
                '" class = "input_style" placeholder = "ألقيمة المقابلة" required = "required" ></td>'))
            tr.appendTo(table)
        });

        function removeItemRow(ItemId) {
            $("#Row" + ItemId).remove()
            var RestrictionsRowsNumber = $("#RestrictionsRowsNumber").val()
            ItemId++
            for (i = ItemId; i <= RestrictionsRowsNumber; i++) {
                myCurrentID = i
                myCurrentID--

                document.getElementById('Row' + i).id = 'Row' + myCurrentID
                $("#AccountID" + i).attr("id", "AccountID" + myCurrentID).attr("name", "AccountID" + myCurrentID)
                $("#AccountName" + i).attr("id", "AccountName" + myCurrentID).attr("name", "AccountName" + myCurrentID)
                $("#SelectAccount" + i).attr("id", "SelectAccount" + myCurrentID)
                $("#details" + i).attr("id", "details" + myCurrentID).attr("name", "details" + myCurrentID)
                $("#TransactionType" + i).attr("id", "TransactionType" + myCurrentID).attr("name", "TransactionType" +
                    myCurrentID)
                $("#amount" + i).attr("id", "amount" + myCurrentID).attr("name", "amount" + myCurrentID)
                $("#val" + i).attr("id", "val" + myCurrentID).attr("name", "val" + myCurrentID)
                $("#remove" + i).attr("id", "remove" + myCurrentID).attr("onclick", 'removeItemRow(' + myCurrentID + ')')
            }
            RestrictionsRowsNumber--
            $("#RestrictionsRowsNumber").val(RestrictionsRowsNumber)

        }

        function validateForm() {
            var RestrictionsRowsNumber = $("#RestrictionsRowsNumber").val()
            var amount = 0
            $(".error-label").remove();
            $(".error_input").removeClass("error_input");
            $("#Results").html("").removeClass("alert-danger");
            var flag = true
            if (RestrictionsRowsNumber <= 1) {
                $("#Results").addClass("alert-danger")
                $("#Results").html("يجب ان يحتوي القيد على حسابين على الاقل")
                flag = false
            }

            if ($('#ResDetails').val() == "") {
                $("#ResDetails").addClass("error_input");
                CreateErrorLabel("ResDetails", "الرجاء ادخال تعليق ")
                flag = false
            }
            for (var i = 1; i <= RestrictionsRowsNumber; i++) {
                if ($('#TransactionType' + i).val() == 1)
                    amount = amount - parseInt($('#amount' + i).val())
                else
                    amount = amount + parseInt($('#amount' + i).val())
                if ($('#AccountID' + i).val() == 0) {
                    $("#AccountName" + i).addClass("error_input");
                    CreateErrorLabel("AccountID" + i, "الرجاء تحديد الحساب")
                    flag = false
                }
                if ($('#amount' + i).val() <= 0) {
                    $("#amount" + i).addClass("error_input");
                    CreateErrorLabel("amount" + i, "الرجاء ادخال قيمة صحيحة")
                    flag = false
                }
                if ($('#details' + i).val() == "") {
                    $("#details" + i).addClass("error_input");
                    CreateErrorLabel("details" + i, "الرجاء ادخال تعليق ")
                    flag = false
                }
            }
            if (amount != 0) {
                $("#Results").addClass("alert-danger")
                $("#Results").html("خطاء في الموازنة")
                flag = false
            }
            return flag
        }
    </script>
@endsection
