@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class=" input_label">
                        <h1>اضافة منتج</h1>
                    </div>
                </div>

                <div class="panel-body">

                    <div>
                        {!! Form::open(['action' => ['CurrencyController@update', $Currency->CurrencyID], 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('name', 'اسم العملة', ['class' => 'ProceduresLabel']) !!}
                            {!! Form::text('CurrencyName', $Currency->CurrencyName, [
                                'class' => 'input_style',
                                'placeholder' => 'ادخل اسم العملة',
                            ]) !!}
                            {!! Form::hidden('CurrencyID', $Currency->CurrencyID, []) !!}
                        </div>
                        <!-- Add more form fields as needed -->
                        {{ Form::hidden('_method', 'PUT') }}
                        {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
