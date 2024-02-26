@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" MainLabel">
            <h1>اضافة منتج</h1>
        </div>

        {!! Form::open(['action' => 'CustomerController@store', 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-6">
                {!! Form::label('', 'اسم العميل', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('CustomerName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم العميل']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'رقم الهاتف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('CustomerPhone', null, ['class' => 'input_style', 'placeholder' => 'ادخل رقم العميل']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'العنوان', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('CustomerAddress', null, ['class' => 'input_style', 'placeholder' => 'ادخل العنوان']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'هل هو مورد؟', ['class' => 'ProceduresLabel']) !!}
                <label class='checkbox-container'>
                    {!! Form::checkbox('isSupplier', 1, null, ['class' => 'AddStage', 'id' => 'isSupplier']) !!}
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
