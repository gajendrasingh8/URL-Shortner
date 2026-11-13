
@extends('layouts.app')

@section('title', 'Companies')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Companies</h3>
            <p class="text-muted mb-0">Manage registered companies</p>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($companies as $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $loop->index }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-4">
                                    No companies found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $companies->links() }}
            </div>

        </div>
    </div>

@endsection
