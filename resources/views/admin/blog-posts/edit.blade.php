@extends('layouts.app')

@section('title', 'Edit Blog Post - Admin Panel')

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            @include('admin.partials.sidebar')
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="admin-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">{{ __('Edit Blog Post') }}: {{ Str::limit($blogPost->title, 30) }}</h2>
                    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Posts') }}
                    </a>
                </div>
                
                <div class="card card-game">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.blog-posts.update', $blogPost) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }} (English) *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title', $blogPost->title) }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="title_mm" class="form-label">{{ __('Title') }} (Myanmar)</label>
                                    <input type="text" class="form-control @error('title_mm') is-invalid @enderror" 
                                           id="title_mm" name="title_mm" value="{{ old('title_mm', $blogPost->title_mm) }}">
                                    @error('title_mm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">{{ __('Category') }} *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id', $blogPost->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->class_level }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="excerpt" class="form-label">{{ __('Excerpt') }} (English)</label>
                                    <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                              id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $blogPost->excerpt) }}</textarea>
                                    <div class="form-text">{{ __('Short description for the post') }}</div>
                                    @error('excerpt')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="excerpt_mm" class="form-label">{{ __('Excerpt') }} (Myanmar)</label>
                                    <textarea class="form-control @error('excerpt_mm') is-invalid @enderror" 
                                              id="excerpt_mm" name="excerpt_mm" rows="3">{{ old('excerpt_mm', $blogPost->excerpt_mm) }}</textarea>
                                    @error('excerpt_mm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="content" class="form-label">{{ __('Content') }} (English) *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" 
                                              id="content" name="content" rows="10" required>{{ old('content', $blogPost->content) }}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="content_mm" class="form-label">{{ __('Content') }} (Myanmar)</label>
                                    <textarea class="form-control @error('content_mm') is-invalid @enderror" 
                                              id="content_mm" name="content_mm" rows="10">{{ old('content_mm', $blogPost->content_mm) }}</textarea>
                                    @error('content_mm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <!-- Current Featured Image -->
                            <div class="mb-3">
                                <label class="form-label">{{ __('Current Featured Image') }}</label>
                                <div class="card">
                                    <div class="card-body">
                                        @if($blogPost->featured_image)
                                            <img src="{{ asset('storage/' . $blogPost->featured_image) }}" 
                                                 alt="{{ $blogPost->title }}" 
                                                 class="img-fluid rounded" 
                                                 style="max-height: 200px;">
                                        @else
                                            <p class="text-muted">{{ __('No featured image uploaded') }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="featured_image" class="form-label">{{ __('Replace Featured Image') }}</label>
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                       id="featured_image" name="featured_image" accept="image/*">
                                <div class="form-text">{{ __('Leave empty to keep current image. Upload image (max 5MB)') }}</div>
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_published" name="is_published" 
                                               {{ old('is_published', $blogPost->is_published) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_published">
                                            {{ __('Published') }}
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                               {{ old('is_featured', $blogPost->is_featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            {{ __('Featured post') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Post Stats -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ __('Post Statistics') }}</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>{{ __('Views') }}:</strong> {{ $blogPost->views_count }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('Author') }}:</strong> {{ $blogPost->author->name }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('Created') }}:</strong> {{ $blogPost->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('Status') }}:</strong> 
                                                    @if($blogPost->is_published)
                                                        <span class="badge bg-success">{{ __('Published') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('Draft') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($blogPost->published_at)
                                            <div class="row mt-2">
                                                <div class="col-md-12">
                                                    <strong>{{ __('Published At') }}:</strong> {{ $blogPost->published_at->format('M d, Y H:i') }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('admin.blog-posts.index') }}" class="btn btn-outline-secondary">
                                        {{ __('Cancel') }}
                                    </a>
                                    @if($blogPost->is_published)
                                        <a href="{{ route('blog.show', $blogPost->slug) }}" class="btn btn-outline-success" target="_blank">
                                            <i class="fas fa-external-link-alt me-2"></i>{{ __('View Post') }}
                                        </a>
                                    @endif
                                </div>
                                <button type="submit" class="btn btn-game">
                                    <i class="fas fa-save me-2"></i>{{ __('Update Post') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .admin-sidebar {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        min-height: calc(100vh - 80px);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
    
    .admin-content {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }
</style>
@endpush
@endsection