@extends('layouts.app')

@section('content')
    <!-- resources/views/AccountTypes/index.blade.php -->
    <h1>انواع الحسابات</h1>

    <a style="width: 20%;" href="AccountTypes/create" class="btn add_button mb-3">اضافة نوع</a>
    @if (count($AccountTypes) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>النوع</th>
                    <th>دائن\مدين</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($AccountTypes as $AccountType)
                <tbody>

                    <tr>
                        <td>{{ $AccountType->AccountTypeID }}</td>
                        <td>{{ $AccountType->AccountTypeName }}</td>
                        <td>
                            @if ($AccountType->AccountTypeSource == 1)
                                دائن
                            @else
                                مدين
                            @endif
                        </td>
                        <td>
                            <a href="AccountTypes/{{ $AccountType->AccountTypeID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['AccountTypeController@destroy', $AccountType->AccountTypeID],
                                'method' => 'post',
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'submit',
                                'class' => 'btn delete_button',
                                'onclick' => "return confirm('تاكيد حذف الصنف  $AccountType->AccountTypeName ')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا توجد انواع حسابات</div>
    @endif
@endsection
