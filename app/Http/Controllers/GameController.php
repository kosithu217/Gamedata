<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function __construct()
    {
        // Remove auth middleware to allow guests to see demo games
        // Auth will be checked individually in each method
    }

    public function index(Request $request)
    {
        $query = Game::where('is_active', true)->with('category');

        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('title_mm', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // For guests, limit to featured games only (demo mode)
        if (!auth()->check()) {
            $query->where('is_featured', true)->take(6);
            $games = $query->orderBy('created_at', 'desc')->get();
            $isDemo = true;
        } else {
            // Filter games based on user's assigned categories (students only)
            if (auth()->user()->isStudent()) {
                $userCategoryIds = auth()->user()->categories()->pluck('categories.id');
                if ($userCategoryIds->isNotEmpty()) {
                    $query->whereIn('category_id', $userCategoryIds);
                } else {
                    // If no categories assigned, show no games
                    $query->whereRaw('1 = 0');
                }
            }
            
            // For logged-in users, show filtered games with pagination
            $games = $query->orderBy('created_at', 'desc')->paginate(12);
            $isDemo = false;
        }

        // Filter categories based on user's assigned categories
        $categoriesQuery = Category::where('is_active', true)
            ->withCount(['games' => function($q) {
                $q->where('is_active', true);
            }]);
            
        if (auth()->check() && auth()->user()->isStudent()) {
            $userCategoryIds = auth()->user()->categories()->pluck('categories.id');
            if ($userCategoryIds->isNotEmpty()) {
                $categoriesQuery->whereIn('id', $userCategoryIds);
            } else {
                // If no categories assigned, show no categories
                $categoriesQuery->whereRaw('1 = 0');
            }
        }
        
        $categories = $categoriesQuery->orderBy('sort_order')->get();

        return view('games.index', compact('games', 'categories', 'isDemo'));
    }

    public function show($slug)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->with('category')
            ->firstOrFail();

        // For guests, only allow viewing featured games (demo games)
        if (!auth()->check() && !$game->is_featured) {
            return redirect()->route('login')
                ->with('error', __('Please login to access this game. Only demo games are available for guests.'));
        }

        // Check category access for students
        if (auth()->check() && auth()->user()->isStudent()) {
            if (!auth()->user()->hasAccessToCategory($game->category_id)) {
                return redirect()->route('games.index')
                    ->with('error', __('You do not have access to this game. Please contact your administrator.'));
            }
        }

        // Increment play count only for logged-in users
        if (auth()->check()) {
            $game->incrementPlays();
        }

        return view('games.show', compact('game'));
    }

    public function play($slug)
    {
        $game = Game::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // For guests, only allow playing featured games (demo games)
        if (!auth()->check() && !$game->is_featured) {
            return redirect()->route('login')
                ->with('error', __('Please login to play this game. Only demo games are available for guests.'));
        }

        // Check category access for students
        if (auth()->check() && auth()->user()->isStudent()) {
            if (!auth()->user()->hasAccessToCategory($game->category_id)) {
                return redirect()->route('games.index')
                    ->with('error', __('You do not have access to this game. Please contact your administrator.'));
            }
        }

        return view('games.play', compact('game'));
    }
}
