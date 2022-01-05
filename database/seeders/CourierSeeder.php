<?php

namespace Database\Seeders;

use App\Models\Courier;
use App\Models\User;
use Illuminate\Database\Seeder;

class CourierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'email' => 'courier1@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Courier::create([
            'name' => "courier seeded 1",
            'user_id' => $user1->id,
        ]);

        $user2 = User::create([
            'email' => 'courier2@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Courier::create([
            'name' => "courier seeded 2",
            'user_id' => $user2->id,
        ]);
    }
}
