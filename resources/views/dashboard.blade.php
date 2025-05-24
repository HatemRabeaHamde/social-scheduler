@extends('layouts.app')

@section('content')
<div class="row g-4">
    <div class="col-md-8">
        <div class="card shadow-sm calendar-card mb-4">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Calendar View</h2>
            </div>
            <div class="card-body p-3">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card shadow-sm upcoming-card">
            <div class="card-header bg-primary text-white">
                <h2 class="h5 mb-0">Upcoming Posts</h2>
            </div>
            <div class="card-body p-0">
                <div class="list-group upcoming-list overflow-auto" style="max-height: 500px;">
                    @foreach($upcomingPosts as $post)
                    <a href="{{ route('posts.show', $post) }}" class="list-group-item list-group-item-action upcoming-item d-flex flex-column">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <h5 class="mb-0">{{ $post->title }}</h5>
                            <small class="status-badge badge 
                                {{ $post->status === 'scheduled' ? 'bg-warning text-dark pulse-badge' : 'bg-success' }}">
                                {{ ucfirst($post->status) }}
                            </small>
                        </div>
                        <small class="text-muted">
                            {{ $post->scheduled_time->format('M j, Y g:i A') }}
                        </small>
                        <div class="mt-2">
                            @foreach($post->platforms as $platform)
                            <span class="badge bg-secondary me-1 platform-badge">{{ $platform->name }}</span>
                            @endforeach
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
<style>
    /* Calendar container with fade-in and shadow */
    .calendar-card {
        animation: fadeInUp 0.7s ease forwards;
        opacity: 0;
        border-radius: 0.5rem;
        box-shadow: 0 4px 10px rgb(0 0 0 / 0.1);
    }
    .calendar-card .fc {
        border-radius: 0.5rem;
    }

    /* Upcoming posts card */
    .upcoming-card {
        animation: fadeInUp 0.8s ease 0.2s forwards;
        opacity: 0;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgb(0 0 0 / 0.12);
    }

    /* List group items fade in */
    .upcoming-item {
        animation: fadeInLeft 0.5s ease forwards;
        opacity: 0;
        border-radius: 0.375rem;
        transition: background-color 0.3s ease, transform 0.2s ease;
        will-change: transform, background-color;
        padding: 1rem 1.25rem;
        margin: 0.25rem 0;
    }
    .upcoming-item:hover {
        background-color: #e9f0ff;
        transform: translateX(6px);
        text-decoration: none;
    }

    /* Status badge pulse effect for scheduled */
    .pulse-badge {
        animation: pulse 2.5s infinite ease-in-out;
    }

    /* Platform badge hover */
    .platform-badge {
        transition: background-color 0.3s ease;
    }
    .platform-badge:hover {
        background-color: #6c757d;
        cursor: default;
    }

    /* Scrollbar style for upcoming posts list */
    .upcoming-list {
        scrollbar-width: thin;
        scrollbar-color: #0d6efd #e9ecef;
    }
    .upcoming-list::-webkit-scrollbar {
        width: 8px;
    }
    .upcoming-list::-webkit-scrollbar-track {
        background: #e9ecef;
        border-radius: 8px;
    }
    .upcoming-list::-webkit-scrollbar-thumb {
        background-color: #0d6efd;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }

    /* FullCalendar event cursor */
    #calendar {
        height: 500px;
        border-radius: 0.5rem;
        overflow: hidden;
    }
    .fc-event {
        cursor: pointer;
        transition: transform 0.2s ease;
    }
    .fc-event:hover {
        transform: scale(1.05);
        z-index: 10;
        box-shadow: 0 4px 10px rgb(0 0 0 / 0.15);
    }

    /* Animations */
    @keyframes fadeInUp {
        0% {
            opacity: 0;
            transform: translateY(12px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    @keyframes fadeInLeft {
        0% {
            opacity: 0;
            transform: translateX(-20px);
        }
        100% {
            opacity: 1;
            transform: translateX(0);
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

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: @json($calendarEvents),
            eventClick: function(info) {
                window.location.href = `/posts/${info.event.id}`;
            }
        });
        calendar.render();
    });
</script>
@endpush
