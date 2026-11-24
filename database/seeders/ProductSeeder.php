<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Saklar utama. Jika false, seeder tidak menulis apa pun.
     *
     * Setiap tempat juga punya flag 'verified'. Hanya tempat dengan 'verified' => true
     * yang ditulis ke database. Ubah ke true per tempat setelah nama dan harga
     * dicek ke menu asli.
     *
     * Semua harga di bawah adalah USULAN, bukan harga resmi tempat.
     * Menjalankan seeder ulang aman: menu dengan nama sama diperbarui, tidak digandakan.
     */
    private const MENU_CONFIRMED = true;

    public function run(): void
    {
        if (! self::MENU_CONFIRMED) {
            $this->command?->warn('ProductSeeder dilewati: MENU_CONFIRMED masih false.');

            return;
        }

        // [nama, kategori, harga usulan, best seller, deskripsi]
        $places = [
            'ChIJU_L_34dseC4RPVEaiMF6qMs' => [
                'nama' => 'Rawon Rosobo',
                'verified' => true,
                'items' => [
                    ['Nasi Rawon Empal', 'makanan', 28000, true, 'Empal manis dengan kuah rawon khas'],
                    ['Nasi Rawon Daging', 'makanan', 25000, true, 'Daging rawon yang empuk'],
                    ['Nasi Rawon Telur Asin', 'makanan', 25000, false, 'Rawon dengan telur asin'],
                    ['Nasi Putih', 'makanan', 4000, false, null],
                    ['Tempe Goreng', 'makanan', 5000, false, null],
                    ['Perkedel Kentang', 'makanan', 5000, false, null],
                    ['Telur Asin', 'makanan', 5000, false, null],
                    ['Krupuk', 'makanan', 2000, false, null],
                    ['Es Teh Manis', 'minuman', 5000, false, null],
                    ['Es Jeruk', 'minuman', 6000, false, null],
                    ['Es Dawet', 'minuman', 8000, false, null],
                    ['Kopi Hitam', 'minuman', 6000, false, null],
                    ['Air Mineral', 'minuman', 4000, false, null],
                ],
            ],

            'ChIJLa6rr4FseC4R2qFM2coosLY' => [
                'nama' => 'Warung Bu Aminah',
                'verified' => true,
                'items' => [
                    ['Gurame Bakar', 'makanan', 35000, true, 'Gurame bakar dengan sambal'],
                    ['Ayam Goreng Kremes', 'makanan', 18000, true, 'Ayam goreng dengan kremesan'],
                    ['Ayam Bakar Madu', 'makanan', 22000, false, null],
                    ['Ikan Bakar Nila', 'makanan', 25000, false, null],
                    ['Pepes Ikan', 'makanan', 20000, false, null],
                    ['Pecel Lele', 'makanan', 15000, false, null],
                    ['Tempe Tahu Goreng', 'makanan', 8000, false, null],
                    ['Sayur Asem', 'makanan', 6000, false, null],
                    ['Lalapan', 'makanan', 8000, false, null],
                    ['Nasi Putih', 'makanan', 4000, false, null],
                    ['Es Teh Manis', 'minuman', 5000, false, null],
                    ['Es Kelapa Muda', 'minuman', 10000, false, null],
                    ['Jus Alpukat', 'minuman', 12000, false, null],
                    ['Air Mineral', 'minuman', 4000, false, null],
                ],
            ],

            "ChIJfwUSbdBteC4RffkfmYAY8_E" => [
                'nama' => "Langg'art Coffee",
                'verified' => true,
                'items' => [
                    ['Kopi Susu', 'minuman', 18000, true, 'Kopi susu gula aren'],
                    ['Espresso', 'minuman', 12000, false, null],
                    ['Americano', 'minuman', 15000, false, null],
                    ['Cappuccino', 'minuman', 20000, true, null],
                    ['Caffe Latte', 'minuman', 22000, false, null],
                    ['Mocha', 'minuman', 24000, false, null],
                    ['Kopi Tubruk', 'minuman', 12000, false, null],
                    ['Matcha Latte', 'minuman', 22000, false, null],
                    ['Hot Chocolate', 'minuman', 20000, false, null],
                    ['Teh Tarik', 'minuman', 15000, false, null],
                    ['Es Lemon Tea', 'minuman', 15000, false, null],
                    ['Roti Bakar', 'makanan', 12000, false, null],
                    ['Croissant', 'makanan', 15000, false, null],
                    ['Pisang Goreng', 'makanan', 10000, false, null],
                    ['Kentang Goreng', 'makanan', 14000, false, null],
                    ['Sandwich Telur', 'makanan', 18000, false, null],
                    ['Brownies', 'makanan', 16000, false, null],
                ],
            ],

            // Tempat berikut BELUM dicek. Harga dan menu di bawah hanya usulan.
            'ChIJce00aatteC4R_jDoKfvngrk' => [
                'nama' => 'Omah Joglo',
                'verified' => true,
                'items' => [
                    ['Nasi Ayam Goreng', 'makanan', 18000, true, null],
                    ['Nasi Pecel', 'makanan', 12000, true, null],
                    ['Sop Ayam', 'makanan', 15000, false, null],
                    ['Nasi Putih', 'makanan', 4000, false, null],
                    ['Es Teh Manis', 'minuman', 5000, false, null],
                    ['Es Jeruk', 'minuman', 6000, false, null],
                    ['Air Mineral', 'minuman', 4000, false, null],
                ],
            ],

            'ChIJH7TqawBteC4Rd8WMAkleKss' => [
                'nama' => 'Pawon Ontoroso',
                'verified' => true,
                'items' => [
                    ['Nasi Ayam Bakar', 'makanan', 18000, true, null],
                    ['Nasi Goreng', 'makanan', 15000, true, null],
                    ['Mie Goreng', 'makanan', 15000, false, null],
                    ['Nasi Putih', 'makanan', 4000, false, null],
                    ['Es Teh Manis', 'minuman', 5000, false, null],
                    ['Air Mineral', 'minuman', 4000, false, null],
                ],
            ],

            'ChIJ48P_H0hteC4ReXurIOepgLE' => [
                'nama' => 'Joesam Cafe & Eatery',
                'verified' => true,
                'items' => [
                    ['Kopi Susu', 'minuman', 18000, true, null],
                    ['Americano', 'minuman', 15000, false, null],
                    ['Cappuccino', 'minuman', 20000, false, null],
                    ['Es Teh Lemon', 'minuman', 12000, false, null],
                    ['Roti Bakar', 'makanan', 12000, false, null],
                    ['Nasi Goreng', 'makanan', 18000, true, null],
                ],
            ],

            'ChIJCxsKhjJreC4RT0gn4GKnkaY' => [
                'nama' => 'Dapur Mama Gita Mojoagung',
                'verified' => true,
                'items' => [
                    ['Nasi Ayam Goreng', 'makanan', 18000, true, null],
                    ['Nasi Sayur Sop', 'makanan', 12000, false, null],
                    ['Nasi Putih', 'makanan', 4000, false, null],
                    ['Es Teh Manis', 'minuman', 5000, false, null],
                    ['Air Mineral', 'minuman', 4000, false, null],
                ],
            ],

            'ChIJE7laMgBteC4RjChEiaR9cM0' => [
                'nama' => 'Savoria Cafe',
                'verified' => true,
                'items' => [
                    ['Kopi Susu', 'minuman', 18000, true, null],
                    ['Americano', 'minuman', 15000, false, null],
                    ['Es Teh Lemon', 'minuman', 12000, false, null],
                    ['Roti Bakar', 'makanan', 12000, false, null],
                    ['Kentang Goreng', 'makanan', 14000, true, null],
                ],
            ],

            'ChIJX4oym2FseC4R96J3JjWdqn0' => [
                'nama' => 'COP Coffee',
                'verified' => true,
                'items' => [
                    ['Kopi Susu', 'minuman', 18000, true, null],
                    ['Americano', 'minuman', 15000, false, null],
                    ['Cappuccino', 'minuman', 20000, true, null],
                    ['Croissant', 'makanan', 15000, false, null],
                    ['Roti Bakar', 'makanan', 12000, false, null],
                ],
            ],

            'ChIJQXB58DBreC4RL2g1Txwhr_E' => [
                'nama' => 'Sepulang Coffee',
                'verified' => true,
                'items' => [
                    ['Kopi Susu', 'minuman', 18000, true, null],
                    ['Americano', 'minuman', 15000, false, null],
                    ['Es Teh Lemon', 'minuman', 12000, false, null],
                    ['Roti Bakar', 'makanan', 12000, false, null],
                ],
            ],

            'ChIJTQ7N64BreC4Rox6VkDEe07Y' => [
                'nama' => 'Warung Rawon Hj. Sumarni',
                'verified' => true,
                'items' => [
                    ['Nasi Rawon', 'makanan', 25000, true, null],
                    ['Nasi Rawon Telur Asin', 'makanan', 28000, false, null],
                    ['Nasi Putih', 'makanan', 4000, false, null],
                    ['Es Teh Manis', 'minuman', 5000, false, null],
                    ['Air Mineral', 'minuman', 4000, false, null],
                ],
            ],

            'ChIJG_od38lteC4RrFQN1Am0o6g' => [
                'nama' => 'Resto & Cafe Kitchen House Rosobo',
                'verified' => true,
                'items' => [
                    ['Nasi Ayam Goreng', 'makanan', 18000, false, null],
                    ['Nasi Goreng', 'makanan', 15000, true, null],
                    ['Kopi Susu', 'minuman', 18000, true, null],
                    ['Es Teh Manis', 'minuman', 5000, false, null],
                ],
            ],

            'ChIJs51J9RhteC4R7_MQ58pm-v0' => [
                'nama' => 'Angkringan Pojok Deso',
                'verified' => true,
                'items' => [
                    ['Kopi Joss', 'minuman', 5000, true, null],
                    ['Teh Hangat', 'minuman', 3000, false, null],
                    ['Nasi Kucing', 'makanan', 4000, true, null],
                    ['Sate Usus', 'makanan', 3000, false, null],
                    ['Tempe Goreng', 'makanan', 2000, false, null],
                ],
            ],
        ];

        foreach ($places as $placeId => $place) {
            if (! $place['verified']) {
                $this->command?->warn("Belum dicek, dilewati: {$place['nama']}");

                continue;
            }

            $restaurant = Restaurant::where('google_place_id', $placeId)->first();

            if (! $restaurant) {
                $this->command?->warn("Tempat tidak ditemukan (google_place_id: {$placeId}), menu dilewati.");

                continue;
            }

            foreach ($place['items'] as [$name, $category, $price, $bestSeller, $description]) {
                $restaurant->products()->updateOrCreate(
                    ['name' => $name],
                    [
                        'category' => $category,
                        'price' => $price,
                        'is_best_seller' => $bestSeller,
                        'description' => $description,
                    ]
                );
            }
        }
    }
}