@extends('layout')
@section('title', 'Peta')

@push('head')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.css" crossorigin="">
    <style>
        /* Halaman peta memakai palet referensi: forest #0F3D3A, bordo #6C151E, cream #F5DABF.
                       Alasan (R-21): peta dibuat gelap seperti referensi Explorer agar pin dan kartu detail menonjol. */
        .explorer :focus-visible {
            outline: 2px solid #F5DABF;
            outline-offset: 2px;
        }

        .explorer-map {
            background: #0B302D;
        }

        .explorer-map .leaflet-tile-pane {
            filter: grayscale(1) invert(1) brightness(.80) contrast(1.05);
        }

        /* Lapisan tint forest di atas tile peta. mix-blend-mode color hanya mengganti warna, bukan kecerahan. */
        .explorer-map::after {
            content: '';
            position: absolute;
            inset: 0;
            pointer-events: none;
            z-index: 400;
            background: #0F3D3A;
            mix-blend-mode: color;
            opacity: .55;
        }

        .ff-pin-wrap {
            background: none;
            border: 0;
        }

        .ff-pin {
            width: 30px;
            height: 30px;
            border-radius: 9999px;
            background: #6C151E;
            border: 2px solid #F5DABF;
            display: grid;
            place-items: center;
            transition: transform 250ms ease-out, background-color 250ms ease-out;
        }

        .ff-pin::after {
            content: '';
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            background: #F5DABF;
        }

        .ff-pin.active {
            background: #F5DABF;
        }

        .ff-pin.active::after {
            background: #6C151E;
        }

        .ff-pin:hover {
            transform: scale(1.1);
        }

        /* Filter dan item daftar. Tujuan: status aktif terlihat tanpa mengandalkan warna saja. */
        .filter-chip {
            transition: background-color 250ms ease-out, color 250ms ease-out;
        }

        .filter-chip[aria-pressed="true"] {
            background: #F5DABF;
            color: #0F3D3A;
        }

        .filter-chip[aria-pressed="false"] {
            border: 1px solid rgba(245, 218, 191, .35);
            color: #F5DABF;
        }

        .place-item,
        .featured-card {
            transition: background-color 250ms ease-out, border-color 250ms ease-out;
            border: 1px solid transparent;
        }

        .place-item:hover,
        .featured-card:hover {
            border-color: rgba(245, 218, 191, .35);
        }

        .place-item.is-active,
        .featured-card.is-active {
            background: rgba(245, 218, 191, .12);
            border-color: rgba(245, 218, 191, .45);
        }
    </style>
@endpush

