@extends('layouts.app')

@section('content')
<div class="container activity-log-container py-4">
    <h1 class="mb-4">Activity Log</h1>

    @if($logs->count())
    <div class="table-responsive">
        <table class="table table-striped activity-log-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Action</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($logs as $log)
                <tr>
                    <td>{{ $log->created_at->format('Y-m-d H:i') }}</td>
                    <td data-tooltip="{{ ucfirst(str_replace('_', ' ', $log->action)) }}">
                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                    </td>
                    <td>{{ $log->description }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if ($logs->lastPage() > 1)
    <nav class="mt-4">
        <ul class="pagination justify-content-center">
            {{-- Previous Page Link --}}
            @if ($logs->onFirstPage())
                <li class="page-item disabled"><span class="page-link">السابق</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $logs->previousPageUrl() }}">السابق</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @for ($i = 1; $i <= $logs->lastPage(); $i++)
                <li class="page-item {{ $logs->currentPage() == $i ? 'active' : '' }}">
                    <a class="page-link" href="{{ $logs->url($i) }}">{{ $i }}</a>
                </li>
            @endfor

            {{-- Next Page Link --}}
            @if ($logs->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $logs->nextPageUrl() }}">التالي</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">التالي</span></li>
            @endif
        </ul>
    </nav>
    @endif

    @else
    <p>No activity found.</p>
    @endif
</div>

<style>
/* Container */
.activity-log-container {
    max-width: 900px;
    margin: auto;
}

/* Table Styling and Effects */
.activity-log-table {
    border-collapse: separate;
    border-spacing: 0 8px; /* space between rows */
    width: 100%;
    font-size: 0.95rem;
    font-weight: 500;
    color: #212529;
}

/* Header */
.activity-log-table thead th {
    background-color: #0d6efd;
    color: white;
    padding: 14px 20px;
    text-align: left;
    border-radius: 6px 6px 0 0;
    user-select: none;
    transition: box-shadow 0.3s ease;
}

/* Header inset glow on hover */
.activity-log-table thead th:hover {
    box-shadow: inset 0 0 10px 3px rgba(13, 110, 253, 0.6);
    cursor: default;
}

/* Rows */
.activity-log-table tbody tr {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    background: #fff;
    border: 1px solid transparent;
    transition: box-shadow 0.3s ease, background-color 0.3s ease, transform 0.3s ease;
    animation: fadeInRows 0.6s ease forwards;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgb(0 0 0 / 0.1);
}

/* Fade-in animation */
@keyframes fadeInRows {
    from { opacity: 0; transform: translateY(15px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Zebra stripes with spacing */
.activity-log-table tbody tr:nth-child(odd) {
    background: #f7faff;
}

.activity-log-table tbody tr:nth-child(even) {
    background: #e9f0ff;
}

/* Hover effect with animated gradient + glow */
.activity-log-table tbody tr:hover {
    background: linear-gradient(90deg, #a3c4ff, #0d6efd);
    box-shadow: 0 6px 15px rgba(13, 110, 253, 0.6), 0 0 12px #0d6efd80;
    color: white;
    transform: translateY(-3px) scale(1.02);
    transition: background 0.6s ease, box-shadow 0.4s ease, transform 0.3s ease;
}

/* Ripple effect on click */
.activity-log-table tbody tr:after {
    content: "";
    position: absolute;
    border-radius: 50%;
    width: 100px;
    height: 100px;
    pointer-events: none;
    background: rgba(255, 255, 255, 0.4);
    opacity: 0;
    transform: scale(0);
    transition: transform 0.6s ease, opacity 1s ease;
    top: 50%;
    left: 50%;
    transform-origin: center;
}

.activity-log-table tbody tr:active:after {
    opacity: 1;
    transform: scale(3);
    transition: transform 0.4s ease, opacity 0.7s ease;
}

/* Cells */
.activity-log-table th,
.activity-log-table td {
    padding: 12px 20px;
    border: none;
    vertical-align: middle;
    user-select: text;
}

/* Time column styling */
.activity-log-table td:first-child {
    font-family: 'Courier New', Courier, monospace;
    font-weight: 700;
    color: #0d6efd;
    user-select: none;
}

/* Action column with tooltip */
.activity-log-table td:nth-child(2) {
    font-weight: 600;
    color: #0a58ca;
    text-transform: capitalize;
    user-select: none;
    position: relative;
}

/* Tooltip */
.activity-log-table td:nth-child(2):hover::after {
    content: attr(data-tooltip);
    position: absolute;
    bottom: 110%;
    left: 50%;
    transform: translateX(-50%);
    padding: 6px 10px;
    background: #0d6efd;
    color: white;
    border-radius: 6px;
    white-space: nowrap;
    font-size: 0.85rem;
    opacity: 0.95;
    pointer-events: none;
    box-shadow: 0 0 8px rgba(13, 110, 253, 0.7);
    transition: opacity 0.3s ease;
    z-index: 10;
}

/* Pagination */
.pagination {
    padding-left: 0;
    margin: 1rem 0;
    list-style: none;
}

.pagination .page-item {
    margin: 0 5px;
}

/* Pagination page links */
.pagination .page-link {
    border-radius: 50% !important;
    width: 42px;
    height: 42px;
    padding: 0;
    line-height: 42px;
    text-align: center;
    font-weight: 700;
    font-size: 1.1rem;
    color: #0d6efd;
    border: 1px solid transparent;
    background-color: transparent;
    transition: transform 0.25s ease, box-shadow 0.25s ease, background-color 0.25s ease;
    cursor: pointer;
    user-select: none;
}

/* Pagination hover effect */
.pagination .page-link:hover:not(.disabled) {
    transform: scale(1.15);
    box-shadow: 0 0 10px rgba(13, 110, 253, 0.7);
    background-color: #dbe9ff;
}

/* Disabled page links */
.pagination .page-item.disabled .page-link {
    color: #999;
    cursor: default;
}

/* Active page link with pulse animation */
@keyframes pulseActive {
    0%, 100% { box-shadow: 0 0 8px #0a58ca; }
    50% { box-shadow: 0 0 20px #0a58ca; }
}

.pagination .page-item.active .page-link {
    background-color: #0a58ca;
    border-color: #0a58ca;
    color: #fff;
    cursor: default;
    animation: pulseActive 2.4s infinite;
    box-shadow: 0 0 8px #0a58ca;
}
</style>
@endsection
