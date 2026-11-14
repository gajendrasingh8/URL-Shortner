<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class InvitationAcceptController extends Controller
{
    public function show(string $token)
    {
        $invitation = Invitation::where('token', $token)->whereNull('accepted_at')
        ->where(function ($query) {
            $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
        })->firstOrFail();

        return view('invitations.accept', compact('invitation'));
    }

    public function store(Request $request, $token)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = DB::transaction(function () use ($token, $validated) {
            $invitation = Invitation::where('token', $token)->whereNull('accepted_at')->where(function($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->lockForUpdate()->firstOrFail();

            if (User::whereRaw('LOWER(email) = ?', [strtolower($invitation->email)])->exists()) {
                throw ValidationException::withMessages([
                    'email' => 'An account already exists for this email',
                ]);
            }

            $user = User::create([
                'name' => $validated['name'],
                'email' => $invitation->email,
                'password' => Hash::make($validated['password']),
                'role_id' => $invitation->role_id,
                'company_id' => $invitation->company_id,
            ]);

            $invitation->accepted_at = now();
            $invitation->save();

            return $user;
        });
        Auth::login($user);
        $request->session()->regenerate();
        return redirect()->intended('/dashboard');
    }
}
