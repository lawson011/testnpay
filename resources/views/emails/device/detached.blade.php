@component('mail::message')
# Device Detach Successful

Hi {{$username}}
<br>
You've successfully added your device - {{$devicename}} to your NutureMFB account.
<br>


<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
