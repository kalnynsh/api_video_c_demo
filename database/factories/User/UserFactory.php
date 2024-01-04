<?php

namespace Database\Factories\User;

use App\Models\User\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

use function fake;
use function hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $dateTime = $this->getRandomDateTime();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'password' => hash('sha256','Secret81%fi'),
            'remember_token' => Str::random(10),
            'created_at' => $dateTime->format('Y-m-d H:i:s'),
            'updated_at' => $dateTime->modify('+1 minutes')->format('Y-m-d H:i:s'),
            'email_verified_at' => $dateTime->modify('+5 minutes')->format('Y-m-d H:i:s'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    private function getRandomDateTime(): \DateTimeImmutable
    {
        $randomInt = \random_int(0, 100);
        $dateTime = (new \DateTimeImmutable())
                        ->modify('-' . $randomInt . ' hours');

        return $dateTime;
    }
}
