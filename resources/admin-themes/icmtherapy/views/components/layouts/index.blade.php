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
        #logo-image { height: 2.5rem !important; max-width: 13rem; object-fit: contain; object-position: left center; width: 13rem !important; }
        .icm-admin-sidebar-toggle { align-items: center; background: #fff; border: 1px solid #d8e9eb; border-radius: .6rem; color: var(--icm-admin-ink); cursor: pointer; display: inline-flex; height: 2.25rem; justify-content: center; left: 14.8rem; position: fixed; top: .75rem; transition: background .2s ease, color .2s ease; width: 2.25rem; z-index: 10003; }
        .icm-admin-sidebar-toggle:hover { background: var(--icm-admin-soft); color: var(--icm-admin-teal); }
        .icm-admin-sidebar-toggle svg { height: 1.2rem; width: 1.2rem; }
        header v-mega-search > div { margin-left: 3.2rem !important; }
        [ref="appLayout"].sidebar-collapsed > div:first-child,
        [ref="appLayout"].sidebar-collapsed > div:first-child > div { width: 4.4rem !important; }
        [ref="appLayout"].sidebar-collapsed > div:nth-child(2) { padding-left: 5.6rem !important; }
        [ref="appLayout"].sidebar-collapsed .journal-scroll nav > div > a p { display: none; }
        [ref="appLayout"].sidebar-collapsed .journal-scroll nav > div { padding-left: 1rem; padding-right: 1rem; }
        .group\/container.sidebar-collapsed > div:first-child,
        .group\/container.sidebar-collapsed > div:first-child > div { width: 4.4rem !important; }
        .group\/container.sidebar-collapsed > div:nth-child(2) { padding-left: 5.6rem !important; }
        .group\/container.sidebar-collapsed .journal-scroll nav > div > a p { display: none; }
        .group\/container.sidebar-collapsed .journal-scroll nav > div { padding-left: 1rem; padding-right: 1rem; }
        .dark body { background: #112f34; }
        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
    </style>
</head>
<body class="h-full dark:bg-gray-950">
    <div id="app" class="h-full">
        <x-admin::flash-group />
        <x-admin::modal.confirm />
        <x-admin::layouts.header />
        <button
            id="icm-admin-sidebar-toggle"
            class="icm-admin-sidebar-toggle"
            type="button"
            aria-label="Contraer o expandir menú lateral"
            title="Contraer o expandir menú lateral"
            onclick="const layout = Array.from(document.querySelectorAll('#app > div')).find((element) => element.classList.contains('group/container')); if (layout) { const collapsed = ! layout.classList.contains('sidebar-collapsed'); layout.classList.toggle('sidebar-collapsed', collapsed); layout.classList.toggle('sidebar-not-collapsed', ! collapsed); document.cookie = `sidebar_collapsed=${collapsed ? 1 : 0}; path=/; max-age=2592000`; }"
        >
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                <path d="M4 7h16M4 12h16M4 17h16"></path>
            </svg>
        </button>
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
    <script>
        const icmAdminLogoUrl = '{{ asset('themes/shop/default/images/LogoIMC.webp') }}';

        const applyIcmAdminLogo = () => {
            document.querySelectorAll('#logo-image').forEach((logo) => {
                if (! logo.src.endsWith('/LogoIMC.webp')) {
                    logo.src = icmAdminLogoUrl;
                }

                logo.alt = 'ICM Therapy';
            });
        };

        const initializeIcmAdminHeader = () => {
            applyIcmAdminLogo();

            const layout = Array.from(document.querySelectorAll('#app > div')).find((element) => element.classList.contains('group/container'));
            const logoLink = document.querySelector('header a[href*="/admin/dashboard"]');

            if (! layout || ! logoLink || document.getElementById('icm-admin-sidebar-toggle')) {
                return;
            }

            const button = document.createElement('button');
            button.id = 'icm-admin-sidebar-toggle';
            button.className = 'icm-admin-sidebar-toggle';
            button.type = 'button';
            button.setAttribute('aria-label', 'Contraer o expandir menú lateral');
            button.setAttribute('title', 'Contraer o expandir menú lateral');
            button.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M4 7h16M4 12h16M4 17h16"/></svg>';
            button.addEventListener('click', () => {
                const currentLayout = Array.from(document.querySelectorAll('#app > div')).find((element) => element.classList.contains('group/container'));

                if (! currentLayout) {
                    return;
                }

                const isCollapsed = ! currentLayout.classList.contains('sidebar-collapsed');
                currentLayout.classList.toggle('sidebar-collapsed', isCollapsed);
                currentLayout.classList.toggle('sidebar-not-collapsed', ! isCollapsed);
                document.cookie = `sidebar_collapsed=${isCollapsed ? 1 : 0}; path=/; max-age=2592000`;
            });
            logoLink.insertAdjacentElement('afterend', button);
        };

        document.addEventListener('DOMContentLoaded', initializeIcmAdminHeader);
        window.addEventListener('load', initializeIcmAdminHeader);
        new MutationObserver(applyIcmAdminLogo).observe(document.body, {
            attributes: true,
            attributeFilter: ['src'],
            childList: true,
            subtree: true,
        });
    </script>
</body>
</html>
