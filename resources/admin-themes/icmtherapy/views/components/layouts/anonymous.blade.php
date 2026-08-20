<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">
<head>
    <title>{{ $title ?? 'ICM Therapy' }}</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="base-url" content="{{ url()->to('/') }}">
    @stack('meta')
    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="icon" type="image/webp" href="{{ asset('themes/shop/default/images/LogoIMC.webp') }}">
    @stack('styles')
    <style>
        body { background: #f5fafb; font-family: 'Poppins', sans-serif; }
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>
    {!! view_render_event('bagisto.admin.layout.head') !!}
</head>
<body>
    {!! view_render_event('bagisto.admin.layout.body.before') !!}
    <div id="app">
        <x-admin::flash-group />
        {!! view_render_event('bagisto.admin.layout.content.before') !!}
        {{ $slot }}
        {!! view_render_event('bagisto.admin.layout.content.after') !!}
    </div>
    {!! view_render_event('bagisto.admin.layout.body.after') !!}
    @stack('scripts')
    {!! view_render_event('bagisto.admin.layout.vue-app-mount.before') !!}
    <script>
        window.addEventListener('load', function () {
            app.mount('#app');
        });
    </script>
    {!! view_render_event('bagisto.admin.layout.vue-app-mount.after') !!}
    <script type="text/javascript">{!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}</script>
</body>
</html>
