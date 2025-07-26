<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SingleSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            $user = auth()->user();
            $currentSessionId = session()->getId();
            
            // Skip single session check for admin users - they can login from multiple devices
            if ($user->isAdmin()) {
                // Just update session tracking for admins without restrictions
                $user->update([
                    'current_session_id' => $currentSessionId,
                    'last_login_at' => now()
                ]);
                
                // Update or create user session record (allow multiple active sessions for admins)
                \App\Models\UserSession::updateOrCreate(
                    ['user_id' => $user->id, 'session_id' => $currentSessionId],
                    [
                        'ip_address' => $request->ip(),
                        'user_agent' => $request->userAgent(),
                        'last_activity' => now(),
                        'is_active' => true
                    ]
                );
                
                return $next($request);
            }
            
            // For students only: Check if user has a different active session
            if ($user->current_session_id && $user->current_session_id !== $currentSessionId) {
                // Get info about the existing session
                $existingSession = \App\Models\UserSession::where('user_id', $user->id)
                    ->where('is_active', true)
                    ->first();
                
                $deviceInfo = '';
                $loginTime = '';
                if ($existingSession) {
                    $deviceInfo = $this->getDeviceInfo($existingSession->user_agent);
                    $loginTime = $existingSession->last_activity->diffForHumans();
                }
                
                // Log out the user and redirect with detailed message
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
                
                $errorMessage = 'Your account is currently logged in from another device (' . $deviceInfo . ')';
                if ($loginTime) {
                    $errorMessage .= ' since ' . $loginTime;
                }
                $errorMessage .= '. Please logout from the other device first.';
                
                return redirect()->route('login')->with('error', $errorMessage);
            }
            
            // Update current session ID and last activity
            $user->update([
                'current_session_id' => $currentSessionId,
                'last_login_at' => now()
            ]);
            
            // Update or create user session record
            \App\Models\UserSession::updateOrCreate(
                ['user_id' => $user->id, 'session_id' => $currentSessionId],
                [
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'last_activity' => now(),
                    'is_active' => true
                ]
            );
        }
        
        return $next($request);
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
}
