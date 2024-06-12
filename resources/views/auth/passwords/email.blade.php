<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اعادة تعيين كلمة المرور</title>
    <script src="{{ asset('js/jquery-3.2.1.min.js') }}"></script>

    <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>



    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-free-6.2.1-web/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/login_style.css') }}" rel="stylesheet">
    <style>
        .form-group {
            position: relative;
            margin-bottom: 2.5rem;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.inputContainer input');
            inputs.forEach(input => {
                if (input.value.trim() !== '') {
                    input.classList.add('filled');
                }
                input.addEventListener('focus', () => {
                    input.classList.add('filled');
                });
                input.addEventListener('blur', () => {
                    if (input.value.trim() === '') {
                        input.classList.remove('filled');
                    }
                });
            });
        });
    </script>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <h2 class="text-view" style="font-size:30px;">تسجيل دخول</h2>
            <img src="/images/logo.jpg" alt="EldinderLogo" class="img-fluid mb-4">
            <form class="form-horizontal" method="POST" action="{{ route('password.email') }}">
                {{ csrf_field() }}
                <div class="form-group">
                    <div class="inputContainer">
                        <i class="fa-solid fa-at fa-2x"></i>
                        <input id="email" type="email" class="form-control" name="email" autocomplete="off"
                            value="{{ old('email') }}" required autofocus placeholder=" ">
                        <label for="email" class="floating-label">البريد الالكتروني</label>
                    </div>
                </div>
                @if ($errors->has('email'))
                    <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                @endif
                <div class="form-group text-center">
                    <button type="submit" class="btn-login">ارسال الرابط </button>
                </div>
            </form>
        </div>
    </div>
</body>

</html>
