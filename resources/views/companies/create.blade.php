
@extends('layouts.app')

@section('title', 'Create Company')

@section('content')

    <div class="mb-4">
        <h3 class="mb-1">Create Company</h3>
        <p class="text-muted mb-0">Add a new company to the system.</p>
    </div>

    <div class="card shadow-sm" style="max-width: 650px;">
        <div class="card-body p-4">

            <form action="{{ route('companies.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Company Name</label>

                    <input
                        type="text"
                        name="name"
                        id="name"
                        class="form-control @error('name') is-invalid @enderror"
                        value="{{ old('name') }}"
                        placeholder="Enter company name"
                        required
                    >

                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Create Company
                </button>

                <a href="{{ route('companies.index') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </form>

        </div>
    </div>

@endsection
