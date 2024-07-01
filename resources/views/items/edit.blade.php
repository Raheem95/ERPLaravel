@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>تعديل منتج</h1>
        </div>

        {!! Form::open(['action' => ['ItemController@update', $Item->ItemID], 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-6">
                {!! Form::label('name', 'اسم المنتج', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('ItemName', $Item->ItemName, ['class' => 'input_style', 'placeholder' => 'ادخل اسم المنتج']) !!}
                {!! Form::hidden('ItemID', $Item->ItemID, []) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'سعر الصنف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('ItemPrice', $Item->ItemPrice, ['class' => 'input_style', 'placeholder' => 'ادخل سعر المنتج']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'سعر البيع', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('ItemSalePrice', $Item->ItemSalePrice, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل سعر البيع',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الحد الادنى للكمية', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('Minimum', $Item->Minimum, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل الحد الادنى للكمية',
                ]) !!}
            </div>
            <?php
            $categories = json_decode($Categories, true);
            $options = collect($categories)->pluck('CategoryName', 'CategoryID');
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'اسم المنتج', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('CategoryID', $options, $Item->CategoryID, [
                    'class' => 'input_style',
                ]) !!}
                {{ Form::hidden('_method', 'PUT') }}
            </div>
            <!-- Add more form fields as needed -->


        </div>
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
