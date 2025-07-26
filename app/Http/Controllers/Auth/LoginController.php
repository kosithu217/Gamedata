<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            $user = Auth::user();
            $sessionId = session()->getId();
            
            // Skip single session check for admin users - they can login from multiple devices
            if (!$user->isAdmin()) {
                // For students only: Check if user has an active session elsewhere
                if ($user->current_session_id && $user->current_session_id !== $sessionId) {
                    // Get info about the existing session
                    $existingSession = UserSession::where('user_id', $user->id)
                        ->where('is_active', true)
                        ->first();
                    
                    $deviceInfo = '';
                    if ($existingSession) {
                        $deviceInfo = ' from ' . $this->getDeviceInfo($existingSession->user_agent) . 
                                     ' (IP: ' . $existingSession->ip_address . ')';
                    }
                    
                    // Force logout the user and show message
                    Auth::logout();
                    $request->session()->invalidate();
                    $request->session()->regenerateToken();
                    
                    return redirect()->route('login')->with('error', 
                        'Your account is already logged in' . $deviceInfo . '. Please logout from the other device first, or contact support if you believe this is an error.');
                }
            }
            
            // Update user's current session (this will deactivate other sessions)
            $user->update([
                'current_session_id' => $sessionId,
                'last_login_at' => now()
            ]);
            
            // Create user session record
            if ($user->isAdmin()) {
                // For admins: Allow multiple active sessions
                UserSession::create([
                    'user_id' => $user->id,
                    'session_id' => $sessionId,
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_activity' => now(),
                    'is_active' => true,
                ]);
            } else {
                // For students: Deactivate other sessions (single session only)
                UserSession::createSession(
                    $user->id,
                    $sessionId,
                    $request->ip(),
                    $request->userAgent()
                );
            }
            
            $redirectRoute = $user->isAdmin() ? route('admin.dashboard') : route('student.dashboard');
            return redirect()->intended($redirectRoute)
                ->with('success', 'Welcome back! You are now logged in.');
        }

        throw ValidationException::withMessages([
            'email' => __('The provided credentials do not match our records.'),
        ]);
    }
    
    /**
     * Get device info from user agent
     */
    private function getDeviceInfo($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome Browser';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox Browser';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            return 'Safari Browser';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            return 'Edge Browser';
        } elseif (strpos($userAgent, 'Mobile') !== false) {
            return 'Mobile Device';
        } else {
            return 'Another Device';
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        if ($user) {
            // Deactivate user sessions
            UserSession::where('user_id', $user->id)->update(['is_active' => false]);
            $user->update(['current_session_id' => null]);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }

    /**
     * Force logout all sessions for a user (admin function)
     */
    public function forceLogoutAllSessions(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = \App\Models\User::find($request->user_id);
        
        // Deactivate all user sessions
        UserSession::where('user_id', $user->id)->update(['is_active' => false]);
        
        // Clear current session ID
        $user->update(['current_session_id' => null]);
        
        return response()->json([
            'success' => true,
            'message' => 'All sessions for ' . $user->name . ' have been terminated.'
        ]);
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'class_levels' => 'required|array|min:1',
            'class_levels.*' => 'required|string',
            'date_of_birth' => 'required|date',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'class_levels' => $request->class_levels,
            'date_of_birth' => $request->date_of_birth,
            'role' => 'student',
        ]);

        Auth::login($user);
        
        $sessionId = session()->getId();
        $user->update([
            'current_session_id' => $sessionId,
            'last_login_at' => now()
        ]);
        
        UserSession::createSession(
            $user->id,
            $sessionId,
            $request->ip(),
            $request->userAgent()
        );

        return redirect()->route('student.dashboard');
    }
}
