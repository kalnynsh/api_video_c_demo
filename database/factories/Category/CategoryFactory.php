<?php

namespace Database\Factories\Category;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category\Category>
 */
class CategoryFactory extends Factory
{
    public static $counter = -1;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->getCategoryName(),
            'created_at' => $this->faker->dateTimeBetween(
                '-' . \mt_rand(1, 120) . 'days',
                'now'
            ),
            'updated_at' => $this->faker->dateTimeBetween(
                '-' . \mt_rand(1, 120) . 'days',
                'now'
            ),
        ];
    }

    public function getCategoryAmount(): int
    {
        return count($this->getCategoryList());
    }

    private function getCategoryName(): string
    {
        $categoryAmount = $this->getCategoryAmount();
        $list = $this->getCategoryList();
        self::$counter += 1;
        $index = self::$counter;

        if ($index <= ($categoryAmount - 1)) {
            return $list[$index];
        }

        return '';
    }

    private function getCategoryList(): array
    {
        return [
            'Recently uploaded',
            'Music',
            'Electronic music',
            'Dance music',
            'Europop music',
            'Chill-out music',
            'Sketch comedy',
            'Television series',
            'Action Thrillers',
            'New Year',
            'Gaming',
            'Action-adventure games',
            'Real-time strategy games',
            'Live',
            'Thrillers',
            'Dramedy',
            'Astronomy',
            'Test drives',
            'History',
            'Trains',
            'Melodramas',
            'History',
            'Podcasts',
            'Mysteries',
            'Wildness',
            'Classical Music',
            'HTML',
            'Cars',
            'Podcasts',
        ];
    }
}
