<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>The web co</title>

    <link rel="apple-touch-icon" sizes="57x57" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/favicon.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="/favicon/favicon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="/favicon/favicon.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="/favicon/favicon.png">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ mix('css/app.css' , '/dist') }}" type="text/css" rel="stylesheet" />
    <link href="https://unpkg.com/element-ui@2.13.2/lib/theme-chalk/index.css" type="text/css" rel="stylesheet" />
</head>
<body>
    <div id="app">
        <app></app>
    </div>

    <script src="https://unpkg.com/vue@2.6.11/dist/vue.min.js"></script>
    <script src="https://unpkg.com/element-ui@2.13.2/lib/index.js"></script>
    <script src="{{ mix('app.js', '/dist') }}"></script>
</body>
</html>
