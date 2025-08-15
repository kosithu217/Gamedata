<?php

namespace App\Console\Commands;

use App\Models\UserSession;
use Illuminate\Console\Command;

class CleanupExpiredSessions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sessions:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired user sessions from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sessionLifetime = config('session.lifetime'); // in minutes
        $expiredTime = now()->subMinutes($sessionLifetime);
        
        // Count expired sessions
        $expiredCount = UserSession::where('is_active', true)
            ->where('last_activity', '<', $expiredTime)
            ->count();
        
        if ($expiredCount > 0) {
            // Mark expired sessions as inactive
            UserSession::where('is_active', true)
                ->where('last_activity', '<', $expiredTime)
                ->update(['is_active' => false]);
            
            $this->info("Cleaned up {$expiredCount} expired sessions.");
        } else {
            $this->info("No expired sessions found.");
        }
        
        // Also clean up very old inactive sessions (older than 30 days)
        $oldSessionsCount = UserSession::where('last_activity', '<', now()->subDays(30))->count();
        
        if ($oldSessionsCount > 0) {
            UserSession::where('last_activity', '<', now()->subDays(30))->delete();
            $this->info("Deleted {$oldSessionsCount} old session records.");
        }
        
        return 0;
    }
}