<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        abort_unless(
            $user->isSuperAdmin() || $user->isAdmin(),
            403
        ); 

        if ($user->isSuperAdmin()) {
            $companies = Company::orderBy('name')->get();
        } else {
            $companies = collect();
        }

        if ($user->isSuperAdmin()) {
            $roles = [User::ROLE_ADMIN];
        } else {
            $roles = [User::ROLE_ADMIN, User::ROLE_MEMBER];
        }

        return view('invitations.create', compact('companies', 'roles'));
    }

    public function store(Request $request)
    {
        $user = $request->user();

        abort_unless(
            $user->isSuperAdmin() || $user->isAdmin(),
            403
        );

        $rules = [
            'email' => 'required|email|max:255',
            'role' => 'required|in:'.($user->isSuperAdmin()?'Admin':'Admin,Member')
        ];

        if ($user->isSuperAdmin()) {
            $rules['company_id'] = 'required|exists:companies,id';
        }

        $validated = $request->validate($rules);
        $role = Role::where('name', $validated['role'])->firstOrFail();
        $companyId = $user->isSuperAdmin() ? $validated['company_id'] : $user->company_id;

        abort_if(!$companyId, 403);

        $invitation = Invitation::create([
            'email' => $validated['email'],
            'role_id' => $role->id,
            'company_id' => $companyId,
            'invited_by' => $user->id,
            'token' => Str::random(64),
            'expires_at' => now()->addDays(7)
        ]);

        $link = route('invitations.show', $invitation->token);

        return redirect()->route('invitations.create')->with('success', 'Invitation created successfully')->with('invitation_link', $link);
    }
}
