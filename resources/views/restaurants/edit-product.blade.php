@extends('layout')
@section('title', 'Edit menu')

@section('content')
    <section class="max-w-2xl mx-auto px-5 md:px-10 pt-4 pb-16">
        <a href="{{ route('restaurants.show', $restaurant) }}"
            class="text-sm text-muted hover:text-ink transition-colors duration-[250ms]">← Kembali ke
            {{ $restaurant->name }}</a>

        <h1 class="font-display text-4xl md:text-5xl mt-6 text-ink">Edit menu</h1>
        <p class="mt-2 text-muted">{{ $product->name }}</p>

        @php
            // Saat validasi gagal, pakai input terakhir. Checkbox yang tidak dicentang tidak ikut dikirim,
            // jadi kalau ada old input, nilainya diambil dari old(), bukan dari database.
            $bestSeller = old() ? (bool) old('is_best_seller') : (bool) $product->is_best_seller;
        @endphp

        <form method="POST" action="{{ route('products.update', [$restaurant, $product]) }}" enctype="multipart/form-data"
            class="mt-8 grid gap-6 rounded-3xl bg-surface border border-line p-6 md:p-8">
            @csrf
            @method('PUT')

            <div class="grid gap-2">
                <label for="name" class="text-sm font-semibold text-ink">Nama menu</label>
                <input id="name" name="name" value="{{ old('name', $product->name) }}" required maxlength="120"
                    class="field">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid gap-2">
                    <label for="category" class="text-sm font-semibold text-ink">Kategori</label>
                    <select id="category" name="category" class="field">
                        <option value="makanan" @selected(old('category', $product->category) === 'makanan')>Makanan</option>
                        <option value="minuman" @selected(old('category', $product->category) === 'minuman')>Minuman</option>
                    </select>
                </div>
                <div class="grid gap-2">
                    <label for="price" class="text-sm font-semibold text-ink">Harga (Rp)</label>
                    <input id="price" name="price" type="number" min="0" step="1"
                        value="{{ old('price', $product->price) }}" required class="field">
                </div>
            </div>

            <div class="grid gap-2">
                <label for="description" class="text-sm font-semibold text-ink">Deskripsi (opsional)</label>
                <textarea id="description" name="description" rows="3" maxlength="500"
                    class="field">{{ old('description', $product->description) }}</textarea>
            </div>

            @if ($product->image_url)
                <div class="flex items-center gap-4">
                    <img src="{{ $product->image_url }}" alt="Foto saat ini: {{ $product->name }}"
                        class="h-20 w-28 rounded-xl object-cover border border-line">
                    <p class="text-sm text-muted">Foto saat ini. Kosongkan kolom di bawah untuk tetap memakainya.</p>
                </div>
            @endif

            <div class="grid gap-2">
                <label for="image" class="text-sm font-semibold text-ink">Ganti foto (opsional)</label>
                <input id="image" name="image" type="file" accept="image/*" class="field">
            </div>
            <div class="grid gap-2">
                <label for="image_url" class="text-sm font-semibold text-ink">Atau tempel link gambar (opsional)</label>
                <input id="image_url" name="image_url" type="url" inputmode="url" placeholder="https://..."
                    value="{{ old('image_url') }}" class="field">
                <p class="text-sm text-muted">Dipakai jika kolom foto di atas kosong.</p>
            </div>
            <label class="flex items-center gap-3 text-sm text-ink">
                <input type="checkbox" name="is_best_seller" value="1" class="accent-terracotta h-4 w-4"
                    @checked($bestSeller)>
                Tandai sebagai best seller
            </label>

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button type="submit"
                    class="btn rounded-xl bg-terracotta text-white font-semibold px-6 py-3 hover:bg-terracotta-dark active:scale-[0.98]">Simpan
                    perubahan</button>
                <a href="{{ route('restaurants.show', $restaurant) }}"
                    class="btn rounded-xl border border-edge text-ink font-semibold px-6 py-3 hover:bg-sand active:scale-[0.98]">Batal</a>
            </div>
        </form>
    </section>
@endsection