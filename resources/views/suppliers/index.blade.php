@extends('layouts.app')

@section('content')
    <!-- resources/views/Suppliers/index.blade.php -->

    <h1>الموردين</h1>

    <a style="width: 20%;" href="/suppliers/create" class="btn add_button mb-3">اضافة مورد</a>
    @if (count($Suppliers) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم المورد</th>
                    <th>عنوان المورد</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Suppliers as $Supplier)
                <tbody>

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
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'submit',
                                'class' => 'btn delete_button',
                                'onclick' => "return confirm('تاكيد حذف المورد  $Supplier->SupplierName ')",
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
@endsection
