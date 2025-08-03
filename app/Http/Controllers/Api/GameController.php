<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    /**
     * Get all games with pagination and filtering
     */
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'nullable|exists:categories,id',
            'search' => 'nullable|string|max:255',
            'is_featured' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:100',
            'page' => 'nullable|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Game::where('is_active', true)->with('category');

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('title_mm', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Filter by featured
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        // For authenticated users, filter by accessible categories
        if ($request->user() && $request->user()->isStudent()) {
            $userCategoryIds = $request->user()->categories()->pluck('categories.id');
            if ($userCategoryIds->isNotEmpty()) {
                $query->whereIn('category_id', $userCategoryIds);
            } else {
                // If no categories assigned, return empty result
                $query->whereRaw('1 = 0');
            }
        }

        $perPage = $request->get('per_page', 15);
        $games = $query->orderBy('sort_order')->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'games' => $games->items(),
                'pagination' => [
                    'current_page' => $games->currentPage(),
                    'last_page' => $games->lastPage(),
                    'per_page' => $games->perPage(),
                    'total' => $games->total(),
                    'from' => $games->firstItem(),
                    'to' => $games->lastItem(),
                ]
            ]
        ]);
    }

    /**
     * Get featured games
     */
    public function featured(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $limit = $request->get('limit', 6);
        $query = Game::where('is_active', true)
                    ->where('is_featured', true)
                    ->with('category');

        // For authenticated users, filter by accessible categories
        if ($request->user() && $request->user()->isStudent()) {
            $userCategoryIds = $request->user()->categories()->pluck('categories.id');
            if ($userCategoryIds->isNotEmpty()) {
                $query->whereIn('category_id', $userCategoryIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $games = $query->orderBy('sort_order')->take($limit)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'games' => $games
            ]
        ]);
    }

    /**
     * Get single game details
     */
    public function show(Request $request, $slug)
    {
        $game = Game::where('slug', $slug)
                   ->where('is_active', true)
                   ->with('category')
                   ->first();

        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Game not found'
            ], 404);
        }

        // Check access for authenticated users
        if ($request->user() && $request->user()->isStudent()) {
            if (!$request->user()->hasAccessToCategory($game->category_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this game'
                ], 403);
            }
        }

        // For guests, only allow featured games
        if (!$request->user() && !$game->is_featured) {
            return response()->json([
                'success' => false,
                'message' => 'This game requires authentication'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'game' => $game
            ]
        ]);
    }

    /**
     * Increment game play count
     */
    public function play(Request $request, $slug)
    {
        $game = Game::where('slug', $slug)
                   ->where('is_active', true)
                   ->first();

        if (!$game) {
            return response()->json([
                'success' => false,
                'message' => 'Game not found'
            ], 404);
        }

        // Check access for authenticated users
        if ($request->user() && $request->user()->isStudent()) {
            if (!$request->user()->hasAccessToCategory($game->category_id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this game'
                ], 403);
            }
        }

        // For guests, only allow featured games
        if (!$request->user() && !$game->is_featured) {
            return response()->json([
                'success' => false,
                'message' => 'This game requires authentication'
            ], 401);
        }

        // Increment play count only for authenticated users
        if ($request->user()) {
            $game->incrementPlays();
        }

        return response()->json([
            'success' => true,
            'message' => 'Game play recorded',
            'data' => [
                'game' => $game->fresh(),
                'play_url' => asset('storage/' . $game->swf_file_path)
            ]
        ]);
    }

    /**
     * Get games by category
     */
    public function byCategory(Request $request, $categorySlug)
    {
        $category = Category::where('slug', $categorySlug)
                           ->where('is_active', true)
                           ->first();

        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Category not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'per_page' => 'nullable|integer|min:1|max:100',
            'search' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = $category->games()->where('is_active', true);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('title_mm', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Check access for authenticated users
        if ($request->user() && $request->user()->isStudent()) {
            if (!$request->user()->hasAccessToCategory($category->id)) {
                return response()->json([
                    'success' => false,
                    'message' => 'You do not have access to this category'
                ], 403);
            }
        }

        $perPage = $request->get('per_page', 15);
        $games = $query->orderBy('sort_order')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'games' => $games->items(),
                'pagination' => [
                    'current_page' => $games->currentPage(),
                    'last_page' => $games->lastPage(),
                    'per_page' => $games->perPage(),
                    'total' => $games->total(),
                    'from' => $games->firstItem(),
                    'to' => $games->lastItem(),
                ]
            ]
        ]);
    }

    /**
     * Get popular games
     */
    public function popular(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'nullable|integer|min:1|max:50'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $limit = $request->get('limit', 10);
        $query = Game::where('is_active', true)->with('category');

        // For authenticated users, filter by accessible categories
        if ($request->user() && $request->user()->isStudent()) {
            $userCategoryIds = $request->user()->categories()->pluck('categories.id');
            if ($userCategoryIds->isNotEmpty()) {
                $query->whereIn('category_id', $userCategoryIds);
            } else {
                $query->whereRaw('1 = 0');
            }
        }

        $games = $query->orderBy('plays_count', 'desc')->take($limit)->get();

        return response()->json([
            'success' => true,
            'data' => [
                'games' => $games
            ]
        ]);
    }
}