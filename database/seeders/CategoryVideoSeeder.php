<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;


class CategoryVideoSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->getCategoryVideo() as $row) {
            DB::table('category_video')->insert($row);
        }
    }

    private function getVideoIds(): Collection
    {
        return DB::table('videos')
            ->pluck('id');
    }

    private function getCategoryIds(): Collection
    {
        return DB::table('categories')
            ->pluck('id');
    }

    private function getCategoryVideo(): array
    {
        $result = [];
        $categories = $this->getCategoryIds();
        $categoriesMaxIdx = $categories->count() - 1;

        foreach ($this->getVideoIds() as $videoId) {
            $result[] = [
                'category_id' => $categories->get(\mt_rand(0,  $categoriesMaxIdx)),
                'video_id' => $videoId,
            ];
        }

        return $result;
    }
}
