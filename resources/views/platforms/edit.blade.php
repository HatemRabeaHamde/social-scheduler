@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Platform</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('platforms.update', $platform) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Platform Name</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $platform->name) }}" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('platforms.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
