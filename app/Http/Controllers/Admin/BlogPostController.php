<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with(['category', 'author'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.blog-posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.blog-posts.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_mm' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'excerpt_mm' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['author_id'] = auth()->id();
        $data['is_published'] = $request->has('is_published');
        $data['is_featured'] = $request->has('is_featured');
        
        if ($data['is_published']) {
            $data['published_at'] = now();
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $imageFile = $request->file('featured_image');
            $imageFileName = time() . '_' . Str::slug($request->title) . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('blog', $imageFileName, 'public');
            $data['featured_image'] = $imagePath;
        }

        BlogPost::create($data);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post created successfully.');
    }

    public function show(BlogPost $blogPost)
    {
        $blogPost->load(['category', 'author']);
        return view('admin.blog-posts.show', compact('blogPost'));
    }

    public function edit(BlogPost $blogPost)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();
        return view('admin.blog-posts.edit', compact('blogPost', 'categories'));
    }

    public function update(Request $request, BlogPost $blogPost)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_mm' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'excerpt_mm' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        $data['is_published'] = $request->has('is_published');
        $data['is_featured'] = $request->has('is_featured');
        
        if ($data['is_published'] && !$blogPost->published_at) {
            $data['published_at'] = now();
        } elseif (!$data['is_published']) {
            $data['published_at'] = null;
        }

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($blogPost->featured_image) {
                Storage::disk('public')->delete($blogPost->featured_image);
            }
            
            $imageFile = $request->file('featured_image');
            $imageFileName = time() . '_' . Str::slug($request->title) . '.' . $imageFile->getClientOriginalExtension();
            $imagePath = $imageFile->storeAs('blog', $imageFileName, 'public');
            $data['featured_image'] = $imagePath;
        }

        $blogPost->update($data);

        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post updated successfully.');
    }

    public function destroy(BlogPost $blogPost)
    {
        // Delete associated image
        if ($blogPost->featured_image) {
            Storage::disk('public')->delete($blogPost->featured_image);
        }

        $blogPost->delete();
        
        return redirect()->route('admin.blog-posts.index')
            ->with('success', 'Blog post deleted successfully.');
    }
}
