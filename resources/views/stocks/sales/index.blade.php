@extends('layouts.app')

@section('content')
    <!-- resources/views/sales/index.blade.php -->
    <input type = 'hidden' id = "SaleID">

    <h1>فواتير المبيعات</h1>
    <div class="col-md-12 alert Result" id = "Results"></div>
    @if (count($Sales) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>الرقم </th>
                    <th>عرض</th>
                    <th>تغذية المخزن</th>
                </tr>
            </thead>
            @foreach ($Sales as $Sale)
                <tbody>

                    <tr>
                        <td id = "SaleNumber{{ $Sale->SaleID }}">{{ $Sale->SaleNumber }}</td>
                        <td>
                            <a href="Sales/{{ $Sale->SaleID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i>
                        </td>
                        <td>
                            <?php $Class = 'UnTransfareButton';
                            $color = 'red'; ?>
                            @if ($Sale->Transfer == 1)
                                <?php $Class = 'TransfareButton';
                                $color = 'blue'; ?>
                            @endif
                            <button id = "TransfareButton{{ $Sale->SaleID }}"
                                class="btn view_button Transfare {{ $Class }}"
                                style="color:{{ $color }}"value='{{ $Sale->SaleID }}'><i
                                    class="fa-solid fa-shuffle fa-2x "></i></button>
                            <input type="hidden" id = "Transfer{{ $Sale->SaleID }}" value = "{{ $Sale->Transfer }}">
                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد فواتير مبيعات</div>
    @endif
    <script>
        $(document).on('click', '.Transfare', function() {
            var SaleID = $(this).val()
            var SaleNumber = $("#SaleNumber" + SaleID).html()
            var Status = 2;
            var AlertMessage = "صرف"
            if ($(this).hasClass("UnTransfareButton")) {
                Status = 1;
                var AlertMessage = " الغاء صرف"
            }
            if (confirm("تاكيد " + AlertMessage + "  الفاتورة رقم" + SaleNumber)) {
                var form_data = new FormData();
                form_data.append('SaleID', SaleID);
                form_data.append('Status', Status);
                $.ajax({
                    url: "{{ route('stock_sale_transfare') }}",
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
                            $("#Transfer" + SaleID).val(Status)

                            resetButtons(SaleID)
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

        function resetButtons(SaleID) {
            $("#TransfareButton" + SaleID).removeClass("TransfareButton").addClass("UnTransfareButton");
            $("#TransfareButton" + SaleID).css("color", "red")
            if ($("#Transfer" + SaleID).val() == 1) {
                $("#TransfareButton" + SaleID).removeClass("UnTransfareButton").addClass("TransfareButton");
                $("#TransfareButton" + SaleID).css("color", "blue")
            }
        }
    </script>
@endsection
