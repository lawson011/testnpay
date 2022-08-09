@if(session()->has('error_message'))
    <div class="alert alert-danger" role="alert">
        <strong>Well done!</strong>
        {!! session('error_message') !!}
    </div>
@endif
