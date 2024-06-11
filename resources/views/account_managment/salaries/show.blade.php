<html dir='rtl'>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Mobile Metas -->
    <meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>كشف حساب راتب {{ $SalaryDetail->Month->MonthName }}</title>
    <!-- Site Metas -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-free-6.2.1-web/css/all.min.css') }}">
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('css/bootstrap/js/bootstrap.min.js') }}"></script>
    <script>
        function printWithSpecialFileName() {
            var Tname = $("#Tname").html()
            var tempTitle = document.title;
            document.title = Tname + ".pdf";
            window.print();
            document.title = Tname;
        }
    </script>
    <style>
        @media print {
            .print_button {
                display: none;
            }

            * {
                visibility: visible !important;
            }

            .table-print .TdStyle {
                background-color: #30a6f0 !important;
            }
        }

        .TdStyle {
            background: #30a6f0 !important;
            border-radius: 5px;
            padding: 15px;
            width: 50%;
            text-align: center;
            color: white;
            font-size: 20px;
            font-weight: 900;
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>

<body>
    <img src='/images/header-logo.jpg' style='width:100%'>
    <br>
    <div class="MainDiv"> <button class='btn print_button' onclick="printWithSpecialFileName()">
            طباعة <i class='fa-solid fa-print fa-2x'></i>
        </button>
        <div class="row">
            <div class="col-md-6">
                <label class="MainLabel">راتب شهر </label>
                <label for="" class="valueLabel" id='Tname'>{{ $SalaryDetail->Month->MonthName }}</label>
            </div>
            <div class="col-md-6">
                <label class="MainLabel">المبلغ الكلي</label>
                <label for="" class="valueLabel">{{ number_format($SalaryDetail->TotalPaidAmount, 2) }}</label>
            </div>
        </div>
        <table class="table">
            <tr>
                <th>اسم الموظف</th>
                <th>الراتب</th>
                <th>اجمالي المسدد</th>
                <th>المسدد</th>
                <th>السلفيات</th>
                <th>المتبقي</th>
            </tr>
            @php
                $totalSalaryAmount = 0;
                $totalPaidAmount = 0;
                $totalLoans = 0;
                $totalCash = 0;
            @endphp

            @foreach ($Salaries as $Salary)
                <tr>
                    <td style="padding: 15px!important;">{{ $Salary->Employee->EmployeeName }}</td>
                    <td style="padding: 15px!important;">{{ number_format($Salary->SalaryAmount, 2) }}</td>
                    <td style="padding: 15px!important;">{{ number_format($Salary->PaidAmount, 2) }}</td>
                    <td style="padding: 15px!important;">{{ number_format($Salary->Cash, 2) }}</td>
                    <td style="padding: 15px!important;">{{ number_format($Salary->Loans, 2) }}</td>
                    <td style="padding: 15px!important;">
                        {{ number_format($Salary->SalaryAmount - $Salary->PaidAmount, 2) }}
                    </td>
                </tr>
                @php
                    $totalSalaryAmount += $Salary->SalaryAmount;
                    $totalPaidAmount += $Salary->PaidAmount;
                    $totalLoans += $Salary->Loans;
                    $totalCash += $Salary->Cash;
                @endphp
            @endforeach

            <!-- Display totals -->
            <tr>
                <th><strong>المجمل</strong></th>
                <th><strong>{{ number_format($totalSalaryAmount, 2) }}</strong></th>
                <th><strong>{{ number_format($totalPaidAmount, 2) }}</strong></th>
                <th><strong>{{ number_format($totalCash, 2) }}</strong></th>
                <th><strong>{{ number_format($totalLoans, 2) }}</strong></th>
                <th>
                    <strong>{{ number_format($totalSalaryAmount - $totalPaidAmount, 2) }}</strong>
                </th>
            </tr>
        </table>

</body>

</html>
<style>
    @font-face {
        font-family: 'MyFont';
        src: url('/font/Changa-VariableFont_wght.ttf') format('woff2');
        /* Add more src definitions for different font formats if necessary */
    }

    body {
        margin: 0;
        font-family: 'MyFont', sans-serif;
    }

    @media print {
        .print_button {
            display: none;
        }

        * {
            visibility: visible !important;
        }

        th {
            color: #274557 !important;
            font-weight: 900;
        }
    }

    .TdStyle {
        background: #274557 !important;
        border-radius: 5px;
        padding: 15px;
        width: 50%;
        text-align: center;
        color: white;
        font-size: 20px;
        font-weight: 900;
        -webkit-print-color-adjust: exact;
    }
</style>

<script>
    function printWithSpecialFileName() {
        var Tname = $("#Tname").html()
        var tempTitle = document.title;
        document.title = Tname + ".pdf";
        window.print();
        document.title = Tname;
    }
</script>
