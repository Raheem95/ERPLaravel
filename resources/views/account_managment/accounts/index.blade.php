<title>ادارة الحسابات</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/Accounts/index.blade.php -->

    <h1> الحسابات</h1>
    <div class="modal fade" id="ModelAddSubAccount" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body ">
                    {!! Form::open(['action' => 'AccountController@store', 'method' => 'post']) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'اسم الحساب', ['class' => 'input_label']) !!}
                        {!! Form::text('AccountName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم الحساب']) !!}
                        {!! Form::hidden('AccountParent', null, ['id' => 'AccountParent']) !!}
                        {!! Form::hidden('AccountTypeID', null, ['id' => 'AccountTypeID']) !!}
                        {!! Form::hidden('CurrencyID', null, ['id' => 'CurrencyID']) !!}
                    </div>
                    <!-- Add more form fields as needed -->
                    {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <a style="width: 20%;" href="Accounts/create" class="btn add_button mb-3">اضافة حساب</a>
    <div class='row MainDiv'>
        <div class='col-md-8'>
            @if (count($Accounts) > 0)
                <div id = "MyAccountsDiv">
                </div>
                <br>
            @else
                <div class="alert alert-danger Result"> لا توجد انواع حسابات</div>
            @endif
            <!-- Rest of your HTML content goes here -->
        </div>
        <div class="col-md-4">
            <table class="table">
                <thead>
                    <th>نوع الحساب</th>
                    <th>العملة</th>
                    <th>المجمل</th>
                </thead>
                <?php $TotalBalance = 0;
                $CurrencyID = 0;
                $CurrencyName = 0; ?>
                @foreach ($SummaryBalance as $AccountSummary)
                    @if ($CurrencyID != $AccountSummary->currency->CurrencyID)
                        @if ($CurrencyID != 0)
                            <tr>
                                <th colspan="2"> المجمل لعملة {{ $CurrencyName }}</th>
                                <th>{{ number_format($TotalBalance, 2) }}</th>
                            </tr>
                        @endif
                        <?php $TotalBalance = 0;
                        $CurrencyID = $AccountSummary->currency->CurrencyID;
                        $CurrencyName = $AccountSummary->currency->CurrencyName; ?>
                    @endif
                    <tr>
                        <td>{{ $AccountSummary->accountType->AccountTypeName }}</td>
                        <td>{{ $AccountSummary->currency->CurrencyName }}</td>
                        <td>{{ number_format($AccountSummary->TotalBalance * $AccountSummary->accountType->AccountTypeSource, 2) }}
                        </td>
                    </tr>
                    <?php
                    $TotalBalance += $AccountSummary->TotalBalance * $AccountSummary->accountType->AccountTypeSource;
                    ?>
                @endforeach
                <tr>
                    <th colspan="2"> المجمل لعملة {{ $CurrencyName }}</th>
                    <th>{{ number_format($TotalBalance, 2) }}</th>
                </tr>
            </table>
        </div>
    </div>

    <script>
        function accounts() {

            var MyCurrency = {!! json_encode($Currencies) !!};
            MyCurrency.forEach(function(currency) {
                var CurrencyID = currency.CurrencyID
                var CurrencyName = currency.CurrencyName
                $("#MyAccountsDiv").append($(" <div class = 'row'>"

                    +
                    "<div class = 'col-md-4 TableHeader '><button style = 'margin-left:5px; font-size:30px' class = 'btn showhideC fa-2x fa-regular fa-square-plus' value = '" +
                    CurrencyID + "'  id = 'C" + CurrencyID + "' ></button><label id = " + CurrencyName +
                    ">" +
                    CurrencyName + "</label></div>" +
                    "</div>" +
                    "<div id = 'CA" + CurrencyID + "' style= 'margin-right:50px' >" +
                    "<div class = 'row'>" +
                    "<div class = 'col-md-4 TableHeader'>اسم الحساب</div>" +
                    "<div class = 'col-md-2 TableHeader Centeralized'>رقم الحساب</div>" +
                    "<div class = 'col-md-3 TableHeader Centeralized'>الرصيد</div>" +
                    "<div class = 'col-md-1 TableHeader Centeralized'>كشف حساب</div>" +
                    "<div class = 'col-md-1 TableHeader Centeralized'>اضافة حساب تابع</div>" +
                    "</div></div>"))
                $("#CA" + CurrencyID).hide()
            });
            var Myaccounts = {!! json_encode($Accounts) !!};
            Myaccounts.forEach(function(Account) {
                var formatter = new Intl.NumberFormat();
                var div = $("#CA" + Account.CurrencyID)
                var divID = Account.AccountID
                var AccountParent = Account.AccountParent
                var Name = Account.AccountName
                var AccountNumber = Account.AccountNumber
                var Balance = formatter.format(Account.Balance)
                var AccountTypeID = Account.AccountTypeID
                var CurrencyID = Account.CurrencyID
                if (Account.AccountParent != 0) {
                    div = $("#" + AccountParent)
                }
                var PlusButton = ""
                if (Account.lastChildNum != 0)
                    PlusButton =
                    "<button style = 'margin-left:5px; font-size:30px' class = 'btn showhide fa-2x fa-regular fa-square-plus' value = '" +
                    divID + "'  id = 'I" + divID + "' ></button>"
                div.append($("<div class = 'row'>" +
                    "<div class = 'col-md-4 TableHeader '>" +
                    PlusButton +
                    "<label id = " + Name + ">" + Name + "</label>" +
                    "</div>" +
                    "<div class = 'col-md-2 normalCell '>" + "<label id = 'accountNumberLabel" + divID +
                    "'>" + AccountNumber + "</label>" + "</div>" +
                    "<div class = 'col-md-3 normalCell '><label>" + Balance + "</label></div>" +
                    "<input type = 'hidden' id = 'AccountTypeID" + divID + "' value = '" + AccountTypeID +
                    "'>" +
                    "<input type = 'hidden' id = 'CurrencyID" + divID + "' value = '" + CurrencyID + "'>" +
                    "<div class = 'col-md-1 normalCell '><button class = 'btn search_button setID ' value = '" +
                    divID +
                    "' data-toggle='modal' data-target='#AccountReport'><i class='fa-regular fa-newspaper fa-2x'></i></button></div>" +
                    "<div class = 'col-md-1 normalCell '><button class = 'btn save_button subaccount' value = '" +
                    divID +
                    "' data-toggle='modal' data-target='#ModelAddSubAccount'><i class='fa-solid fa-circle-plus fa-2x'></i></button></div>" +
                    "</div>" +
                    "<div id = '" + divID + "' style= 'margin-right:50px;display:none;' ></div>"))

            });
        }
        accounts();
        $(document).on('click', '.showhide', function() {
            var divID = $(this).val()
            if ($("#I" + divID).hasClass("fa-2x fa-regular fa-square-plus")) {
                $("#I" + divID).removeClass("fa-2x fa-regular fa-square-plus").addClass(
                    "fa-2x fa-regular fa-square-minus")
                $("#" + divID).show();
            } else {
                $("#I" + divID).removeClass("fa-2x fa-regular fa-square-minus").addClass(
                    "fa-2x fa-regular fa-square-plus")
                $("#" + divID).hide();
            }
        });
        $(document).on('click', '.showhideC', function() {
            var divID = $(this).val()
            if ($("#C" + divID).hasClass("fa-2x fa-regular fa-square-plus")) {
                $("#C" + divID).removeClass("fa-2x fa-regular fa-square-plus").addClass(
                    "fa-2x fa-regular fa-square-minus")
                $("#CA" + divID).show();
            } else {
                $("#C" + divID).removeClass("fa-2x fa-regular fa-square-minus").addClass(
                    "fa-2x fa-regular fa-square-plus")
                $("#CA" + divID).hide();
            }
        });

        $(document).on('click', '.subaccount', function() {
            var AccountID = $(this).val()
            $("#AccountParent").val(AccountID);
            $("#AccountTypeID").val($("#AccountTypeID" + AccountID).val());
            $("#CurrencyID").val($("#CurrencyID" + AccountID).val());
        });
    </script>
@endsection
