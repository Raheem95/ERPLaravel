@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class=" MainLabel">
            <h1>اضافة حساب</h1>
        </div>

        {!! Form::open(['action' => 'AccountController@store', 'method' => 'post']) !!}
        <div class = "row">

            <div class="form-group col-md-6">
                {!! Form::label('AccountName', 'اسم الحساب', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('AccountName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم الحساب']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('Balance', 'الرصيد الافتتاحي ', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('Balance', null, ['class' => 'input_style', 'placeholder' => 'ادخل الرصيد الافتتاحي ']) !!}
            </div>
            <?php
            $AccountTypes = json_decode($AccountTypes, true);
            $AccountTypes = collect($AccountTypes)->pluck('AccountTypeName', 'AccountTypeID');
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', ' نوع الحساب', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('AccountTypeID', $AccountTypes, null, [
                    'class' => 'input_style',
                ]) !!}

            </div>
            <?php
            $Currencies = json_decode($Currencies, true);
            $Currencies = collect($Currencies)->pluck('CurrencyName', 'CurrencyID');
            ?>
            <div class="form-group col-md-6">
                {!! Form::label('name', 'العملة', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('CurrencyID', $Currencies, null, [
                    'class' => 'input_style',
                ]) !!}

            </div> <!-- Add more form fields as needed -->
            {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}

        </div>
        {!! Form::close() !!}
    </div>
@endsection
