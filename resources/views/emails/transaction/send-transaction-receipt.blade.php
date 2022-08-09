@component('mail::message')

## Dear {{ ucfirst($data['source_account_name']) }}

## Please find attached the receipt of your transaction:

@component('mail::panel')
    Details of this transaction can be found below
@endcomponent

@component('mail::table')
Amount :       {{ $data['amount'] }}

Beneficiary Name :       {{ $data['receivers_account_name'] }}

Beneficiary Account Number :       {{ $data['receivers_account_number'] }}

Transaction Date :       {{ now()->format('g:ia \o\n l jS F Y') }}

@endcomponent

Thank you for banking with us.

<img src="https://nuturemfbank.com/_nuxt/img/0235bbe.png" style="height: 50px;"/>

Nuture Microfinance Bank Limited.

@endcomponent
