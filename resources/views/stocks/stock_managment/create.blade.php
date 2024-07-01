@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>اضافة مخزن</h1>
        </div>

        {!! Form::open(['action' => 'StockController@store', 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-12">
                {!! Form::label('', 'اسم المخزن', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('StockName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم المخزن']) !!}
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                {!! Form::close() !!}
            </div>
            <div class="col-md-3"><a href="/Stocks/StockManagment" class="btn cancel_button mb-3">رجوع</a></div>
            <!-- Add more form fields as needed -->


        </div>
    </div>
@endsection
