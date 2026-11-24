<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use Illuminate\Database\Seeder;

class RestaurantSeeder extends Seeder
{
    public function run(): void
    {
        // [nama, tipe, alamat, lat, lng, telepon, rating, google_place_id]
        $data = [
            ['Omah Joglo', 'restoran', 'Jl. Raya Gambiran, Gambiran Utara, Mojoagung', -7.5678414, 112.3344014, '+62 812-2888-5262', 4.0, 'ChIJce00aatteC4R_jDoKfvngrk'],
            ['Pawon Ontoroso', 'restoran', 'Gg. 3 No.4, Kauman, Mojoagung', -7.5688125, 112.3429375, '+62 856-4816-8076', 4.8, 'ChIJH7TqawBteC4Rd8WMAkleKss'],
            ['Joesam Cafe & Eatery', 'cafe', 'Sambang, Mancilan, Mojoagung', -7.5649475, 112.3513895, '+62 812-1798-5389', 4.4, 'ChIJ48P_H0hteC4ReXurIOepgLE'],
            ['Dapur Mama Gita Mojoagung', 'restoran', 'Perum Graha Jetis Indah 2, Mancilan, Mojoagung', -7.5593674, 112.3400666, '+62 823-3810-3407', 4.9, 'ChIJCxsKhjJreC4RT0gn4GKnkaY'],
            ['Savoria Cafe', 'cafe', 'Jl. Sayyid Sulaiman, Bandaran, Mancilan, Mojoagung', -7.5628261, 112.3461176, '+62 821-6227-1119', 3.9, 'ChIJE7laMgBteC4RjChEiaR9cM0'],
            ['COP Coffee', 'cafe', 'Subontoro Barat Gg. 2, Sanan Selatan, Mojotrisno, Mojoagung', -7.5722729, 112.3523784, '+62 857-0785-3555', 4.4, 'ChIJX4oym2FseC4R96J3JjWdqn0'],
            ["Langg'art Coffee", 'cafe', 'Kemodo Utara, Dukuh Mojo, Mojoagung', -7.5910899, 112.3656665, '+62 822-3278-8157', 4.8, 'ChIJfwUSbdBteC4RffkfmYAY8_E'],
            ['Sepulang Coffee', 'cafe', 'Jl. Raden Patah, Wersah, Jombang', -7.5427613, 112.2399313, null, 4.9, 'ChIJQXB58DBreC4RL2g1Txwhr_E'], // nomor dari Google terlihat tidak valid, isi manual
            ['Warung Bu Aminah', 'restoran', 'Jl. Totok Kerot No.60, Gambiran Utara, Mojoagung', -7.5683788, 112.3361848, '+62 857-9064-3528', 4.5, 'ChIJLa6rr4FseC4R2qFM2coosLY'],
            ['Warung Rawon Hj. Sumarni', 'restoran', 'Jl. Yos Sudarso, Janti, Mojoagung', -7.5752201, 112.3319109, null, 4.4, 'ChIJTQ7N64BreC4Rox6VkDEe07Y'],
            ['Rawon Rosobo', 'restoran', 'Jl. Raya Mojoagung No.191, Pekunden, Kademangan', -7.5696175, 112.3457207, '+62 823-3291-5349', 4.5, 'ChIJU_L_34dseC4RPVEaiMF6qMs'],
            ['Resto & Cafe Kitchen House Rosobo', 'restoran', 'Jl. Sayyid Sulaiman No.32, Pekunden, Kademangan', -7.5692805, 112.3457445, '+62 819-3915-1705', 3.4, 'ChIJG_od38lteC4RrFQN1Am0o6g'],
            ['Angkringan Pojok Deso', 'cafe', 'Jl. Kamboja, Miagan, Mojoagung', -7.5657789, 112.3592842, '+62 812-2005-6365', 4.5, 'ChIJs51J9RhteC4R7_MQ58pm-v0'],
        ];

        foreach ($data as [$name, $type, $address, $lat, $lng, $phone, $rating, $placeId]) {
            Restaurant::updateOrCreate(
                ['google_place_id' => $placeId],
                compact('name', 'type', 'address', 'lat', 'lng', 'phone', 'rating')
            );
        }
    }
}