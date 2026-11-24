@extends('layout')
@section('title', 'Home')

@push('head')
    <style>
        /* Gradien dari canvas ke sand untuk memisahkan hero dari bagian bawah. Tidak menambah warna baru. */
        .hero-bg {
            background-image: linear-gradient(180deg, #FAF7F2 0%, #F3EDE4 100%);
        }

        /* Crossfade antar foto hero. */
        .hero-slide {
            transition: opacity 700ms ease-out;
        }
    </style>
@endpush

@section('content')
    @php
        $slides = collect([$hero])
            ->merge($featured)
            ->filter(fn ($r) => $r && $r->image_url)
            ->values();
    @endphp

    {{-- Hero dengan slideshow foto tempat --}}
    <section class="hero-bg">
        <div class="max-w-7xl mx-auto px-5 md:px-10 min-h-[80vh] flex items-center">
            <div class="grid lg:grid-cols-12 gap-12 items-center w-full py-10">
                <div class="lg:col-span-6">
                    <p class="font-display text-muted text-xl font-semibold">Mojoagung, Jombang &amp; sekitarnya</p>
                    <h1 class="font-display text-5xl md:text-7xl leading-[1.02] mt-5 text-ink">
                        Makan enak,<br>ngopi tenang,<br>
                        <span class="text-terracotta">tanpa drama cari tempat.</span>
                    </h1>
                    <p class="mt-7 max-w-md text-muted text-lg leading-relaxed">
                        Temukan restoran dan cafe terdekat, cek menu lengkap beserta harganya, lalu langsung buka rutenya di Maps.
                    </p>
                    <div class="flex flex-wrap gap-3 mt-9">
                        <a href="{{ route('restaurants.list') }}"
                           class="btn rounded-xl bg-terracotta text-white font-semibold px-7 py-3.5 hover:bg-terracotta-dark active:scale-[0.98]">Jelajahi restoran →</a>
                        <a href="{{ route('restaurants.map') }}"
                           class="btn rounded-xl border border-edge text-ink font-semibold px-7 py-3.5 hover:bg-sand active:scale-[0.98]">Buka peta</a>
                    </div>
                </div>

                <div class="lg:col-span-6 relative">
                    {{-- Bentuk arch adalah identitas hero, satu-satunya radius di luar tiga token. --}}
                    @if ($slides->isNotEmpty())
                        <div data-slideshow>
                            <div class="relative mx-auto w-full max-w-md aspect-[4/5] rounded-t-[999px] rounded-b-[2rem] overflow-hidden bg-sand">
                                @foreach ($slides as $i => $s)
                                    <figure class="hero-slide absolute inset-0 m-0 {{ $i === 0 ? 'opacity-100' : 'opacity-0' }}"
                                            data-slide aria-hidden="{{ $i === 0 ? 'false' : 'true' }}">
                                        <img src="{{ $s->image_url }}" alt="{{ $s->name }}" class="h-full w-full object-cover"
                                             @if ($i === 0) fetchpriority="high" @else loading="lazy" @endif>
                                        <figcaption class="absolute bottom-5 left-5 right-5 rounded-xl bg-surface border border-line p-4">
                                            <p class="text-xs text-muted">{{ ucfirst($s->type) }} · rating {{ $s->rating ?? '-' }}</p>
                                            <p class="font-display text-xl mt-0.5 text-ink">{{ $s->name }}</p>
                                        </figcaption>
                                    </figure>
                                @endforeach
                            </div>

                            <div class="mt-6 flex items-center justify-center gap-6">
                                <div class="flex items-center gap-2">
                                    @foreach ($slides as $i => $s)
                                        <button type="button" data-dot="{{ $i }}" aria-label="Tampilkan {{ $s->name }}"
                                                class="h-3 rounded-lg transition-all duration-[250ms] {{ $i === 0 ? 'w-8 bg-terracotta' : 'w-3 bg-edge' }}"></button>
                                    @endforeach
                                </div>
                                <button type="button" data-pause aria-pressed="false"
                                        class="btn rounded-xl border border-edge px-4 py-2 text-sm font-semibold text-ink hover:bg-sand active:scale-[0.98]">Jeda tampilan</button>
                            </div>
                        </div>
                    @else
                        <div class="relative mx-auto w-full max-w-md aspect-[4/5] rounded-t-[999px] rounded-b-[2rem] overflow-hidden bg-sand grid place-items-center text-[9rem]"
                             aria-hidden="true">🍽️</div>
                    @endif

                    {{-- Badge diam, tanpa putaran, supaya tidak ada animasi tanpa henti. --}}
                    <div class="absolute -top-6 -left-4 md:-left-10 h-28 w-28 rounded-full bg-terracotta grid place-items-center" aria-hidden="true">
                        <svg class="absolute inset-0 h-full w-full" viewBox="0 0 120 120">
                            <defs>
                                <path id="circ" d="M60,60 m-44,0 a44,44 0 1,1 88,0 a44,44 0 1,1 -88,0"/>
                            </defs>
                            <text class="fill-white text-[11px] tracking-[.12em]">
                                <textPath href="#circ">makan · ngopi · jajan · makan · ngopi · jajan ·</textPath>
                            </text>
                        </svg>
                        <span class="text-2xl">☕</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Marquee nama tempat. Bisa dijeda. --}}
    <section class="border-y border-line py-5 overflow-hidden bg-surface">
        <div class="max-w-7xl mx-auto px-5 md:px-10 flex justify-end mb-3">
            <button type="button" data-pause-target="#marquee" aria-pressed="false"
                    class="btn rounded-xl border border-edge px-4 py-2 text-sm font-semibold text-ink hover:bg-sand active:scale-[0.98]">
                Jeda animasi
            </button>
        </div>
        <div id="marquee" class="marquee flex w-max gap-10 font-display text-3xl text-ink">
            @foreach (array_merge($names, $names) as $name)
                <span class="flex items-center gap-10 whitespace-nowrap">{{ $name }} <span class="text-edge" aria-hidden="true">✦</span></span>
            @endforeach
        </div>
    </section>

    {{-- Angka langsung tampil, tanpa animasi hitung naik. --}}
    <section class="bg-ink text-canvas py-20">
        <div class="max-w-7xl mx-auto px-5 md:px-10 grid grid-cols-2 md:grid-cols-4 gap-10">
            <div class="reveal" style="--d: 0ms">
                <p class="font-display text-6xl md:text-7xl">{{ $stats['restoran'] }}</p>
                <p class="text-stone-300 mt-2">Restoran</p>
            </div>
            <div class="reveal" style="--d: 120ms">
                <p class="font-display text-6xl md:text-7xl">{{ $stats['cafe'] }}</p>
                <p class="text-stone-300 mt-2">Cafe</p>
            </div>
            <div class="reveal" style="--d: 240ms">
                <p class="font-display text-6xl md:text-7xl">{{ $stats['menu'] }}</p>
                <p class="text-stone-300 mt-2">Menu terdaftar</p>
            </div>
            <div class="reveal" style="--d: 360ms">
                <p class="font-display text-6xl md:text-7xl">{{ number_format($stats['rating'], 1, ',', '.') }}</p>
                <p class="text-stone-300 mt-2">Rata-rata rating</p>
            </div>
        </div>
    </section>

    {{-- Best seller --}}
    <section class="bg-sand text-ink py-24">
        <div class="max-w-7xl mx-auto px-5 md:px-10">
            <div class="reveal mb-12">
                <p class="font-display text-muted text-lg font-semibold">Yang paling sering dipesan</p>
                <h2 class="font-display text-4xl md:text-6xl mt-2 leading-tight text-ink">Best seller pilihan</h2>
            </div>
        </div>

        @if ($favorites->isNotEmpty())
            <div class="noscroll flex gap-5 overflow-x-auto snap-x snap-mandatory px-5 md:px-10 pt-4 pb-6">
                @foreach ($favorites as $p)
                    <div class="reveal snap-start shrink-0 w-72 md:w-80" style="--d: {{ $loop->index * 100 }}ms">
                        <article class="bs-card rounded-3xl bg-surface border border-line overflow-hidden h-full">
                            <div class="relative h-56 bg-sand bs-media">
                                @if ($p->image_url)
                                    <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="h-full flex items-center justify-center text-6xl">🍽️</div>
                                @endif
                                <span class="absolute top-3 left-3 rounded-lg bg-terracotta text-white text-xs font-bold px-3 py-1">🔥 Best Seller</span>
                                <button type="button"
                                        onclick="this.textContent = this.textContent === '♡' ? '♥' : '♡'"
                                        class="btn absolute top-3 right-3 h-9 w-9 rounded-xl bg-surface text-ink border border-line grid place-items-center text-lg hover:bg-sand active:scale-[0.98]"
                                        aria-label="Simpan">♡</button>
                            </div>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <h3 class="font-display text-xl leading-tight text-ink">{{ $p->name }}</h3>
                                    <p class="font-semibold text-terracotta whitespace-nowrap">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                                </div>
                                <p class="text-sm text-muted mt-1">{{ $p->restaurant->name }}</p>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        @else
            <p class="max-w-7xl mx-auto px-5 md:px-10 text-muted">Belum ada menu best seller. Tandai menu lewat halaman detail tempat.</p>
        @endif
    </section>

    {{-- Bento: tempat dengan rating tertinggi. Kartu pertama paling besar. --}}
    <section class="max-w-7xl mx-auto px-5 md:px-10 py-24">
        <div class="reveal max-w-2xl mb-12">
            <p class="font-display text-muted text-lg font-semibold">Lagi cari tempat?</p>
            <h2 class="font-display text-4xl md:text-6xl mt-2 leading-tight text-ink">Rating bagus, layak dicoba</h2>
        </div>

        <div class="grid md:grid-cols-6 gap-5 md:auto-rows-[220px]">
            @foreach ($featured as $i => $r)
                @php
                    $span = match ($i) {
                        0 => 'md:col-span-4 md:row-span-2',
                        1, 2 => 'md:col-span-2',
                        default => 'md:col-span-6',
                    };
                @endphp
                <a href="{{ route('restaurants.show', $r) }}"
                   class="reveal img-zoom lift group relative block h-80 md:h-auto rounded-3xl overflow-hidden bg-sand border border-line {{ $span }}"
                   style="--d: {{ $i * 100 }}ms">
                    @if ($r->image_url)
                        <img src="{{ $r->image_url }}" alt="{{ $r->name }}" class="absolute inset-0 h-full w-full object-cover">
                    @else
                        <div class="absolute inset-0 flex items-center justify-center text-7xl bg-sand">{{ $r->type === 'cafe' ? '☕' : '🍽️' }}</div>
                    @endif
                    <div class="absolute bottom-0 inset-x-0 p-5 bg-surface border-t border-line">
                        <div class="flex items-end justify-between gap-4">
                            <div>
                                <h3 class="font-display text-2xl md:text-3xl text-ink">{{ $r->name }}</h3>
                                <p class="text-sm text-muted mt-1">{{ ucfirst($r->type) }} · {{ $r->address }}</p>
                            </div>
                            <span class="shrink-0 rounded-lg border border-edge bg-canvas px-3 py-1 text-sm text-ink">⭐ {{ $r->rating ?? '-' }}</span>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    {{-- Cafe. Kartu gaya katalog: label, rating, foto, dan bar nama tempat. Saat hover atau fokus keyboard, foto membesar dan sedikit meredup. --}}
