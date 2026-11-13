
@extends('layouts.app')

@section('title', 'Short URLs')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Generated Short URLs</h3>
            <p class="text-muted mb-0">Manage your shortened URLs</p>
        </div>

        <a href="{{ route('short-urls.create') }}" class="btn btn-primary">
            + Create Short URL
        </a>
    </div>

    @if (session('short_link'))
        <div class="alert alert-info">
            <strong>Your Short URL:</strong>
            <a href="{{ session('short_link') }}" target="_blank">
                {{ session('short_link') }}
            </a>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Original URL</th>
                            <th>Short URL</th>

                            @if (auth()->user()->isAdmin())
                                <th>Created By</th>
                            @endif

                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($shortUrls as $shortUrl)
                            <tr>
                                <td>{{ $shortUrls->firstItem() + $loop->index }}</td>

                                <td>
                                    <a href="{{ $shortUrl->original_url }}"
                                       target="_blank"
                                       class="text-break">
                                        {{ Str::limit($shortUrl->original_url, 50) }}
                                    </a>
                                </td>

                                <td>
                                    <a href="{{ url('/s/' . $shortUrl->short_code) }}"
                                       target="_blank">
                                        {{ url('/s/' . $shortUrl->short_code) }}
                                    </a>
                                </td>

                                @if (auth()->user()->isAdmin())
                                    <td>{{ $shortUrl->user->name }}</td>
                                @endif

                                <td>{{ $shortUrl->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}"
                                    class="text-center text-muted py-4">
                                    No short URLs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $shortUrls->links() }}
            </div>

        </div>
    </div>

@endsection
