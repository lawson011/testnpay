@if(session()->has('success'))
<div class="alert alert-success" role="alert">
    <strong>Well done!</strong>
    {!! session('success') !!}
</div>
@endif
