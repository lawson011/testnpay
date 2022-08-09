@component('mail::message')

Hi {{$data['name']}},<br><br>
We are sorry, your loan application has been denied.<br><br>
<b>Remarks: </b> {{ $data['remarks'] }} <br><br>
Please try again at a later time.<br><br>
Love!<br>


{{ config('app.name') }} By Nuture Microfinance Bank.
@endcomponent
