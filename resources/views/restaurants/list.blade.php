@extends('layout')
@section('title', $title)

@section('content')
<section class="max-w-7xl mx-auto px-5 md:px-10 pt-6 pb-12">
    <p class="font-display text-muted text-xl font-semibold">
        {{ $type === 'cafe' ? 'Tempat ngopi' : 'Tempat makan' }} di sekitar Mojoagung
    </p>
    <h1 class="font-display text-6xl md:text-8xl mt-2 leading-[.95] text-ink">{{ $title }}</h1>

    <form method="GET" class="mt-10 flex gap-3 max-w-xl">
        <input name="q" value="{{ $q }}" placeholder="Cari nama tempat..." class="field rounded-xl px-6 py-4">
        <button class="btn shrink-0 rounded-xl bg-terracotta text-white font-semibold px-6 hover:bg-terracotta-dark active:scale-[0.98]">Cari</button>
    </form>
</section>

<section class="max-w-7xl mx-auto px-5 md:px-10 pb-16">
    <div class="border-t border-line">
        @forelse ($restaurants as $r)
            <div class="reveal border-b border-line" style="--d: {{ min($loop->index, 6) * 70 }}ms">
                <a href="{{ route('restaurants.show', $r) }}" class="group grid grid-cols-12 items-center gap-5 py-6">
                    <div class="img-zoom col-span-4 md:col-span-2 aspect-[4/3] rounded-xl overflow-hidden bg-sand">
                        @if ($r->image_url)
                            <img src="{{ $r->image_url }}" alt="{{ $r->name }}" class="h-full w-full object-cover">
                        @else
                            <div class="h-full flex items-center justify-center text-4xl">{{ $r->type === 'cafe' ? '☕' : '🍽️' }}</div>
                        @endif
                    </div>

                    <div class="col-span-8 md:col-span-8">
                        <p class="text-sm text-muted">{{ ucfirst($r->type) }} · ⭐ {{ $r->rating ?? '-' }}</p>
                        <h3 class="font-display text-3xl md:text-5xl mt-1 leading-tight text-ink group-hover:text-terracotta transition-colors duration-[250ms]">{{ $r->name }}</h3>
                        <p class="text-sm text-muted mt-2 line-clamp-1">{{ $r->address }}</p>
                    </div>

                    <div class="hidden md:flex md:col-span-2 justify-end">
                        <span class="h-14 w-14 rounded-full border border-edge text-ink grid place-items-center text-xl group-hover:bg-terracotta group-hover:text-white group-hover:border-terracotta transition-colors duration-[250ms]">→</span>
                    </div>
                </a>
            </div>
        @empty
            <p class="py-16 text-muted">Belum ada tempat di kategori ini. Coba kata pencarian lain, atau tambahkan tempat baru.</p>
        @endforelse
    </div>
</section>
@endsection