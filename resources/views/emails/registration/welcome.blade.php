<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>

        body {
            background-color: black;
        }
        .container {
            min-height: 1080.55pt;
            width: 376pt;
            background-color: white;
            margin-left: auto;
            margin-right: auto;
        }

        .box1 {
            height: 400.17pt;
            width: 375pt;
        }
        .top {
            display: flex;

        }

        .element {
            padding-left: 63pt;
            padding-bottom: 5pt;
        }

        .box2 {
            /*background-image: url(../../assets/welcome-mail-assets/images/middle-alt-01.png);*/
            background-image: url("{{ url('assets/welcome-mail-assets/images/middle-alt-01.png') }}");
            height: 457.389pt;
            width: 375pt;
        }
        .textelement2 {
            display: flex;
        }

        .text {
            width: 95%;

        }

        .element2 {
            width: 10%;
            padding-top: 10%
        }

        .box3 {
            height: 188.642pt;
            width: 375pt;
        }

        .logo {
            width: 50%;
            height: 110pt;
            padding-left: 25pt;
        }

        .element {
            width: 50%;
        }

        .list {
            padding-bottom: 1pt;
        }

        .box1 h1 {
            padding-left: 35pt;
            font-family: 'Montserrat', sans-serif;
            font-weight: 300;
            font-size: 19pt;
        }
        .illustration {
            padding-left: 40pt;
        }

        .box1 h2 {
            padding-left: 35pt;
            font-family: 'Montserrat', sans-serif;
            font-weight: 550;
            font-size: 12.5pt;
        }

        .box1 h3 {
            padding-left: 35pt;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            font-size: 13pt;
        }

        .box1 p {
            padding-left: 35pt;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            font-size: 8pt;
        }

        .text p {
            padding-right: 45pt;
            padding-bottom: pt;
            font-family: 'Montserrat', sans-serif;
            padding-top: 0pt;
            line-height: 11pt;
            font-size: 7pt;

        }

        .box2 h1 {
            padding-left: 35pt;
            font-family: 'Montserrat', sans-serif;
            font-weight: 550;
            font-size: 10pt;
            padding-top: 20pt;
            padding-bottom: 5pt;
        }

        .box2 h1 {
            padding-left: 90pt;
        }
        .box2 img {
            padding-left: 40pt;
        }
        .box3 p{
            padding-left: 35pt;
            font-size: 7pt;
        }

        .text2 h1 {
            padding-left: 30pt;
            font-family: 'Montserrat', sans-serif;
            font-weight: 550;
            font-size: 12pt;
            padding-bottom: 50pt;
            line-height: 5pt;
        }

        .wrappersocial {
            display: flex;
            height: 18pt;
            padding-left: 5pt;

        }
        .social {
            padding-top: 3pt;
            width: 25pt;
            padding-left: 5pt;
            align-content: space-between;
        }

        .text3 p {
            padding-left: 35pt;
            font-family: 'Montserrat', sans-serif;
            font-weight: 400;
            font-size: 7pt;
        }

        .wrappersocial1 {
            height: 10pt;
            width: 100pt;
            padding-left: 270pt;
            padding-top: 8pt;
        }

        .app1 {
            padding-right: 10pt;
        }

        .foot {
            padding-left: 35pt;
            padding-bottom: 50pt;
        }

        .foot {
            display: flex;
        }

        .foot {
            width: 70%;
            height: 100pt;
            background-image: url("{{ url('assets/welcome-mail-assets/images/footer-01.png') }}");
            margin-left: 35pt;
        }

        .app {
            padding-left: 220pt;
            padding-top: 37pt;

        }
    </style>
{{--    <link rel="stylesheet" href="{{ url('assets/welcome-mail-assets/style.css') }}">--}}
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,700;0,800;0,900;1,600&display=swap" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="box1">
        <div class="top">
            <div class="logo">
                <img src="{{ asset('assets/welcome-mail-assets/images/Nuturelogonew-01-01.png') }}" alt="">
            </div>

            <div class="element">
                <img src="{{ asset('assets/welcome-mail-assets/images/cover-new-01.png') }}" alt="">
            </div>


        </div>

        <!-- Put name here -->
        <h1>Hello, {{$data['name']}}.</h1>

        <!-- Put account number here -->
        <h3>Your account number is {{$data['account_number']}}.</h3>
        <h2>We are so proud of you for choosing premium.</h2>
        <p>The future of digital banking technology is now in the palm of your hands.</p>
        <div class="illustration">
            <img src="{{ asset('assets/welcome-mail-assets/images/illustration-02-01.png') }}" alt="">
        </div>
        <div class="textelement2">
            <div class="text">
                <p>We understand that you are busy and that time is money. Well, congratulations 'cos you are in luck. We are a bank that doesn't waste your time or money. We do not believe that you should pay to save your own money or get the usual 'close of business' excuse when you need to withdraw. No way. This is a bank that gives you more on everything - payments, loans and investment on savings. Welcome to the premium MFbank, welcome to NuturePay.</p>
            </div>
            <div class="element2">
                <img src="{{ asset('assets/welcome-mail-assets/images/ring-01.png') }}" alt="">
            </div>
        </div>
    </div>
    <div class="box2">
        <h1>Here's how to enjoy our premium features;</h1>
        <div class="list">

            <img src="{{ asset('assets/welcome-mail-assets/images/listnew-01.png') }}" alt="">
        </div>

    </div>
    <div class="box3">
        <div class="text">
            <p>Please do not hesitate to contact us on 0700CALLNUTURE should you have any questions. We will contact you in the very near future to ensure you are completely satisfied with our services you have recieved thus far. We are always willing to learn, listen and innovate.</p>

            <textish>
                <p>While you enjoy your new app, let's connect on social media.</p>
            </textish>
        </div>
        <div class="wrappersocial">
            <div class="text2">
                <h1>Follow us on</h1>

            </div>
            <div class="social">
                <a href="https://web.facebook.com/nuturemfb/"><img src="{{ asset('assets/welcome-mail-assets/images/facebooknew-01.png') }}" alt=""></a>
            </div>
            <div class="social">
                <a href="https://www.instagram.com/nuturemfb/"><img src="{{ asset('assets/welcome-mail-assets/images/instagram-01.png') }}" alt=""></a>
            </div>
            <div class="social">
                <a href="https://twitter.com/nuturemfb"><img src="{{ asset('assets/welcome-mail-assets/images/twitter-01.png') }}" alt=""></a>
            </div>
            <div class="social">

                <!-- No linkedin yet so no link. Will notify when that is up. -->
                <a href="#"><img src="{{ asset('assets/welcome-mail-assets/images/linkedin-01.png') }}" alt=""></a>

            </div>
        </div>
        <div class="text3">
            <p>Nuture MFBank, the premium bank. The MFbank that gives you more. Cheers!</p>
        </div>

        <div class="foot">
            <!-- <div class="footer1">
{{--                <img src="{{ asset('assets/welcome-mail-assets/images/footer-01.png') }}" alt="">--}}
            </div> -->

            <!-- put appstore link here -->
            <div class="app">
                <div class="app1">
                    <a href="#"><img src="{{ asset('assets/welcome-mail-assets/images/appstore-01-01.png') }}" alt=""></a>
                </div>

                <!-- Put Playstore link here -->
                <div class="app1">
                    <a href="#"><img src="{{ asset('assets/welcome-mail-assets/images/playstore-01-01.png') }}" alt=""></a>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</body>
</html>
