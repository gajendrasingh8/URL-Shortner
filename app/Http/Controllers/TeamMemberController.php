<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class TeamMemberController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);
        
        $members = User::with('role')
            ->withCount('shortUrls')
            ->where('company_id', $user->company_id)
            ->whereHas('role', function ($query) {
                $query->where('name', User::ROLE_MEMBER);
            })
            ->latest()
            ->paginate(10);
        
        return view('team-members.index', compact('members'));
    }
}
