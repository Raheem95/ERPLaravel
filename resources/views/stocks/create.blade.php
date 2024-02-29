@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" MainLabel">
            <h1>اضافة مخزن</h1>
        </div>

        {!! Form::open(['action' => 'StockController@store', 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-12">
                {!! Form::label('', 'اسم المخزن', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('StockName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم المخزن']) !!}
            </div>

            <!-- Add more form fields as needed -->
            {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}

        </div>
        {!! Form::close() !!}
    </div>
@endsection