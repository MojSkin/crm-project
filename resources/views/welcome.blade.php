@php
    $settings = [];
    foreach (\App\Models\Setting::all() as $val) {
        $settings[$val->key] = $val->value;
    }
    $size = ini_get('upload_max_filesize');
    $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
    $size = preg_replace('/[^0-9\.]/', '', $size);
    if ($unit) {
        $size = round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
    } else {
        $size = round($size);
    }
    $settings['max_allowed_file_size'] = $size;
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{  $settings['site_name'] ?? config('app.name') }}</title>
        @vite(['resources/css/app.css', 'resources/scss/main.scss'])
    </head>
    <body>
        <div id="app"></div>
        @routes
        <script>
            window.csrfToken = "{!! csrf_token() !!}";
            window.Settings = {!! json_encode($settings) !!}
        </script>
        @vite('resources/js/app.js')
    </body>
</html>
