<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait SimpanGambar
{
    /**
     * Menentukan nilai kolom 'image' dari form.
     * Prioritas: file yang diunggah, lalu link dari 'image_url', lalu gambar lama.
     */
    protected function simpanGambar(Request $request, string $folder, ?string $gambarLama = null): ?string
    {
        if ($request->hasFile('image')) {
            $this->hapusFileLokal($gambarLama);

            return $request->file('image')->store($folder, 'public');
        }

        if ($request->filled('image_url')) {
            $this->hapusFileLokal($gambarLama);

            return $request->input('image_url');
        }

        return $gambarLama;
    }

    protected function hapusFileLokal(?string $path): void
    {
        if ($path && !str_starts_with($path, 'http://') && !str_starts_with($path, 'https://')) {
            Storage::disk('public')->delete($path);
        }
    }
}