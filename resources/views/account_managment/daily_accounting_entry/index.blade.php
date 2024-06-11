<title> قيود اليومية</title>
@extends('layouts.app')

@section('content')

    <!-- resources/views/AccountTypes/index.blade.php -->
    <h1>انواع الحسابات</h1>
    <div class="modal fade" id="RestrictionModel" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class='row'>
                        <div class='col-md-4'>
                            <label class="MainLabel">تاريخ القيد</label>
                            <label class="valueLabel" id='RDate'></label>
                            <label class="MainLabel">محرر القيد</label>
                            <label class="valueLabel" id='RCreator'></label>
                        </div>
                        <div class='col-md-7'>
                            <label class="MainLabel">تفاصيل القيد</label>
                            <label class="valueLabel" id='RDetails'></label>
                        </div>
                    </div>
                    <div id="RTransactions"></div>
                    <div class='row'>
                        <div class='col-md-2'>
                            <button type="button" class="btn cancel_button" data-dismiss="modal">اغلاق</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <a style="width: 20%;" href="DailyAccountingEntries/create" class="btn add_button mb-3">اضافة قيد</a>
    @if (count($DailyAccountingEntries) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>تفاصيل القيد</th>
                    <th>التاريخ</th>
                    <th>عرض</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($DailyAccountingEntries as $DailyAccountingEntry)
                <tbody>

                    <tr>
                        <td>{{ $DailyAccountingEntry->RestrictionID }}</td>
                        <td>{{ $DailyAccountingEntry->RestrictionDetails }}</td>
                        <td style="direction: ltr;">{{ $DailyAccountingEntry->created_at }}</td>
                        <td>
                            <button data-toggle='modal' data-target='#RestrictionModel'
                                class="btn view_button viewRestrictionDetails"
                                value = "{{ $DailyAccountingEntry->RestrictionID }}">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i>
                            </button>
                        </td>
                        <td>
                            @if ($DailyAccountingEntry->Deletable == 0)
                                {!! Form::open([
                                    'action' => ['DailyAccountingEntryController@destroy', $DailyAccountingEntry->RestrictionID],
                                    'method' => 'post',
                                ]) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                    'type' => 'submit',
                                    'class' => 'btn delete_button',
                                    'onclick' => "return confirm('تاكيد حذف القيد  $DailyAccountingEntry->RestrictionID ')",
                                ]) !!}

                                {!! Form::close() !!}
                            @else
                                NotDeletable
                            @endif
                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا توجد قيود يومية </div>
    @endif
    <script>
        $(document).on('click', '.viewRestrictionDetails', function() {
            var RestrictionID = $(this).val()
            $("#RTransactions").empty()
            $.ajax({
                url: 'DailyAccountingEntries/' +
                    RestrictionID, // URL to your Laravel route
                method: 'GET',
                dataType: 'json', // Expect JSON response
                success: function(response) {
                    var dailyEntry = response.daily_entry;
                    var account = response.accounts;
                    var details = response.details;
                    var user = response.user;
                    $("#RDate").html(new Date(dailyEntry.created_at).toISOString().split('T')[0]);
                    $("#RCreator").html(user.name)
                    $("#RDetails").html(dailyEntry.RestrictionDetails)
                    var MyTable = $(
                        "<table class = 'table' style = 'background-color:white'><tr style= 'background-color:black;color:white;'><th>التاريخ</th><th>رقم الحساب</th><th>إسم الحساب</th><th>البيان</th><th>منه</th><th>له</th></tr></table>"
                    )
                    var formatter = new Intl.NumberFormat();
                    for (var i = 0; i < details.length; i++) {
                        var detail = details[i];
                        var MyAccount = account[i];
                        var TR = $("<tr></tr>");
                        TR.append($("<td>" + new Date(detail.created_at).toISOString().split('T')[0] +
                            "</td>"));
                        TR.append($("<td>" + MyAccount.AccountNumber + "</td>"));
                        TR.append($("<td>" + MyAccount.AccountName + "</td>"));
                        TR.append($("<td>" + detail.TransactionDetails + "</td>"));
                        if (detail.TransactionType == 1)
                            TR.append($("<td>" + formatter.format(detail.TransactionAmount) +
                                "</td><td>-</td>"));
                        else
                            TR.append($("<td>-</td><td>" + formatter.format(detail.TransactionAmount) +
                                "</td>"));
                        MyTable.append(TR);
                    }
                    $("#RTransactions").append(MyTable)
                },
                error: function(xhr, status, error) {
                    // Handle error
                    console.error(error);
                }
            });
        });
    </script>


@endsection
<style>
    .MainLabel,
    .valueLabel {
        padding: 10px !important;
        font-size: 18px !important;
        font-weight: 500 !important;
    }
</style>