<section class="bg-sand py-24">
    <div class="max-w-7xl mx-auto px-5 md:px-10 grid lg:grid-cols-12 gap-12">
        <div class="lg:col-span-4 reveal">
            <p class="font-display text-muted text-lg font-semibold">Lagi butuh kafein?</p>
            <h2 class="font-display text-4xl md:text-5xl mt-2 leading-tight text-ink">Cafe pilihan di sekitar Mojoagung</h2>
            <a href="{{ route('coffee.list') }}"
               class="btn inline-block mt-8 rounded-xl bg-terracotta text-white font-semibold px-6 py-3 hover:bg-terracotta-dark active:scale-[0.98]">Lihat semua cafe →</a>
        </div>

        <div class="lg:col-span-8 grid sm:grid-cols-3 gap-4">
            @forelse ($coffee as $i => $c)
                <div class="reveal" style="--d: {{ $i * 150 }}ms">
                    <a href="{{ route('restaurants.show', $c) }}"
                       class="group lift flex flex-col h-full min-h-[440px] rounded-3xl bg-surface border border-line hover:border-edge p-4">
                        <span class="self-start rounded-lg bg-sand px-3 py-1 text-xs font-semibold text-ink">Cafe</span>

                        <p class="font-display text-3xl leading-tight text-ink mt-4">⭐ {{ $c->rating ?? '-' }}</p>

                        <div class="relative mt-4 flex-1 min-h-[260px] rounded-3xl overflow-hidden bg-sand">
                            @if ($c->image_url)
                                <img src="{{ $c->image_url }}" alt="{{ $c->name }}"
                                     class="absolute inset-0 h-full w-full object-cover transition duration-[250ms] ease-out group-hover:scale-[1.04] group-hover:brightness-[.85] group-focus-visible:scale-[1.04] group-focus-visible:brightness-[.85]">
                            @else
                                <span class="absolute inset-0 grid place-items-center text-6xl" aria-hidden="true">☕</span>
                            @endif

                            <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between gap-3 rounded-xl bg-surface p-2 pl-4">
                                <p class="font-semibold text-ink leading-tight">{{ $c->name }}</p>
                                <span class="grid h-11 w-11 shrink-0 place-items-center rounded-full bg-ink text-canvas" aria-hidden="true">→</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <p class="reveal text-muted sm:col-span-3">Belum ada cafe terdaftar.</p>
            @endforelse
        </div>
    </div>
