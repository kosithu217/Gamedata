<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'class_levels',
        'date_of_birth',
        'phone',
        'address',
        'avatar',
        'is_active',
        'expires_at',
        'last_login_at',
        'current_session_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'date_of_birth' => 'date',
        'expires_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'class_levels' => 'array',
    ];

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class, 'author_id');
    }

    public function userSessions()
    {
        return $this->hasMany(UserSession::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class)->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isStudent()
    {
        return $this->role === 'student';
    }

    /**
     * Get user's class levels as array
     */
    public function getClassLevels()
    {
        return $this->class_levels ?? [];
    }

    /**
     * Check if user has a specific class level
     */
    public function hasClassLevel($classLevel)
    {
        return in_array($classLevel, $this->getClassLevels());
    }

    /**
     * Add a class level to user
     */
    public function addClassLevel($classLevel)
    {
        $classLevels = $this->getClassLevels();
        if (!in_array($classLevel, $classLevels)) {
            $classLevels[] = $classLevel;
            $this->class_levels = $classLevels;
        }
        return $this;
    }

    /**
     * Remove a class level from user
     */
    public function removeClassLevel($classLevel)
    {
        $classLevels = $this->getClassLevels();
        $this->class_levels = array_values(array_filter($classLevels, function($level) use ($classLevel) {
            return $level !== $classLevel;
        }));
        return $this;
    }

    /**
     * Get formatted class levels string for display
     */
    public function getClassLevelsString()
    {
        $levels = $this->getClassLevels();
        return empty($levels) ? 'No class assigned' : implode(', ', $levels);
    }

    /**
     * Check if user can access content for a specific class level
     */
    public function canAccessClassLevel($classLevel)
    {
        // Admins can access all content
        if ($this->isAdmin()) {
            return true;
        }
        
        // Students can only access their assigned class levels
        return $this->hasClassLevel($classLevel);
    }

    /**
     * Check if user has access to a specific category
     */
    public function hasAccessToCategory($categoryId)
    {
        // Admins can access all categories
        if ($this->isAdmin()) {
            return true;
        }
        
        // Students can only access assigned categories
        return $this->categories()->where('category_id', $categoryId)->exists();
    }

    /**
     * Get categories accessible by this user
     */
    public function getAccessibleCategories()
    {
        // Admins can access all categories
        if ($this->isAdmin()) {
            return Category::where('is_active', true)->get();
        }
        
        // Students can only access assigned categories
        return $this->categories()->where('is_active', true)->get();
    }

    /**
     * Get games accessible by this user based on assigned categories
     */
    public function getAccessibleGames()
    {
        // Admins can access all games
        if ($this->isAdmin()) {
            return Game::where('is_active', true)->with('category')->get();
        }
        
        // Students can only access games from assigned categories
        $categoryIds = $this->categories()->pluck('categories.id');
        return Game::whereIn('category_id', $categoryIds)
                   ->where('is_active', true)
                   ->with('category')
                   ->get();
    }

    /**
     * Check if user account is expired
     */
    public function isExpired()
    {
        if (!$this->expires_at) {
            return false; // No expiration date set
        }
        
        return $this->expires_at->isPast();
    }

    /**
     * Check if user account will expire soon (within 7 days)
     */
    public function isExpiringSoon()
    {
        if (!$this->expires_at) {
            return false;
        }
        
        return $this->expires_at->isFuture() && $this->expires_at->diffInDays(now()) <= 7;
    }

    /**
     * Get days until expiration
     */
    public function getDaysUntilExpiration()
    {
        if (!$this->expires_at) {
            return null;
        }
        
        if ($this->expires_at->isPast()) {
            return 0;
        }
        
        return $this->expires_at->diffInDays(now());
    }

    /**
     * Deactivate expired user
     */
    public function deactivateIfExpired()
    {
        if ($this->isExpired() && $this->is_active) {
            $this->update([
                'is_active' => false,
                'current_session_id' => null
            ]);
            
            // Delete all user sessions
            $this->userSessions()->delete();
            
            return true;
        }
        
        return false;
    }
}
