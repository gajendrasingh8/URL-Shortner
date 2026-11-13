
@extends('layouts.app')

@section('title', 'Accept Invitation')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">

            <div class="card shadow-sm">
                <div class="card-body p-4">

                    <div class="text-center mb-4">
                        <h3>Accept Invitation</h3>
                        <p class="text-muted">
                            Complete your details to create your account.
                        </p>
                    </div>

                    <form action="{{ route('invitations.accept', $invitation->token) }}"
                          method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Email Address</label>

                            <input
                                type="email"
                                class="form-control"
                                value="{{ $invitation->email }}"
                                readonly
                            >
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>

                            <input
                                type="text"
                                name="name"
                                id="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name') }}"
                                placeholder="Enter your name"
                                required
                            >

                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>

                            <input
                                type="password"
                                name="password"
                                id="password"
                                class="form-control @error('password') is-invalid @enderror"
                                placeholder="Enter password"
                                required
                            >

                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">
                                Confirm Password
                            </label>

                            <input
                                type="password"
                                name="password_confirmation"
                                id="password_confirmation"
                                class="form-control"
                                placeholder="Confirm password"
                                required
                            >
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Create Account
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>

@endsection
