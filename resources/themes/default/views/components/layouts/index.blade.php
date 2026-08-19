@props([
    'hasHeader'  => true,
    'hasFeature' => true,
    'hasFooter'  => true,
])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ core()->getCurrentLocale()->direction }}">
    <head>
        {!! view_render_event('bagisto.shop.layout.head.before') !!}

        <title>{{ $title ?? '' }}</title>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="content-language" content="{{ app()->getLocale() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="base-url" content="{{ url()->to('/') }}">
        <meta name="currency" content="{{ core()->getCurrentCurrency()->toJson() }}">
        <meta name="generator" content="Bagisto">

        @stack('meta')

        <link rel="icon" sizes="16x16" href="{{ core()->getCurrentChannel()->favicon_url ?? bagisto_asset('images/favicon.ico') }}">
        @bagistoVite(['src/Resources/assets/css/app.css', 'src/Resources/assets/js/app.js'])
        <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap">

        @stack('styles')

        <style>
            :root {
                --icm-teal: #1aa6b2;
                --icm-teal-dark: #128d99;
                --icm-green: #70b63f;
                --icm-ink: #293238;
                --icm-muted: #879196;
                --icm-soft: #eaf8f9;
            }

            html { scroll-behavior: smooth; }
            body { background: #fff; color: var(--icm-ink); font-family: 'Poppins', sans-serif; }
            main { overflow: hidden; }
            header { border-top: 3px solid var(--icm-green); transition: box-shadow .3s ease; }
            header > div, header .min-h-\[78px\] { min-height: 74px; }
            header a { transition: color .2s ease, transform .2s ease; }
            header a:hover { color: var(--icm-teal); }
            header input[name="query"] { background: #fff !important; border: 1px solid #dce3e5 !important; border-radius: 999px !important; font-size: .85rem; }
            header input[name="query"]:focus { border-color: var(--icm-teal) !important; box-shadow: 0 0 0 3px rgba(26, 166, 178, .13); }
            header .icon-search { color: var(--icm-teal); }
            header a:has(> img[src*="logo"]) { background: url('{{ asset('themes/shop/default/images/LogoIMC.webp') }}') left center / contain no-repeat; display: block; height: 48px; width: 190px; }
            header a:has(> img[src*="logo"]) img { height: 0; opacity: 0; overflow: hidden; width: 0; }
            .primary-button { background: var(--icm-teal) !important; border-color: var(--icm-teal) !important; }
            .primary-button:hover { background: var(--icm-teal-dark) !important; }
            .secondary-button { color: var(--icm-teal) !important; border-color: var(--icm-teal) !important; transition: background-color .2s ease, color .2s ease, transform .2s ease; }
            .secondary-button:hover { background: var(--icm-teal) !important; color: #fff !important; transform: translateY(-2px); }
            .icm-brand { align-items: center; display: inline-flex; gap: .55rem; white-space: nowrap; }
            .icm-brand-mark { align-items: center; background: linear-gradient(145deg, var(--icm-green), var(--icm-teal)); border-radius: 1rem; color: #fff; display: inline-flex; font-size: 1.35rem; font-weight: 800; height: 3.15rem; justify-content: center; width: 3.15rem; }
            .icm-brand-name { color: var(--icm-teal); font-size: 1.45rem; font-weight: 800; letter-spacing: -.06em; }
            .icm-brand-name span { color: var(--icm-green); }
            .icm-home-hero { background: linear-gradient(135deg, #168b99 0%, var(--icm-teal) 52%, #40bbc3 100%); border-radius: 0 0 2.5rem 2.5rem; color: #fff; isolation: isolate; overflow: hidden; position: relative; }
            .icm-home-hero::after { background: rgba(255,255,255,.16); border-radius: 50% 50% 0 0; bottom: -7rem; content: ''; height: 14rem; left: -5%; position: absolute; transform: rotate(-6deg); width: 110%; z-index: -1; }
            .icm-home-hero::before { border: 1px solid rgba(255,255,255,.22); border-radius: 999px; content: ''; height: 25rem; position: absolute; right: -9rem; top: -12rem; width: 25rem; z-index: -1; }
            .icm-home-hero h1 { font-size: clamp(3rem, 8vw, 7rem); font-weight: 700; letter-spacing: -.06em; line-height: .95; position: relative; z-index: 1; }
            .icm-home-hero p { color: rgba(255,255,255,.85); position: relative; z-index: 1; }
            .icm-hero-kicker { align-items: center; animation: icm-reveal .6s ease both; display: inline-flex; gap: .65rem; }
            .icm-hero-kicker::before { background: var(--icm-green); border-radius: 999px; content: ''; height: .55rem; width: .55rem; }
            .icm-hero-title { animation: icm-reveal .7s .1s ease both; }
            .icm-hero-copy { animation: icm-reveal .7s .2s ease both; }
            .icm-hero-actions { animation: icm-reveal .7s .3s ease both; display: flex; flex-wrap: wrap; gap: .75rem; position: relative; z-index: 1; }
            .icm-hero-actions a { border-radius: 999px; font-size: .9rem; font-weight: 700; padding: .85rem 1.25rem; transition: transform .2s ease, background-color .2s ease; }
            .icm-hero-actions a:hover { transform: translateY(-3px); }
            .icm-hero-primary { background: #fff; color: var(--icm-teal); }
            .icm-hero-secondary { border: 1px solid rgba(255,255,255,.55); color: #fff; }
            .icm-hero-secondary:hover { background: rgba(255,255,255,.14); }
            .icm-section-title { color: var(--icm-ink); font-size: clamp(1.75rem, 4vw, 3rem); font-weight: 700; letter-spacing: -.04em; }
            .icm-section-title::after { background: var(--icm-green); border-radius: 999px; content: ''; display: block; height: .3rem; margin-top: .7rem; width: 4.5rem; }
            #icm-products .container { margin-top: 0; padding-left: 0; padding-right: 0; }
            #icm-products .font-dmserif { font-family: 'Poppins', sans-serif; font-size: 1.45rem; font-style: normal; font-weight: 700; letter-spacing: -.04em; }
            #icm-products .icon-arrow-left-stylish, #icm-products .icon-arrow-right-stylish { align-items: center; background: #fff; border: 1px solid #d7e7e9; border-radius: 999px; color: var(--icm-teal); display: inline-flex !important; font-size: 1rem; height: 2.7rem; justify-content: center; transition: background-color .2s ease, color .2s ease, transform .2s ease; width: 2.7rem; }
            #icm-products .icon-arrow-left-stylish:hover, #icm-products .icon-arrow-right-stylish:hover { background: var(--icm-teal); color: #fff; transform: translateY(-2px); }
            #icm-products .icon-arrow-left-stylish:focus-visible, #icm-products .icon-arrow-right-stylish:focus-visible { outline: 3px solid rgba(26, 166, 178, .25); outline-offset: 3px; }
            .icm-product-card { background: #fff; border: 1px solid #e4eeee; border-radius: 1.25rem; box-shadow: 0 10px 30px rgba(31, 61, 67, .06); display: flex; flex-direction: column; height: 100%; overflow: hidden; transition: box-shadow .25s ease, transform .25s ease; }
            .icm-product-card:hover { box-shadow: 0 18px 38px rgba(26, 166, 178, .18); transform: translateY(-6px); }
            .icm-product-card__media { aspect-ratio: 1 / 1.04; background: #f3f7f7; display: block; overflow: hidden; position: relative; }
            .icm-product-card__media img { height: 100%; object-fit: cover; transition: transform .35s ease; width: 100%; }
            .icm-product-card:hover .icm-product-card__media img { transform: scale(1.05); }
            .icm-product-card__badge { background: var(--icm-green); border-radius: 999px; color: #fff; font-size: .72rem; font-weight: 700; left: .75rem; letter-spacing: .03em; padding: .35rem .65rem; position: absolute; text-transform: uppercase; top: .75rem; }
            .icm-product-card__badge--sale { background: #e8584e; }
            .icm-product-card__quick-actions { display: flex; flex-direction: column; gap: .45rem; position: absolute; right: .75rem; top: .75rem; }
            .icm-product-card__icon { align-items: center; background: rgba(255,255,255,.94); border: 1px solid #e4eeee; border-radius: 999px; color: var(--icm-ink); cursor: pointer; display: inline-flex; font-size: 1rem; height: 2.35rem; justify-content: center; transition: background-color .2s ease, color .2s ease, transform .2s ease; width: 2.35rem; }
            .icm-product-card__icon:hover { background: var(--icm-teal); color: #fff; transform: translateY(-2px); }
            .icm-product-card__content { display: flex; flex: 1; flex-direction: column; gap: .65rem; padding: 1rem; }
            .icm-product-card__name { color: var(--icm-ink); display: -webkit-box; font-size: .96rem; font-weight: 600; line-height: 1.35; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; }
            .icm-product-card__name:hover { color: var(--icm-teal); }
            .icm-product-card__price { color: var(--icm-ink); font-size: 1.08rem; font-weight: 700; margin-top: auto; }
            .icm-product-card__price .text-red-600 { color: #df5148 !important; }
            .icm-product-card__add { border-radius: .75rem; font-size: .84rem; font-weight: 700; margin-top: .2rem; padding: .7rem; width: 100%; }
            .icm-product-card--list { align-items: stretch; display: grid; grid-template-columns: minmax(150px, 230px) 1fr; max-width: none; }
            .icm-product-card--list .icm-product-card__media { aspect-ratio: auto; min-height: 220px; }
            .icm-product-card--list .icm-product-card__content { justify-content: center; padding: 1.5rem; }
            v-category .container, v-search .container { max-width: 1440px; }
            v-category .panel-side, v-search .panel-side { background: var(--icm-soft); border: 1px solid #d9eeee; border-radius: 1rem; padding: 1.1rem; }
            v-category .panel-side input, v-search .panel-side input { background: #fff; border-color: #d4e5e7; }
            v-category .panel-side input:focus, v-search .panel-side input:focus { border-color: var(--icm-teal); box-shadow: 0 0 0 3px rgba(26,166,178,.12); }
            v-category [class*="rounded-lg"][class*="border-zinc-200"], v-search [class*="rounded-lg"][class*="border-zinc-200"] { border-color: #d4e5e7; border-radius: .8rem; }
            v-category [class*="bg-gray-100"], v-search [class*="bg-gray-100"] { background: var(--icm-soft); }
            v-category .fixed.bottom-0, v-search .fixed.bottom-0 { border-color: #d5e5e7; box-shadow: 0 -8px 25px rgba(31,61,67,.08); }
            v-product-card [class*="bg-navyBlue"] { background: var(--icm-green) !important; }
            main input:not([type="checkbox"]):not([type="radio"]), main select, main textarea { border-color: #d5e5e7 !important; border-radius: .8rem !important; }
            main input:not([type="checkbox"]):not([type="radio"]):focus, main select:focus, main textarea:focus { border-color: var(--icm-teal) !important; box-shadow: 0 0 0 3px rgba(26,166,178,.12) !important; }
            main .container > .flex.w-full.justify-between, main .container > .flex.items-center { border-color: #dcebed; }
            main [class*="font-dmserif"] { color: var(--icm-ink); font-family: 'Poppins', sans-serif; font-style: normal; font-weight: 700; letter-spacing: -.04em; }
            main [class*="rounded-xl"][class*="border-zinc-200"], main [class*="rounded-lg"][class*="border-zinc-200"] { border-color: #dcebed; box-shadow: 0 10px 30px rgba(31,61,67,.05); }
            v-mini-cart [class*="bg-navyBlue"], v-cart [class*="bg-navyBlue"], v-checkout [class*="bg-navyBlue"] { background: var(--icm-green) !important; }
            v-mini-cart [class*="rounded"], v-cart [class*="rounded"], v-checkout [class*="rounded"] { border-radius: 1rem; }
            v-mini-cart [class*="border-zinc-200"], v-cart [class*="border-zinc-200"], v-checkout [class*="border-zinc-200"] { border-color: #dcebed; }
            v-mini-cart [class*="bg-gray-100"], v-cart [class*="bg-gray-100"], v-checkout [class*="bg-gray-100"] { background: var(--icm-soft); }
            main form .primary-button, main [class*="primary-button"] { border-radius: .8rem; font-weight: 700; }
            main > .container { max-width: 1440px; }
            main > .container > div[class*="max-w"] { border-color: #dcebed; box-shadow: 0 16px 40px rgba(31,61,67,.08); }
            main nav a[class*="border"] { border-color: #dcebed; }
            v-product .container { max-width: 1440px; }
            v-product [class*="border-zinc-200"], v-product [class*="border-zinc-300"] { border-color: #dcebed; }
            v-product [class*="bg-zinc-100"] { background: #f3f8f8; }
            v-product [class*="text-navyBlue"] { color: var(--icm-teal) !important; }
            v-product [class*="rounded"] { border-radius: 1rem; }
            v-product [class*="font-dmserif"] { font-family: 'Poppins', sans-serif; font-style: normal; font-weight: 700; }
            v-product button[class*="primary-button"] { border-radius: .85rem; min-height: 3.25rem; }
            v-cart .container, v-checkout .container { max-width: 1440px; }
            v-cart [class*="border-zinc"], v-checkout [class*="border-zinc"] { border-color: #dcebed; }
            v-cart [class*="shadow"], v-checkout [class*="shadow"] { box-shadow: 0 12px 30px rgba(31,61,67,.07); }
            v-mini-cart [class*="fixed"], v-mini-cart [class*="drawer"] { box-shadow: -12px 0 32px rgba(31,61,67,.14); }
            main [class*="account"] [class*="border-zinc"], main [class*="account"] [class*="border-gray"] { border-color: #dcebed; }
            main [class*="account"] a:hover { color: var(--icm-teal); }
            main [class*="account"] [class*="bg-gray-100"] { background: var(--icm-soft); }
            .icm-footer { background: radial-gradient(circle at 90% 0%, #23676c 0, #173d43 42%, #102d32 100%); color: rgba(255,255,255,.74); }
            .icm-footer a { transition: color .2s ease; }
            .icm-footer a:hover { color: #fff; }
            .icm-footer-logo { display: block; height: auto; max-width: 190px; width: 100%; }
            .icm-footer-link { align-items: center; display: inline-flex; gap: .45rem; }
            .icm-footer-link::before { background: var(--icm-green); border-radius: 999px; content: ''; height: .35rem; transition: transform .2s ease; width: .35rem; }
            .icm-footer-link:hover::before { transform: scale(1.5); }
            @keyframes icm-reveal { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
            @media (max-width: 1023px) {
                header > div, header .min-h-\[78px\] { min-height: 66px; }
                .icm-brand-name { font-size: 1.15rem; }
                .icm-brand-mark { border-radius: .8rem; font-size: 1rem; height: 2.5rem; width: 2.5rem; }
                .icm-home-hero { border-radius: 0 0 1.75rem 1.75rem; }
                #icm-products .container { margin-top: 1.5rem; }
                .icm-product-card:hover { transform: none; }
            }
            @media (max-width: 639px) {
                header { border-top-width: 2px; }
                header a:has(> img[src*="logo"]) { height: 38px; width: 142px; }
                .icm-home-hero { min-height: 420px; padding-bottom: 4.5rem !important; padding-top: 4.5rem !important; }
                .icm-home-hero h1 { font-size: clamp(3rem, 13vw, 4rem); overflow-wrap: anywhere; }
                .icm-home-hero::before { height: 18rem; right: -10rem; top: -8rem; width: 18rem; }
                .icm-home-hero::after { bottom: -4rem; height: 8rem; }
                .icm-hero-actions { display: grid; grid-template-columns: 1fr; max-width: 17rem; }
                .icm-hero-actions a { text-align: center; }
                #icm-products .container > div:nth-child(2) { gap: 1rem; }
                #icm-products .icon-arrow-left-stylish, #icm-products .icon-arrow-right-stylish { height: 2.35rem; width: 2.35rem; }
                .icm-product-card { border-radius: 1rem; }
                .icm-product-card__content { padding: .8rem; }
                .icm-product-card__name { font-size: .84rem; }
                .icm-product-card__price { font-size: .95rem; }
                .icm-product-card__icon { height: 2rem; width: 2rem; }
                .icm-product-card--list { grid-template-columns: 130px 1fr; }
                .icm-product-card--list .icm-product-card__media { min-height: 170px; }
                .icm-product-card--list .icm-product-card__content { padding: 1rem; }
            }
            @media (prefers-reduced-motion: reduce) {
                *, *::before, *::after { animation-duration: .01ms !important; scroll-behavior: auto !important; transition-duration: .01ms !important; }
            }
        </style>

        {!! core()->getConfigData('general.content.custom_scripts.custom_css') !!}
        @if(core()->getConfigData('general.content.speculation_rules.enabled'))
            <script type="speculationrules">@json(core()->getSpeculationRules(), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)</script>
        @endif
        {!! view_render_event('bagisto.shop.layout.head.after') !!}
    </head>

    <body>
        {!! view_render_event('bagisto.shop.layout.body.before') !!}
        <a href="#main" class="skip-to-main-content-link">Skip to main content</a>

        <div id="app">
            <x-shop::flash-group />
            <x-shop::modal.confirm />

            @if ($hasHeader)
                <x-shop::layouts.header />
            @endif

            @if(core()->getConfigData('general.gdpr.settings.enabled') && core()->getConfigData('general.gdpr.cookie.enabled'))
                <x-shop::layouts.cookie />
            @endif

            {!! view_render_event('bagisto.shop.layout.content.before') !!}
            <main id="main" class="bg-white">{{ $slot }}</main>
            {!! view_render_event('bagisto.shop.layout.content.after') !!}

            @if ($hasFeature)
                <x-shop::layouts.services />
            @endif

            @if ($hasFooter)
                <x-shop::layouts.footer />
            @endif
        </div>

        {!! view_render_event('bagisto.shop.layout.body.after') !!}
        <x-shop::layouts.webmcp />
        @stack('scripts')
        {!! view_render_event('bagisto.shop.layout.vue-app-mount.before') !!}
        <script>
            function mountApp() {
                app.mount('#app');
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', mountApp);
            } else {
                mountApp();
            }
        </script>
        <script>
            if (document.documentElement.lang === 'es') {
                const icmSpanishLabels = {
                    'Product': 'Producto',
                    'Description': 'Descripción',
                    'Material': 'Material',
                    'Default Header': 'Encabezado principal',
                    'View Details': 'Ver detalles',
                    'Excl. Tax:': 'Sin impuestos:',
                };

                const translateIcmLabels = (node) => {
                    if (node.nodeType === Node.TEXT_NODE) {
                        const value = node.nodeValue.trim();

                        if (icmSpanishLabels[value]) {
                            node.nodeValue = node.nodeValue.replace(value, icmSpanishLabels[value]);
                        }
                    }
                };

                const observer = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.TEXT_NODE) {
                            translateIcmLabels(node);
                        } else if (node.nodeType === Node.ELEMENT_NODE) {
                            node.childNodes.forEach(translateIcmLabels);
                        }
                    }));
                });

                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('body *').forEach((element) => element.childNodes.forEach(translateIcmLabels));
                    observer.observe(document.body, { childList: true, subtree: true });
                });
            }
        </script>
        {!! view_render_event('bagisto.shop.layout.vue-app-mount.after') !!}
        <script type="text/javascript">{!! core()->getConfigData('general.content.custom_scripts.custom_javascript') !!}</script>
    </body>
</html>
