<?php
namespace Database\Seeders;

use App\Models\Platform;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $platforms = [
            ['name' => 'Twitter', 'type' => 'twitter'],
            ['name' => 'Instagram', 'type' => 'instagram'],
            ['name' => 'LinkedIn', 'type' => 'linkedin'],
            ['name' => 'Facebook', 'type' => 'facebook'],
            ['name' => 'TikTok', 'type' => 'tiktok'],
        ];

        foreach ($platforms as $platform) {
            Platform::create($platform);
        }

        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create 10 posts with proper factory data
        Post::factory(10)->create([
            'user_id' => $user->id,
        ])->each(function ($post) {
            $post->platforms()->attach(
                Platform::inRandomOrder()->limit(rand(1, 3))->pluck('id')
            );
        });
    }
}