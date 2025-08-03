<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Get all blog posts with pagination and filtering
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

        $query = Blog::where('is_active', true)->with('category');

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
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        // Filter by featured
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        $perPage = $request->get('per_page', 15);
        $blogs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'blogs' => $blogs->items(),
                'pagination' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'per_page' => $blogs->perPage(),
                    'total' => $blogs->total(),
                    'from' => $blogs->firstItem(),
                    'to' => $blogs->lastItem(),
                ]
            ]
        ]);
    }

    /**
     * Get featured blog posts
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
        $blogs = Blog::where('is_active', true)
                    ->where('is_featured', true)
                    ->with('category')
                    ->orderBy('created_at', 'desc')
                    ->take($limit)
                    ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'blogs' => $blogs
            ]
        ]);
    }

    /**
     * Get single blog post
     */
    public function show($slug)
    {
        $blog = Blog::where('slug', $slug)
                   ->where('is_active', true)
                   ->with('category')
                   ->first();

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        // Increment view count
        $blog->increment('views_count');

        return response()->json([
            'success' => true,
            'data' => [
                'blog' => $blog->fresh()
            ]
        ]);
    }

    /**
     * Get blog posts by category
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

        $query = $category->blogs()->where('is_active', true);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('title_mm', 'like', '%' . $search . '%')
                  ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        $perPage = $request->get('per_page', 15);
        $blogs = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => [
                'category' => $category,
                'blogs' => $blogs->items(),
                'pagination' => [
                    'current_page' => $blogs->currentPage(),
                    'last_page' => $blogs->lastPage(),
                    'per_page' => $blogs->perPage(),
                    'total' => $blogs->total(),
                    'from' => $blogs->firstItem(),
                    'to' => $blogs->lastItem(),
                ]
            ]
        ]);
    }

    /**
     * Create new blog post (Admin only)
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_mm' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $blogData = [
            'title' => $request->title,
            'title_mm' => $request->title_mm,
            'content' => $request->content,
            'content_mm' => $request->content_mm,
            'category_id' => $request->category_id,
            'is_featured' => $request->is_featured ?? false,
            'is_active' => $request->is_active ?? true,
            'author_id' => $request->user()->id,
        ];

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imagePath = $image->store('blog', 'public');
            $blogData['featured_image'] = $imagePath;
        }

        $blog = Blog::create($blogData);

        return response()->json([
            'success' => true,
            'message' => 'Blog post created successfully',
            'data' => [
                'blog' => $blog->load('category')
            ]
        ], 201);
    }

    /**
     * Update blog post (Admin only)
     */
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'title_mm' => 'nullable|string|max:255',
            'content' => 'sometimes|required|string',
            'content_mm' => 'nullable|string',
            'category_id' => 'sometimes|required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $updateData = $request->only([
            'title', 'title_mm', 'content', 'content_mm', 'category_id', 'is_featured', 'is_active'
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $image = $request->file('featured_image');
            $imagePath = $image->store('blog', 'public');
            $updateData['featured_image'] = $imagePath;
        }

        $blog->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Blog post updated successfully',
            'data' => [
                'blog' => $blog->fresh()->load('category')
            ]
        ]);
    }

    /**
     * Delete blog post (Admin only)
     */
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json([
                'success' => false,
                'message' => 'Blog post not found'
            ], 404);
        }

        $blog->delete();

        return response()->json([
            'success' => true,
            'message' => 'Blog post deleted successfully'
        ]);
    }
}
