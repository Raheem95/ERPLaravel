@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger Result">{{ $error }}</div>
    @endforeach
@endif

@if (session('success'))
    <div class="alert alert-success Result">
        {{ session('success') }}
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger  Result">
        {{ session('error') }}
    </div>
@endif
