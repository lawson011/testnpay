@component('mail::message')

Hi {{$username}},<br>
Your loan has been approved and has been disbursed to your bank account.<br>
Love!<br>

{{ config('app.name') }} By Nuture Microfinance Bank.
@endcomponent
