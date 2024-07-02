@extends('layouts.app')

@section('content')
    <!-- resources/views/Customers/index.blade.php -->

    <h1>العملاء</h1>

    <a style="width: 20%;" href="/customers/create" class="btn add_button mb-3">اضافة عميل</a>
    @if (count($Customers) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم العميل</th>
                    <th>عنوان العميل</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Customers as $Customers)
                <tbody>

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
@endsection
