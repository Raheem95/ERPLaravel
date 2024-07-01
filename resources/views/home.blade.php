@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <label for="" class="input_label">المنتجات الاكثر مبيعا</label>
            <div class="SeconderyDiv"><canvas id="TopSaleItems" class="barCtx"></canvas></div>
            <input type='hidden' value='<?php echo implode(',', $TopItemsSaleLabels); ?>' id="TopSaleItemsLabel">
            <input type='hidden' value='<?php echo implode(',', $TopItemsSaleData); ?>' id="TopSaleItemsData">
            <input type='hidden' value='' id="TopSaleItemsHint">
        </div>
        <div class="col-md-6">
            <label for="" class="input_label">المنتجات الاكثر دخلا</label>
            <div class="SeconderyDiv"><canvas id="TopItemsIncome" class="barCtx"></canvas></div>
            <input type='hidden' value='<?php echo implode(',', $TopItemsIncomeLabels); ?>' id="TopItemsIncomeLabel">
            <input type='hidden' value='<?php echo implode(',', $TopItemsIncomeData); ?>' id="TopItemsIncomeData">
            <input type='hidden' value='' id="TopItemsIncomeHint">
        </div>
        <div class="col-md-4">
            <label for="" class="input_label">المبيعات </label>
            <table class="table">
                <tr>
                    <th>الشهر</th>
                    <th>المبيعات</th>
                </tr>

                @for ($i = 0; $i < count($SalesData); $i++)
                    <tr>
                        <td style="padding: 25px!important;">{{ $SalesLabels[$i] }}</td>
                        <td style="padding: 25px!important;">{{ number_format($SalesData[$i], 2) }}</td>
                    </tr>
                @endfor
            </table>
        </div>
        <div class="col-md-8">
            <label for="" class="input_label"> </label>
            <div class="SeconderyDiv"><canvas class="lineCharts" id="SalesLineChart"></canvas></div>
            <input type='hidden' value='<?php echo implode(',', $SalesLabels); ?>' id="SalesLineChartLabel">
            <input type='hidden' value='<?php echo implode(',', $SalesData); ?>' id="SalesLineChartData">
            <input type='hidden' value='ايرادات الاشهر' id="SalesLineChartHint">
        </div>
        <div class="col-md-6">
            <label for="" class="input_label">العملاء الاكثر شراء</label>
            <div class="SeconderyDiv"><canvas id="CustomerSale" class="barCtx"></canvas></div>
            <input type='hidden' value='<?php echo implode(',', $CustomerSaleLabels); ?>' id="CustomerSaleLabel">
            <input type='hidden' value='<?php echo implode(',', $CustomerSaleData); ?>' id="CustomerSaleData">
            <input type='hidden' value='' id="CustomerSaleHint">
        </div>
        <div class="col-md-6">
            <label for="" class="input_label">العملاء الاكثر مديونية</label>
            <div class="SeconderyDiv"><canvas id="CustomerDepit" class="barCtx"></canvas></div>
            <input type='hidden' value='<?php echo implode(',', $CustomerDepitLabels); ?>' id="CustomerDepitLabel">
            <input type='hidden' value='<?php echo implode(',', $CustomerDepitData); ?>' id="CustomerDepitData">
            <input type='hidden' value='' id="CustomerDepitHint">
        </div>
    </div>
    <script src="{{ asset('js/charts.js') }}"></script>
    <script src="{{ asset('js/MyCharts.js') }}"></script>
    <script src="{{ asset('js/SearchInTable.js') }}"></script>
@endsection
