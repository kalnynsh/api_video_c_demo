<?php

namespace Database\Factories\Channel;

use App\Models\Channel\Channel;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Channel\Channel>
 */
class ChannelFactory extends Factory
{
    protected $model = Channel::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => \ucfirst($this->faker->words(\mt_rand(1, 3), true)),
            'user_id' => User::inRandomOrder('id')->first()->id,
        ];
    }
}
