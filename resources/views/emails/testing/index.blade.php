@component('mail::message')
    # Registration Otp-Code

    <div>
        Here is your registration Otp-Code:
        <ul>Welcome to the town of blue revel</ul>
        Token is valid for 10 minutes.
    </div>

    <br>
    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
