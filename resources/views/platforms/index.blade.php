@extends('layouts.app')

@section('content')
<div class="container my-4 platform-page">
    <h1 class="mb-4">Platforms</h1>

    <a href="{{ route('platforms.create') }}" class="btn btn-primary mb-3">Add New Platform</a>

    <table class="table table-bordered table-hover align-middle">
        <thead>
            <tr>
                <th>Name</th>
                <th>Your Preference</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($platforms as $platform)
                <tr>
                    <td>{{ $platform->name }}</td>
                    <td>
                        <form action="{{ route('platforms.toggle', $platform->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="checkbox" name="enabled" onchange="this.form.submit()" 
                                {{ in_array($platform->id, $userPlatforms) ? 'checked' : '' }}>
                        </form>
                    </td>
                    <td>
                        <a href="{{ route('platforms.edit', $platform->id) }}" class="btn btn-sm btn-warning me-1">Edit</a>
                        <form action="{{ route('platforms.destroy', $platform->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Are you sure?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
    /* Table Styling */
    table {
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    body.light-mode {
        background-color: #fff;
        color: #212529;
    }
    body.light-mode table {
        background-color: #fff;
        color: #212529;
        border-color: #dee2e6;
    }
    body.light-mode thead th {
        background-color: #0d6efd;
        color: #fff;
        border-color: #0a58ca;
    }
    body.light-mode tbody tr:hover {
        background-color: #e9f0ff;
    }

    body.light-mode .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
        color: white;
    }
    body.light-mode .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    body.light-mode .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    body.light-mode .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
        color: #fff;
    }

    body.light-mode .btn-danger {
        background-color: #dc3545;
        border-color: #dc3545;
        color: white;
    }
    body.light-mode .btn-danger:hover {
        background-color: #bb2d3b;
        border-color: #b02a37;
    }

    body.dark-mode {
        background-color: #121212;
        color: #e4e6eb;
    }
    body.dark-mode table {
        background-color: #1e1e1e;
        color: #e4e6eb;
        border-color: #333;
    }
    body.dark-mode thead th {
        background-color: #0d6efd;
        color: #fff;
        border-color: #084298;
    }
    body.dark-mode tbody tr:hover {
        background-color: #2c2f38;
    }
    body.dark-mode .btn-primary {
        background-color: #0d6efd;
        border-color: #084298;
        color: white;
    }
    body.dark-mode .btn-primary:hover {
        background-color: #084298;
        border-color: #062c6f;
    }
    body.dark-mode .btn-warning {
        background-color: #ffc107;
        border-color: #d39e00;
        color: #212529;
    }
    body.dark-mode .btn-warning:hover {
        background-color: #d39e00;
        border-color: #b28704;
        color: #fff;
    }
    body.dark-mode .btn-danger {
        background-color: #dc3545;
        border-color: #b02a37;
        color: white;
    }
    body.dark-mode .btn-danger:hover {
        background-color: #b02a37;
        border-color: #7a1c24;
    }

    input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
    }
</style>
@endsection
