<?php

namespace Database\Seeders;

use App\Models\User\User;
use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    private const QUANTITY = 30;

    public function run(): void
    {
        User::factory()
            ->count(self::QUANTITY)
            ->create();
    }
}
