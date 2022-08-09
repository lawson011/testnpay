@component('mail::message')
# Password Reset Token

<div>
    Here is your password reset token:
<ul><b>{{$token}}</b></ul>
    Token is valid for 10 minutes.
</div>

<br>
    Thanks,
<br>
    {{ config('app.name') }}
@endcomponent
