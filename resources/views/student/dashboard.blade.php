@extends('layouts.app')

@section('title', 'My Dashboard - Game World')

@push('styles')
<style>
    .dashboard-card {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1, #f9ca24);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .dashboard-card:hover::before {
        opacity: 1;
    }
    
    .dashboard-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 50px rgba(0,0,0,0.2);
    }
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px;
        padding: 2rem;
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        transform: scale(0);
        transition: transform 0.6s ease;
    }
    
    .stat-card:hover::before {
        transform: scale(1);
    }
    
    .stat-card:hover {
        transform: translateY(-8px) scale(1.02);
    }
    
    .stat-card i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.9;
        position: relative;
        z-index: 2;
    }
    
    .stat-card h3 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
        position: relative;
        z-index: 2;
    }
    
    .stat-card p {
        position: relative;
        z-index: 2;
    }
    
    .class-level-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        margin: 0.25rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }
    
    .class-level-badge:hover {
        transform: scale(1.05);
    }
    

    
    .game-card {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        transition: all 0.4s ease;
        background: white;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        position: relative;
    }
    
    .game-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .game-card:hover::before {
        transform: scaleX(1);
    }
    
    .game-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }
    
    .category-card {
        border: none;
        border-radius: 20px;
        text-align: center;
        transition: all 0.4s ease;
        background: white;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    
    .category-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255,107,107,0.1), rgba(78,205,196,0.1));
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .category-card:hover::before {
        opacity: 1;
    }
    
    .category-card:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 15px 40px rgba(0,0,0,0.2);
    }
    
    .category-card .card-body {
        position: relative;
        z-index: 2;
    }
    
    .section-title {
        font-size: 1.8rem;
        font-weight: 700;
        color: white;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.3);
    }
    
    .section-title i {
        background: rgba(255,255,255,0.2);
        padding: 0.5rem;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .quick-action-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        border-radius: 30px;
        padding: 0.75rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-block;
        margin: 0.25rem;
        position: relative;
        overflow: hidden;
    }
    
    .quick-action-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: rgba(255,255,255,0.2);
        transition: left 0.3s ease;
    }
    
    .quick-action-btn:hover::before {
        left: 0;
    }
    
    .quick-action-btn:hover {
        background: rgba(255,255,255,0.3);
        color: white;
        transform: translateY(-3px);
        border-color: rgba(255,255,255,0.5);
    }
    
    .quick-action-btn span {
        position: relative;
        z-index: 2;
    }
    
    .games-grid-section {
        background: rgba(255,255,255,0.05);
        border-radius: 25px;
        padding: 2rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(10px);
    }
    
    .category-filter-tabs {
        display: flex;
        gap: 1rem;
        margin-bottom: 2rem;
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .category-tab {
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.2);
        color: white;
        border-radius: 25px;
        padding: 0.5rem 1.5rem;
        text-decoration: none;
        transition: all 0.3s ease;
        font-weight: 500;
    }
    
    .category-tab.active,
    .category-tab:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.4);
        color: white;
        transform: translateY(-2px);
    }
    

    
    @keyframes progress {
        to {
            stroke-dashoffset: 0;
        }
    }
    
    .animate-on-scroll {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.6s ease;
    }
    
    .animate-on-scroll.visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Mobile Responsive Styles */
    @media (max-width: 768px) {
        .quick-action-btn {
            display: block;
            width: 100%;
            margin: 0.5rem 0;
            text-align: center;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
        
        .stat-card h3 {
            font-size: 2rem;
        }
        
        .section-title {
            font-size: 1.4rem;
            text-align: center;
            justify-content: center;
        }
        
        .category-filter-tabs {
            justify-content: center;
            gap: 0.5rem;
        }
        
        .category-tab {
            padding: 0.4rem 1rem;
            font-size: 0.9rem;
        }
        
        .games-grid-section {
            padding: 1.5rem;
        }
        
        .game-card {
            margin-bottom: 1.5rem;
        }
        
        .dashboard-card {
            margin-bottom: 1rem;
        }
        
        .class-level-badge {
            display: block;
            margin: 0.5rem auto;
            text-align: center;
            max-width: 200px;
        }
    }
    
    @media (max-width: 576px) {
        .stat-card {
            padding: 1.5rem;
        }
        
        .stat-card h3 {
            font-size: 1.8rem;
        }
        
        .stat-card i {
            font-size: 2.5rem;
        }
        
        .category-filter-tabs {
            flex-direction: column;
            align-items: center;
        }
        
        .category-tab {
            width: 80%;
            text-align: center;
            margin: 0.25rem 0;
        }
        
        .section-title i {
            width: 40px;
            height: 40px;
            font-size: 1.2rem;
        }
        
        #game-search {
            font-size: 16px; /* Prevents zoom on iOS */
        }
    }
    
    /* Dark mode support */
    @media (prefers-color-scheme: dark) {
        .dashboard-card {
            background: rgba(30,30,30,0.95);
            color: #e0e0e0;
        }
        
        .game-card {
            background: rgba(40,40,40,0.95);
            color: #e0e0e0;
        }
        
        .category-card {
            background: rgba(40,40,40,0.95);
            color: #e0e0e0;
        }
        

    }
    
    /* Print styles */
    @media print {
        .quick-action-btn,
        .category-filter-tabs,
        .btn {
            display: none !important;
        }
        
        .dashboard-card,
        .game-card,
        .category-card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="fas fa-gamepad"></i>
                <h3>{{ $stats['total_games'] }}</h3>
                <p class="mb-0">{{ __('Available Games') }}</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="fas fa-tags"></i>
                <h3>{{ $stats['total_categories'] }}</h3>
                <p class="mb-0">{{ __('Categories') }}</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="fas fa-blog"></i>
                <h3>{{ $stats['total_posts'] }}</h3>
                <p class="mb-0">{{ __('Blog Posts') }}</p>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-4">
            <div class="stat-card">
                <i class="fas fa-graduation-cap"></i>
                <h3>{{ count($stats['class_levels']) }}</h3>
                <p class="mb-0">{{ __('Class Levels') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Games Section with Category Filtering -->
    @if($userCategories->count() > 0)
    <div class="games-grid-section animate-on-scroll">
        <h2 class="section-title">
            <i class="fas fa-gamepad"></i>{{ __('Your Games Library') }}
        </h2>
        
        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" id="game-search" class="form-control" 
                           placeholder="{{ __('Search games...') }}" 
                           style="background: rgba(255,255,255,0.9); border: 2px solid rgba(255,255,255,0.3); border-radius: 25px 0 0 25px;">
                    <button class="btn btn-outline-light" type="button" style="border-radius: 0 25px 25px 0;">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="category-filter-tabs justify-content-end">
                    <a href="#" class="category-tab active" data-category="all">
                        <i class="fas fa-th me-1"></i>{{ __('All') }}
                    </a>
                    @foreach($userCategories->take(3) as $category)
                    <a href="#" class="category-tab" data-category="{{ $category->id }}">
                        {{ Str::limit($category->name, 10) }}
                    </a>
                    @endforeach
                    <a href="#" class="category-tab" data-category="featured">
                        <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Games Grid -->
        <div id="games-container" class="row">
            @if($featuredGames->count() > 0)
                @foreach($featuredGames as $game)
                <div class="col-lg-4 col-md-6 mb-4 game-item animate-on-scroll" 
                     data-category="{{ $game->category_id }}" 
                     data-featured="{{ $game->is_featured ? 'true' : 'false' }}">
                    <div class="card game-card h-100">
                        @if($game->thumbnail)
                            <div class="position-relative">
                                <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                                     class="card-img-top" alt="{{ $game->title }}" 
                                     style="height: 220px; object-fit: cover;">
                                @if($game->is_featured)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        @else
                            <div class="card-img-top d-flex align-items-center justify-content-center bg-light position-relative" 
                                 style="height: 220px;">
                                <i class="fas fa-gamepad fa-3x text-muted"></i>
                                @if($game->is_featured)
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                    </span>
                                </div>
                                @endif
                            </div>
                        @endif
                        
                        <div class="card-body">
                            <h5 class="card-title">{{ $game->title }}</h5>
                            <p class="card-text text-muted">{{ Str::limit($game->description, 80) }}</p>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-play me-1"></i>{{ $game->plays_count }} {{ __('plays') }}
                                </small>
                                <span class="badge" style="background-color: {{ $game->category->color }}; color: white;">
                                    {{ $game->category->name }}
                                </span>
                            </div>
                            
                            <!-- Game Rating/Difficulty -->
                            <div class="mb-2">
                                <small class="text-muted">
                                    <i class="fas fa-signal me-1"></i>{{ __('Difficulty') }}:
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star {{ $i <= 3 ? 'text-warning' : 'text-muted' }}"></i>
                                    @endfor
                                </small>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent">
                            <div class="d-grid gap-2">
                                <a href="{{ route('games.show', $game->slug) }}" class="btn btn-game">
                                    <i class="fas fa-play me-2"></i>{{ __('Play Now') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @endif
            
            @foreach($allUserGames->whereNotIn('id', $featuredGames->pluck('id'))->take(6) as $game)
            <div class="col-lg-4 col-md-6 mb-4 game-item animate-on-scroll" 
                 data-category="{{ $game->category_id }}" 
                 data-featured="{{ $game->is_featured ? 'true' : 'false' }}">
                <div class="card game-card h-100">
                    @if($game->thumbnail)
                        <div class="position-relative">
                            <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                                 class="card-img-top" alt="{{ $game->title }}" 
                                 style="height: 220px; object-fit: cover;">
                            @if($game->is_featured)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light position-relative" 
                             style="height: 220px;">
                            <i class="fas fa-gamepad fa-3x text-muted"></i>
                            @if($game->is_featured)
                            <div class="position-absolute top-0 end-0 m-2">
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $game->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($game->description, 80) }}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <small class="text-muted">
                                <i class="fas fa-play me-1"></i>{{ $game->plays_count }} {{ __('plays') }}
                            </small>
                            <span class="badge" style="background-color: {{ $game->category->color }}; color: white;">
                                {{ $game->category->name }}
                            </span>
                        </div>
                        
                        <!-- Game Rating/Difficulty -->
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="fas fa-signal me-1"></i>{{ __('Difficulty') }}:
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= 3 ? 'text-warning' : 'text-muted' }}"></i>
                                @endfor
                            </small>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <div class="d-grid gap-2">
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-game">
                                <i class="fas fa-play me-2"></i>{{ __('Play Now') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- View All Games Button -->
        <div class="text-center mt-4">
            <a href="{{ route('games.index') }}" class="btn btn-outline-light btn-lg">
                <i class="fas fa-th-large me-2"></i>{{ __('View All Games') }}
            </a>
        </div>
    </div>
    @endif
    
    @if($userCategories->count() > 0)
    <!-- Your Categories Section -->
    <div class="mb-5">
        <h2 class="section-title">
            <i class="fas fa-th-large"></i>{{ __('Your Learning Categories') }}
        </h2>
        <div class="row">
            @foreach($userCategories as $index => $category)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card category-card h-100">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background-color: {{ $category->color }};">
                                @php
                                    $icons = ['fas fa-gamepad', 'fas fa-puzzle-piece', 'fas fa-brain', 'fas fa-rocket', 'fas fa-star', 'fas fa-trophy', 'fas fa-heart', 'fas fa-magic'];
                                    $iconClass = $icons[$index % count($icons)];
                                @endphp
                                <i class="{{ $iconClass }} fa-2x text-white"></i>
                            </div>
                        </div>
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text text-muted">{{ $category->class_level }}</p>
                        <p class="text-muted">
                            <i class="fas fa-gamepad me-1"></i>{{ $category->games_count }} {{ __('games') }}
                        </p>
                        <a href="{{ route('games.index', ['category' => $category->slug]) }}" class="btn btn-outline-primary">
                            {{ __('Explore') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
    
    @if($recentPosts->count() > 0)
    <!-- Recent Blog Posts Section -->
    <div class="mb-5">
        <h2 class="section-title">
            <i class="fas fa-newspaper"></i>{{ __('Latest Updates for You') }}
        </h2>
        <div class="row">
            @foreach($recentPosts as $post)
            <div class="col-lg-6 mb-4">
                <div class="card dashboard-card h-100">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge" style="background-color: {{ $post->category->color }}; color: white;">
                                {{ $post->category->name }}
                            </span>
                            @if($post->is_featured)
                            <span class="badge bg-warning text-dark">
                                <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                            </span>
                            @endif
                        </div>
                        
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($post->excerpt ?: strip_tags($post->content), 100) }}</p>
                        
                        <div class="d-flex justify-content-between align-items-center text-muted small">
                            <div>
                                <i class="fas fa-user me-1"></i>{{ $post->author->name }}
                            </div>
                            <div>
                                <i class="fas fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}
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
    </div>
    @endif
    

    
    @if($userCategories->count() == 0)
    <!-- No Categories Assigned -->
    <div class="text-center py-5">
        <div class="dashboard-card p-5">
            <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
            <h3 class="text-muted">{{ __('No Categories Assigned') }}</h3>
            <p class="text-muted">{{ __('Please contact your administrator to assign categories to your account.') }}</p>
            <a href="{{ route('student.profile') }}" class="btn btn-primary">
                <i class="fas fa-user me-2"></i>{{ __('View Account Details') }}
            </a>
        </div>
    </div>
    @endif
    

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animate elements on scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
            }
        });
    }, observerOptions);
    
    document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
    });
    
    // Game search functionality
    const gameSearch = document.getElementById('game-search');
    const categoryTabs = document.querySelectorAll('.category-tab');
    const gameItems = document.querySelectorAll('.game-item');
    
    let currentCategory = 'all';
    let currentSearchTerm = '';
    
    function filterGames() {
        gameItems.forEach(item => {
            const itemCategory = item.dataset.category;
            const itemFeatured = item.dataset.featured;
            const gameTitle = item.querySelector('.card-title').textContent.toLowerCase();
            const gameDescription = item.querySelector('.card-text').textContent.toLowerCase();
            
            let categoryMatch = false;
            let searchMatch = true;
            
            // Category filtering
            if (currentCategory === 'all') {
                categoryMatch = true;
            } else if (currentCategory === 'featured') {
                categoryMatch = itemFeatured === 'true';
            } else {
                categoryMatch = itemCategory === currentCategory;
            }
            
            // Search filtering
            if (currentSearchTerm) {
                searchMatch = gameTitle.includes(currentSearchTerm) || 
                             gameDescription.includes(currentSearchTerm);
            }
            
            const shouldShow = categoryMatch && searchMatch;
            
            if (shouldShow) {
                item.style.display = 'block';
                setTimeout(() => {
                    item.style.opacity = '1';
                    item.style.transform = 'translateY(0)';
                }, 100);
            } else {
                item.style.opacity = '0';
                item.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    item.style.display = 'none';
                }, 300);
            }
        });
    }
    
    // Search input handler
    if (gameSearch) {
        gameSearch.addEventListener('input', function() {
            currentSearchTerm = this.value.toLowerCase();
            filterGames();
        });
    }
    
    // Category filtering for games
    categoryTabs.forEach(tab => {
        tab.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all tabs
            categoryTabs.forEach(t => t.classList.remove('active'));
            // Add active class to clicked tab
            this.classList.add('active');
            
            currentCategory = this.dataset.category;
            filterGames();
        });
    });
    
    // Add smooth scrolling to quick action buttons
    document.querySelectorAll('.quick-action-btn').forEach(btn => {
        btn.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-3px) scale(1.05)';
        });
        
        btn.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add loading animation to game cards
    document.querySelectorAll('.game-card').forEach(card => {
        const playBtn = card.querySelector('.btn-game');
        if (playBtn) {
            playBtn.addEventListener('click', function(e) {
                const icon = this.querySelector('i');
                const originalIcon = icon.className;
                
                icon.className = 'fas fa-spinner fa-spin me-2';
                this.disabled = true;
                
                // Re-enable after a short delay (in case of navigation issues)
                setTimeout(() => {
                    icon.className = originalIcon;
                    this.disabled = false;
                }, 3000);
            });
        }
    });
    
    // Add hover effects to stat cards
    document.querySelectorAll('.stat-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            const icon = this.querySelector('i');
            icon.style.transform = 'scale(1.2) rotate(10deg)';
        });
        
        card.addEventListener('mouseleave', function() {
            const icon = this.querySelector('i');
            icon.style.transform = 'scale(1) rotate(0deg)';
        });
    });
    
    // Add parallax effect to welcome section
    window.addEventListener('scroll', function() {
        const scrolled = window.pageYOffset;
        const welcomeSection = document.querySelector('.welcome-section');
        
        if (welcomeSection) {
            const rate = scrolled * -0.5;
            welcomeSection.style.transform = `translateY(${rate}px)`;
        }
    });
    
    // Add typing effect to welcome message
    const welcomeTitle = document.querySelector('.welcome-section h1');
    if (welcomeTitle) {
        const originalText = welcomeTitle.textContent;
        welcomeTitle.textContent = '';
        
        let i = 0;
        const typeWriter = () => {
            if (i < originalText.length) {
                welcomeTitle.textContent += originalText.charAt(i);
                i++;
                setTimeout(typeWriter, 50);
            }
        };
        
        setTimeout(typeWriter, 500);
    }
});
</script>
@endpush
@endsection