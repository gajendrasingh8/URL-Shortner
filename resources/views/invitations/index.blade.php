@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>
            {{ auth()->user()->isSuperAdmin() ? 'All Admins' : 'Invited Admins & Members' }}
        </h3>

        <a href="{{ route('invitations.create') }}" class="btn btn-primary">
            Invite  {{ auth()->user()->isSuperAdmin() ? 'Admins' : 'Admins & Members' }}
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Email</th>
                            <th>Company</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Invitation Link</th>
                            <th>Invited At</th>
                            <th>Expires At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($invitations as $invitation)
                            <tr>
                                <td>{{ $invitations->firstItem() + $loop->index }}</td>
                                <td>{{ $invitation->email }}</td>
                                <td>{{ $invitation->company->name }}</td>
                                <td>{{ $invitation->role->name }}</td>
                                <td>
                                    @if($invitation->accepted_at)
                                        <span class="badge bg-success">Accepted</span>
                                    @elseif($invitation->expires_at && $invitation->expires_at->isPast())
                                        <span class="badge bg-danger">Expired</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!$invitation->accepted_at && (!$invitation->expires_at || $invitation->expires_at->isFuture()))
                                        <div class="input-group input-group-sm">
                                            <input
                                                type="text"
                                                class="form-control"
                                                value="{{ route('invitations.show', $invitation->token) }}"
                                                id="invitation-{{ $invitation->id }}"
                                                readonly
                                            >

                                            <button
                                                type="button"
                                                class="btn btn-outline-primary"
                                                onclick="copyInvitationLink({{ $invitation->id }})"
                                            >
                                                Copy
                                            </button>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $invitation->created_at->format('d M Y') }}</td>
                                <td>{{ $invitation->expires_at?->format('d M Y') ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">No Admin invitations found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $invitations->links() }}
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script>
    function copyInvitationLink(id) {
        const input = document.getElementById('invitation-' + id);

        navigator.clipboard.writeText(input.value);

        alert('Invitation link copied!');
    }
</script>
@endsection