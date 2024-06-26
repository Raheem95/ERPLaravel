@extends('layouts.app')

@section('content')
    <!-- resources/views/items/index.blade.php -->

    <h1>المنتجات</h1>

    <a style="width: 20%;" href="/items/create" class="btn add_button mb-3">اضافة منتج</a>
    @if (count($items) > 0)
        <table class="table ">
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
            @foreach ($items as $item)
                <tbody>

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
@endsection
