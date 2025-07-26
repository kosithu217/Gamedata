@extends('layouts.app')

@section('title', 'Blog Posts - Admin Panel')

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
                    <h2 class="fw-bold">{{ __('Blog Posts') }}</h2>
                    <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-game">
                        <i class="fas fa-plus me-2"></i>{{ __('Add Post') }}
                    </a>
                </div>
                
                @if($posts->count() > 0)
                <div class="card card-game">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Post') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Author') }}</th>
                                        <th>{{ __('Views') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Published') }}</th>
                                        <th width="200">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($posts as $post)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($post->featured_image)
                                                    <img src="{{ asset('storage/' . $post->featured_image) }}" 
                                                         alt="{{ $post->title }}" 
                                                         class="rounded me-3" 
                                                         style="width: 60px; height: 45px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 45px;">
                                                        <i class="fas fa-blog text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ Str::limit($post->title, 40) }}</strong>
                                                    @if($post->title_mm)
                                                        <br><small class="text-muted">{{ Str::limit($post->title_mm, 40) }}</small>
                                                    @endif
                                                    @if($post->is_featured)
                                                        <br><span class="badge bg-warning text-dark">
                                                            <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $post->category->color }}; color: white;">
                                                {{ $post->category->name }}
                                            </span>
                                        </td>
                                        <td>{{ $post->author->name }}</td>
                                        <td>{{ $post->views_count }}</td>
                                        <td>
                                            @if($post->is_published)
                                                <span class="badge bg-success">{{ __('Published') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('Draft') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($post->published_at)
                                                {{ $post->published_at->format('M d, Y') }}
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @if($post->is_published)
                                                <a href="{{ route('blog.show', $post->slug) }}" 
                                                   class="btn btn-sm btn-outline-success" target="_blank">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                @endif
                                                <a href="{{ route('admin.blog-posts.show', $post) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.blog-posts.edit', $post) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.blog-posts.destroy', $post) }}" 
                                                      class="d-inline" onsubmit="return confirm('Are you sure? This will delete the post and its images.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <div class="card card-game">
                        <div class="card-body py-5">
                            <i class="fas fa-blog fa-4x text-muted mb-3"></i>
                            <h3 class="text-muted">{{ __('No blog posts found') }}</h3>
                            <p class="text-muted">{{ __('Create your first blog post to get started.') }}</p>
                            <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-game">
                                <i class="fas fa-plus me-2"></i>{{ __('Add Post') }}
                            </a>
                        </div>
                    </div>
                </div>
                @endif
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