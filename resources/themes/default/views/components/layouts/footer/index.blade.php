<footer class="icm-footer mt-10">
    <div class="mx-auto grid max-w-[1440px] gap-10 px-6 py-12 sm:grid-cols-2 sm:px-10 lg:grid-cols-[1.5fr_1fr_1fr] lg:px-20">
        <div>
            <a href="{{ route('shop.home.index') }}" aria-label="ICM Therapy">
                <img class="icm-footer-logo" src="{{ asset('themes/shop/default/images/LogoIMC.webp') }}" alt="ICM Therapy">
            </a>
            <p class="mt-5 max-w-sm text-sm leading-6">Bienestar, movimiento y recuperación para acompañarte en cada paso.</p>
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
            <p class="mb-4 text-sm font-semibold uppercase tracking-[.18em] text-white">Compra con confianza</p>
            <p class="text-sm leading-6">Una experiencia simple, segura y cercana. Estamos aquí para ayudarte a elegir mejor.</p>
        </div>
    </div>

    <div class="border-t border-white/10 px-6 py-5 text-center text-xs sm:px-10">
        © {{ date('Y') }} ICM Therapy. Todos los derechos reservados.
    </div>
</footer>
