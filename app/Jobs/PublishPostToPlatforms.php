<?php
namespace App\Jobs;

use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PublishPostToPlatforms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Post $post) {}

    public function handle()
    {
        try {
            foreach ($this->post->platforms as $platform) {
                $this->publishToPlatform($platform);
            }
            
            $this->post->update(['status' => 'published']);
            
        } catch (\Exception $e) {
            $this->post->update(['status' => 'failed']);
            Log::error("Failed to publish post #{$this->post->id}: " . $e->getMessage());
        }
    }

    protected function publishToPlatform($platform)
    {
        try {
            // Simulate API call with 90% success rate
            $success = rand(1, 10) <= 9;
            
            $this->post->platforms()->updateExistingPivot($platform->id, [
                'status' => $success ? 'published' : 'failed',
                'failure_reason' => $success ? null : 'API request failed'
            ]);

            if (!$success) {
                throw new \Exception("Failed to publish to {$platform->name}");
            }

        } catch (\Exception $e) {
            $this->post->platforms()->updateExistingPivot($platform->id, [
                'status' => 'failed',
                'failure_reason' => $e->getMessage()
            ]);
            throw $e;
        }
    }
}