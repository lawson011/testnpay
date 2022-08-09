@component('mail::message')
# Password Reset Token

<div>
Your password is: {{ $data['password'] }} <br>
Please <a href="{{$data['url']}}">click this link</a> to change your password.
</div>

<br>
Thanks,<br>
 {{ config('app.name') }}
@endcomponent
