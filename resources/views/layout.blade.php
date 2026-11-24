<!DOCTYPE html>
<html lang="id" class="no-js">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Tanda JS aktif. Dipasang sinkron di head agar gaya "sembunyi sampai terlihat" hanya berlaku bila JS jalan. --}}
    <script>document.documentElement.classList.replace('no-js', 'js');</script>
    <title>FindFood · @yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        canvas: '#FAF7F2',      // latar utama, warm cream
                        surface: '#FFFFFF',     // permukaan kartu dan panel
                        sand: '#F3EDE4',        // latar seksi selang-seling (netral hangat)
                        line: '#E7E5E4',        // border dekoratif kartu
                        edge: '#79716B',        // border input dan panah lingkaran (3:1 untuk komponen UI)
                        ink: '#1C1917',         // teks utama, Deep Espresso
                        muted: '#57534E',       // teks sekunder, 7:1 di atas canvas
                        terracotta: {
                            DEFAULT: '#C83E2B', // aksen dan tombol CTA, teks putih 5,02:1
                            dark: '#A93221',    // keadaan hover tombol, teks putih 6,63:1
                        },
                    },
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'system-ui', 'sans-serif'],
                        body: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        };
    </script>
    <style>
        :root {
            --e: cubic-bezier(.2, .7, .2, 1);
            /* Sistem radius, hanya tiga nilai (L-02) */
            --r-sm: .5rem;
            /* badge, tag, thumbnail kecil */
            --r-md: .75rem;
            /* tombol, input, nav bawah, caption */
            --r-lg: 1.5rem;
            /* kartu dan foto utama */
        }

        html {
            background: #FAF7F2;
        }

        body {
            font-family: Inter, system-ui, sans-serif;
        }

        /* Judul: Plus Jakarta Sans ExtraBold dengan tracking rapat (M-07) */
        .font-display {
            font-family: 'Plus Jakarta Sans', system-ui, sans-serif;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        /* Input. Tujuan: field mudah dikenali dan fokusnya terlihat. Border memakai warna 3:1 (edge). */
        .field {
            width: 100%;
            border-radius: var(--r-md);
            background: #FFFFFF;
            border: 1px solid #79716B;
            padding: .75rem 1rem;
            color: #1C1917;
            transition: border-color 250ms ease-out;
        }

        .field::placeholder {
            color: #57534E;
        }

        .field:focus {
            border-color: #C83E2B;
        }

        .field:focus-visible {
            outline: 2px solid #C83E2B;
            outline-offset: 2px;
        }

        /* Fokus keyboard untuk seluruh halaman (menggantikan outline:none, H-07) */
        :focus-visible {
            outline: 2px solid #C83E2B;
            outline-offset: 3px;
        }

        /* Tekstur tipis agar latar krem tidak terasa datar (R-07). Opacity rendah agar teks tetap bersih. */
        .grain::after {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 100;
            opacity: .035;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='180' height='180'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        }

        /*
         * Staggered reveal (M-06, L-06).
         * Tujuan: menuntun mata dari kartu ke kartu saat masuk layar, sekali saja per elemen.
         * Fallback: tanpa JS, .reveal tetap terlihat karena aturan sembunyi hanya ada di bawah .js.
         * Delay per elemen diatur lewat variabel --d di atribut style.
         */
        .js .reveal {
            opacity: 0;
        }

        .js .reveal.in {
            opacity: 1;
            animation: revealUp 700ms var(--e) var(--d, 0ms) backwards;
        }

        @keyframes revealUp {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: none;
            }
        }

        /*
         * Card hover lift (efek interaksi).
         * Tujuan: memberi umpan balik bahwa kartu bisa diklik. Transisi 250ms ease-out.
         */
        .lift {
            transition: transform 250ms ease-out, border-color 250ms ease-out, background-color 250ms ease-out;
        }

        .lift:hover {
            transform: translateY(-4px);
        }

        /*
         * Image zoom saat hover (efek interaksi).
         * Tujuan: konfirmasi bahwa area foto bisa diklik, dengan gerakan kecil scale 1.04.
         */
        .img-zoom {
            overflow: hidden;
        }

        .img-zoom img {
            transition: transform 250ms ease-out;
        }

        .img-zoom:hover img {
            transform: scale(1.04);
        }

        /* Transisi tombol. Efek tekan (active:scale-[0.98]) ditulis langsung di kelas Tailwind pada tombol. */
        .btn {
            transition: background-color 250ms ease-out, color 250ms ease-out, border-color 250ms ease-out, transform 150ms ease-out;
        }

        /* Marquee: bisa dijeda lewat tombol. Tujuan: gerakan terus-menerus tetap bisa dihentikan pengguna (H-10). */
        @keyframes marquee {
            to {
                transform: translateX(-50%);
            }
        }

        .marquee {
            animation: marquee 38s linear infinite;
        }

        .marquee.paused {
            animation-play-state: paused;
        }

        .noscroll {
            scrollbar-width: none;
        }

        .noscroll::-webkit-scrollbar {
            display: none;
        }

        /*
         * Sticky header transisi halus (M-01).
         * Tujuan: saat di-scroll, header lebih padat dan diberi garis batas, tanpa blur.
         */
        #hdr {
            border-bottom: 1px solid transparent;
            transition: background-color 250ms ease-out, border-color 250ms ease-out;
        }

        #hdr .hdr-inner {
            height: 5rem;
            transition: height 250ms ease-out;
        }

        #hdr.scrolled {
            background: rgba(250, 247, 242, .96);
            border-bottom-color: #E7E5E4;
        }

        #hdr.scrolled .hdr-inner {
            height: 4rem;
        }

        @media (prefers-reduced-motion: reduce) {

            *,
            *::before,
            *::after {
                animation: none !important;
                transition: none !important;
            }

            .js .reveal {
                opacity: 1;
            }
        }
    </style>
    @stack('head')
