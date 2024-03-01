@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class=" MainLabel">
                        <h1>اضافة منتج</h1>
                    </div>
                </div>

                <div class="panel-body">

                    <div>
                        {!! Form::open(['action' => ['CategoryController@update', $Category->CategoryID], 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('name', 'اسم الصنف', ['class' => 'ProceduresLabel']) !!}
                            {!! Form::text('CategoryName', $Category->CategoryName, [
                                'class' => 'input_style',
                                'placeholder' => 'ادخل اسم الصنف',
                            ]) !!}
                            {!! Form::hidden('CategoryID', $Category->CategoryID, []) !!}
                        </div>
                        <!-- Add more form fields as needed -->
                        {{ Form::hidden('_method', 'PUT') }}
                        <div class="row">
                            <div class="col-md-3"></div>
                            <div class="col-md-3">
                                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                                {!! Form::close() !!}
                            </div>

                            <div class="col-md-3"><a href = "/categories"><button type="button"
                                        class="btn cancel_button">رجوع</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
