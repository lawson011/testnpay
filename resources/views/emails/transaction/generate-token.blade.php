@component('mail::message')
    # Transaction Pin Token

<div>
    Here is your Token for setting up a pin:
    <ul><b>{{ $token }}</b></ul>
    Token is valid for 10 minutes.
</div>

<br>
Thanks,
<br>
    {{ config('app.name') }}
@endcomponent
