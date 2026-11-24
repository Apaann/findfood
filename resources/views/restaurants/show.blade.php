@extends('layout')
@section('title', $restaurant->name)

@section('content')
    <section class="max-w-7xl mx-auto px-5 md:px-10 pt-4 pb-16">
        <a href="{{ route($restaurant->type === 'cafe' ? 'coffee.list' : 'restaurants.list') }}"
            class="text-sm text-muted hover:text-ink transition-colors duration-[250ms]">← Kembali</a>

        <div class="grid lg:grid-cols-12 gap-10 items-end mt-6">
            <div class="img-zoom lg:col-span-7 h-[420px] rounded-3xl overflow-hidden bg-sand">
                @if ($restaurant->image_url)
                    <img src="{{ $restaurant->image_url }}" alt="{{ $restaurant->name }}" class="h-full w-full object-cover">
                @else
                    <div class="h-full flex items-center justify-center text-[8rem]">
                        {{ $restaurant->type === 'cafe' ? '☕' : '🍽️' }}
                    </div>
                @endif
            </div>

            <div class="lg:col-span-5">
                <p class="font-display text-muted text-xl font-semibold">{{ ucfirst($restaurant->type) }}</p>
                <h1 class="font-display text-5xl md:text-6xl leading-[.95] mt-2 text-ink">{{ $restaurant->name }}</h1>
                <p class="mt-5 text-muted">⭐ {{ $restaurant->rating ?? '-' }} · {{ $restaurant->address }}</p>
                @if ($restaurant->phone)
                    <p class="mt-1 text-muted">📞 {{ $restaurant->phone }}</p>
                @endif

                <div class="flex flex-wrap gap-3 mt-8">
                    <a href="{{ $restaurant->maps_url }}" target="_blank"
                        class="btn rounded-xl bg-terracotta text-white font-semibold px-6 py-3.5 hover:bg-terracotta-dark active:scale-[0.98]">📍
                        Buka Maps</a>
                    <a href="{{ $restaurant->directions_url }}" target="_blank"
                        class="btn rounded-xl border border-edge text-ink font-semibold px-6 py-3.5 hover:bg-sand active:scale-[0.98]">Rute
                        ke sini</a>
                    <a href="{{ route('restaurants.edit', $restaurant) }}"
                        class="btn rounded-xl border border-edge text-ink font-semibold px-6 py-3.5 hover:bg-sand active:scale-[0.98]">Edit
                        tempat</a>

                </div>
            </div>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-5 md:px-10 pb-24 space-y-24">
        @foreach (['makanan' => 'Menu makanan', 'minuman' => 'Menu minuman'] as $category => $label)
            @php $items = $restaurant->products->where('category', $category); @endphp
            <div>
                <div class="reveal flex items-end justify-between border-b border-line pb-5 mb-10">
                    <h2 class="font-display text-4xl md:text-5xl text-ink">{{ $label }}</h2>
                    <span class="text-sm text-muted">{{ $items->count() }} item</span>
                </div>

                <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($items as $i => $p)
                        <div class="reveal" style="--d: {{ min($i, 5) * 90 }}ms">
                            <article class="lift relative h-full rounded-3xl overflow-hidden bg-surface border border-line">
                                <div class="relative h-48 img-zoom bg-sand">
                                    @if ($p->is_best_seller)
                                        <span
                                            class="absolute top-3 left-3 z-10 rounded-lg bg-terracotta text-white text-xs font-bold px-3 py-1">🔥
                                            Best Seller</span>
                                    @endif
                                    @if ($p->image_url)
                                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}" class="h-full w-full object-cover">
                                    @else
                                        <div class="h-full flex items-center justify-center text-5xl">
                                            {{ $category === 'minuman' ? '🥤' : '🍛' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="p-5">
                                    <div class="flex items-start justify-between gap-4">
                                        <div>
                                            <h3 class="font-display text-2xl leading-tight text-ink">{{ $p->name }}</h3>
                                            @if ($p->description)
                                                <p class="text-sm text-muted mt-1">{{ $p->description }}</p>
                                            @endif
                                        </div>
                                        <p class="font-semibold text-terracotta whitespace-nowrap">Rp
                                            {{ number_format($p->price, 0, ',', '.') }}</p>
                                    </div>

                                    <div class="mt-4 flex items-center gap-4">
                                        <a href="{{ route('products.edit', [$restaurant, $p]) }}"
                                            class="text-sm font-semibold text-terracotta hover:text-terracotta-dark transition-colors duration-[250ms]">
                                            Edit<span class="sr-only"> {{ $p->name }}</span>
                                        </a>

                                        <form method="POST" action="{{ route('products.destroy', [$restaurant, $p]) }}"
                                            onsubmit="return confirm('Hapus menu {{ addslashes($p->name) }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-sm font-semibold text-terracotta hover:text-terracotta-dark transition-colors duration-[250ms]">
                                                Hapus<span class="sr-only"> {{ $p->name }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @empty
                        <p class="reveal text-muted col-span-full">Belum ada {{ strtolower($label) }}.</p>
                    @endforelse
                </div>
            </div>
        @endforeach

        <details class="reveal rounded-3xl bg-surface border border-line p-6 max-w-lg">
            <summary class="font-semibold text-ink cursor-pointer">+ Tambah menu</summary>
            <form method="POST" action="{{ route('products.store', $restaurant) }}" enctype="multipart/form-data"
                class="grid gap-3 mt-5">
                @csrf
                <input name="name" placeholder="Nama menu" required class="field">
                <div class="grid grid-cols-2 gap-3">
                    <select name="category" class="field">
                        <option value="makanan">Makanan</option>
                        <option value="minuman">Minuman</option>
                    </select>
                    <input name="price" type="number" min="0" placeholder="Harga (Rp)" required class="field">
                </div>
                <textarea name="description" placeholder="Deskripsi singkat" class="field"></textarea>
                <input name="image" type="file" accept="image/*" class="field">
                <input name="image_url" type="url" inputmode="url" placeholder="Atau tempel link gambar: https://..."
                    class="field">
                <label class="flex items-center gap-2 text-sm text-ink">
                    <input type="checkbox" name="is_best_seller" value="1" class="accent-terracotta"> Tandai sebagai best
                    seller
                </label>
                <button
                    class="btn rounded-xl bg-terracotta text-white font-semibold px-6 py-3 justify-self-start hover:bg-terracotta-dark active:scale-[0.98]">Simpan
                    menu</button>
            </form>
        </details>
    </section>
@endsection