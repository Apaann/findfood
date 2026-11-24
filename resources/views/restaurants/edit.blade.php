@extends('layout')
@section('title', 'Edit tempat')

@section('content')
    <section class="max-w-2xl mx-auto px-5 md:px-10 pt-4 pb-16">
        <a href="{{ route('restaurants.show', $restaurant) }}"
            class="text-sm text-muted hover:text-ink transition-colors duration-[250ms]">← Kembali ke
            {{ $restaurant->name }}</a>

        <h1 class="font-display text-4xl md:text-5xl mt-6 text-ink">Edit tempat</h1>
        <p class="mt-2 text-muted">Ubah data dan foto tempat. Foto baru akan menggantikan foto lama.</p>

        <form method="POST" action="{{ route('restaurants.update', $restaurant) }}" enctype="multipart/form-data"
            class="mt-8 grid gap-6 rounded-3xl bg-surface border border-line p-6 md:p-8">
            @csrf
            @method('PUT')

            @if ($restaurant->image_url)
                <div class="flex items-center gap-4">
                    <img src="{{ $restaurant->image_url }}" alt="Foto saat ini: {{ $restaurant->name }}"
                        class="h-24 w-32 rounded-xl object-cover border border-line">
                    <p class="text-sm text-muted">Foto saat ini. Kosongkan kolom foto dan link di bawah untuk tetap memakainya.
                    </p>
                </div>
            @endif

            <div class="grid gap-2">
                <label for="name" class="text-sm font-semibold text-ink">Nama tempat</label>
                <input id="name" name="name" value="{{ old('name', $restaurant->name) }}" required maxlength="120"
                    class="field">
            </div>

            <div class="grid gap-2">
                <label for="type" class="text-sm font-semibold text-ink">Jenis</label>
                <select id="type" name="type" class="field">
                    <option value="restoran" @selected(old('type', $restaurant->type) === 'restoran')>Restoran</option>
                    <option value="cafe" @selected(old('type', $restaurant->type) === 'cafe')>Cafe</option>
                </select>
            </div>

            <div class="grid gap-2">
                <label for="address" class="text-sm font-semibold text-ink">Alamat lengkap</label>
                <input id="address" name="address" value="{{ old('address', $restaurant->address) }}" required
                    maxlength="255" class="field">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid gap-2">
                    <label for="lat" class="text-sm font-semibold text-ink">Latitude</label>
                    <input id="lat" name="lat" type="number" step="any" min="-90" max="90"
                        value="{{ old('lat', $restaurant->lat) }}" required class="field">
                </div>
                <div class="grid gap-2">
                    <label for="lng" class="text-sm font-semibold text-ink">Longitude</label>
                    <input id="lng" name="lng" type="number" step="any" min="-180" max="180"
                        value="{{ old('lng', $restaurant->lng) }}" required class="field">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div class="grid gap-2">
                    <label for="phone" class="text-sm font-semibold text-ink">Telepon (opsional)</label>
                    <input id="phone" name="phone" type="tel" value="{{ old('phone', $restaurant->phone) }}" maxlength="30"
                        class="field">
                </div>
                <div class="grid gap-2">
                    <label for="rating" class="text-sm font-semibold text-ink">Rating 0 sampai 5 (opsional)</label>
                    <input id="rating" name="rating" type="number" step="0.1" min="0" max="5"
                        value="{{ old('rating', $restaurant->rating) }}" class="field">
                </div>
            </div>

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

            <div class="flex flex-wrap items-center gap-3 pt-2">
                <button type="submit"
                    class="btn rounded-xl bg-terracotta text-white font-semibold px-6 py-3 hover:bg-terracotta-dark active:scale-[0.98]">
                    Simpan perubahan
                </button>
                <a href="{{ route('restaurants.show', $restaurant) }}"
                    class="btn rounded-xl border border-edge text-ink font-semibold px-6 py-3 hover:bg-sand active:scale-[0.98]">Batal</a>
            </div>
        </form>
        <form method="POST" action="{{ route('restaurants.destroy', $restaurant) }}" class="mt-6"
            onsubmit="return confirm('Hapus {{ addslashes($restaurant->name) }}? Semua menunya ikut terhapus dan tidak bisa dikembalikan.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="btn rounded-xl border border-terracotta text-terracotta font-semibold px-6 py-3 hover:bg-terracotta hover:text-white active:scale-[0.98]">
                Hapus tempat
            </button>
        </form> 
    </section>
@endsection