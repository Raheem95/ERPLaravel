<title>تعديل موظف</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/employees/create.blade.php -->
    <div class="container">
        <div class=" input_label">
            <h1>إضافة موظف</h1>
        </div>
        {!! Form::open([
            'action' => ['EmployeeController@update', $employee->EmployeeID],
            'method' => 'post',
            'enctype' => 'multipart/form-data',
        ]) !!}
        <br>
        <div class="row" id="ImagesContainer">
            <div class="form-group col-md-3">
                <img id="ViewImage1" src="/{{ $employee->EmployeeImage }}"
                    style="width: 200px;height:200px;margin-bottom:10px;">
                {!! Form::file('EmployeeImage', ['class' => 'loadImg']) !!}
            </div>
        </div>
        <div class="row">
            <div class="form-group col-md-6">
                {!! Form::label('name', 'الاسم ', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('EmployeeName', $employee->EmployeeName, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل الاسم ',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('phone', 'الهاتف ', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('EmployeePhone', $employee->EmployeePhone, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل الهاتف ',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('address', 'العنوان ', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('EmployeeAddress', $employee->EmployeeAddress, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل العنوان ',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('salary', 'الراتب ', ['class' => 'ProceduresLabel']) !!}
                {!! Form::text('EmployeeSalary', $employee->EmployeeSalary, [
                    'class' => 'input_style',
                    'placeholder' => 'ادخل الراتب ',
                ]) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('hire_date', 'تاريخ التوظيف ', ['class' => 'ProceduresLabel']) !!}
                {!! Form::date('HireDate', $employee->HireDate, ['class' => 'input_style']) !!}
            </div>
            <div class="form-group col-md-6">
                {!! Form::label('suspended', 'ايقاف', ['class' => 'ProceduresLabel']) !!}
                {!! Form::select('Suspended', ['0' => 'لا', '1' => 'نعم'], $employee->Suspended, ['class' => 'input_style']) !!}
            </div>
        </div>
        <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-3">
                {{ Form::hidden('_method', 'PUT') }}
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
