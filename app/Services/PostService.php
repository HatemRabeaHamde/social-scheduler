<?php
 
namespace App\Services;

use App\DTOs\Post\PostData;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PostService
{
   public function createPost(PostData $data): Post
{
    return DB::transaction(function () use ($data) {
      
        $user = Auth::user();

        $scheduledPostsCount = $user->posts()
            ->whereDate('scheduled_time', $data->scheduledTime->toDateString())
            ->where('status', 'scheduled')
            ->count();

        if ($scheduledPostsCount >= 10) {
            throw new \Exception('You have reached the maximum of 10 scheduled posts for this day.');
        }

        $post = $user->posts()->create([
            'title' => $data->title,
            'content' => $data->content,
            'image' => $data->imageUrl,
            'scheduled_time' => $data->scheduledTime,
            'status' => 'scheduled',
        ]);

        $post->platforms()->attach($data->platformIds);

        $this->logActivity('create_post', "Created post titled: {$data->title}");

        return $post;
    });
}

    protected function logActivity(string $action, ?string $description = null): void
    {
        Log::info("Logging activity: $action - $description");

        Auth::user()->activityLogs()->create([
            'action' => $action,
            'description' => $description,
        ]);
    }
   public function updatePost(Post $post, array $data): void
{
    if (isset($data['image'])) {
        $data['image'] = $data['image']->store('posts', 'public');
    }
    $platforms = $data['platforms'] ?? [];
    unset($data['platforms'], $data['image']);

    // Set status to 'scheduled' on update
    $data['status'] = 'scheduled';

    $post->update($data);
    $post->platforms()->sync($platforms);
}

public function updateStatus(Post $post, array $data): void
{
    if ($data['status'] === 'scheduled' && isset($data['scheduled_time'])) {
        $data['scheduled_time'] = Carbon::parse($data['scheduled_time']);
    } else {
        $data['scheduled_time'] = null;
    }
    $post->update([
        'status' => $data['status'],
        'scheduled_time' => $data['scheduled_time'],
    ]);
}
 

}
