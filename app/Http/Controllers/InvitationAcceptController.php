<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

        DB::transaction(function () use ($token, $validated) {
            $invitation = Invitation::where('token', $token)->whereNull('accepted_at')->where(function($query) {
                $query->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })
            ->lockForUpdate()->firstOrFail();
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $invitation->email;
            $user->password = Hash::make($validated['password']);
            $user->role_id = $invitation->role_id;
            $user->company_id = $invitation->company_id;
            $user->save();

            $invitation->accepted_at = now();
            $invitation->save();
        });

        return redirect()->route('login')->with('success', 'Registration completed, please login');
    }
}
