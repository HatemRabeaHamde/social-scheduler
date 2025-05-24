@extends('layouts.app')

@section('content')
<div class="container analytics-container py-4">
    <h1 class="mb-4">Post Analytics</h1>

    <div class="analytics-stat mb-3">
        <strong>Total Posts:</strong> <span>{{ $totalPosts }}</span>
    </div>

    <div class="analytics-stat mb-3">
        <strong>Publishing Success Rate:</strong> <span>{{ $successRate }}%</span>
    </div>

    <div class="analytics-stat mb-3">
        <strong>Scheduled Posts:</strong> <span>{{ $scheduledCount }}</span>
    </div>

    <div class="analytics-stat mb-3">
        <strong>Published Posts:</strong> <span>{{ $publishedCount }}</span>
    </div>

    <h3 class="mt-5 mb-3">Posts Per Platform</h3>
    <ul class="posts-per-platform list-group">
        @foreach ($postsPerPlatform as $platformStat)
            <li class="list-group-item d-flex justify-content-between align-items-center">
                {{ $platformStat->name }}
                <span class="badge bg-primary rounded-pill">{{ $platformStat->total }}</span>
            </li>
        @endforeach
    </ul>
</div>

<style>
/* Base container style */
.analytics-container {
    max-width: 700px;
    margin: auto;
}

/* Light mode */
body.light-mode {
    background-color: #fff;
    color: #212529;
    transition: background-color 0.3s ease, color 0.3s ease;
}
body.light-mode .analytics-container {
    background-color: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
body.light-mode h1, body.light-mode h3 {
    color: #0d6efd;
}
body.light-mode .analytics-stat strong {
    color: #343a40;
}
body.light-mode .posts-per-platform .list-group-item {
    background-color: #fff;
    color: #212529;
    border: 1px solid #dee2e6;
    transition: background-color 0.3s ease;
}
body.light-mode .posts-per-platform .list-group-item:hover {
    background-color: #e9f0ff;
}
body.light-mode .badge.bg-primary {
    background-color: #0d6efd;
}

/* Dark mode */
body.dark-mode {
    background-color: #121212;
    color: #e4e6eb;
    transition: background-color 0.3s ease, color 0.3s ease;
}
body.dark-mode .analytics-container {
    background-color: #1e1e1e;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 12px rgba(255,255,255,0.1);
}
body.dark-mode h1, body.dark-mode h3 {
    color: #66b2ff;
}
body.dark-mode .analytics-stat strong {
    color: #cfd9ff;
}
body.dark-mode .posts-per-platform .list-group-item {
    background-color: #2c2f38;
    color: #e4e6eb;
    border: 1px solid #444;
    transition: background-color 0.3s ease;
}
body.dark-mode .posts-per-platform .list-group-item:hover {
    background-color: #3a3f51;
}
body.dark-mode .badge.bg-primary {
    background-color: #3399ff;
}

</style>
@endsection
