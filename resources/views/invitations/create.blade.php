
@extends('layouts.app')

@section('title', 'Invite User')

@section('content')

    <div class="mb-4">
        <h3 class="mb-1">
            {{ auth()->user()->isSuperAdmin() ? 'Invite Admin' : 'Invite Team Member' }}
        </h3>
        <p class="text-muted mb-0">
            Send an invitation to join your organization.
        </p>
    </div>

    <div class="card shadow-sm" style="max-width: 650px;">
        <div class="card-body p-4">

            @if (session('invitation_link'))
                <div class="alert alert-success mb-4">
                    <p class="mb-2">Share this link with the invited user:</p>

                    <div class="input-group">
                        <input
                            type="text"
                            id="invitationLink"
                            class="form-control"
                            value="{{ session('invitation_link') }}"
                            readonly
                        >

                        <button
                            type="button"
                            class="btn btn-success"
                            onclick="copyInvitationLink()"
                        >
                            Copy Link
                        </button>
                    </div>

                    <small class="text-muted d-block mt-2">
                        This invitation link is valid for 7 days.
                    </small>
                </div>
            @endif

            <form action="{{ route('invitations.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}"
                        placeholder="user@example.com"
                        required
                    >

                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>

                    <select
                        name="role"
                        id="role"
                        class="form-select @error('role') is-invalid @enderror"
                        required
                    >
                        <option value="">Select Role</option>

                        @foreach ($roles as $role)
                            <option value="{{ $role }}"
                                {{ old('role') == $role ? 'selected' : '' }}>
                                {{ $role }}
                            </option>
                        @endforeach
                    </select>

                    @error('role')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if (auth()->user()->isSuperAdmin())
                    <div class="mb-3">
                        <label for="company_id" class="form-label">Company</label>

                        <select
                            name="company_id"
                            id="company_id"
                            class="form-select @error('company_id') is-invalid @enderror"
                            required
                        >
                            <option value="">Select Company</option>

                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}"
                                    {{ old('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>

                        @error('company_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif

                <button type="submit" class="btn btn-primary">
                    Send Invitation
                </button>

                <a href="{{ url('dashboard') }}"
                   class="btn btn-outline-secondary">
                    Cancel
                </a>
            </form>

        </div>
    </div>

@endsection


@section('scripts')
<script>
    function copyInvitationLink() {
        const input = document.getElementById('invitationLink');

        input.select();
        input.setSelectionRange(0, 99999);

        navigator.clipboard.writeText(input.value);
    }
</script>
@endsection
