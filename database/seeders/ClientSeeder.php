<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user1 = User::create([
            'email' => 'client1@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Client::create([
            'name' => "client seeded 1",
            'user_id' => $user1->id,
        ]);

        $user2 = User::create([
            'email' => 'client2@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Client::create([
            'name' => "client seeded 2",
            'user_id' => $user2->id,
        ]);

        $user3 = User::create([
            'email' => 'client3@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Client::create([
            'name' => "client seeded 3",
            'user_id' => $user3->id,
        ]);

        $user4 = User::create([
            'email' => 'client4@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Client::create([
            'name' => "client seeded 4",
            'user_id' => $user4->id,
        ]);


        $user5 = User::create([
            'email' => 'client5@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Client::create([
            'name' => "client seeded 5",
            'user_id' => $user5->id,
        ]);

    }
}
