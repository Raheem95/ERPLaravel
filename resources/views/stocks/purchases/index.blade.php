@extends('layouts.app')

@section('content')
    <!-- resources/views/Purchases/index.blade.php -->
    <input type = 'hidden' id = "PurchaseID">

    <h1>فواتير المشتريات</h1>
    <div class="col-md-12 alert Result" id = "Results"></div>
    <a style="width: 20%;" href="/purchases/create" class="btn add_button mb-3">اضافة فاتورة</a>
    @if (count($Purchases) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>الرقم </th>
                    <th>عرض</th>
                    <th>تغذية المخزن</th>
                </tr>
            </thead>
            @foreach ($Purchases as $Purchase)
                <tbody>

                    <tr>
                        <td id = "PurchaseNumber{{ $Purchase->PurchaseID }}">{{ $Purchase->PurchaseNumber }}</td>
                        <td>
                            <a href="Purchases/{{ $Purchase->PurchaseID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i>
                        </td>
                        <td>
                            <?php $Class = 'UnTransfareButton';
                            $color = 'red'; ?>
                            @if ($Purchase->Transfer == 1)
                                <?php $Class = 'TransfareButton';
                                $color = 'blue'; ?>
                            @endif
                            <button id = "TransfareButton{{ $Purchase->PurchaseID }}"
                                class="btn view_button Transfare {{ $Class }}"
                                style="color:{{ $color }}"value='{{ $Purchase->PurchaseID }}'><i
                                    class="fa-solid fa-shuffle fa-2x "></i></button>
                            <input type="hidden" id = "Transfer{{ $Purchase->PurchaseID }}"
                                value = "{{ $Purchase->Transfer }}">
                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد فواتير مشتريات</div>
    @endif
    <script>
        $(document).on('click', '.Transfare', function() {
            var PurchaseID = $(this).val()
            var PurchaseNumber = $("#PurchaseNumber" + PurchaseID).html()
            var Status = 2;
            var AlertMessage = "صرف"
            if ($(this).hasClass("UnTransfareButton")) {
                Status = 1;
                var AlertMessage = " الغاء صرف"
            }
            if (confirm("تاكيد " + AlertMessage + "  الفاتورة رقم" + PurchaseNumber)) {
                var form_data = new FormData();
                form_data.append('PurchaseID', PurchaseID);
                form_data.append('Status', Status);
                $.ajax({
                    url: "{{ route('stock_purchase_transfare') }}",
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
                            $("#Transfer" + PurchaseID).val(Status)

                            resetButtons(PurchaseID)
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

        function resetButtons(PurchaseID) {
            $("#TransfareButton" + PurchaseID).removeClass("TransfareButton").addClass("UnTransfareButton");
            $("#TransfareButton" + PurchaseID).css("color", "red")
            if ($("#Transfer" + PurchaseID).val() == 1) {
                $("#TransfareButton" + PurchaseID).removeClass("UnTransfareButton").addClass("TransfareButton");
                $("#TransfareButton" + PurchaseID).css("color", "blue")
            }
        }
    </script>
@endsection
