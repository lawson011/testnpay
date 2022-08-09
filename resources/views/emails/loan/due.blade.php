@component('mail::message')

Hi {{$username}},<br>
Your loan is due on {{$enddate}}. Please ensure the card linked to your NuturePay account is funded for repayment.<br>
Love!<br>

{{ config('app.name') }} By Nuture Microfinance Bank.
@endcomponent
