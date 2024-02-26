@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" MainLabel">
            <h1>اضافة منتج</h1>
        </div>

        {!! Form::open(['action' => 'ItemController@store', 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-6">
                {!! Form::label('name', 'اسم المنتج', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('ItemName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم المنتج']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'سعر الصنف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('ItemPrice', null, ['class' => 'input_style', 'placeholder' => 'ادخل سعر المنتج']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'سعر البيع', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('ItemSalePrice', null, ['class' => 'input_style', 'placeholder' => 'ادخل سعر البيع']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الحد الادنى للكمية', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('Minimum', null, ['class' => 'input_style', 'placeholder' => 'ادخل الحد الادنى للكمية']) !!}
            </div>
            <?php
            $categories = json_decode($Categories, true);
            $options = collect($categories)->pluck('CategoryName', 'CategoryID');
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اسم الصنف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('CategoryID', $options, null, [
                    'class' => 'input_style',
                ]) !!}

            </div>
            <!-- Add more form fields as needed -->
            {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}

        </div>
        {!! Form::close() !!}
    </div>
@endsection