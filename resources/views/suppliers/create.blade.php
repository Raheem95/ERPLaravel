@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" MainLabel">
            <h1>اضافة منتج</h1>
        </div>

        {!! Form::open(['action' => 'SupplierController@store', 'method' => 'post']) !!}
        <div class = "row">
            <div class="form-group col-md-6">
                {!! Form::label('', 'اسم المورد', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('SupplierName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم المورد']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'رقم الهاتف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('SupplierPhone', null, ['class' => 'input_style', 'placeholder' => 'ادخل رقم المورد']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'العنوان', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('SupplierAddress', null, ['class' => 'input_style', 'placeholder' => 'ادخل العنوان']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'هل هو مورد؟', ['class' => 'ProceduresLabel']) !!}
                <label class='checkbox-container'>
                    {!! Form::checkbox('isCustomer', 1, null, ['class' => 'AddStage', 'id' => 'isSupplier']) !!}
                    <div class='custom-checkbox'>
                        <div class='checkmark'></div>
                    </div>
                </label>
            </div>

            <!-- Add more form fields as needed -->
            {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}

        </div>
        {!! Form::close() !!}
    </div>
@endsection
