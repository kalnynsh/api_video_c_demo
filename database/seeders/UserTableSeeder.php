<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    private const QUANTITY = 10;

    public function run(): void
    {
        foreach ($this->getUsers(self::QUANTITY) as $user) {
            DB::table('users')->insert($user);
        }
    }

    private function getUsers($quantity = 10): array
    {
        $users = [];
        $faker = fake();

        foreach (\range(1, $quantity) as $i) {
            $name = $faker->name;
            $email = \str_replace(' ', '.', \strtolower($name)) . '@google.com';
            $dateTime = $this->getRandomDateTime();

            $users[] = [
                'name' => $name,
                'email' => $email,
                'password' => hash('sha256','Secret81%fi'),
                'created_at' => $dateTime->format('Y-m-d H:i:s'),
                'updated_at' => $dateTime->modify('+1 minutes')->format('Y-m-d H:i:s'),
                'email_verified_at' => $dateTime->modify('+5 minutes')->format('Y-m-d H:i:s'),
            ];
        }

        return $users;
    }

    private function getRandomDateTime(): \DateTimeImmutable
    {
        $randomInt = \random_int(0, 100);
        $dateTime = (new \DateTimeImmutable())
                        ->modify('-' . $randomInt . ' hours');

        return $dateTime;
    }
}
