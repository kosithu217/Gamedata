<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = BlogPost::where('is_published', true)->with(['category', 'author']);

        // Filter by user's assigned categories for students
        if (auth()->check() && auth()->user()->isStudent()) {
            $userCategoryIds = auth()->user()->categories()->pluck('categories.id');
            if ($userCategoryIds->isNotEmpty()) {
                $query->whereIn('category_id', $userCategoryIds);
            } else {
                // If no categories assigned, show no posts
                $query->whereRaw('1 = 0');
            }
        }

        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('title_mm', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(10);
        
        // Filter categories based on user's assigned categories
        $categoriesQuery = Category::where('is_active', true);
        if (auth()->check() && auth()->user()->isStudent()) {
            $userCategoryIds = auth()->user()->categories()->pluck('categories.id');
            if ($userCategoryIds->isNotEmpty()) {
                $categoriesQuery->whereIn('id', $userCategoryIds);
            } else {
                $categoriesQuery->whereRaw('1 = 0');
            }
        }
        $categories = $categoriesQuery->orderBy('sort_order')->get();

        return view('blog.index', compact('posts', 'categories'));
    }

    public function show($slug)
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_published', true)
            ->with(['category', 'author'])
            ->firstOrFail();

        // Check category access for students
        if (auth()->check() && auth()->user()->isStudent()) {
            if (!auth()->user()->hasAccessToCategory($post->category_id)) {
                return redirect()->route('blog.index')
                    ->with('error', __('You do not have access to this blog post. Please contact your administrator.'));
            }
        }

        // Increment view count
        $post->incrementViews();

        $relatedPostsQuery = BlogPost::where('category_id', $post->category_id)
            ->where('id', '!=', $post->id)
            ->where('is_published', true);
            
        // Filter related posts by user's assigned categories for students
        if (auth()->check() && auth()->user()->isStudent()) {
            $userCategoryIds = auth()->user()->categories()->pluck('categories.id');
            if ($userCategoryIds->isNotEmpty()) {
                $relatedPostsQuery->whereIn('category_id', $userCategoryIds);
            }
        }
        
        $relatedPosts = $relatedPostsQuery->take(3)->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
}
