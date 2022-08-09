@component('mail::message')
# Login Opt-Code

<div>
    Here is your login Opt-Code:
    <ul><b>{{$token}}</b></ul>
    Token is valid for 10 minutes.
</div>

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
