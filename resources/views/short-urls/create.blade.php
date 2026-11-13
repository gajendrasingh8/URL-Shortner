
@extends('layouts.app')

@section('title', 'Create Short URL')

@section('content')

    <div class="mb-4">
        <h3 class="mb-1">Create Short URL</h3>
        <p class="text-muted mb-0">Convert your long URL into a short link.</p>
    </div>

    <div class="card shadow-sm" style="max-width: 700px;">
        <div class="card-body p-4">

            <form action="{{ route('short-urls.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="original_url" class="form-label">
                        Original URL
                    </label>

                    <input
                        type="url"
                        name="original_url"
                        id="original_url"
                        class="form-control @error('original_url') is-invalid @enderror"
                        placeholder="https://example.com/your-long-url"
                        value="{{ old('original_url') }}"
                        required
                    >

                    @error('original_url')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Generate Short URL
                </button>

                <a href="{{ url('short-urls') }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
            </form>

        </div>
    </div>

@endsection
