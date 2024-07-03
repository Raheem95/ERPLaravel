@extends('layouts.app')

@section('content')
    <!-- resources/views/items/index.blade.php -->

    <h1>المنتجات</h1>


    <div class="row">
        <div class="col-md-2">
            <a href="/items/create" class="btn add_button mb-3">اضافة منتج</a>
        </div>
        <div class="col-md-8">
            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    @if (count($items) > 0)
        <table class="table " id="ItemsTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>الرقم التسلسلي</th>
                    <th>الاسم</th>
                    <th>السعر</th>
                    <th>الكمية</th>
                    <th>الحد الادنى</th>
                    <th>الصنف</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item->ItemID }}</td>
                        <td>{{ $item->ItemPartNumber }}</td>
                        <td>{{ $item->ItemName }}</td>
                        <td>{{ number_format($item->ItemPrice) }}</td>
                        <td>{{ number_format($item->ItemQty) }}</td>
                        <td>{{ $item->Minimum }}</td>
                        <td>{{ $item->categories->CategoryName }}</td>
                        <td>
                            <a href="items/{{ $item->ItemID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['ItemController@destroy', $item->ItemID],
                                'method' => 'post',
                                'id' => 'deleteForm' . $item->ItemID,
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button',
                                'class' => 'btn delete_button',
                                'onclick' => "confirmDelete('تاكيد حذف  الصنف   {$item->ItemName}','deleteForm{$item->ItemID}')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا توجد منتجات</div>
    @endif
    <script>
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword) Keyword = 0;
            $.ajax({
                url: '{{ url('items_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table body
                    $('#ItemsTable tbody').empty();
                    var formatter = new Intl.NumberFormat();

                    // Iterate over the response data and create rows
                    response.forEach(function(item) {
                        var row = `
                    <tr>
                        <td>${item.ItemID}</td>
                        <td>${item.ItemPartNumber}</td>
                        <td>${item.ItemName}</td>
                        <td>${formatter.format(item.ItemPrice)}</td>
                        <td>${formatter.format(item.ItemQty)}</td>
                        <td>${item.Minimum}</td>
                        <td>${item.categories.CategoryName}</td>
                        <td>
                            <a href="items/${item.ItemID}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i>
                            </a>
                        </td>
                        <td>
                            <form action="items/${item.ItemID}" method="post" id="deleteForm${item.ItemID}" style="display: inline;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="button" class="btn delete_button" onclick="confirmDelete('تاكيد حذف الصنف ${item.ItemName}', 'deleteForm${item.ItemID}')" id="DeleteButton${item.ItemID}">
                                    <i class="fas fa-trash-alt fa-2x"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                        $('#ItemsTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }
    </script>
@endsection
