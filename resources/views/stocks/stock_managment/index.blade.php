@extends('layouts.app')

@section('content')
    <!-- resources/views/stocks/index.blade.php -->

    <h1>المخازن</h1>

    <a style="width: 20%;" href="StockManagment/create" class="btn add_button mb-3">اضافة مخزن</a>
    @if (count($Stocks) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المخزن</th>
                    <th>عرض التفاصيل</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Stocks as $Stock)
                <tbody>

                    <tr>
                        <td>{{ $Stock->StockID }}</td>
                        <td>{{ $Stock->StockName }}</td>
                        <td>
                            <a target="_blank" href="StockManagment/{{ $Stock->StockID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i></a>
                        </td>
                        <td>
                            <a href="StockManagment/{{ $Stock->StockID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['StockController@destroy', $Stock->StockID],
                                'id' => 'deleteForm' . $Stock->StockID,
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'button',
                                'class' => 'btn delete_button',
                                'onclick' => "confirmDelete('تاكيد حذف  المخزن   {$Stock->StockName}','deleteForm{$Stock->StockID}')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد مخازن</div>
    @endif
@endsection
