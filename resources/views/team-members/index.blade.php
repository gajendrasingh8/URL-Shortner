
@extends('layouts.app')

@section('title', 'Team Members')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">Team Members</h3>
            <p class="text-muted mb-0">Manage users in your company</p>
        </div>

        <a href="{{ route('invitations.create') }}" class="btn btn-primary">
            + Invite User
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Total Generated URLs</th>
                            <th>Joined At</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($members as $member)
                            <tr>
                                <td>{{ $members->firstItem() + $loop->index }}</td>
                                <td>{{ $member->name }}</td>
                                <td>{{ $member->email }}</td>
                                <td>
                                    <span class="badge bg-secondary">
                                        {{ $member->role->name }}
                                    </span>
                                </td>
                                <td>{{ $member->short_urls_count }}</td>
                                <td>{{ $member->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    No team members found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $members->links() }}
            </div>

        </div>
    </div>

@endsection
