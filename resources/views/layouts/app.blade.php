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

    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.2.1-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">


    <!-- Font Awesome CSS -->

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">

    <script>
        function handleClickOutside(event) {
            var div = document.getElementById('ContainerNav');
            var sidebar = document.getElementById('sidebar');
            var icon = document.getElementById('icon');
            var arrowIcon = document.getElementById('ArrorIcon');
            // Check if the click is outside the sidebar and not on the arrow icon
            if (!div.contains(event.target) && event.target.id != "ArrorIcon") {
                sidebar.style.right = "-200px";
                icon.style.right = "25px";
                $("#icon").empty().append($('<i id="ArrorIcon" class="fa-solid fa-circle-arrow-left"></i>'));
            }
        }
        // Add event listener to the document to detect clicks
        document.addEventListener('click', handleClickOutside);

        function toggleSidebar(Type) {
            var sidebar = document.getElementById("sidebar");
            var icon = document.getElementById("icon");
            if (sidebar.style.right === "0px") {
                if (Type != 1) {
                    sidebar.style.right = "-200px";
                    icon.style.right = "25px";
                    $("#icon").empty().append($('<i id="ArrorIcon"  class="fa-solid fa-circle-arrow-left "></i>'))
                }
            } else {
                sidebar.style.right = "0px";
                icon.style.right = "225px";
                $("#icon").empty().append($('<i id="ArrorIcon" class="fa-solid fa-circle-xmark"></i>'));
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
        @font-face {
            font-family: 'MyFont';
            src: url('/font/Changa-VariableFont_wght.ttf') format('woff2');
            /* Add more src definitions for different font formats if necessary */
        }

        body {
            margin: 0;
            font-family: 'MyFont', sans-serif;
        }

        .toggle-btn {
            position: fixed;
            top: 50%;
            transform: translateY(-50%);
            right: 25px;
            font-size: 30px;
            background: #e7eef1;
            color: #274557;
            border: none;
            transition: 0.5s;
            padding: 15px;
            border-radius: 60px 0 0px 60px;
            z-index: 1;
        }

        .sidebar {
            height: 100%;
            width: 250px;
            position: fixed;
            top: 0;
            right: -200px;
            /* Initially hidden */
            background-color: #e7eef1;
            padding-top: 30px;
            transition: 0.5s;
            z-index: 2;
            overflow-y: scroll;
            /* Smooth transition effect */
        }

        .sidebar h2 {
            color: #274557;
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
            color: #274557;
            text-decoration: none;
            display: block;
            text-align: right;
            padding: 10px;
            font-weight: 900;
            border-bottom: 1px solid #274557;
            font-size: 18px;
        }

        .sidebara:hover {
            background-color: white;
            color: #274557;

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

        .sidebar::-webkit-scrollbar {
            height: 12px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: #e7eef1;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background: #e7eef1;
            border-radius: 10px;
        }

        .sidebar::-webkit-scrollbar-thumb:hover {
            background: #e7eef1;
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

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
            <div id="ContainerNav">
                <button class="toggle-btn notPrint" id="icon" onclick="toggleSidebar(0)">
                    <i id="ArrorIcon" class="fa-solid fa-circle-arrow-left"></i>
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
                            <a class="sidebara" href="#" onclick="toggleSidebar(1)">
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
                            <a class="sidebara" href="#" onclick="toggleSidebar(1)">
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
                            <a class="sidebara" href="{{ url('/AccountManagment/Sale') }}"><label
                                    class="HeaderA">فواتير
                                    المبيعات
                                </label></a>

                            <a class="sidebara" href="{{ url('/AccountManagment/Expenses') }}">المنصرفات </a>
                            <a class="sidebara" href="{{ url('/AccountManagment/CreditorsDebtors') }}">دائنون و
                                مدينون </a>
                            <a class="sidebara" href="{{ url('/AccountManagment/Salaries') }}">الرواتب </a>
                            <a class="sidebara" href="{{ url('/AccountManagment/Loans') }}">السلفيات </a>
                            <a class="sidebara" href="{{ url('/AccountManagment/DailyAccountingEntries') }}">القيود
                            </a>
                        </div>
                        <li>
                            <a class="sidebara" href="/Employees">
                                <i class="IconClass IconStyleClose fa-solid fa-user"></i>
                                <label class="HeaderA">الموظفين </label>
                            </a>
                        </li>

                        <li>
                            <a class="sidebara" style="color: red;background: white;" href="{{ route('logout') }}"
                                onclick="event.preventDefault();document.getElementById('logout-form').submit();">
                                <label class="HeaderA">خروج </label><i
                                    class="IconClass IconStyleClose fa-solid fa-arrow-right-from-bracket"></i>
                            </a>
                        </li>
                    </ul>
                </div>

                <div style="padding:20px;padding-right:120px;text-align:right;margin-top:10px;">
                    @include('inc.messages')
                    @yield('content')
                </div>

                <!-- Scripts -->
                <script src="{{ asset('js/app.js') }}"></script>

</body>
@endif

</html>
