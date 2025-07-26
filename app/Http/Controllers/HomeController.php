<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Game;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // For guests (non-logged in users), show only limited demo games
        if (!auth()->check()) {
            $featuredGames = Game::where('is_featured', true)
                ->where('is_active', true)
                ->with('category')
                ->orderBy('sort_order')
                ->take(6) // Show 6 demo games for guests
                ->get();
        } else {
            // For logged in users, show more games
            $featuredGames = Game::where('is_featured', true)
                ->where('is_active', true)
                ->with('category')
                ->orderBy('sort_order')
                ->take(8) // Show 8 games for logged in users
                ->get();
        }

        $categories = Category::where('is_active', true)
            ->withCount(['games' => function($query) {
                $query->where('is_active', true);
            }])
            ->orderBy('sort_order')
            ->get();

        // For blog posts, show limited for guests
        $postLimit = auth()->check() ? 6 : 3;
        $recentPosts = BlogPost::where('is_published', true)
            ->with(['category', 'author'])
            ->orderBy('published_at', 'desc')
            ->take($postLimit)
            ->get();

        return view('home', compact('featuredGames', 'categories', 'recentPosts'));
    }
}
