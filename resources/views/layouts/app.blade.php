<!DOCTYPE html>
<html lang="{{ app()->getLocale() }} " dir="rtl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.2.1-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Font Awesome CSS -->

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById("sidebar");

            var icon = document.getElementById("icon");
            if (sidebar.style.right === "0px") {
                sidebar.style.right = "-200px";
                icon.style.right = "2px";
            } else {
                sidebar.style.right = "0px";
                icon.style.right = "200px";
            }
        }
        $(document).on('click', '.ViewSubMenu', function() {
            if ($("#" + $(this).attr("id") + "Div").css("display") == "grid")
                $("#" + $(this).attr("id") + "Div").css("display", "none")
            else
                $("#" + $(this).attr("id") + "Div").css("display", "grid")
        });
    </script>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .toggle-btn {
            position: fixed;
            top: 20px;
            right: 0px;
            font-size: 20px;
            cursor: pointer;
            background: none;
            border: none;
            color: #111;
            z-index: 3;
            transition: 0.5s;
            color: white;
            position: fixed;
            font-size: 30px;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            right: -200px;
            /* Initially hidden */
            background-color: #30a6f0;
            padding-top: 90px;
            transition: 0.5s;
            z-index: 1;
            overflow-y: scroll;
            /* Smooth transition effect */
        }

        .sidebar h2 {
            color: #30a6f0;
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 3px;
        }

        .sidebara {
            color: white;
            text-decoration: none;
            display: block;
            text-align: right;
            padding: 10px;
            font-weight: 900;
            border-bottom: 1px solid white;
            font-size: 18px;
        }

        .sidebara:hover {
            background-color: white;
            color: #30a6f0;

        }

        .SupMenuClass {
            position: relative;
            display: none;
            z-index: 99;
            border-radius: 5px;
            text-align: right;
            margin-right: 30px;
            width: 100%;
        }

        .IconStyleClose {
            position: absolute;
            left: 5px;
        }
    </style>
</head>

<body>
    @if (!Auth::check())
        <?php
        return redirect()->guest('/');
        ?>
    @else
        <div id="app" class="content" style="width:100%">
            <div class='MainLabel notPrint'>
                <label class='MainLabel' style='text-align: right; padding-right: 50px; font-size: 20px;'>
                    مرحبا <b>
                        {{ Auth::user()->name }}
                    </b>
                </label>
                <a href="logout.php" style="position: absolute;top: 10;left: 10px;">
                    <button class="btn delete_button">خروج <i
                            class=" fa-solid fa-arrow-right-from-bracket"></i></button>
                </a>
                <a href="{{ route('logout') }}"
                    style="position: absolute;top: 10;left: 10px;"onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                    <button class="btn delete_button">خروج <i class=" fa-solid fa-arrow-right-from-bracket"></i>
                    </button>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </div>
            <button class="toggle-btn notPrint" id="icon" onclick="toggleSidebar()">
                <i class="fa-brands fa-elementor"></i>
            </button>
            <div class="sidebar notPrint" id="sidebar">
                <ul>
                    <li><a class="sidebara" href="/home">
                            <i class="IconClass IconStyleClose fa-solid fa-house"></i>
                            <label class="HeaderA">الرئيسية</label></a>
                    </li>
                    <li>
                        <a class="sidebara" href="/purchases">
                            <i class="IconClass IconStyleClose fa-solid fa-cart-shopping"></i>
                            <label class="HeaderA">المشتريات </label>
                        </a>
                    </li>
                    <li>
                        <a class="sidebara" href="/sales">
                            <i class="IconClass IconStyleClose fa-solid fa-basket-shopping"></i>
                            <label class="HeaderA">المبيعات </label>
                        </a>
                    </li>
                    <li>
                        <a class="sidebara" href="/categories">
                            <i class="IconClass IconStyleClose fa-solid fa-layer-group"></i>
                            <label class="HeaderA">الاصناف </label>
                        </a>
                    </li>
                    <li><a class="sidebara" href="/items">
                            <i class="IconClass IconStyleClose fa-solid fa-boxes-stacked"></i>
                            <label class="HeaderA">المنتجات </label>
                        </a>
                    </li>
                    <li id="StockSubMenu" class="ViewSubMenu">
                        <a class="sidebara" href="#">
                            <i class="IconClass IconStyleClose fa-solid fa-warehouse"></i>
                            <label class="HeaderA">المخازن </label>
                        </a>
                    </li>
                    <div id="StockSubMenuDiv" class='SupMenuClass' style="display:none;">
                        <a class="sidebara" href="{{ url('/Stocks/StockManagment') }}"><label class="HeaderA">
                                ادارة المخازن</label></a>
                        <a class="sidebara" href="{{ url('/Stocks/Purchases') }}"><label class="HeaderA">فواتير
                                المشتريات
                            </label></a>
                        <a class="sidebara" href="{{ url('/Stocks/Sales') }}"><label class="HeaderA">
                                فواتير المبيعات
                            </label></a>
                        <a class="sidebara" href="{{ url('/Stocks/Transfare') }}">التحويلات المخزنية</a>
                    </div>
                    <li>
                        <a class="sidebara" href="/customers">
                            <i class="IconClass IconStyleClose fa-solid fa-people-group"></i>
                            <label class="HeaderA">العملاء</label>
                        </a>
                    </li>
                    <li>
                        <a class="sidebara" href="/suppliers">
                            <i class="IconClass IconStyleClose fa-solid fa-people-carry-box"></i>
                            <label class="HeaderA">الموردين</label></a>
                    </li>
                    <li id="AccountSubMenu" class="ViewSubMenu">
                        <a class="sidebara" href="#">
                            <i class="IconClass IconStyleClose fa-solid fa-sack-dollar"></i>
                            <label class="HeaderA">حسابات </label>
                        </a>
                    </li>
                    <div id="AccountSubMenuDiv" class='SupMenuClass' style="display:none;">
                        <a class="sidebara" href="{{ url('/AccountManagment/AccountTypes') }}"><label
                                class="HeaderA">
                                انواع الحسابات </label></a>
                        <a class="sidebara" href="{{ url('/AccountManagment/Currencies') }}"><label
                                class="HeaderA">العملات
                            </label></a>
                        <a class="sidebara" href="{{ url('/AccountManagment/Accounts') }}"><label
                                class="HeaderA">ادارة
                                الحسابات </label></a>
                        <a class="sidebara" href="{{ url('/AccountManagment/Purchase') }}"><label
                                class="HeaderA">فواتير المشتريات
                            </label></a>
                        <a class="sidebara" href="{{ url('/AccountManagment/Sale') }}"><label class="HeaderA">فواتير
                                المبيعات
                            </label></a>
                        <a class="sidebara" href="expenses.php">المنصرفات </a>
                        <a class="sidebara" href="CreditorsDebtors.php">دائنون و
                            مدينون </a>

                        <a class="sidebara" href="Salaries.php">الرواتب </a>

                        <a class="sidebara" href="Loans.php">السلفيات </a>



                        <a class="sidebara" href="{{ url('/AccountManagment/DailyAccountingEntries') }}">القيود
                        </a>
                    </div>

                    <li>
                        <a class="sidebara" href="employees.php">
                            <i class="IconClass IconStyleClose fa-solid fa-user"></i>
                            <label class="HeaderA">الموظفين </label>
                        </a>
                    </li>
                </ul>
            </div>

            <div style="padding:20px;padding-right:120px;text-align:right;">
                @include('inc.messages')
                @yield('content')
            </div>

            <!-- Scripts -->
            <script src="{{ asset('js/app.js') }}"></script>

</body>
@endif

</html>
