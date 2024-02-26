@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" MainLabel">
            <h1>تعديل مورد</h1>
        </div>

        {!! Form::open(['action' => ['SupplierController@update', $Supplier->SupplierID], 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-6">
                {!! Form::label('', 'اسم المورد', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('SupplierName', $Supplier->SupplierName, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل اسم المورد',
                ]) !!}{!! Form::hidden('SupplierID', $Supplier->SupplierID, []) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'رقم الهاتف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('SupplierPhone', $Supplier->SupplierPhone, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل رقم المورد',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'العنوان', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('SupplierAddress', $Supplier->SupplierAddress, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل العنوان',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'هل هو عميل', ['class' => 'ProceduresLabel']) !!}
                <label class='checkbox-container'>
                    {!! Form::checkbox('isSupplier', 1, $Supplier->isCustomer, ['class' => 'AddStage', 'id' => 'isSupplier']) !!}
                    <div class='custom-checkbox'>
                        <div class='checkmark'></div>
                    </div>
                </label>
            </div>
            {{ Form::hidden('_method', 'PUT') }}
            <!-- Add more form fields as needed -->
            {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}

        </div>
        {!! Form::close() !!}
    </div>
@endsection