</head>

<body class="bg-canvas text-ink min-h-screen grain">
    @php
        $links = [
            ['Home', route('home'), 'home'],
            ['Restoran', route('restaurants.list'), 'restaurants.list'],
            ['Cafe', route('coffee.list'), 'coffee.list'],
            ['Maps', route('restaurants.map'), 'restaurants.map'],
        ];
    @endphp

    <header id="hdr" class="fixed top-0 inset-x-0 z-40">
        <div class="hdr-inner max-w-7xl mx-auto px-5 md:px-10 flex items-center justify-between">
            <a href="{{ route('home') }}" class="font-display text-3xl text-ink">Find<em
                    class="not-italic text-terracotta">Food</em></a>

            <nav class="hidden md:flex items-center gap-8 text-sm font-medium">
                @foreach ($links as [$label, $href, $name])
                    <a href="{{ $href }}"
                        class="transition-colors duration-[250ms] {{ request()->routeIs($name) ? 'text-ink font-semibold' : 'text-muted hover:text-ink' }}">{{ $label }}</a>
                @endforeach
            </nav>

            <a href="{{ route('restaurants.create') }}"
                class="btn rounded-xl bg-terracotta text-white text-sm font-semibold px-5 py-2.5 hover:bg-terracotta-dark active:scale-[0.98]">
                + Tambah tempat
            </a>
        </div>
    </header>

    <nav
        class="md:hidden fixed bottom-4 inset-x-4 z-40 flex justify-around rounded-xl bg-surface border border-line p-1.5 text-sm">
        @foreach ($links as [$label, $href, $name])
            <a href="{{ $href }}"
                class="px-3 py-2 rounded-lg transition-colors duration-[250ms] {{ request()->routeIs($name) ? 'bg-ink text-canvas font-semibold' : 'text-muted' }}">{{ $label }}</a>
        @endforeach
    </nav>

    <main class="pt-24 pb-28 md:pb-16">
        @if (session('ok'))
            <div class="max-w-7xl mx-auto px-5 md:px-10 mb-6">
                <div class="rounded-xl bg-ink text-canvas px-5 py-3 font-medium">{{ session('ok') }}</div>
            </div>
        @endif
        @if ($errors->any())
            <div class="max-w-7xl mx-auto px-5 md:px-10 mb-6">
                <div class="rounded-xl bg-surface border border-terracotta text-ink px-5 py-3">
                    @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>@endforeach
                </div>
            </div>
        @endif
        @yield('content')
    </main>

    <footer class="border-t border-line">
        <div class="max-w-7xl mx-auto px-5 md:px-10 py-14 grid md:grid-cols-3 gap-10">
            <div>
                <p class="font-display text-3xl text-ink">FindFood</p>
                <p class="text-muted mt-3 max-w-xs">Cari restoran dan cafe di sekitar Mojoagung dan Jombang, lihat
                    menunya, lalu langsung buka rutenya.</p>
            </div>
            <div>
                <p class="font-display font-bold text-ink">Jelajah</p>
                <ul class="mt-3 space-y-2 text-sm text-muted">
                    @foreach ($links as [$label, $href])
                        <li><a href="{{ $href }}" class="hover:text-ink transition-colors duration-[250ms]">{{ $label }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div>
                <p class="font-display font-bold text-ink">Area</p>
                <p class="mt-3 text-sm text-muted">Mojoagung &amp; Jombang<br>Jawa Timur, Indonesia</p>
            </div>
        </div>
        <p class="text-center text-xs text-muted pb-8">© {{ date('Y') }} FindFood</p>
    </footer>

    <script>
        // Reveal saat elemen masuk layar. Jika IntersectionObserver tidak tersedia atau skrip gagal,
        // semua elemen langsung ditampilkan agar konten tidak pernah hilang.
        (function () {
            const items = document.querySelectorAll('.reveal');
            const show = (el) => el.classList.add('in');
            try {
                if (!('IntersectionObserver' in window)) { items.forEach(show); return; }
                const io = new IntersectionObserver((entries) => {
                    entries.forEach((e) => {
                        if (e.isIntersecting) { show(e.target); io.unobserve(e.target); }
                    });
                }, { threshold: .12 });
                items.forEach((el) => io.observe(el));
            } catch (err) {
                items.forEach(show);
            }
        })();

        // Tombol jeda untuk elemen yang bergerak terus-menerus (H-10)
        document.querySelectorAll('[data-pause-target]').forEach((btn) => {
            const target = document.querySelector(btn.dataset.pauseTarget);
            if (!target) return;
            btn.addEventListener('click', () => {
                const paused = target.classList.toggle('paused');
                btn.textContent = paused ? 'Putar animasi' : 'Jeda animasi';
                btn.setAttribute('aria-pressed', String(paused));
            });
        });

        // Header lebih padat dan diberi garis batas saat halaman di-scroll
        const hdr = document.getElementById('hdr');
        const onScroll = () => hdr.classList.toggle('scrolled', window.scrollY > 30);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    </script>
    @stack('scripts')
</body>

</html>