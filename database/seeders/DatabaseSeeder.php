<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();
        $categories = Category::factory(8)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $events = Event::factory(20)->recycle($users)->create();

        foreach ($events as $event) {
            $event->categories()->sync(
                $categories->random(rand(1, 3))->pluck('id')
            );

            $event->participants()->attach(
                $users->random(rand(0, 5))->pluck('id')
            );
        }
    }
}
