@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->

    <h1>الاصناف</h1>

    <div class="row">
        <div class="col-md-2">
            <a href="/categories/create" class="btn add_button mb-3">اضافة صنف</a>
        </div>
        <div class="col-md-8">
            <input type='text' id='Keyword' class='input_style' oninput="Search()"
                placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    @if (count($Categories) > 0)
        <table class="table" id="CategoriesTable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم الصنف</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Categories as $category)
                <tbody>

                    <tr>
                        <td>{{ $category->CategoryID }}</td>
                        <td>{{ $category->CategoryName }}</td>
                        <td>
                            <a href="categories/{{ $category->CategoryID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['CategoryController@destroy', $category->CategoryID],
                                'method' => 'post',
                                'id' => 'deleteForm' . $category->CategoryID,
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button',
                                'class' => 'btn delete_button',
                                'onclick' => "confirmDelete('تاكيد حذف  الصنف   {$category->CategoryName}','deleteForm{$category->CategoryID}')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا توجد اصناف</div>
    @endif
    <script>
        function Search() {
            var Keyword = $("#Keyword").val();
            if (!Keyword) Keyword = 0;
            $.ajax({
                url: '{{ url('categories_search') }}/' + Keyword,
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Clear existing table body
                    $('#CategoriesTable tbody').empty();

                    // Iterate over the response data and create rows
                    response.forEach(function(category) {
                        var row = `
                    <tr>
                        <td>${category.CategoryID}</td>
                        <td>${category.CategoryName}</td>
                        <td>
                            <a href="categories/${category.CategoryID}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i>
                            </a>
                        </td>
                        <td>
                            <form action="categories/${category.CategoryID}" method="post" id="deleteForm${category.CategoryID}" style="display: inline;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <button type="button" class="btn delete_button" onclick="confirmDelete('تاكيد حذف الصنف ${category.CategoryName}', 'deleteForm${category.CategoryID}')" id="DeleteButton${category.CategoryID}">
                                    <i class="fas fa-trash-alt fa-2x"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                `;
                        $('#CategoriesTable tbody').append(row);
                    });
                },
                error: function(xhr, status, error) {
                    customAlert("حدث خطأ أثناء الاتصال بالخادم", "danger");
                }
            });
        }
    </script>
@endsection
