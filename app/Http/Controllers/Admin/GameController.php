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
    public function index()
    {
        $games = Game::with('category')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.games.index', compact('games'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.games.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_mm' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'swf_file' => 'required|file|mimes:swf|max:50000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'width' => 'nullable|integer|min:100|max:2000',
            'height' => 'nullable|integer|min:100|max:2000',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['width'] = $request->width ?? 800;
        $data['height'] = $request->height ?? 600;
        $data['sort_order'] = $request->sort_order ?? 0;

        // Handle SWF file upload
        if ($request->hasFile('swf_file')) {
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
        $request->validate([
            'title' => 'required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'description_mm' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'swf_file' => 'nullable|file|mimes:swf|max:50000',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
            'width' => 'nullable|integer|min:100|max:2000',
            'height' => 'nullable|integer|min:100|max:2000',
            'sort_order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_featured'] = $request->has('is_featured');
        $data['is_active'] = $request->has('is_active');
        $data['width'] = $request->width ?? 800;
        $data['height'] = $request->height ?? 600;
        $data['sort_order'] = $request->sort_order ?? 0;

        // Handle SWF file upload
        if ($request->hasFile('swf_file')) {
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
}
