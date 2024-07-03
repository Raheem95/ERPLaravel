<title> الموظفين</title>
@extends('layouts.app')

@section('content')
    <h1>الموظفين</h1>
    <br>
    <div class="row">
        <div class="col-md-2">
            <a href='/Employees/create'>
                <button class="btn add_button">
                    <i class="fa-regular fa-square-plus fontawesomeIcons"></i>
                    اضافة
                    موظف
                </button>
            </a>
        </div>

        <div class="col-md-9 text-center">
            <input type='text' id='Keyword' class='input_style Search' placeholder='ادخل كلمات مفتاحية للبحث'><br>
        </div>
    </div>
    <br>
    <div class="row" id="employees">
        @foreach ($employees as $employee)
            <div class="col-md-3">
                <div class="card mb-4">
                    <div class="card-img-top"
                        style="background-image: url({{ $employee->EmployeeImage }});background-size: cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title" id="EmployeeName{{ $employee->EmployeeID }}">{{ $employee->EmployeeName }}
                        </h5>
                        <p class="card-text">الهاتف: {{ $employee->EmployeePhone }}</p>
                        <p class="card-text">العنوان: {{ $employee->EmployeeAddress }}</p>
                        <p class="card-text">الراتب: {{ number_format($employee->EmployeeSalary) }}</p>
                        <p class="card-text">تاريخ التوظيف: {{ $employee->HireDate }}</p>
                        <p class="card-text">ايقاف: {{ $employee->Suspended ? 'نعم' : 'لا' }}</p>
                        <div class="row">
                            <div class="col-md-6">
                                <a href="Employees/{{ $employee->EmployeeID }}/edit">
                                    <button style="width: 100%" class="edit_button">
                                        تعديل <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                </a>
                            </div>
                            <div class="col-md-6">
                                {!! Form::open([
                                    'action' => ['EmployeeController@destroy', $employee->EmployeeID],
                                    'method' => 'post',
                                    'id' => 'deleteForm' . $employee->EmployeeID,
                                ]) !!}
                                {!! Form::hidden('_method', 'DELETE') !!}
                                {!! Form::button('حذف <i class="fas fa-trash-alt fa-2x"></i> ', [
                                    'type' => 'button',
                                    'class' => 'delete_button',
                                    'style' => 'width:100%;',
                                    'onclick' => "confirmDelete('تاكيد حذف  الموظف   {$employee->EmployeeName}','deleteForm{$employee->EmployeeID}')",
                                ]) !!}

                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <script>
        $(document).on('input', '.Search', function() {
            var Keyword = $("#Keyword").val();
            if (!Keyword)
                Keyword = "allEmployees"
            var form_data = new FormData();
            form_data.append('Keyword', Keyword);
            $.ajax({
                url: "/Employees/Search",
                dataType: 'json',
                cache: false,
                contentType: false,
                processData: false,
                data: form_data,
                type: 'post',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                },
                success: function(result) {
                    $("#employees").empty();
                    if (result.length > 0)
                        $.each(result, function(index, employee) {
                            var employeeHtml = `
                                <div class="col-md-3">
                                    <div class="card mb-4">
                                        <div class="card-img-top"
                                            style="background-image: url(${employee.EmployeeImage});background-size: cover;">
                                        </div>
                                        <div class="card-body">
                                            <h5 class="card-title" id="EmployeeName${employee.EmployeeID}">${employee.EmployeeName}</h5>
                                            <p class="card-text">الهاتف: ${employee.EmployeePhone}</p>
                                            <p class="card-text">العنوان: ${employee.EmployeeAddress}</p>
                                            <p class="card-text">الراتب: ${Number(employee.EmployeeSalary).toLocaleString()}</p>
                                            <p class="card-text">تاريخ التوظيف: ${employee.HireDate}</p>
                                            <p class="card-text">ايقاف: ${employee.Suspended ? 'نعم' : 'لا'}</p>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <a href="Employees/${employee.EmployeeID}/edit">
                                                        <button style="width: 100%" class="edit_button">
                                                            تعديل <i class="fa-solid fa-pen-to-square"></i>
                                                        </button>
                                                    </a>
                                                </div>
                                                <div class="col-md-6">
                                                    <form action="Employees/${employee.EmployeeID}" method="post" id="deleteForm${employee.EmployeeID}" style="display: inline;">
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                        <button type="button" style="width: 100%" class=" delete_button" onclick="confirmDelete('تاكيد حذف الموظف ${employee.EmployeeName}', 'deleteForm${employee.EmployeeID}')">
                                                            حذف <i class="fas fa-trash-alt fa-2x"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                            $("#employees").append(employeeHtml);
                        });
                    else
                        $("#employees").append(
                            "<div class = 'col-md-12 alert alert-danger Result'>لا توجد نتائج</div>")
                }
            });
        });
    </script>
@endsection
