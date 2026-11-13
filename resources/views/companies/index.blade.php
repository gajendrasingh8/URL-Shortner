
@extends('layouts.app')

@section('title', 'Companies')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Companies</h2>

        <a href="{{ route('companies.create') }}" class="btn btn-primary">
            Create Company
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Company Name</th>
                            <th>Total Users</th>
                            <th>Created At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($companies as $company)
                            <tr>
                                <td>{{ $companies->firstItem() + $loop->index }}</td>
                                <td>{{ $company->name }}</td>
                                <td>{{ $company->users_count }}</td>
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
