@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>اضافة مورد</h1>
        </div>

        {!! Form::open(['action' => 'SupplierController@store', 'method' => 'post']) !!}
        <div class = "row">
            <div class="form-group col-md-6">
                {!! Form::label('', 'اسم المورد', ['class' => 'input_label']) !!}
                {!! Form::text('SupplierName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم المورد']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'رقم الهاتف', ['class' => 'input_label']) !!}
                {!! Form::text('SupplierPhone', null, ['class' => 'input_style', 'placeholder' => 'ادخل رقم المورد']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'العنوان', ['class' => 'input_label']) !!}
                {!! Form::text('SupplierAddress', null, ['class' => 'input_style', 'placeholder' => 'ادخل العنوان']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('', 'هل هو مورد؟', ['class' => 'input_label']) !!}
                <label class='checkbox-container'>
                    {!! Form::checkbox('isCustomer', 1, null, ['class' => 'AddStage', 'id' => 'isSupplier']) !!}
                    <div class='custom-checkbox'>
                        <div class='checkmark'></div>
                    </div>
                </label>
            </div>
            <div class="col-md-3"></div>
            <div class="col-md-3">
                <!-- Add more form fields as needed -->
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}

                {!! Form::close() !!}
            </div>
            <div class="col-md-3"><a href="/suppliers" class="btn cancel_button mb-3">رجوع</a></div>

            <!-- Add more form fields as needed -->

        </div>
    </div>
@endsection