</section>

    {{-- CTA --}}
    <section class="max-w-7xl mx-auto px-5 md:px-10 py-24">
        <div class="reveal rounded-3xl bg-surface border border-line p-10 md:p-16 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <h2 class="font-display text-4xl md:text-6xl leading-tight max-w-xl text-ink">Semua tempat sekitar, dalam satu peta.</h2>
            <a href="{{ route('restaurants.map') }}"
               class="btn shrink-0 rounded-xl bg-terracotta text-white font-semibold px-8 py-4 hover:bg-terracotta-dark active:scale-[0.98]">Buka peta →</a>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        (function () {
            const root = document.querySelector('[data-slideshow]');
            if (!root) return;

            const slides = root.querySelectorAll('[data-slide]');
            const dots = root.querySelectorAll('[data-dot]');
            const pause = root.querySelector('[data-pause]');
            if (slides.length < 2) return;

            // Pengguna yang meminta gerak berkurang mulai dalam keadaan jeda.
            let paused = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let current = 0;
            let timer = null;

            function show(i) {
                current = i;
                slides.forEach((s, n) => {
                    s.classList.toggle('opacity-100', n === i);
                    s.classList.toggle('opacity-0', n !== i);
                    s.setAttribute('aria-hidden', String(n !== i));
                });
                dots.forEach((d, n) => {
                    d.classList.toggle('w-8', n === i);
                    d.classList.toggle('bg-terracotta', n === i);
                    d.classList.toggle('w-3', n !== i);
                    d.classList.toggle('bg-edge', n !== i);
                    d.setAttribute('aria-current', String(n === i));
                });
            }

            function start() {
                clearInterval(timer);
                timer = paused ? null : setInterval(() => show((current + 1) % slides.length), 5000);
            }

            function syncPauseLabel() {
                pause.textContent = paused ? 'Putar tampilan' : 'Jeda tampilan';
                pause.setAttribute('aria-pressed', String(paused));
            }

            dots.forEach((d, n) => d.addEventListener('click', () => { show(n); start(); }));

            pause.addEventListener('click', () => {
                paused = !paused;
                syncPauseLabel();
                start();
            });

            syncPauseLabel();
            show(0);
            start();
        })();
    </script>
@endpush