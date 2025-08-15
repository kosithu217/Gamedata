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
            
            // Clean up expired sessions first
            $this->cleanupExpiredSessions($user->id);
            
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
            
            // For students only: Check if user has an ACTIVE session elsewhere (not expired)
            $activeSession = \App\Models\UserSession::where('user_id', $user->id)
                ->where('is_active', true)
                ->where('session_id', '!=', $currentSessionId)
                ->where('last_activity', '>', now()->subMinutes(config('session.lifetime')))
                ->first();
            
            if ($activeSession) {
                $deviceInfo = $this->getDeviceInfo($activeSession->user_agent);
                $loginTime = $activeSession->last_activity->diffForHumans();
                
                // Log out the user and redirect with detailed message
                auth()->logout();
                session()->invalidate();
                session()->regenerateToken();
                
                $errorMessage = 'Your account is currently logged in from another device (' . $deviceInfo . ') since ' . $loginTime . '. Please logout from the other device first.';
                
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
     * Clean up expired sessions for a user
     */
    private function cleanupExpiredSessions($userId)
    {
        $sessionLifetime = config('session.lifetime'); // in minutes
        $expiredTime = now()->subMinutes($sessionLifetime);
        
        // Mark expired sessions as inactive
        \App\Models\UserSession::where('user_id', $userId)
            ->where('is_active', true)
            ->where('last_activity', '<', $expiredTime)
            ->update(['is_active' => false]);
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
