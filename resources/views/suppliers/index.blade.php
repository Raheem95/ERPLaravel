@extends('layouts.app')

@section('content')
    <!-- resources/views/Suppliers/index.blade.php -->

    <h1>الموردين</h1>

    <div class="row">
        <div class="col-md-2">
            <a href="/suppliers/create" class="btn add_button mb-3">اضافة مورد</a>
        </div>
        <div class="col-md-8">
            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    @if (count($Suppliers) > 0)
        <table class="table " id="SuppliersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المورد</th>
                    <th>عنوان المورد</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Suppliers as $Supplier)
                    <tr>
                        <td>{{ $Supplier->SupplierID }}</td>
                        <td>{{ $Supplier->SupplierName }}</td>
                        <td>{{ $Supplier->SupplierAddress }}</td>
                        <td>
                            <a href="suppliers/{{ $Supplier->SupplierID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['SupplierController@destroy', $Supplier->SupplierID],
                                'method' => 'post',
                                'id' => 'deleteForm' . $Supplier->SupplierID,
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button',
                                'class' => 'btn delete_button',
                                'onclick' => "confirmDelete('تاكيد حذف  المورد   {$Supplier->SupplierName}','deleteForm{$Supplier->SupplierID}')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد موردين</div>
    @endif
    <script>
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword) Keyword = 0;
            $.ajax({
                url: '{{ url('supplier_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table body
                    $('#SuppliersTable tbody').empty();

                    // Iterate over the response data and create rows
                    response.forEach(function(supplier) {
                        var row = `
                    <tr>
                        <td>${supplier.SupplierID}</td>
                        <td>${supplier.SupplierName}</td>
                        <td>${supplier.SupplierAddress}</td>
                        <td>
                            <a href="suppliers/${supplier.SupplierID}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            <form action="suppliers/${supplier.SupplierID}" method="post" id="deleteForm${supplier.SupplierID}" style="display: inline;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="button" class="btn delete_button" onclick="confirmDelete('تاكيد حذف المورد ${supplier.SupplierName}', 'deleteForm${supplier.SupplierID}')" id="DeleteButton${supplier.SupplierID}">
                                    <i class="fas fa-trash-alt fa-2x"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                        $('#SuppliersTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }
    </script>
@endsection
