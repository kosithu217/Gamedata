@extends('layouts.app')

@section('title', 'Blog - Game World')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="fw-bold text-white">
                <i class="fas fa-blog me-2"></i>{{ __('Blog') }}
            </h1>
            <p class="text-white-50">{{ __('Latest news, updates, and educational content') }}</p>
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('blog.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="{{ __('Search posts...') }}" 
                           value="{{ request('search') }}">
                    <button class="btn btn-game" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                @if(request('category'))
                    <input type="hidden" name="category" value="{{ request('category') }}">
                @endif
            </form>
        </div>
    </div>
    
    <!-- Categories Filter -->
    @if($categories->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-game">
                <div class="card-body">
                    <h6 class="card-title mb-3">{{ __('Filter by Category') }}</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('blog.index') }}" 
                           class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            {{ __('All Posts') }}
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category->slug]) }}" 
                           class="btn {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            {{ $category->name }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Blog Posts -->
    @if($posts->count() > 0)
    <div class="row">
        @foreach($posts as $post)
        <div class="col-lg-6 mb-4">
            <div class="card card-game h-100">
                @if($post->featured_image)
                    <img src="{{ asset('storage/' . $post->featured_image) }}" 
                         class="card-img-top" alt="{{ $post->title }}" 
                         style="height: 200px; object-fit: cover;">
                @endif
                
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge" style="background-color: {{ $post->category->color }}">
                            {{ $post->category->name }}
                        </span>
                        @if($post->is_featured)
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                        </span>
                        @endif
                    </div>
                    
                    <h5 class="card-title">{{ $post->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($post->excerpt ?: strip_tags($post->content), 120) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center text-muted small">
                        <div>
                            <i class="fas fa-user me-1"></i>{{ $post->author->name }}
                        </div>
                        <div>
                            <i class="fas fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
                        </div>
                        <div>
                            <i class="fas fa-eye me-1"></i>{{ $post->views_count }} {{ __('views') }}
                        </div>
                    </div>
                </div>
                
                <div class="card-footer bg-transparent">
                    <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary w-100">
                        {{ __('Read More') }} <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->appends(request()->query())->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="card card-game">
            <div class="card-body py-5">
                <i class="fas fa-blog fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">{{ __('No posts found') }}</h3>
                <p class="text-muted">{{ __('Try adjusting your search or filter criteria.') }}</p>
                <a href="{{ route('blog.index') }}" class="btn btn-primary">
                    {{ __('View All Posts') }}
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection