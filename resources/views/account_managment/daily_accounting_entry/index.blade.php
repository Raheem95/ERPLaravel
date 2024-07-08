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
                        <div class='col-md-6'>
                            <label class="model_label">تاريخ القيد</label>
                            <label class="input_style" id='RDate'></label>
                        </div>
                        <div class='col-md-6'>
                            <label class="model_label">محرر القيد</label>
                            <label class="input_style" id='RCreator'></label>
                        </div>
                        <div class='col-md-12'>
                            <label class="model_label">تفاصيل القيد</label>
                            <label class="input_style" id='RDetails'></label>
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
    <div class="row">
        <div class="col-md-2">
            <a href="DailyAccountingEntries/create" class="btn add_button mb-3">اضافة قيد</a>

        </div>
        <div class="col-md-10">
            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    @if (count($DailyAccountingEntries) > 0)
        <table class="table " id="DailyAccountingEntriesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>تفاصيل القيد</th>
                    <th>التاريخ</th>
                    <th>عرض</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($DailyAccountingEntries as $DailyAccountingEntry)
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
                                    'id' => 'deleteForm' . $DailyAccountingEntry->RestrictionID,
                                ]) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                    'type' => 'button',
                                    'class' => 'btn delete_button',
                                    'onclick' => "confirmDelete('تاكيد حذف  القيد    {$DailyAccountingEntry->RestrictionDetails}','deleteForm{$DailyAccountingEntry->RestrictionID}')",
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
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword) Keyword = 0;
            $.ajax({
                url: '{{ url('restriction_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table rows
                    $('#DailyAccountingEntriesTable tbody').empty();

                    // Iterate over each restriction in the response and append rows to table
                    response.forEach(function(restriction) {
                        const row = `
                    <tr>
                        <td>${restriction.RestrictionID}</td>
                        <td>${restriction.RestrictionDetails}</td>
                        <td style="direction: ltr;">${restriction.created_at}</td>
                        <td>
                            <button data-toggle='modal' data-target='#RestrictionModel'
                                class="btn view_button viewRestrictionDetails"
                                value="${restriction.RestrictionID}">
                                <i class='fa-solid fa-clipboard-list fa-2x'></i>
                            </button>
                        </td>
                        <td>
                            ${restriction.Deletable == 0 ?
                                `<form action="DailyAccountingEntries/${restriction.RestrictionID}" method="post" style="display: inline;" id='deleteForm${restriction.RestrictionID}'>
                                                                <input type="hidden" name="_method" value="DELETE">
                                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                                <button type="button" class="btn delete_button" onclick="return confirmDelete('تأكيد حذف القيد ${restriction.RestrictionDetails}','deleteForm${restriction.RestrictionID}')">
                                                                    <i class="fas fa-trash-alt fa-2x"></i>
                                                                </button>
                                                            </form>` :
                                'NotDeletable'}
                        </td>
                    </tr>
                `;
                        $('#DailyAccountingEntriesTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }


        function number_format(number) {
            var formatter = new Intl.NumberFormat();
            return formatter.format(number)

        }
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
    .input_label,
    .valueLabel {
        padding: 10px !important;
        font-size: 18px !important;
        font-weight: 500 !important;
    }
</style>
