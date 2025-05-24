<?php
 
namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Carbon\Carbon;

class PublishScheduledPosts extends Command
{
    protected $signature = 'posts:publish';
    protected $description = 'Publish scheduled posts that are due';

    public function handle()
    {
        $now = Carbon::now('Africa/Cairo');

        $posts = Post::where('status', 'scheduled')
            ->where('scheduled_time', '<=', $now)
            ->get();

        foreach ($posts as $post) {
            $post->update(['status' => 'published']);
            $this->info("Post #{$post->id} has been published at {$now}");
        }

        $this->info("Total published: {$posts->count()}");
        return Command::SUCCESS;
    }
}
