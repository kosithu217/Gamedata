<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::with('category');
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('title_mm', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('description_mm', 'LIKE', "%{$search}%")
                  ->orWhereHas('category', function($categoryQuery) use ($search) {
                      $categoryQuery->where('name', 'LIKE', "%{$search}%")
                                   ->orWhere('name_mm', 'LIKE', "%{$search}%");
                  });
            });
        }
        
        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }
        
        // Filter by status
        if ($request->filled('status')) {
            if ($request->get('status') === 'active') {
                $query->where('is_active', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Filter by featured
        if ($request->filled('featured')) {
            if ($request->get('featured') === 'yes') {
                $query->where('is_featured', true);
            } elseif ($request->get('featured') === 'no') {
                $query->where('is_featured', false);
            }
        }
        
        // Sort options
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');
        
        $allowedSorts = ['created_at', 'title', 'plays_count', 'updated_at'];
        if (in_array($sortBy, $allowedSorts)) {
            $query->orderBy($sortBy, $sortOrder);
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $games = $query->paginate(15)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        
        return view('admin.games.index', compact('games', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_mm' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'game_type' => 'required|in:swf,iframe',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'width' => 'nullable|integer|min:100|max:2000',
            'height' => 'nullable|integer|min:100|max:2000',
            'sort_order' => 'nullable|integer',
        ];
        
        // Add conditional validation based on game type
        if ($request->game_type === 'swf') {
            $rules['swf_file'] = 'required|file|mimes:swf|max:50000';
        } else {
            $rules['iframe_url'] = 'nullable|url';
            $rules['iframe_code'] = 'nullable|string';
        }
        
        // Ensure at least one iframe option is provided for iframe games
        $request->validate($rules);
        
        if ($request->game_type === 'iframe' && !$request->iframe_url && !$request->iframe_code) {
            return back()->withErrors(['iframe_content' => 'Please provide either an iframe URL or iframe code.'])->withInput();
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['width'] = $request->width ?? 800;
        $data['height'] = $request->height ?? 600;
        $data['sort_order'] = $request->sort_order ?? 0;

        // Handle file upload for SWF games
        if ($request->game_type === 'swf' && $request->hasFile('swf_file')) {
            $swfFile = $request->file('swf_file');
            $swfFileName = time() . '_' . Str::slug($request->title) . '.swf';
            $swfPath = $swfFile->storeAs('games', $swfFileName, 'public');
            $data['swf_file_path'] = $swfPath;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnailFile = $request->file('thumbnail');
            $thumbnailFileName = time() . '_' . Str::slug($request->title) . '.' . $thumbnailFile->getClientOriginalExtension();
            $thumbnailPath = $thumbnailFile->storeAs('thumbnails', $thumbnailFileName, 'public');
            $data['thumbnail'] = $thumbnailPath;
        }

        Game::create($data);

        return redirect()->route('admin.games.index')
            ->with('success', 'Game created successfully.');
    }

    public function show(Game $game)
    {
        $game->load('category');
        return view('admin.games.show', compact('game'));
    }

    public function edit(Game $game)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.games.edit', compact('game', 'categories'));
    }

    public function update(Request $request, Game $game)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_mm' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'game_type' => 'required|in:swf,iframe',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'width' => 'nullable|integer|min:100|max:2000',
            'height' => 'nullable|integer|min:100|max:2000',
            'sort_order' => 'nullable|integer',
        ];
        
        // Add conditional validation based on game type
        if ($request->game_type === 'swf') {
            $rules['swf_file'] = 'nullable|file|mimes:swf|max:50000';
        } else {
            $rules['iframe_url'] = 'nullable|url';
            $rules['iframe_code'] = 'nullable|string';
        }
        
        $request->validate($rules);
        
        if ($request->game_type === 'iframe' && !$request->iframe_url && !$request->iframe_code) {
            return back()->withErrors(['iframe_content' => 'Please provide either an iframe URL or iframe code.'])->withInput();
        }

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['width'] = $request->width ?? 800;
        $data['height'] = $request->height ?? 600;
        $data['sort_order'] = $request->sort_order ?? 0;

        // Handle game type change
        if ($request->game_type !== $game->game_type) {
            if ($request->game_type === 'iframe') {
                // Switching to iframe, clear SWF data
                if ($game->swf_file_path) {
                    Storage::disk('public')->delete($game->swf_file_path);
                }
                $data['swf_file_path'] = null;
            } else {
                // Switching to SWF, clear iframe data
                $data['iframe_url'] = null;
                $data['iframe_code'] = null;
            }
        }

        // Handle SWF file upload for SWF games
        if ($request->game_type === 'swf' && $request->hasFile('swf_file')) {
            // Delete old file
            if ($game->swf_file_path) {
                Storage::disk('public')->delete($game->swf_file_path);
            }
            
            $swfFile = $request->file('swf_file');
            $swfFileName = time() . '_' . Str::slug($request->title) . '.swf';
            $swfPath = $swfFile->storeAs('games', $swfFileName, 'public');
            $data['swf_file_path'] = $swfPath;
        }

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($game->thumbnail) {
                Storage::disk('public')->delete($game->thumbnail);
            }
            
            $thumbnailFile = $request->file('thumbnail');
            $thumbnailFileName = time() . '_' . Str::slug($request->title) . '.' . $thumbnailFile->getClientOriginalExtension();
            $thumbnailPath = $thumbnailFile->storeAs('thumbnails', $thumbnailFileName, 'public');
            $data['thumbnail'] = $thumbnailPath;
        }

        $game->update($data);

        return redirect()->route('admin.games.index')
            ->with('success', 'Game updated successfully.');
    }

    public function destroy(Game $game)
    {
        // Delete associated files
        if ($game->swf_file_path) {
            Storage::disk('public')->delete($game->swf_file_path);
        }
        if ($game->thumbnail) {
            Storage::disk('public')->delete($game->thumbnail);
        }

        $game->delete();
        
        return redirect()->route('admin.games.index')
            ->with('success', 'Game deleted successfully.');
    }
    
    /**
     * Toggle game status (active/inactive)
     */
    public function toggleStatus(Game $game)
    {
        $game->update(['is_active' => !$game->is_active]);
        
        $status = $game->is_active ? 'activated' : 'deactivated';
        return response()->json([
            'success' => true,
            'message' => "Game {$status} successfully.",
            'is_active' => $game->is_active
        ]);
    }
    
    /**
     * Toggle featured status
     */
    public function toggleFeatured(Game $game)
    {
        $game->update(['is_featured' => !$game->is_featured]);
        
        $status = $game->is_featured ? 'featured' : 'unfeatured';
        return response()->json([
            'success' => true,
            'message' => "Game {$status} successfully.",
            'is_featured' => $game->is_featured
        ]);
    }
}
