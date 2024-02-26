@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->

    <h1>الاصناف</h1>

    <a style="width: 20%;" href="/categories/create" class="btn add_button mb-3">اضافة صنف</a>
    @if (count($Categories) > 0)
        <table class="table ">
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
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'submit',
                                'class' => 'btn delete_button',
                                'onclick' => "return confirm('تاكيد حذف الصنف  $category->CategoryName ')",
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
@endsection
