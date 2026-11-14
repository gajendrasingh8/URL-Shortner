
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

    @if (auth()->user()->isSuperAdmin())
        <h3 class="mb-4">Super Admin Dashboard</h3>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Manage Companies</h5>
                        <a href="{{ route('companies.index') }}" class="btn btn-primary btn-sm">
                            View Companies
                        </a>
                        <a href="{{ route('companies.create') }}" class="btn btn-primary btn-sm">
                            Create Company
                        </a>

                        <a href="{{ route('invitations.create') }}" class="btn btn-outline-primary btn-sm">
                            Invite Admin
                        </a>
                        <a href="{{ route('invitations.index') }}" class="btn btn-outline-primary btn-sm">
                            View Admins
                        </a>
                    </div>
                </div>
            </div>
             <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">All Short URLs</h5>
                        <p class="card-text">View short URLs from all companies.</p>

                        <a href="{{ route('short-urls.index') }}" class="btn btn-primary">
                            View All URLs
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @elseif (auth()->user()->isAdmin())
        <h3 class="mb-4">Admin Dashboard</h3>

        <div class="row g-3">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Team Members</h5>
                        <a href="{{ route('invitations.create') }}" class="btn btn-primary btn-sm">
                            Invite User
                        </a>
                        <a href="{{ route('invitations.index') }}" class="btn btn-outline-primary btn-sm">
                            View Invited user
                        </a>
                        <a href="{{ route('team-members.index') }}" class="btn btn-outline-primary btn-sm">
                            View Team Members
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5>Short URLs</h5>
                        <a href="{{ route('short-urls.index') }}" class="btn btn-outline-primary btn-sm">
                            View URLs
                        </a>
                        <a href="{{ route('short-urls.create') }}" class="btn btn-primary btn-sm">
                            Create URL
                        </a>
                    </div>
                </div>
            </div>
        </div>

    @elseif (auth()->user()->isMember())
        <h3 class="mb-4">Member Dashboard</h3>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5>Short URLs</h5>
                <a href="{{ route('short-urls.create') }}" class="btn btn-primary btn-sm">
                    Create Short URL
                </a>
                <a href="{{ route('short-urls.index') }}" class="btn btn-outline-primary btn-sm">
                    View My URLs
                </a>
            </div>
        </div>
    @endif

@endsection
