<html>

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
    <style>
        @font-face {
            font-family: 'MyFont';
            src: url('/font/Changa-VariableFont_wght.ttf') format('woff2');
            /* Add more src definitions for different font formats if necessary */
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'MyFont', sans-serif;
            background: gainsboro
        }

        .error-div {
            padding: 30px;
            background: #fffcfc;
            text-align: center;
            border-radius: 10px;
        }

        .back-home {
            margin: 40px;
            font-size: 25px;
            color: #6f0d11;
            border: 1px solid #6f0d11;
            background: #fff;
            padding: 10px;
        }

        .btn:hover {
            border: 1px solid #6f0d11;
            color: #fff;
            background: #6f0d11;
        }
    </style>
</head>

<body>
    <div class="error-div">
        <h1>404<span>عذرًا، الصفحة التي تبحث عنها غير موجودة.</span></h1>
        <a href="/"><button class="btn back-home">عودة للصفحة الرئيسية </button></a>
    </div>
    </section>
</body>

</html>
