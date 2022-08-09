@component('mail::message')
# Hello {{ $data['name'] }}

<div>
  You requested for a password reset for admin dashboard, please visit the link below
</div>

@component('mail::button', ['url' => $data['url']])
  Click Button
@endcomponent

<br>
  Thanks,
<br>
  {{ config('app.name') }}
@endcomponent
