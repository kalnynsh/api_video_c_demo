<?php

namespace Database\Seeders;

use App\Models\Channel\Channel;
use App\Models\User\User;
use Illuminate\Database\Seeder;


class ChannelSeeder extends Seeder
{
    public function run(): void
    {
        $usersAmount = User::count();

        Channel::factory()
            ->count($usersAmount)
            ->create();
    }
}
