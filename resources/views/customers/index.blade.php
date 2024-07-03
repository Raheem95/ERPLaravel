@extends('layouts.app')

@section('content')
    <!-- resources/views/Customers/index.blade.php -->

    <h1>العملاء</h1>


    <div class="row">
        <div class="col-md-2">
            <a href="/customers/create" class="btn add_button mb-3">اضافة عميل</a>
        </div>
        <div class="col-md-8">
            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    @if (count($Customers) > 0)
        <table class="table " id="CustomersTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم العميل</th>
                    <th>عنوان العميل</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Customers as $Customers)
                    <tr>
                        <td>{{ $Customers->CustomerID }}</td>
                        <td>{{ $Customers->CustomerName }}</td>
                        <td>{{ $Customers->CustomerAddress }}</td>
                        <td>
                            <a href="customers/{{ $Customers->CustomerID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['CustomerController@destroy', $Customers->CustomerID],
                                'method' => 'post',
                                'id' => 'deleteForm' . $Customers->CustomerID,
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button',
                                'class' => 'btn delete_button',
                                'onclick' => "confirmDelete('تاكيد حذف  العميل   {$Customers->CustomerName}','deleteForm{$Customers->CustomerID}')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد عملاء</div>
    @endif
    <script>
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword) Keyword = 0;
            $.ajax({
                url: '{{ url('customers_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table body
                    $('#CustomersTable tbody').empty();

                    // Iterate over the response data and create rows
                    response.forEach(function(customer) {
                        var row = `
                    <tr>
                        <td>${customer.CustomerID}</td>
                        <td>${customer.CustomerName}</td>
                        <td>${customer.CustomerAddress}</td>
                        <td>
                            <a href="customers/${customer.CustomerID}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            <form action="customers/${customer.CustomerID}" method="post" id="deleteForm${customer.CustomerID}" style="display: inline;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="button" class="btn delete_button" onclick="confirmDelete('تاكيد حذف العميل ${customer.CustomerName}', 'deleteForm${customer.CustomerID}')" id="DeleteButton${customer.CustomerID}">
                                    <i class="fas fa-trash-alt fa-2x"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                        $('#CustomersTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }
    </script>
@endsection
