<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    @vite(['resources/css/app.css', 'resources/scss/main.scss'])
</head>
<body>
<div id="app">
    salam
    <Page404/>
</div>

@vite('resources/js/app.js')
</body>

</html>