@section('content')
    @php
        $featured = $restaurants->take(4);
    @endphp

    <section class="explorer max-w-7xl mx-auto md:px-10">
        <div
            class="grid md:grid-cols-[340px_1fr] md:h-[calc(100dvh-9.5rem)] md:min-h-[620px] overflow-hidden md:rounded-3xl bg-[#0F3D3A] text-[#F5DABF] md:border border-y border-[#F5DABF]/15">

            {{-- SIDEBAR --}}
            <aside class="order-2 md:order-1 flex flex-col min-h-0 bg-[#0B302D] border-r border-[#F5DABF]/10">
                <div class="p-5 pb-4 shrink-0">
                    <p class="text-sm text-[#F5DABF]/70">Peta tempat makan dan cafe</p>
                    <h1 class="font-display text-3xl mt-1">Jelajah peta</h1>

                    <label for="map-search" class="block text-sm font-semibold mt-5">Cari tempat</label>
                    <input id="map-search" type="search" placeholder="Nama tempat..."
                        class="mt-2 w-full rounded-xl bg-[#0F3D3A] border border-[#F5DABF]/30 px-4 py-3 text-[#F5DABF] placeholder:text-[#F5DABF]/70 focus:border-[#F5DABF]">

                    <div role="group" aria-label="Filter jenis tempat" class="mt-4 flex gap-2">
                        <button type="button" data-filter="all" aria-pressed="true"
                            class="filter-chip rounded-xl px-4 py-2 text-sm font-semibold">Semua</button>
                        <button type="button" data-filter="restoran" aria-pressed="false"
                            class="filter-chip rounded-xl px-4 py-2 text-sm font-semibold">Restoran</button>
                        <button type="button" data-filter="cafe" aria-pressed="false"
                            class="filter-chip rounded-xl px-4 py-2 text-sm font-semibold">Cafe</button>
                    </div>
                </div>

                <div class="flex-1 min-h-0 overflow-y-auto px-5 pb-6">
                    @if ($restaurants->isEmpty())
                        <p class="text-sm text-[#F5DABF]/70">Belum ada tempat yang terdaftar.</p>
                    @else
                        <h2 class="text-sm font-semibold text-[#F5DABF]/70 mt-2">Koleksi pilihan</h2>
                        <div class="mt-3 flex gap-3 overflow-x-auto noscroll pb-2">
                            @foreach ($featured as $r)
                                <button type="button" data-item data-place-id="{{ $r->id }}"
                                    class="featured-card shrink-0 w-40 text-left rounded-xl overflow-hidden bg-[#0F3D3A]">
                                    <span class="block h-24 bg-[#0B302D] overflow-hidden">
                                        @if ($r->image_url)
                                            <img src="{{ $r->image_url }}" alt="" class="h-full w-full object-cover">
                                        @else
                                            <span class="h-full grid place-items-center text-3xl"
                                                aria-hidden="true">{{ $r->type === 'cafe' ? '☕' : '🍽️' }}</span>
                                        @endif
                                    </span>
                                    <span class="block p-3">
                                        <span class="block font-semibold text-sm truncate">{{ $r->name }}</span>
                                        <span class="block text-xs text-[#F5DABF]/70 mt-0.5">{{ ucfirst($r->type) }} · ⭐
                                            {{ $r->rating ?? '-' }}</span>
                                    </span>
                                </button>
                            @endforeach
                        </div>

                        <h2 class="text-sm font-semibold text-[#F5DABF]/70 mt-6">Semua tempat</h2>
                        <ul class="mt-3 space-y-2">
                            @foreach ($restaurants as $r)
                                <li data-item>
                                    <button type="button" data-place-id="{{ $r->id }}"
                                        class="place-item w-full flex items-center gap-3 rounded-xl p-2 text-left">
                                        <span
                                            class="h-14 w-14 shrink-0 rounded-lg overflow-hidden bg-[#0F3D3A] grid place-items-center">
                                            @if ($r->image_url)
                                                <img src="{{ $r->image_url }}" alt="" class="h-full w-full object-cover">
                                            @else
                                                <span class="text-2xl" aria-hidden="true">{{ $r->type === 'cafe' ? '☕' : '🍽️' }}</span>
                                            @endif
                                        </span>
                                        <span class="min-w-0">
                                            <span class="block font-semibold truncate">{{ $r->name }}</span>
                                            <span class="block text-xs text-[#F5DABF]/70">{{ ucfirst($r->type) }} · ⭐
                                                {{ $r->rating ?? '-' }}</span>
                                        </span>
                                    </button>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </aside>

            {{-- MAP --}}
            <div class="relative order-1 md:order-2 h-[55vh] md:h-auto min-h-[360px]">
                <div id="explorer-map" class="explorer-map absolute inset-0" role="region"
                    aria-label="Peta tempat makan dan cafe"></div>

                <div class="absolute top-4 left-4 z-[500]">
                    <button type="button" id="fit-all"
                        class="rounded-xl bg-[#0B302D] border border-[#F5DABF]/30 px-4 py-2 text-sm font-semibold text-[#F5DABF] hover:bg-[#6C151E] transition-colors duration-[250ms]">
                        Lihat semua
                    </button>
                </div>

                <div
                    class="absolute left-4 right-4 bottom-4 md:left-auto md:right-6 md:bottom-6 md:w-96 z-[500] rounded-3xl bg-[#0B302D] border border-[#F5DABF]/20 p-5">
                    <p id="info-empty" class="text-sm text-[#F5DABF]/70">Pilih tempat di peta atau di daftar untuk melihat
                        detail.</p>

                    <div id="info-body" hidden>
                        <div class="flex gap-4">
                            <div class="h-20 w-24 shrink-0 rounded-xl overflow-hidden bg-[#0F3D3A] grid place-items-center">
                                <img id="info-img" alt="" class="h-full w-full object-cover" hidden>
                                <span id="info-emoji" class="text-3xl" aria-hidden="true"></span>
                            </div>
                            <div class="min-w-0">
                                <p id="info-meta" class="text-sm text-[#F5DABF]/70"></p>
                                <h2 id="info-name" class="font-display text-xl leading-tight"></h2>
                                <p id="info-address" class="text-sm text-[#F5DABF]/70 mt-1"></p>
                            </div>
                        </div>
                        <div class="mt-4 flex flex-wrap gap-2">
                            <a id="info-route" href="#" target="_blank" rel="noopener"
                                class="rounded-xl bg-[#F5DABF] text-[#0F3D3A] font-semibold px-4 py-2.5 hover:bg-white transition-colors duration-[250ms]">
                                Rute ke sini<span class="sr-only"> (buka di tab baru)</span>
                            </a>
                            <a id="info-detail" href="#"
                                class="rounded-xl border border-[#F5DABF]/40 text-[#F5DABF] font-semibold px-4 py-2.5 hover:bg-[#F5DABF]/10 transition-colors duration-[250ms]">
                                Detail menu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.9.4/leaflet.min.js" crossorigin=""></script>
    <script>
        (function () {
            const PLACES = @json($places);
            const FALLBACK = [-7.4667, 112.5667]; // pusat Mojoagung, hanya dipakai bila belum ada koordinat
            const withCoords = PLACES.filter((p) => p.lat != null && p.lng != null);
            const center = withCoords.length ? [withCoords[0].lat, withCoords[0].lng] : FALLBACK;

            const map = L.map('explorer-map', { zoomControl: false }).setView(center, 14);
            L.control.zoom({ position: 'bottomright' }).addTo(map);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            }).addTo(map);

            const group = L.layerGroup().addTo(map);
            const pinIcon = (active) => L.divIcon({
                className: 'ff-pin-wrap',
                html: '<div class="ff-pin' + (active ? ' active' : '') + '"></div>',
                iconSize: [30, 30],
                iconAnchor: [15, 15],
            });

            const PLACE = new Map(PLACES.map((p) => [String(p.id), p]));
            const MARKER = new Map();
            let activeId = null;
            let filterType = 'all';
            let query = '';

            withCoords.forEach((p) => {
                const m = L.marker([p.lat, p.lng], { icon: pinIcon(false), title: p.name, alt: p.name });
                m.on('click', () => select(p.id));
                MARKER.set(String(p.id), m);
            });

            function visible(p) {
                const typeOk = filterType === 'all' || p.type.toLowerCase() === filterType;
                return typeOk && p.name.toLowerCase().includes(query);
            }

            function render() {
                group.clearLayers();
                MARKER.forEach((m, id) => { if (visible(PLACE.get(id))) group.addLayer(m); });
                document.querySelectorAll('[data-place-id]').forEach((el) => {
                    const item = el.closest('[data-item]');
                    if (item) item.hidden = !visible(PLACE.get(el.dataset.placeId));
                });
            }

            function setActiveStyles() {
                document.querySelectorAll('[data-place-id]').forEach((el) => {
                    const on = el.dataset.placeId === activeId;
                    el.classList.toggle('is-active', on);
                    el.setAttribute('aria-current', on ? 'true' : 'false');
                });
            }

            function showCard(p) {
                document.getElementById('info-empty').hidden = true;
                document.getElementById('info-body').hidden = false;
                document.getElementById('info-meta').textContent = p.type + ' · ⭐ ' + (p.rating ?? '-');
                document.getElementById('info-name').textContent = p.name;
                document.getElementById('info-address').textContent = p.address || '';

                const img = document.getElementById('info-img');
                const emoji = document.getElementById('info-emoji');
                if (p.image) {
                    img.src = p.image;
                    img.hidden = false;
                    emoji.textContent = '';
                } else {
                    img.hidden = true;
                    img.removeAttribute('src');
                    emoji.textContent = p.type.toLowerCase() === 'cafe' ? '☕' : '🍽️';
                }

                document.getElementById('info-route').href = p.route || '#';
                document.getElementById('info-detail').href = p.url;
            }

            function select(id) {
                id = String(id);
                const p = PLACE.get(id);
                if (!p) return;
                if (activeId && MARKER.has(activeId)) MARKER.get(activeId).setIcon(pinIcon(false));
                activeId = id;
                if (MARKER.has(id)) {
                    MARKER.get(id).setIcon(pinIcon(true));
                    map.panTo([p.lat, p.lng], { animate: true });
                }
                showCard(p);
                setActiveStyles();
            }

            function fitAll() {
                const layers = group.getLayers();
                if (layers.length) {
                    map.fitBounds(L.featureGroup(layers).getBounds(), { padding: [48, 48], maxZoom: 16 });
                } else {
                    map.setView(center, 14);
                }
            }

            document.querySelectorAll('[data-filter]').forEach((btn) => {
                btn.addEventListener('click', () => {
                    filterType = btn.dataset.filter;
                    document.querySelectorAll('[data-filter]').forEach((b) => b.setAttribute('aria-pressed', String(b === btn)));
                    render();
                });
            });

            document.getElementById('map-search').addEventListener('input', (e) => {
                query = e.target.value.trim().toLowerCase();
                render();
            });

            document.querySelectorAll('[data-place-id]').forEach((el) => {
                el.addEventListener('click', () => select(el.dataset.placeId));
            });

            document.getElementById('fit-all').addEventListener('click', fitAll);

            if (!withCoords.length) {
                document.getElementById('info-empty').textContent = PLACES.length
                    ? 'Belum ada tempat dengan koordinat. Isi lat dan lng pada data tempat.'
                    : 'Belum ada tempat yang terdaftar.';
            }

            render();
            fitAll();
        })();
    </script>
@endpush