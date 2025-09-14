<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PERNECZKY BarberShop</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('logo/favicon.ico') }}">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@800&display=swap');
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap');
        *{
            font-family: 'Poppins', sans-serif;
            margin: 0;
        }

        #innerDiv{
            margin-left: auto;
            margin-right: auto;
            max-width: 42rem;
        }

        header {
            padding-top: 2rem;
            padding-bottom: 2rem;
            text-align: center;
        }

        img {
            margin-left: auto;
            margin-right: auto;
            height: 5rem;
        }

        main{
            background-color: rgb(248 250 252);
            color: rgb(30 41 59);
            padding: 2rem;
        }

        h1{
            font-weight: 700;
            font-size: 1.5rem;
            line-height: 2rem;
        }

        table{
            border-collapse: collapse;
            width: 100%;
        }

        thead{
            background-color: rgb(226 232 240);
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
            border-radius: 0.375rem;
            font-weight: bold;
            max-height: fit-content;
            padding: 0.5rem;
            text-decoration: none;

            transition-property: all;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        #ctaButton:hover{
            background-color: #0f0f0f;
            filter: drop-shadow(0 4px 3px rgb(0 0 0 / 0.07));
        }

        #linkTrouble{
            color: rgb(100 116 139);
        }

        footer{
            color: rgb(100 116 139);
            font-size: small;
            margin-top: 2rem;
            margin-bottom: 2rem;
        }

        .changed {
            font-weight: bold;
        }

        .italic{
            font-style: italic;
        }

        .mb-4{
            margin-bottom: 1rem
        }

        .mb-8{
            margin-bottom: 2rem;
        }

        .text-center {
            text-align: center;
        }

        .link{
            color: rgb(29 78 216);
        }
        .link:hover{
            text-decoration: underline;
        }

        @media not all and (min-width: 720px) {
            body{
                font-size: small;
            }

            main{
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }
    </style>
</head>

<body>
    <div id="innerDiv">
        <header>
            <a href="{{ route('home') }}" target="_blank">
                <img src="{{ asset('logo/perneczky_circle.png') }}" alt="Perneczky BarberShop">
            </a>
        </header>

        <main>
            {{ $slot }}
        </main>

        <footer class="text-center">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved. Looking forward to serving you!
        </footer>
    </div>
</body>
</html>