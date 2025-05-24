<?php
 
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // جلب المنشورات القادمة (scheduled + published فقط)
        $upcomingPosts = Post::where('user_id', auth()->id())
            ->whereIn('status', ['scheduled', 'published'])
            ->where('scheduled_time', '>', now())
            ->orderBy('scheduled_time')
            ->limit(5)
            ->get();

        // إعداد الأحداث لعرضها في الكالندر
        $calendarEvents = Post::where('user_id', auth()->id())
            ->whereIn('status', ['scheduled', 'published']) // فقط المنشورات ذات صلة بالزمن
            ->get()
            ->map(function ($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'start' => $post->scheduled_time ? Carbon::parse($post->scheduled_time)->toIso8601String() : null,
                    'end' => $post->scheduled_time ? Carbon::parse($post->scheduled_time)->addHour()->toIso8601String() : null,
                    'color' => match ($post->status) {
                        'published' => '#10B981', // أخضر
                        'scheduled' => '#F59E0B', // برتقالي
                        default => '#6B7280',     // رمادي
                    },
                    'url' => route('posts.show', $post->id),
                    'description' => $post->excerpt ?? '', // أو وصف مختصر
                ];
            });

        return view('dashboard', [
            'upcomingPosts' => $upcomingPosts,
            'calendarEvents' => $calendarEvents,
        ]);
    }
}
