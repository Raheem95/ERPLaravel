@if (count($errors) > 0)
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger Result">{{ $error }}</div>
    @endforeach
@endif

@if (session('success'))
    <input type="hidden" id="ResultText" value="{{ session('success') }}">
    <input type="hidden" id="ResultType" value="success">
@endif
@if (session('error'))
    <input type="hidden" id="ResultText" value="{{ session('error') }}">
    <input type="hidden" id="ResultType" value="danger">
@endif
