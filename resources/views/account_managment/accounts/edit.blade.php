@extends('layouts.app')

@section('content')
    <!-- resources/views/categories/index.blade.php -->
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class=" input_label">
                        <h1>تعديل نوع حساب</h1>
                    </div>
                </div>

                <div class="panel-body">

                    <div>
                        {!! Form::open(['action' => ['AccountTypeController@update', $AccountType->AccountTypeID], 'method' => 'post']) !!}
                        <div class="form-group">
                            {!! Form::label('name', 'النوع', ['class' => 'input_label']) !!}
                            {!! Form::text('AccountTypeName', $AccountType->AccountTypeName, [
                                'class' => 'input_style',
                                'placeholder' => 'ادخل النوع ',
                            ]) !!}
                            {!! Form::hidden('AccountTypeID', $AccountType->AccountTypeID, []) !!}
                        </div>
                        <?php
                        $categories = '[{"TypeID":1,"TypeName":دائن},{"TypeID":-1,"TypeName":مدين}]';
                        $categories = json_decode($categories, true);
                        $options = collect($categories)->pluck('TypeName', 'TypeID');
                        ?>
                        <div class="form-group">
                            {!! Form::label('name', 'دائن\مدين', ['class' => 'input_label']) !!}
                            {!! Form::select('AccountTypeSource', $options, $AccountType->AccountTypeSource, [
                                'class' => 'input_style',
                            ]) !!}
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
