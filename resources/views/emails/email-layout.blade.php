<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PERNECZKY BarberShop</title>

    <style>

        body, td, div, p, a {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Helvetica Neue', Arial, sans-serif;
            font-weight: 400;
        }

        body{
            margin: 0;
        }

        #innerDiv{
            margin-left: auto;
            margin-right: auto;
            max-width: 672px;
        }

        #header {
            padding-top: 32px;
            padding-bottom: 32px;
            text-align: center;
        }

        img {
            margin-left: auto;
            margin-right: auto;
            height: 80px;
        }

        #main{
            background-color: #F8FAFC;
            color: #1E293B;
            padding: 32px;
        }

        h1{
            font-weight: 700;
            font-size: 24px;
        }

        h4{
            line-height: 0;
        }

        table{
            border-collapse: collapse;
            width: 100%;
        }

        thead{
            background-color: #E2E8F0;
        }

        tbody{
            background-color: white;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        #ctaButton{
            background-color: #0018d5;
            color: white;
            border-width: 1px;
            border-radius: 6px;
            font-weight: 700;
            padding: 8px;
            text-decoration: none;

            transition-property: all;
            transition-duration: 150ms;
        }

        #ctaButton:hover{
            background-color: #0f0f0f;
        }

        #linkTrouble{
            color: #64748B;
        }

        #footer{
            color: #64748B;
            font-size: small;
            margin-top: 32px;
            margin-bottom: 32px;
            text-align: center;
        }

        .changed {
            font-weight: 700;
        }

        .italic{
            font-style: italic;
        }

        .mb-4{
            margin-bottom: 16px
        }

        .mb-8{
            margin-bottom: 32px;
        }

        .text-center {
            text-align: center;
        }

        .link{
            color: #1D4ED8;
            text-decoration: none;
        }
        .link:hover{
            text-decoration: underline;
        }

        .word-break{
            word-break: break-all;
        }

        .bg-slate-200{
            background-color: #E2E8F0;
        }

        @media not all and (min-width: 720px) {
            body{
                font-size: small;
            }

            #main{
                padding-left: 16px;
                padding-right: 16px;
            }
        }
    </style>
</head>

<body>
    <div id="innerDiv">
        <div id="header">
            <a href="{{ route('home') }}" target="_blank">
                <img src="{{ asset('logo/perneczky_circle.png') }}" alt="Perneczky BarberShop">
            </a>
        </div>

        <div id="main">
            {{ $slot }}
        </div>

        <div id="footer">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Looking forward to serving you!
        </div>
    </div>
</body>
</html>