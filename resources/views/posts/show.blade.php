@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="card shadow-sm post-show-card mx-auto" style="max-width: 720px;">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center rounded-top">
            <h1 class="h4 mb-0 fw-bold text-truncate">{{ $post->title }}</h1>
            <span class="badge 
                {{ $post->status === 'scheduled' ? 'bg-warning text-dark pulse-badge' : 'bg-success' }}" 
                style="font-size: 0.9rem;">
                {{ ucfirst($post->status) }}
            </span>
        </div>
        <div class="card-body">
            <p class="text-muted fst-italic mb-3">
                Scheduled for: <strong>{{ \Carbon\Carbon::parse($post->scheduled_time)->format('F j, Y \a\t g:i A') }}</strong>
            </p>

            @if($post->image)
            <div class="mb-4 text-center">
                <img src="{{ asset($post->image) }}" alt="{{ $post->title }}" class="img-fluid rounded shadow-sm" style="max-height: 350px; object-fit: cover;">
            </div>
            @endif

            <div class="mb-4" style="white-space: pre-line; line-height: 1.6; font-size: 1.05rem; color: #333;">
                {{ $post->content ?? 'No content available.' }}
            </div>

            <div class="mb-4">
                @foreach($post->platforms as $platform)
                <span class="badge bg-secondary platform-badge me-2">{{ $platform->name }}</span>
                @endforeach
            </div>

           <div class="d-flex justify-content-end gap-3">
    @if(auth()->check() && auth()->id() === $post->user_id)
        <a href="{{ route('posts.edit', $post) }}" class="btn btn-outline-primary btn-sm shadow-sm">
            <i class="bi bi-pencil-square me-1"></i> Edit
        </a>
    @endif
    <a href="{{ route('dashboard') }}" class="btn btn-secondary btn-sm shadow-sm">
        <i class="bi bi-arrow-left me-1"></i> Back to List
    </a>
</div>

        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
<style>
    .post-show-card {
        border-radius: 0.7rem;
        animation: fadeInUp 0.6s ease forwards;
        opacity: 0;
        background: #fff;
        box-shadow: 0 10px 28px rgb(0 0 0 / 0.1);
        transition: box-shadow 0.3s ease;
    }
    .post-show-card:hover {
        box-shadow: 0 14px 40px rgb(0 0 0 / 0.15);
    }
    .post-show-card .card-header {
        border-radius: 0.7rem 0.7rem 0 0;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-weight: 700;
        display: flex;
        align-items: center;
    }
    .post-show-card .badge {
        padding: 0.4em 0.75em;
        font-weight: 600;
        letter-spacing: 0.04em;
        user-select: none;
    }
    .pulse-badge {
        animation: pulse 2.5s infinite ease-in-out;
    }
    .platform-badge {
        font-size: 0.85rem;
        user-select: none;
        padding: 0.3em 0.7em;
        border-radius: 0.3rem;
        transition: background-color 0.3s ease;
    }
    .platform-badge:hover {
        background-color: #6c757d;
        cursor: default;
    }
    .btn i {
        vertical-align: middle;
    }

    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(15px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.75;
        }
    }
</style>
@endpush
