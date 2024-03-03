@extends('layouts.app')

@section('content')
    <!-- resources/views/Transfares/index.blade.php -->

    <h1>المخازن</h1>

    <a style="width: 20%;" href="Transfare/create" class="btn add_button mb-3">اضافة تحويل مخزني</a>
    @if (count($Transfares) > 0)
        <table class="table ">
            <thead>
                <tr>
                    <th>#</th>
                    <th> التفاصيل</th>
                    <th> من مخزن</th>
                    <th> الى مخزن</th>
                    <th>عرض التفاصيل</th>
                    <th>تحويل\الغاء</th>
                    <th>تعديل</th>
                    <th>حذف</th>
                </tr>
            </thead>
            @foreach ($Transfares as $Transfare)
                <tbody>

                    <tr>
                        <td>{{ $Transfare->TransfareID }}</td>
                        <td>{{ $Transfare->Comment }}</td>
                        <td>{{ $Transfare->from_stock->StockName }}</td>
                        <td>{{ $Transfare->to_stock->StockName }}</td>
                        <td>
                            <a href="Transfare/{{ $Transfare->TransfareID }}/" class="btn view_button">
                                <i class='fa-solid  fa-clipboard-list fa-2x'></i></a>
                        </td>
                        <td>
                            @if ($Transfare->Transfer < 2)
                                <?php $Class = 'UnTransfareButton';
                                $color = 'red'; ?>
                                @if ($Transfare->Transfer == 0)
                                    <?php $Class = 'TransfareButton';
                                    $color = 'blue'; ?>
                                @endif
                                <button id = "TransfareButton{{ $Transfare->TransfareID }}"
                                    class="btn view_button Transfare {{ $Class }}"
                                    style="color:{{ $color }}"value='{{ $Transfare->TransfareID }}'><i
                                        class="fa-solid fa-shuffle fa-2x "></i></button>
                            @else
                                تم صرف الفاتورة من المخزن
                            @endif
                            <input type="hidden" id = "Transfer{{ $Transfare->TransfareID }}"
                                value = "{{ $Transfare->Transfer }}">

                        </td>
                        <td>
                            <a href="Transfare/{{ $Transfare->TransfareID }}/edit" class="btn edit_button">
                                <i class='fa-solid fa-file-pen fa-2x'></i></a>
                        </td>
                        <td>
                            {!! Form::open([
                                'action' => ['StockTransfareController@destroy', $Transfare->TransfareID],
                                'method' => 'post',
                            ]) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::button('<i class="fas fa-trash-alt fa-2x"></i> ', [
                                'type' => 'submit',
                                'class' => 'btn delete_button',
                                'onclick' => "return confirm('تاكيد حذف المخزن  $Transfare->TransfareName ')",
                            ]) !!}

                            {!! Form::close() !!}

                        </td>
                    </tr>
            @endforeach
            </tbody>
        </table>
    @else
        <div class="alert alert-danger Result"> لا يوجد تحويلات</div>
    @endif
@endsection
