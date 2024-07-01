<title>اضافة موظف</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/employees/create.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>إضافة موظف</h1>
        </div>

        {!! Form::open(['action' => 'EmployeeController@store', 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
        <br>
        <div class="row" id="ImagesContainer">
            <div class="form-group col-md-3">
                <img id="ViewImage1" src="/EmployeesImages/Default.png" style="width: 200px;height:200px;margin-bottom:10px;">
                {!! Form::file('EmployeeImage', ['class' => 'loadImg']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الاسم ', ['class' => 'input_label']) !!}
                {!! Form::text('EmployeeName', null, ['class' => 'input_style', 'placeholder' => 'ادخل الاسم ']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('phone', 'الهاتف ', ['class' => 'input_label']) !!}
                {!! Form::text('EmployeePhone', null, ['class' => 'input_style', 'placeholder' => 'ادخل الهاتف ']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('address', 'العنوان ', ['class' => 'input_label']) !!}
                {!! Form::text('EmployeeAddress', null, ['class' => 'input_style', 'placeholder' => 'ادخل العنوان ']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('salary', 'الراتب ', ['class' => 'input_label']) !!}
                {!! Form::text('EmployeeSalary', null, ['class' => 'input_style', 'placeholder' => 'ادخل الراتب ']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('hire_date', 'تاريخ التوظيف ', ['class' => 'input_label']) !!}
                {!! Form::date('HireDate', null, ['class' => 'input_style']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('suspended', 'ايقاف', ['class' => 'input_label']) !!}
                {!! Form::select('Suspended', ['0' => 'لا', '1' => 'نعم'], null, ['class' => 'input_style']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                {!! Form::submit('حفظ', ['class' => 'btn save_button']) !!}
                {!! Form::close() !!}
            </div>
            <div class="col-md-3"><a href="/Employees"><button type="button" class="btn cancel_button">رجوع</button></a>
            </div>
        </div>
    </div>
    <script>
        $(document).on('change', '.loadImg', function() {
            var input = event.target;
            var reader = new FileReader();
            reader.onload = function() {
                var dataURL = reader.result;
                $('#ViewImage1').attr('src', dataURL);
            };
            reader.readAsDataURL(input.files[0]);
        });
    </script>
@endsection
