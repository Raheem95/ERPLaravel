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

</head>

<body>
    <div class="MainDiv"> <button class='btn print_button' onclick="printWithSpecialFileName()">
            طباعة <i class='fa-solid fa-print fa-2x'></i>
        </button>
        <div class="row">
            <div class="col-md-4">
                <label class="input_label">راتب شهر </label>
                <label for="" class="input_style" id='Tname'>{{ $SalaryDetail->Month->MonthName }}</label>
            </div>
            <div class="col-md-4">
                <label class="input_label">المبلغ الكلي</label>
                <label for="" class="input_style">{{ number_format($SalaryDetail->TotalPaidAmount, 2) }}</label>
            </div>
            <div class="col-md-4 text-left">
                <img src="/images/logo.jpg"
                    style="width: 200px;height: 200px;border: 2px solid #e5dfdf;border-radius: 10px;" alt="">
            </div>
        </div>
        <br>
        <table class="table">
            <tr>
                <th class="TdStyle">اسم الموظف</th>
                <th class="TdStyle">الراتب</th>
                <th class="TdStyle">اجمالي المسدد</th>
                <th class="TdStyle">المسدد</th>
                <th class="TdStyle">السلفيات</th>
                <th class="TdStyle">المتبقي</th>
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
                <th class="TdStyle">المجمل</th>
                <th class="TdStyle">{{ number_format($totalSalaryAmount, 2) }}</th>
                <th class="TdStyle">{{ number_format($totalPaidAmount, 2) }}</th>
                <th class="TdStyle">{{ number_format($totalCash, 2) }}</th>
                <th class="TdStyle">{{ number_format($totalLoans, 2) }}</th>
                <th class="TdStyle"> {{ number_format($totalSalaryAmount - $totalPaidAmount, 2) }}
                </th>
            </tr>
        </table>

</body>

</html>
<script>
    function printWithSpecialFileName() {
        var Tname = $("#Tname").html()
        var tempTitle = document.title;
        document.title = Tname + ".pdf";
        window.print();
        document.title = Tname;
    }
</script>
