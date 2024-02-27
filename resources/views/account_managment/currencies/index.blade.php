@extends('layouts.app')

@section('content')
    <!-- resources/views/Accounts/index.blade.php -->

    <h1>انواع العملات</h1>

    <a style="width: 20%;" href="Currencies/create" class="btn add_button mb-3">اضافة عملة</a>
    @if (count($Currencies) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>اسم العملة</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Currencies as $Currency)
                <tbody>

                    <tr>
                        <td>{{ $Currency->CurrencyID }}</td>
                        <td>{{ $Currency->CurrencyName }}</td>
                        <td>
                            <a href="Currencies/{{ $Currency->CurrencyID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['CurrencyController@destroy', $Currency->CurrencyID],
                                'method' => 'post',
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'submit',
                                'class' => 'btn delete_button',
                                'onclick' => "return confirm('تاكيد حذف العملة  $Currency->CurrencyName ')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا توجد عملات </div>
    @endif
@endsection
