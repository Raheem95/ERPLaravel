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
                        {!! Form::open(['action' => 'CategoryController@store', 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('name', 'اسم الصنف', ['class' => 'input_label']) !!}
                            {!! Form::text('CategoryName', null, ['class' => 'input_style', 'placeholder' => 'ادخل اسم الصنف']) !!}
                        </div>
                        <!-- Add more form fields as needed -->
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
