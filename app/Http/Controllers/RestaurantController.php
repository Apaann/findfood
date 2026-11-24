<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SimpanGambar;
use App\Models\Product;
use App\Models\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    use SimpanGambar;

    public function home()
    {
        $ranked = Restaurant::orderByDesc('rating')->get();

        return view('home', [
            'hero' => $ranked->first(),
            'featured' => $ranked->slice(1, 4)->values(),
            'coffee' => Restaurant::where('type', 'cafe')->orderByDesc('rating')->take(3)->get(),
            'favorites' => Product::with('restaurant')
                ->where('is_best_seller', true)
                ->latest()
                ->take(8)
                ->get(),
            'names' => Restaurant::orderBy('name')->pluck('name')->all(),
            'stats' => [
                'restoran' => Restaurant::where('type', 'restoran')->count(),
                'cafe' => Restaurant::where('type', 'cafe')->count(),
                'menu' => Product::count(),
                'rating' => round((float) Restaurant::avg('rating'), 1),
            ],
        ]);
    }

    public function restaurants(Request $request)
    {
        return $this->listing($request, 'restoran', 'Restoran');
    }

    public function coffee(Request $request)
    {
        return $this->listing($request, 'cafe', 'Cafe');
    }

    private function listing(Request $request, string $type, string $title)
    {
        $q = $request->query('q', '');

        $restaurants = Restaurant::where('type', $type)
            ->when($q, fn($query) => $query->where('name', 'like', "%{$q}%"))
            ->orderByDesc('rating')
            ->get();

        return view('restaurants.list', compact('restaurants', 'q', 'type', 'title'));
    }

    public function show(Restaurant $restaurant)
    {
        $restaurant->load('products');

        return view('restaurants.show', compact('restaurant'));
    }

    public function map()
    {
        $restaurants = Restaurant::orderBy('name')->get();

        $places = $restaurants->map(fn($r) => [
            'id' => $r->id,
            'name' => $r->name,
            'type' => ucfirst($r->type),
            'rating' => $r->rating,
            'address' => $r->address,
            'lat' => $r->lat,
            'lng' => $r->lng,
            'image' => $r->image_url,
            'url' => route('restaurants.show', $r),
            'route' => $r->directions_url,
        ])->values();

        return view('restaurants.map', compact('restaurants', 'places'));
    }

    public function create()
    {
        return view('restaurants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->aturanTempat());
        $data['image'] = $this->simpanGambar($request, 'restaurants');
        unset($data['image_url']);

        $restaurant = Restaurant::create($data);

        return redirect()->route('restaurants.show', $restaurant)
            ->with('ok', 'Tempat berhasil ditambahkan.');
    }

    public function edit(Restaurant $restaurant)
    {
        return view('restaurants.edit', compact('restaurant'));
    }

    public function update(Request $request, Restaurant $restaurant)
    {
        $data = $request->validate($this->aturanTempat());

        $data['image'] = $this->simpanGambar($request, 'restaurants', $restaurant->image);
        unset($data['image_url']);

        $restaurant->update($data);

        return redirect()->route('restaurants.show', $restaurant)
            ->with('ok', 'Tempat berhasil diperbarui.');
    }

    private function aturanTempat(): array
    {
        return [
            'name' => 'required|max:120',
            'type' => 'required|in:restoran,cafe',
            'address' => 'required|max:255',
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'phone' => 'nullable|max:30',
            'rating' => 'nullable|numeric|between:0,5',
            'image' => 'nullable|image|max:2048',
            'image_url' => 'nullable|url|max:2048',
        ];
    }

    public function destroy(Restaurant $restaurant)
    {
        $this->hapusFileLokal($restaurant->image);

        $restaurant->delete();

        return redirect()->route('restaurants.list')
            ->with('ok', 'Tempat berhasil dihapus.');
    }

}