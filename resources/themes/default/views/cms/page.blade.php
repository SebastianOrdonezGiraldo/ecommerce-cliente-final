@push('meta')
    <meta name="title" content="{{ $page->meta_title }}">
    <meta name="description" content="{{ $page->meta_description }}">
    <meta name="keywords" content="{{ $page->meta_keywords }}">
@endpush

<x-shop::layouts>
    <x-slot:title>{{ $page->meta_title }}</x-slot>
    <section class="bg-[var(--icm-soft)] px-6 py-14 sm:px-10 lg:px-20">
        <div class="mx-auto max-w-[1440px]"><p class="text-sm font-bold uppercase tracking-[.18em] text-[var(--icm-green)]">ICM Therapy</p><h1 class="mt-3 text-4xl font-bold tracking-tight text-[var(--icm-ink)] sm:text-5xl">{{ $page->page_title }}</h1></div>
    </section>
    <article class="prose prose-slate mx-auto max-w-4xl px-6 py-14 sm:px-10 lg:py-20 prose-headings:font-bold prose-a:text-[var(--icm-teal)]">{!! $page->html_content !!}</article>
</x-shop::layouts>
