<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    const DEFAULT_PASSWORD = 'Password123';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::truncate();

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@test.com',
            ],
            [
                'name' => 'Goku',
                'email' => 'goku@test.com',
            ],
            [
                'name' => 'Vegeta',
                'email' => 'vegeta@test.com',
            ],
            [
                'name' => 'Saitama',
                'email' => 'saitama@test.com',
            ],
        ];

        foreach ($users as $user) {
            $user = array_merge($user, ['password' => $this->cryptedPassword()]);
            User::create($user);
        }
    }

    private function cryptedPassword()
    {
        return bcrypt(self::DEFAULT_PASSWORD);
    }
}
