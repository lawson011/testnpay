@component('mail::message')
# Device Detach Token

<div>
    Here is your device detach token:
    <ul><b>{{$token}}</b></ul>
    Token is valid for 10 minutes.
</div>

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent