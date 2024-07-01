@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class=" input_label">
                        <h1>اضافة نوع حساب</h1>
                    </div>
                </div>

                <div class="panel-body">

                    <div>
                        {!! Form::open(['action' => 'AccountTypeController@store', 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('name', 'النوع', ['class' => 'ProceduresLabel']) !!}
                            {!! Form::text('AccountTypeName', null, ['class' => 'input_style', 'placeholder' => 'ادخل النوع']) !!}
                        </div>
                        <?php
                        $categories = '[{"TypeID":1,"TypeName":"دائن"},{"TypeID":-1,"TypeName":"مدين"}]';
                        $categories = json_decode($categories, true);
                        $options = collect($categories)->pluck('TypeName', 'TypeID');
                        ?>

                        <div class="form-group">
                            {!! Form::label('name', 'دائن\مدين', ['class' => 'ProceduresLabel']) !!}
                            {!! Form::select('AccountTypeSource', $options, null, [
                                'class' => 'input_style',
                            ]) !!}
                        </div>

                        <!-- Add more form fields as needed -->
                        {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
