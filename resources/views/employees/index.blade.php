<title> الموظفين</title>
@extends('layouts.app')

@section('content')
    <div class="modal fade" id="DeleteModel" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body text-center">
                    <i class="fa-regular fa-circle-xmark fa-9x" style="color: #aa3232;"></i>
                    <br>
                    <br>
                    <br>
                    <label style="font-size: 25px;color: #6b6969;">هل انت متاكد من حذف الموظف <label
                            id="DeletedEmployeeName"></label></label>
                    <br>
                    <br>
                    <p class="card-text">سيتم حذف الموظف بجميع التفاصيل المالية</p>

                    <div class="row">
                        <div class="col-md-6">
                            {!! Form::open([
                                'action' => ['EmployeeController@destroy', 'PLACEHOLDER_ITEM_ID'],
                                'method' => 'post',
                                'class' => 'delete-form',
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('حذف', [
                                'type' => 'submit',
                                'class' => 'delete_button',
                                'style' => 'width:100%',
                            ]) !!}
                            {!! Form::close() !!}
                        </div>
                        <div class="col-md-6">
                            <button class="delete_button" data-dismiss="modal"
                                style="background:#6b6969;color:white;width:100%;">الغاء</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                                <button style="width: 100%" type="button" class="delete_button DeleteEmployee"
                                    data-toggle="modal" data-target="#DeleteModel" value="{{ $employee->EmployeeID }}">
                                    حذف <i class="fa-solid fa-trash-can"></i>
                                </button>
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
                                                    <button style="width: 100%" type="button" class="delete_button DeleteEmployee"
                                                        data-toggle="modal" data-target="#DeleteModel" value="${employee.EmployeeID}">
                                                        حذف <i class="fa-solid fa-trash-can"></i>
                                                    </button>
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

        $(document).on('click', '.DeleteEmployee', function() {
            $("#DeletedEmployeeName").html($("#EmployeeName" + $(this).val()).html())
            $('.delete-form').attr('action', '/Employees/' + $(this).val());
        });
    </script>
@endsection
