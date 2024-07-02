@extends('layouts.app')

@section('content')
    <!-- resources/views/Transfares/index.blade.php -->

    <h1>المخازن</h1>
    <div class="col-md-12 Result" id = "Results"></div>
    <a style="width: 20%;" href="Transfare/create" class="btn add_button mb-3">اضافة تحويل مخزني</a>
    @if (count($Transfares) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th> التفاصيل</th>
                    <th> من مخزن</th>
                    <th> الى مخزن</th>
                    <th>عرض التفاصيل</th>
                    <th>تحويل\الغاء</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Transfares as $Transfare)
                <tbody>

                    <tr>
                        <td>{{ $Transfare->TransfareID }}</td>
                        <td id = "TransfareDetsils{{ $Transfare->TransfareID }}">{{ $Transfare->Comment }}</td>
                        <td>{{ $Transfare->from_stock->StockName }}</td>
                        <td>{{ $Transfare->to_stock->StockName }}</td>
                        <td>
                            <a target="_blank" href="Transfare/{{ $Transfare->TransfareID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i></a>
                        </td>
                        <td>
                            @if ($Transfare->Transfare < 2)
                                <?php $Class = 'UnTransfareButton';
                                $color = 'red'; ?>
                                @if ($Transfare->Transfare == 0)
                                    <?php $Class = 'TransfareButton';
                                    $color = 'blue'; ?>
                                @endif
                                <button id = "TransfareButton{{ $Transfare->TransfareID }}"
                                    class="btn view_button Transfare {{ $Class }}"
                                    style="color:{{ $color }}"value='{{ $Transfare->TransfareID }}'><i
                                        class="fa-solid fa-shuffle fa-2x "></i></button>
                            @else
                                تم صرف الفاتورة من المخزن
                            @endif
                            <input type="hidden" id = "Transfer{{ $Transfare->TransfareID }}"
                                value = "{{ $Transfare->Transfare }}">

                        </td>
                        <td>
                            <a href="Transfare/{{ $Transfare->TransfareID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['StockTransfareController@destroy', $Transfare->TransfareID],
                                'method' => 'post',
                                'id' => 'deleteForm' . $Transfare->TransfareID,
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button',
                                'class' => 'btn delete_button',
                                'onclick' => "confirmDelete('تاكيد حذف  النحويل   {$Transfare->Comment}','deleteForm{$Transfare->TransfareID}')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد تحويلات</div>
    @endif
    <script>
        $(document).on('click', '.Transfare', function() {
            var TransfareID = $(this).val()
            var TransfareDetsils = $("#TransfareDetsils" + TransfareID).html()
            var Status = 1;
            var AlertMessage = "صرف"
            if ($(this).hasClass("UnTransfareButton")) {
                Status = 0;
                var AlertMessage = " الغاء صرف"
            }
            if (confirm("تاكيد " + AlertMessage + "  التحويل " + TransfareDetsils)) {
                var form_data = new FormData();
                form_data.append('TransfareID', TransfareID);
                form_data.append('Status', Status);
                $.ajax({
                    url: "{{ route('stock_transfare') }}",
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
                        if (!isNaN(result)) {
                            $("#Results").removeClass("alert-danger").addClass(
                                    "alert-success")
                                .html(
                                    "تم  " + AlertMessage + " الفاتورة بنجاح");
                            $("#Transfer" + TransfareID).val(Status)

                            resetButtons(TransfareID)
                        } else
                            $("#Results").removeClass("alert-success").addClass(
                                "alert-danger").html(
                                result);
                    },
                    error: function(xhr, status, error) {
                        // Handle error
                    }
                });
            }
        });

        function resetButtons(TransfareID) {
            $("#TransfareButton" + TransfareID).removeClass("TransfareButton").addClass("UnTransfareButton");
            $("#TransfareButton" + TransfareID).css("color", "red")
            if ($("#Transfer" + TransfareID).val() == 0) {
                $("#TransfareButton" + TransfareID).removeClass("UnTransfareButton").addClass("TransfareButton");
                $("#TransfareButton" + TransfareID).css("color", "blue")
            }
        }
    </script>
@endsection
