<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \App\Models\User::factory()->create([
            'name' => 'Eu User',
            'email' => 'eu@app.pt',
        ]);

        \App\Models\Task::factory()->count(25)->create([
            'user_id' => 1,
        ]);
    }
}
