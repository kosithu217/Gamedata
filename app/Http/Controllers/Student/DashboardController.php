<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class DashboardController extends Controller
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
        
        // Get user's accessible categories (assigned by admin)
        $userCategories = $user->getAccessibleCategories()
            ->load(['games' => function($q) {
                $q->where('is_active', true);
            }]);
        
        // Get user's accessible games (from assigned categories)
        $userGames = $user->getAccessibleGames()
            ->sortByDesc('plays_count');
        
        // Get featured games from user's accessible categories
        $categoryIds = $userCategories->pluck('id');
        $featuredGames = collect();
        if ($categoryIds->isNotEmpty()) {
            $featuredGames = Game::where('is_active', true)
                ->where('is_featured', true)
                ->whereIn('category_id', $categoryIds)
                ->with('category')
                ->orderBy('sort_order')
                ->take(6)
                ->get();
        }
        
        // Get all games for the games section (limited for performance)
        $allUserGames = collect();
        if ($categoryIds->isNotEmpty()) {
            $allUserGames = Game::where('is_active', true)
                ->whereIn('category_id', $categoryIds)
                ->with('category')
                ->orderBy('plays_count', 'desc')
                ->take(12)
                ->get();
        }
        
        // Get recent blog posts from user's accessible categories
        $recentPosts = collect();
        if ($categoryIds->isNotEmpty()) {
            $recentPosts = BlogPost::where('is_published', true)
                ->whereIn('category_id', $categoryIds)
                ->with(['category', 'author'])
                ->orderBy('published_at', 'desc')
                ->take(4)
                ->get();
        }
        
        // Get statistics
        $stats = [
            'total_games' => $allUserGames->count(),
            'total_categories' => $userCategories->count(),
            'total_posts' => $recentPosts->count(),
            'class_levels' => $user->getClassLevels(),
            'assigned_categories' => $userCategories->count(),
        ];
        
        return view('student.dashboard', compact(
            'user', 
            'userCategories', 
            'userGames', 
            'featuredGames', 
            'recentPosts', 
            'stats',
            'allUserGames'
        ));
    }
}
