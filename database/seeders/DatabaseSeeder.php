<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Test User',
            'username' => 'test',
            'email' => 'test@example.com',
        ]);

        $faker = Faker::create();

        DB::table('schedules')->insert([
            'ucode' => Str::random(10),
            'start_time' => '2024-11-05 08:00:00',
            'end_time' => '2024-11-05 10:00:00',
            'description' => 'This is test test test test test test test test',
            'class' => 'event-primary border-primary',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('schedules')->insert([
            'ucode' => Str::random(10),
            'start_time' => '2024-11-06 08:00:00',
            'end_time' => '2024-11-06 10:00:00',
            'description' => 'This is test test test test test test test test',
            'class' => 'event-secondary border-secondary',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('schedules')->insert([
            'ucode' => Str::random(10),
            'start_time' => '2024-11-06 10:00:00',
            'end_time' => '2024-11-06 11:00:00',
            'description' => 'This is test test test test test test test test',
            'class' => 'event-danger border-danger',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('schedules')->insert([
            'ucode' => Str::random(10),
            'start_time' => '2024-11-06 12:00:00',
            'end_time' => '2024-11-06 13:00:00',
            'description' => 'This is test test test test test test test test',
            'class' => 'event-warning border-warning',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('schedules')->insert([
            'ucode' => Str::random(10),
            'start_time' => '2024-11-06 14:00:00',
            'end_time' => '2024-11-06 17:00:00',
            'description' => 'This is test test test test test test test test',
            'class' => 'event-info border-info',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('schedules')->insert([
            'ucode' => Str::random(10),
            'start_time' => '2024-11-07 07:00:00',
            'end_time' => '2024-11-07 17:00:00',
            'description' => 'This is test test test test test test test test',
            'class' => 'event-dark border-dark',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
