<!DOCTYPE html>
<html class="{{ request()->cookie('dark_mode') ?? 0 ? 'dark' : '' }}" lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">
<head>
    <title>{{ $title ?? 'ICM Therapy Admin' }}</title>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="base-url" content="{{ url()->to('/') }}"><meta name="currency" content="{{ core()->getBaseCurrency()->toJson() }}">
    @stack('meta')
    @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">
    @stack('styles')
    <style>
        :root { --icm-admin-teal: #1aa6b2; --icm-admin-green: #70b63f; --icm-admin-ink: #293238; --icm-admin-soft: #edf8f8; }
        body { background: #f5fafb; color: var(--icm-admin-ink); font-family: 'Poppins', sans-serif; }
        #app > div:first-child + div + div { font-family: 'Poppins', sans-serif; }
        .bg-navyBlue, [class*="bg-navyBlue"] { background-color: var(--icm-admin-teal) !important; }
        .text-navyBlue, [class*="text-navyBlue"] { color: var(--icm-admin-teal) !important; }
        .border-navyBlue, [class*="border-navyBlue"] { border-color: var(--icm-admin-teal) !important; }
        button, input, select, textarea { font-family: inherit; }
        input:focus, select:focus, textarea:focus { border-color: var(--icm-admin-teal) !important; box-shadow: 0 0 0 3px rgba(26,166,178,.13) !important; }
        [class*="rounded"] { border-radius: .75rem; }
        .dark body { background: #112f34; }
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>
</head>
<body class="h-full dark:bg-gray-950">
    <div id="app" class="h-full">
        <x-admin::flash-group />
        <x-admin::modal.confirm />
        <x-admin::layouts.header />
        <div class="group/container {{ request()->cookie('sidebar_collapsed') ?? 0 ? 'sidebar-collapsed' : 'sidebar-not-collapsed' }} flex flex-col gap-0 lg:flex-row lg:gap-4" ref="appLayout">
            <div class="lg:fixed lg:top-[62px] lg:left-0 rtl:lg:right-0 rtl:lg:left-auto lg:z-10 w-full lg:w-auto"><x-admin::layouts.sidebar /></div>
            <div class="flex min-h-[calc(100vh-62px)] max-w-full flex-1 flex-col bg-white px-2 pt-3 transition-all duration-300 sm:px-4 lg:pl-[286px] lg:pt-3 lg:group-[.sidebar-collapsed]/container:ltr:pl-[85px] rtl:lg:pr-[286px] dark:bg-gray-950">
                <div class="pb-6"><div class="w-full overflow-x-hidden">{{ $slot }}</div></div>
                <div class="mt-auto border-t bg-white py-3 text-center text-xs text-slate-500 dark:border-gray-800 dark:bg-gray-900">© {{ date('Y') }} ICM Therapy · Administración</div>
            </div>
        </div>
    </div>
    @stack('scripts')
    <script>window.addEventListener('load', function () { app.mount('#app'); });</script>
</body>
</html>
