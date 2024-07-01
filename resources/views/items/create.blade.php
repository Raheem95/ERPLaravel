@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>اضافة منتج</h1>
        </div>

        {!! Form::open(['action' => 'ItemController@store', 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-6">
                {!! Form::label('name', 'اسم المنتج', ['class' => 'input_label']) !!}
                {!! Form::text('ItemName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم المنتج']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'سعر الصنف', ['class' => 'input_label']) !!}
                {!! Form::text('ItemPrice', null, ['class' => 'input_style', 'placeholder' => 'ادخل سعر المنتج']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'سعر البيع', ['class' => 'input_label']) !!}
                {!! Form::text('ItemSalePrice', null, ['class' => 'input_style', 'placeholder' => 'ادخل سعر البيع']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الحد الادنى للكمية', ['class' => 'input_label']) !!}
                {!! Form::text('Minimum', null, ['class' => 'input_style', 'placeholder' => 'ادخل الحد الادنى للكمية']) !!}
            </div>
            <?php
            $categories = json_decode($Categories, true);
            $options = collect($categories)->pluck('CategoryName', 'CategoryID');
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اسم الصنف', ['class' => 'input_label']) !!}
                {!! Form::select('CategoryID', $options, null, [
                    'class' => 'input_style',
                ]) !!}

            </div>
        </div>
        <!-- Add more form fields as needed -->
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                {!! Form::close() !!}
            </div>
            <div class="col-md-3"><a href = "/items"><button type="button" class="btn cancel_button">رجوع</button></a>
            </div>
        </div>
    </div>
@endsection
