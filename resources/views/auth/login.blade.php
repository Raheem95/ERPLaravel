<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل دخول</title>
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>



    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.2.1-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login_style.css') }}" rel="stylesheet">

</head>

<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-view" style="font-size:30px;">تسجيل دخول</h2>
            <img src="/images/logo.jpg" alt="EldinderLogo" class="img-fluid mb-4">
            <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="email" class="text-view">البريد الالكتروني</label><br>
                    <div class="inputContainer">
                        <i class="fa-solid fa-at fa-2x"></i>
                        <input id="email" type="email" class="form-control" name="email" autocomplete="off"
                            placeholder="البريد الالكتروني" value="{{ old('email') }}" required autofocus>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password" class="text-view">كلمة المرور</label>
                    <div class="inputContainer">
                        <i class="fa-solid fa-key fa-2x"></i>
                        <input id="password" type="password" class="form-control" name="password"
                            placeholder="كلمة المرور" autocomplete="off">
                    </div>
                </div>
                @if ($errors->has('email') || $errors->has('password'))
                    <div class="form-group login-result">
                        <span class="help-block">
                            <strong>خطاء في البريد الالكتروني او كلمة المرور</strong>
                        </span>
                    </div>
                @endif
                <div class="form-group text-center">
                    <button type="submit" class="btn-login">تسجيل دخول</button>
                </div>
            </form>
            <div class="form-group">
                <a href="/password/reset">
                    <label class="text-view" style="font-size: 12px"> نسيت كلمة المرور؟</label>
                </a>
            </div>
        </div>
    </div>
</body>

</html>
