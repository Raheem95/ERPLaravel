@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" MainLabel">
            <h1>اضافة منتج</h1>
        </div>

        {!! Form::open(['action' => ['CustomerController@update', $Customer->CustomerID], 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-6">
                {!! Form::label('', 'اسم العميل', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('CustomerName', $Customer->CustomerName, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل اسم العميل',
                ]) !!}{!! Form::hidden('CustomerID', $Customer->CustomerID, []) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'رقم الهاتف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('CustomerPhone', $Customer->CustomerPhone, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل رقم العميل',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'العنوان', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('CustomerAddress', $Customer->CustomerAddress, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل العنوان',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'هل هو مورد؟', ['class' => 'ProceduresLabel']) !!}
                <label class='checkbox-container'>
                    {!! Form::checkbox('isSupplier', 1, $Customer->isSupplier, ['class' => 'AddStage', 'id' => 'isSupplier']) !!}
                    <div class='custom-checkbox'>
                        <div class='checkmark'></div>
                    </div>
                </label>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                {{ Form::hidden('_method', 'PUT') }}
                <!-- Add more form fields as needed -->
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}

                {!! Form::close() !!}
            </div>
            <div class="col-md-3"><a href="/customers" class="btn cancel_button mb-3">رجوع</a></div>
        </div>
    </div>
@endsection
