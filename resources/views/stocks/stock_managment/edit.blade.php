@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>تعديل مخزن</h1>
        </div>

        {!! Form::open(['action' => ['StockController@update', $Stock->StockID], 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-12">
                {!! Form::label('', 'اسم المخزن', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('StockName', $Stock->StockName, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل اسم المخزن',
                ]) !!}{!! Form::hidden('StockID', $Stock->StockID, []) !!}
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                {{ Form::hidden('_method', 'PUT') }}
                <!-- Add more form fields as needed -->
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                {!! Form::close() !!}
            </div>
            <div class="col-md-3"><a href="/Stocks/StockManagment" class="btn cancel_button mb-3">رجوع</a></div>
        </div>
    @endsection
