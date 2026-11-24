<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SimpanGambar;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use SimpanGambar;

    public function store(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate($this->aturan());

        $data['image'] = $this->simpanGambar($request, 'products');
        unset($data['image_url']);
        $data['is_best_seller'] = $request->boolean('is_best_seller');

        $restaurant->products()->create($data);

        return back()->with('ok', 'Menu berhasil ditambahkan.');
    }

    public function edit(Restaurant $restaurant, Product $product)
    {
        $this->pastikanMilikTempat($restaurant, $product);

        return view('restaurants.edit-product', compact('restaurant', 'product'));
    }

    public function update(Request $request, Restaurant $restaurant, Product $product)
    {
        $this->pastikanMilikTempat($restaurant, $product);

        $data = $request->validate($this->aturan());

        $product->name = $data['name'];
        $product->category = $data['category'];
        $product->price = $data['price'];
        $product->description = $data['description'] ?: null;
        $product->is_best_seller = $request->boolean('is_best_seller');
        $product->image = $this->simpanGambar($request, 'products', $product->image);

        $product->save();

        return redirect()->route('restaurants.show', $restaurant)
            ->with('ok', 'Menu berhasil diperbarui.');
    }

    private function aturan(): array
    {
        return [
            'name' => 'required|max:120',
            'category' => 'required|in:makanan,minuman',
            'price' => 'required|integer|min:0',
            'description' => 'nullable|max:500',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:2048',
        ];
    }

    private function pastikanMilikTempat(Restaurant $restaurant, Product $product): void
    {
        abort_unless($product->restaurant_id === $restaurant->id, 404);
    }

    public function destroy(Restaurant $restaurant, Product $product)
    {
        $this->pastikanMilikTempat($restaurant, $product);

        $this->hapusFileLokal($product->image);

        $product->delete();

        return redirect()->route('restaurants.show', $restaurant)
            ->with('ok', 'Menu berhasil dihapus.');
    }

}