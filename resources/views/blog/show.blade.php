@extends('layouts.app')

@section('title', $post->title . ' - Blog - Game World')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card card-game">
                <div class="card-body">
                    <!-- Post Header -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex gap-2">
                                <span class="badge" style="background-color: {{ $post->category->color }}">
                                    {{ $post->category->name }}
                                </span>
                                @if($post->is_featured)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                </span>
                                @endif
                            </div>
                        </div>
                        
                        <h1 class="fw-bold mb-3">{{ $post->title }}</h1>
                        
                        <div class="d-flex justify-content-between align-items-center text-muted mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div>
                                    <i class="fas fa-user me-1"></i>{{ $post->author->name }}
                                </div>
                                <div>
                                    <i class="fas fa-calendar me-1"></i>{{ $post->published_at->format('F d, Y') }}
                                </div>
                                <div>
                                    <i class="fas fa-eye me-1"></i>{{ $post->views_count }} {{ __('views') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Featured Image -->
                    @if($post->featured_image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $post->featured_image) }}" 
                             class="img-fluid rounded" alt="{{ $post->title }}">
                    </div>
                    @endif
                    
                    <!-- Post Content -->
                    <div class="post-content">
                        {!! nl2br(e($post->content)) !!}
                    </div>
                    
                    <!-- Post Footer -->
                    <hr class="my-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>{{ __('Category') }}:</strong> 
                            <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" 
                               class="text-decoration-none">
                                {{ $post->category->name }}
                            </a>
                        </div>
                        <div>
                            <strong>{{ __('Author') }}:</strong> {{ $post->author->name }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Navigation -->
            <div class="card card-game mb-4">
                <div class="card-body">
                    <h6 class="card-title">{{ __('Navigation') }}</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('blog.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Blog') }}
                        </a>
                        <a href="{{ route('blog.index', ['category' => $post->category->slug]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>{{ __('More from') }} {{ $post->category->name }}
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
            <div class="card card-game">
                <div class="card-body">
                    <h6 class="card-title">{{ __('Related Posts') }}</h6>
                    @foreach($relatedPosts as $relatedPost)
                    <div class="mb-3 {{ !$loop->last ? 'border-bottom pb-3' : '' }}">
                        <h6 class="mb-1">
                            <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                               class="text-decoration-none">
                                {{ Str::limit($relatedPost->title, 50) }}
                            </a>
                        </h6>
                        <small class="text-muted">
                            <i class="fas fa-calendar me-1"></i>{{ $relatedPost->published_at->format('M d, Y') }}
                        </small>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
.post-content {
    line-height: 1.8;
    font-size: 1.1rem;
}

.post-content p {
    margin-bottom: 1.5rem;
}

.post-content h1, .post-content h2, .post-content h3, 
.post-content h4, .post-content h5, .post-content h6 {
    margin-top: 2rem;
    margin-bottom: 1rem;
    font-weight: 600;
}

.post-content ul, .post-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.post-content blockquote {
    border-left: 4px solid var(--primary-color);
    padding-left: 1rem;
    margin: 1.5rem 0;
    font-style: italic;
    background: rgba(0,0,0,0.05);
    padding: 1rem;
    border-radius: 0 8px 8px 0;
}
</style>
@endpush
@endsection