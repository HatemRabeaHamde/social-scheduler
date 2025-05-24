<?php
namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Platform;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Requests\Post\PostStoreRequest;
use App\Services\PostService;
use Illuminate\Http\RedirectResponse;
   use App\Http\Requests\Post\PostUpdateRequest;
 use App\Http\Requests\Post\PostUpdateStatusRequest;

class PostController extends Controller
{
     protected PostService $postService;  

    public function __construct(PostService $postService)  
    {
        $this->postService = $postService;  
    }

 

 public function index()
{
    $user = auth()->user();
    $today = now()->toDateString();

    $scheduledCount = $user->posts()
        ->whereDate('created_at', $today) 
        ->where('status', 'scheduled')
        ->count();

    $remaining = max(0, 10 - $scheduledCount);

    return view('posts.index', [
        'posts' => $user->posts()->with('platforms')->latest()->paginate(5),
        'scheduledCount' => $scheduledCount,
        'remaining' => $remaining,
        'maxScheduledPerDay' => 10,
    ]);
}

public function edit(Post $post)
{
    $user = $post->user; 
    $platforms = $user->platforms;  

    return view('posts.edit', compact('post', 'platforms'));
}

public function update(PostUpdateRequest $request, Post $post): RedirectResponse
{
    $this->postService->updatePost($post, $request->validated());

    $this->logActivity('update_post', "Updated post ID: {$post->id}");

    return redirect()->route('posts.show', $post->id)->with('success', 'Post updated successfully!');
}

public function updateStatus(PostUpdateStatusRequest $request, Post $post): RedirectResponse
{
    $this->postService->updateStatus($post, $request->validated());

    return back()->with('success', 'تم تحديث الحالة بنجاح.');
}
 
  public function store(PostStoreRequest $request): RedirectResponse
{
    try {
        $postData = $request->toDTO();
        $this->postService->createPost($postData);

        return redirect()->route('posts.index')->with('success', 'Post created and scheduled!');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()])->withInput();
    }
}


public function create()
{
    $user = auth()->user();
    $platforms = $user->platforms;

    $today = now()->toDateString();

    $scheduledCount = $user->posts()
        ->whereDate('created_at', $today)
        ->where('status', 'scheduled')
        ->count();

    $remaining = max(0, 10 - $scheduledCount);

    return view('posts.create', [
        'platforms' => $platforms,
        'scheduledCount' => $scheduledCount,
        'remaining' => $remaining,
        'maxScheduledPerDay' => 10,
    ]);
}


  
 
    public function show(Post $post)
{
    // Eager load platforms relation if not already loaded
    $post->load('platforms');

    return view('posts.show', compact('post'));
}

 

    public function publish(Post $post)
    {
         $post->update(['status' => 'published']);

        $this->logActivity('publish_post', "Published post ID: {$post->id}");

        return back()->with('success', 'Post published successfully!');
    }

    public function analytics()
    {
        $user = auth()->user();

        $totalPosts = $user->posts()->count();

      $postsPerPlatform = \DB::table('post_platform') // corrected table name
    ->join('platforms', 'post_platform.platform_id', '=', 'platforms.id')
    ->join('posts', 'post_platform.post_id', '=', 'posts.id')
    ->where('posts.user_id', $user->id)
    ->select('platforms.name', \DB::raw('count(posts.id) as total'))
    ->groupBy('platforms.name')
    ->get();


        $publishedCount = $user->posts()->where('status', 'published')->count();
        $successRate = $totalPosts ? round(($publishedCount / $totalPosts) * 100, 2) : 0;

        $scheduledCount = $user->posts()->where('status', 'scheduled')->count();

        return view('posts.analytics', compact(
            'totalPosts', 'postsPerPlatform', 'successRate', 'scheduledCount', 'publishedCount'
        ));
    }

    protected function logActivity($action, $description = null)
    {
            \Log::info("Logging activity: $action - $description");

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'description' => $description,
        ]);
    }

    public function activityLog()
    {
        $logs = auth()->user()->activityLogs()->latest()->paginate(15);
        return view('posts.activity-log', compact('logs'));
    }

    public function destroy(Post $post)
    {
         $post->delete();

        $this->logActivity('delete_post', "Deleted post ID: {$post->id}");

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }
  

}
