@component('mail::message')
# Welcome Back!!!

<div>
    Hello {{ $data['full_name'] }},

    Thank you for banking with us all this while. We hope that you have had an incredible experience with us.

    At Nuture, we aim to always provide quality service and we realise that now is the best time to take it one step further.

    To serve you better, we have introduced Nuture Pay. A digital mobile banking app that serves all your needs - bank transfers, bill payments,

    withdrawals with our unique ATM cards, as well as access to loans. With Nuture Pay, you are always good to go. No downtime, no excuses.

    We have made it absolutely seamless for you. All you have to do is download the Nuture Pay app on PlayStore (for Android) and AppStore (for iOS).

    Please see your personal login details to activate your account below:
 <b>
    Username: {{ $data['username'] }}
 </b>

    After downloading the app, click on login, forgot password your password will be reset then you can start enjoying seamless banking

</div>

<br>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
