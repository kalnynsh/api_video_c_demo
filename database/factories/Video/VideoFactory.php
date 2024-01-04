<?php

namespace Database\Factories\Video;

use App\Models\Channel\Channel;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video\Video>
 */
class VideoFactory extends Factory
{
    protected $model = Video::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => \ucfirst($this->faker->words(\mt_rand(1, 3), true)),
            'description'=> $this->faker->sentence(),
            'channel_id' => Channel::inRandomOrder('id')->first()->id,
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
}
