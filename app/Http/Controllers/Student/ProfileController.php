<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\UserSession;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'single.session']);
    }

    public function index()
    {
        $user = auth()->user();
        
        // Redirect admins to admin dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        
        $currentSession = session()->getId();
        $userSessions = UserSession::where('user_id', $user->id)
            ->orderBy('last_activity', 'desc')
            ->get();
        
        $activeSession = $userSessions->where('is_active', true)->first();
        
        return view('student.profile', compact('user', 'currentSession', 'userSessions', 'activeSession'));
    }
}
