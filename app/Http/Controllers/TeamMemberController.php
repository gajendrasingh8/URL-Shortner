<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class TeamMemberController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        abort_unless($user->isAdmin(), 403);
        
        $members = User::with('role')->where('company_id', $user->company_id)->latest()->paginate(10);
        
        return view('team-members.index', compact('members'));
    }
}
