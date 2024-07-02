<title>الدائنين والمدينين</title>
@extends('layouts.app')

@section('content')
    <!-- resources/views/creditors_debtors/index.blade.php -->
    <h1>الدائنين والمدينين</h1>

    <a style="width: 20%;" href="CreditorsDebtors/create" class="btn add_button mb-3">اضافة مديونية</a>
    <div class="row">
        <div class="col-md-6">
            @if (count($Creditors) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الحساب</th>
                            <th>المبلغ</th>
                            <th>التفاصيل</th>
                            <th>النوع</th>
                            <th>المضاف بواسطة</th>
                            <th>تعديل</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    @foreach ($Creditors as $Creditor)
                        <tbody>
                            <tr>
                                <td>{{ $Creditor->created_at->format('y-m-d') }}</td>
                                <td>{{ $Creditor->Account->AccountName }}</td>
                                <td>{{ number_format($Creditor->Amount, 2) }}</td>
                                <td>{{ $Creditor->OprationDetails }}</td>
                                <td>{{ $Creditor->OprationType == 1 ? 'دائن' : 'مدين' }}</td>
                                <td>{{ $Creditor->user->name }}</td>
                                <td>
                                    <a href="CreditorsDebtors/{{ $Creditor->OprationID }}/edit" class="btn edit_button">
                                        <i class='fa-solid fa-file-pen fa-2x'></i></a>
                                </td>
                                <td>
                                    {!! Form::open([
                                        'action' => ['CreditorsDebtorController@destroy', $Creditor->OprationID],
                                        'method' => 'post',
                                        'id' => 'deleteForm' . $Creditor->OprationID,
                                    ]) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                        'type' => 'button',
                                        'class' => 'btn delete_button',
                                        'onclick' => "confirmDelete('تاكيد حذف المديونية الحساب {$Creditor->Account->AccountName}','deleteForm$Creditor->OprationID')",
                                    ]) !!}

                                    {!! Form::close() !!}

                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-danger Result"> لا توجد دائنون </div>
            @endif
        </div>
        <div class="col-md-6">
            @if (count($Debtors) > 0)
                <table class="table">
                    <thead>
                        <tr>
                            <th>التاريخ</th>
                            <th>الحساب</th>
                            <th>المبلغ</th>
                            <th>التفاصيل</th>
                            <th>النوع</th>
                            <th>المضاف بواسطة</th>
                            <th>تعديل</th>
                            <th>حذف</th>
                        </tr>
                    </thead>
                    @foreach ($Debtors as $Debtor)
                        <tbody>
                            <tr>
                                <td>{{ $Debtor->created_at->format('y-m-d') }}</td>
                                <td>{{ $Debtor->Account->AccountName }}</td>
                                <td>{{ number_format($Debtor->Amount, 2) }}</td>
                                <td>{{ $Debtor->OprationDetails }}</td>
                                <td>{{ $Debtor->OprationType == 1 ? 'دائن' : 'مدين' }}</td>
                                <td>{{ $Debtor->user->name }}</td>
                                <td>
                                    <a href="CreditorsDebtors/{{ $Debtor->OprationID }}/edit" class="btn edit_button">
                                        <i class='fa-solid fa-file-pen fa-2x'></i></a>
                                </td>
                                <td>
                                    {!! Form::open([
                                        'action' => ['CreditorsDebtorController@destroy', $Debtor->OprationID],
                                        'method' => 'post',
                                        'id' => 'deleteForm' . $Debtor->OprationID,
                                    ]) !!}
                                    {!! Form::hidden('_method', 'DELETE') !!}
                                    {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                        'type' => 'button',
                                        'class' => 'btn delete_button',
                                        'onclick' => "confirmDelete('تاكيد حذف مديونية الحساب {$Debtor->Account->AccountName}','deleteForm$Debtor->OprationID')",
                                    ]) !!}

                                    {!! Form::close() !!}

                                </td>
                            </tr>
                    @endforeach
                    </tbody>
                </table>
            @else
                <div class="alert alert-danger Result"> لا توجد مدينون </div>
            @endif
        </div>
    </div>

    </div>
@endsection
