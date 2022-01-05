<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\User;
use Illuminate\Database\Seeder;

class BusinessSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $user = User::create([
            'email' => 'business@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Business::create([
            'name' => 'Business seeded 1',
            'user_id' => $user->id,
            'email' => $user->email,
        ]);

        $user2 = User::create([
            'email' => 'business2@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Business::create([
            'name' => 'Business seeded 2',
            'user_id' => $user2->id,
            'email' => $user2->email,
        ]);

        $user3 = User::create([
            'email' => 'business3@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Business::create([
            'name' => 'Business seeded 3',
            'user_id' => $user3->id,
            'email' => $user3->email,
        ]);

        $user4 = User::create([
            'email' => 'business4@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Business::create([
            'name' => 'Business seeded 4',
            'user_id' => $user4->id,
            'email' => $user4->email,
        ]);

        $user5 = User::create([
            'email' => 'business5@email.com',
            'password' => bcrypt('Password1.'),
        ]);

        Business::create([
            'name' => 'Business seeded 5',
            'user_id' => $user5->id,
            'email' => $user5->email,
        ]);

    }
}
