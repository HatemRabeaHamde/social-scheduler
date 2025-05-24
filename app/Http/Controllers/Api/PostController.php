<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Platform;
use App\Http\Requests\Post\PostStoreRequest;
use App\Http\Requests\Post\PostUpdateRequest;
use App\Http\Requests\Post\PostUpdateStatusRequest;
use App\Services\PostService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PostController extends Controller
{
    protected PostService $postService;

    public function __construct(PostService $postService)
    {
        $this->postService = $postService;
    }

    // List posts with pagination
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $today = now()->toDateString();

        $scheduledCount = $user->posts()
            ->whereDate('created_at', $today)
            ->where('status', 'scheduled')
            ->count();

        $remaining = max(0, 10 - $scheduledCount);

        $posts = $user->posts()->with('platforms')->latest()->paginate(10);

        return response()->json([
            'data' => $posts,
            'scheduled_count' => $scheduledCount,
            'remaining' => $remaining,
            'max_scheduled_per_day' => 10,
        ]);
    }

    // Show single post
    public function show(Post $post): JsonResponse
    {
        $post->load('platforms');
 
        return response()->json(['data' => $post]);
    }

    // Create post
    public function store(PostStoreRequest $request): JsonResponse
    {
        try {
            $postData = $request->toDTO();
            $post = $this->postService->createPost($postData);

            return response()->json([
                'message' => 'Post created and scheduled!',
                'data' => $post
            ], 201); // Created
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create post',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    // Update post
    public function update(PostUpdateRequest $request, Post $post): JsonResponse
    {
 
        try {
            $this->postService->updatePost($post, $request->validated());

            $this->logActivity('update_post', "Updated post ID: {$post->id}");

            return response()->json([
                'message' => 'Post updated successfully!',
                'data' => $post->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update post',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    // Update status only
    public function updateStatus(PostUpdateStatusRequest $request, Post $post): JsonResponse
    {
 
        try {
            $this->postService->updateStatus($post, $request->validated());

            return response()->json(['message' => 'Status updated successfully.']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update status',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    // Delete post
    public function destroy(Post $post): JsonResponse
    {
 
        try {
            $post->delete();

            $this->logActivity('delete_post', "Deleted post ID: {$post->id}");

            return response()->json(['message' => 'Post deleted successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete post',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    // Publish post
    public function publish(Post $post): JsonResponse
    {
 
        try {
            $post->update(['status' => 'published']);

            $this->logActivity('publish_post', "Published post ID: {$post->id}");

            return response()->json(['message' => 'Post published successfully!']);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to publish post',
                'details' => $e->getMessage()
            ], 422);
        }
    }

    // Analytics summary
    public function analytics(Request $request): JsonResponse
    {
        $user = $request->user();

        $totalPosts = $user->posts()->count();

        $postsPerPlatform = \DB::table('post_platform')
            ->join('platforms', 'post_platform.platform_id', '=', 'platforms.id')
            ->join('posts', 'post_platform.post_id', '=', 'posts.id')
            ->where('posts.user_id', $user->id)
            ->select('platforms.name', \DB::raw('count(posts.id) as total'))
            ->groupBy('platforms.name')
            ->get();

        $publishedCount = $user->posts()->where('status', 'published')->count();
        $scheduledCount = $user->posts()->where('status', 'scheduled')->count();
        $successRate = $totalPosts ? round(($publishedCount / $totalPosts) * 100, 2) : 0;

        return response()->json(compact(
            'totalPosts',
            'postsPerPlatform',
            'successRate',
            'scheduledCount',
            'publishedCount'
        ));
    }

    // Log activity
    protected function logActivity($action, $description = null)
    {
        \Log::info("Logging activity: $action - $description");

        \App\Models\ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
        ]);
    }
}
