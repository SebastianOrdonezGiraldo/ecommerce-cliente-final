<footer class="icm-footer mt-12">
    <div class="mx-auto grid max-w-[1440px] gap-10 px-8 py-14 sm:grid-cols-2 sm:px-12 lg:grid-cols-[1.35fr_1fr_1fr] lg:px-20 lg:py-16" style="padding-bottom: clamp(3.5rem, 5vw, 5rem); padding-top: clamp(3.5rem, 5vw, 5rem);">
        <div>
            <a href="{{ route('shop.home.index') }}" aria-label="ICM Therapy">
                <img class="icm-footer-logo" src="{{ asset('themes/shop/default/images/LogoIMC.webp') }}" alt="ICM Therapy">
            </a>
            <p class="mt-4 max-w-sm text-sm leading-6">Equipos y soluciones para rehabilitación, bienestar y movimiento.</p>
        </div>

        <div>
            <p class="mb-4 text-sm font-semibold uppercase tracking-[.18em] text-white">Explora</p>
            <div class="grid gap-3 text-sm">
                <a class="icm-footer-link" href="{{ route('shop.home.index') }}">Inicio</a>
                <a class="icm-footer-link" href="{{ route('shop.search.index') }}">Productos</a>
                <a class="icm-footer-link" href="{{ route('shop.home.contact_us') }}">Contáctanos</a>
            </div>
        </div>

        <div>
            <p class="mb-4 text-sm font-semibold uppercase tracking-[.18em] text-white">Atención</p>
            <p class="text-sm leading-6">¿Tienes dudas sobre un producto? Contáctanos y recibe orientación antes de comprar.</p>
            <a class="mt-4 inline-flex text-sm font-semibold text-[#8bc53f]" href="{{ route('shop.home.contact_us') }}">Hablar con un asesor →</a>
        </div>
    </div>

    <div class="border-t border-white/10 px-8 py-6 text-center text-xs text-white/55 sm:px-12">
        © {{ date('Y') }} ICM Therapy. Todos los derechos reservados.
    </div>
</footer>
