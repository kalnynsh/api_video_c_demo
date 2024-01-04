<?php

namespace Database\Seeders;

use App\Models\Video\Video;
use App\Models\Channel\Channel;
use Illuminate\Database\Seeder;

class VideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videoAmount = Channel::count() * 2;

        Video::factory()
            ->count($videoAmount)
            ->create();
    }
}
