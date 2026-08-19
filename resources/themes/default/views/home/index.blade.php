@php
    $channel = core()->getCurrentChannel();
@endphp

@push('meta')
    <meta name="title" content="{{ $channel->home_seo['meta_title'] ?? '' }}">
    <meta name="description" content="{{ $channel->home_seo['meta_description'] ?? '' }}">
    <meta name="keywords" content="{{ $channel->home_seo['meta_keywords'] ?? '' }}">
@endpush

@push('scripts')
    @if(! empty($categories))
        <script>localStorage.setItem('categories', JSON.stringify(@json($categories)));</script>
    @endif
@endpush

<x-shop::layouts :has-feature="false">
    <x-slot:title>{{ $channel->home_seo['meta_title'] ?? 'ICM Therapy' }}</x-slot>

    <section class="icm-home-hero px-6 py-20 sm:px-10 lg:px-20 lg:py-28">
        <div class="mx-auto max-w-[1440px]">
            <p class="icm-hero-kicker mb-5 text-sm font-semibold uppercase tracking-[.25em]">ICM Therapy</p>
            <h1 class="icm-hero-title">Equipos de terapia física<br>que generan confianza.</h1>
            <p class="icm-hero-copy mt-7 max-w-xl text-base sm:text-lg">Importamos y distribuimos soluciones para rehabilitación, fisioterapia, movimiento y bienestar. Calidad técnica para acompañar cada recuperación.</p>
            <div class="icm-hero-actions mt-8">
                <a class="icm-hero-primary" href="#icm-products">Ver productos</a>
                <a class="icm-hero-secondary" href="{{ route('shop.search.index') }}">Explorar catálogo</a>
            </div>
        </div>
    </section>

    <section class="mx-auto max-w-[1440px] px-6 py-14 sm:px-10 lg:px-20">
        <div class="grid gap-5 rounded-[1.5rem] bg-[var(--icm-soft)] p-7 md:grid-cols-3 md:p-9">
            <div><p class="text-sm font-bold uppercase tracking-[.16em] text-[var(--icm-green)]">Compra informada</p><h2 class="mt-2 text-xl font-bold">Equipos para cada etapa de recuperación</h2></div>
            <div><p class="font-semibold">Asesoría especializada</p><p class="mt-2 text-sm text-slate-600">Te ayudamos a encontrar la solución que necesitas.</p></div>
            <div><p class="font-semibold">Calidad y respaldo</p><p class="mt-2 text-sm text-slate-600">Información técnica, garantía y condiciones claras.</p></div>
        </div>
    </section>

    <section class="mx-auto max-w-[1440px] px-6 pb-8 sm:px-10 lg:px-20">
        <p class="mb-2 text-sm font-semibold uppercase tracking-[.2em] text-[var(--icm-green)]">Encuentra tu solución</p>
        <h2 class="icm-section-title">Compra por categoría</h2>
        <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach (['Rehabilitación física', 'Electroterapia', 'Deporte y movilidad', 'Bienestar y recuperación'] as $category)
                <a href="{{ route('shop.search.index') }}" class="group rounded-[1.25rem] border border-[#dcebed] bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-[var(--icm-teal)] hover:shadow-lg">
                    <span class="text-2xl text-[var(--icm-teal)]">✦</span><h3 class="mt-7 text-lg font-bold">{{ $category }}</h3><span class="mt-3 inline-block text-sm font-semibold text-[var(--icm-teal)]">Explorar →</span>
                </a>
            @endforeach
        </div>
    </section>

    <section class="mx-auto max-w-[1440px] px-6 py-8 sm:px-10 lg:px-20">
        <div class="overflow-hidden rounded-[1.5rem] bg-[#173d43] p-8 text-white md:flex md:items-center md:justify-between md:p-11">
            <div><p class="text-sm font-bold uppercase tracking-[.18em] text-[var(--icm-green)]">Opciones de pago</p><h2 class="mt-3 text-3xl font-bold tracking-tight">Invierte en tu recuperación.</h2><p class="mt-3 max-w-xl text-sm leading-6 text-white/75">Consulta nuestras alternativas de pago y recibe asesoría antes de elegir tu equipo.</p></div>
            <a href="{{ route('shop.home.contact_us') }}" class="mt-6 inline-flex rounded-full bg-white px-6 py-3 text-sm font-bold text-[var(--icm-teal)] md:mt-0">Hablar con un asesor</a>
        </div>
    </section>

    <section id="icm-products" class="mx-auto max-w-[1440px] px-6 py-14 sm:px-10 lg:px-20 lg:py-20">
        <div id="icm-products-heading" class="mb-8 flex items-end justify-between gap-4">
            <div>
                <p class="mb-2 text-sm font-semibold uppercase tracking-[.2em]" style="color: var(--icm-green)">Descubre lo nuevo</p>
                <h2 class="icm-section-title">Productos destacados</h2>
            </div>
            <a class="hidden font-semibold sm:block" style="color: var(--icm-teal)" href="{{ route('shop.search.index') }}">Ver catálogo</a>
        </div>

        <x-shop::products.carousel
            title=""
            :src="route('shop.api.products.index', ['limit' => 8, 'sort' => 'created_at', 'order' => 'desc'])"
            :navigation-link="route('shop.search.index')"
            aria-label="Productos destacados"
        />

        <div id="icm-empty-catalog" class="grid gap-8 rounded-[2rem] bg-[var(--icm-soft)] px-6 py-10 sm:grid-cols-3 sm:px-10">
            <div class="sm:col-span-3">
                <p class="mb-2 text-sm font-semibold uppercase tracking-[.2em]" style="color: var(--icm-green)">Estamos preparando el catálogo</p>
                <h3 class="text-2xl font-bold tracking-tight sm:text-3xl">Muy pronto encontrarás aquí nuestros productos.</h3>
                <p class="mt-3 max-w-2xl text-sm text-slate-500 sm:text-base">Estamos organizando una selección de bienestar, fisioterapia y deporte para acompañarte en cada movimiento.</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><span class="text-2xl" style="color: var(--icm-teal)">✦</span><h4 class="mt-4 font-semibold">Bienestar</h4><p class="mt-2 text-sm text-slate-500">Productos para sentirte mejor cada día.</p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><span class="text-2xl" style="color: var(--icm-green)">↗</span><h4 class="mt-4 font-semibold">Movimiento</h4><p class="mt-2 text-sm text-slate-500">Soluciones para entrenar y recuperarte.</p></div>
            <div class="rounded-2xl bg-white p-5 shadow-sm"><span class="text-2xl" style="color: var(--icm-teal)">♡</span><h4 class="mt-4 font-semibold">Acompañamiento</h4><p class="mt-2 text-sm text-slate-500">Una experiencia cercana y sencilla.</p></div>
        </div>
    </section>

    <section class="bg-[var(--icm-soft)] px-6 py-16 sm:px-10 lg:px-20">
        <div class="mx-auto grid max-w-[1440px] gap-8 lg:grid-cols-[1.1fr_.9fr] lg:items-center">
            <div><p class="text-sm font-bold uppercase tracking-[.18em] text-[var(--icm-green)]">Compra con criterio</p><h2 class="mt-3 text-3xl font-bold tracking-tight text-[var(--icm-ink)]">¿Cómo elegir tu equipo de fisioterapia?</h2><p class="mt-4 max-w-xl leading-7 text-slate-600">Revisa el objetivo de uso, los atributos técnicos, el espacio disponible y la recomendación profesional. Nuestro catálogo está pensado para hacer esa decisión más clara.</p><a href="{{ route('shop.search.index') }}" class="mt-6 inline-flex font-bold text-[var(--icm-teal)]">Conocer el catálogo →</a></div>
            <div class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1"><div class="rounded-2xl bg-white p-5 shadow-sm"><b class="text-[var(--icm-teal)]">01</b><p class="mt-2 font-semibold">Identifica tu necesidad</p></div><div class="rounded-2xl bg-white p-5 shadow-sm"><b class="text-[var(--icm-teal)]">02</b><p class="mt-2 font-semibold">Compara especificaciones</p></div><div class="rounded-2xl bg-white p-5 shadow-sm"><b class="text-[var(--icm-teal)]">03</b><p class="mt-2 font-semibold">Recibe acompañamiento</p></div></div>
        </div>
    </section>

    <section class="mx-auto max-w-[1440px] px-6 py-16 sm:px-10 lg:px-20">
        <div class="rounded-[1.5rem] border border-[#dcebed] bg-white p-8 text-center sm:p-12"><p class="text-sm font-bold uppercase tracking-[.18em] text-[var(--icm-green)]">Mantente informado</p><h2 class="mt-3 text-3xl font-bold tracking-tight">Recibe novedades y consejos de bienestar.</h2><p class="mx-auto mt-3 max-w-xl text-slate-600">Nuevos equipos, guías de uso y recomendaciones para tu recuperación.</p><a href="{{ route('shop.home.contact_us') }}" class="primary-button mt-7 inline-flex rounded-xl px-6 py-3 font-bold text-white">Quiero recibir información</a></div>
    </section>

    @pushOnce('scripts')
        <script>
            fetch(@json(route('shop.api.products.index', ['limit' => 1])))
                .then(response => response.json())
                .then(data => {
                    if (data.data?.length) {
                        document.querySelector('#icm-empty-catalog')?.remove();
                    } else {
                        document.querySelector('#icm-products-heading')?.remove();
                    }
                });
        </script>
    @endPushOnce
</x-shop::layouts>
