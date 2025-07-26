@extends('layouts.app')

@section('title', 'Admin Dashboard - Game World')

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
    
    .stat-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 15px;
        padding: 2rem;
        text-align: center;
        transition: transform 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
    }
    
    .stat-card i {
        font-size: 3rem;
        margin-bottom: 1rem;
        opacity: 0.8;
    }
    
    .stat-card h3 {
        font-size: 2.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .nav-pills .nav-link {
        border-radius: 10px;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .nav-pills .nav-link:hover {
        background: var(--primary-color);
        color: white;
    }
    
    .nav-pills .nav-link.active {
        background: var(--primary-color);
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-3 mb-4">
            <div class="admin-sidebar p-4">
                <h5 class="fw-bold mb-4">
                    <i class="fas fa-cog me-2"></i>{{ __('Admin Panel') }}
                </h5>
                
                <ul class="nav nav-pills flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>{{ __('Dashboard') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">
                            <i class="fas fa-tags me-2"></i>{{ __('Categories') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.games.index') }}">
                            <i class="fas fa-gamepad me-2"></i>{{ __('Games') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.blog-posts.index') }}">
                            <i class="fas fa-blog me-2"></i>{{ __('Blog Posts') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users.index') }}">
                            <i class="fas fa-users me-2"></i>{{ __('Users') }}
                        </a>
                    </li>
                    <li class="nav-item mt-3">
                        <a class="nav-link text-muted" href="{{ route('home') }}">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Site') }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
        
        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="admin-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">{{ __('Dashboard Overview') }}</h2>
                    <div class="text-muted">
                        {{ __('Welcome back,') }} {{ Auth::user()->name }}!
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-5">
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <i class="fas fa-users"></i>
                            <h3>{{ \App\Models\User::where('role', 'student')->count() }}</h3>
                            <p class="mb-0">{{ __('Students') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <i class="fas fa-gamepad"></i>
                            <h3>{{ \App\Models\Game::count() }}</h3>
                            <p class="mb-0">{{ __('Games') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <i class="fas fa-blog"></i>
                            <h3>{{ \App\Models\BlogPost::count() }}</h3>
                            <p class="mb-0">{{ __('Blog Posts') }}</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 mb-4">
                        <div class="stat-card">
                            <i class="fas fa-tags"></i>
                            <h3>{{ \App\Models\Category::count() }}</h3>
                            <p class="mb-0">{{ __('Categories') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="card card-game">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Recent Users') }}</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $recentUsers = \App\Models\User::where('role', 'student')
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @if($recentUsers->count() > 0)
                                    @foreach($recentUsers as $user)
                                    <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                        <div>
                                            <strong>{{ $user->name }}</strong><br>
                                            <small class="text-muted">{{ $user->email }}</small>
                                        </div>
                                        <div class="text-end">
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small><br>
                                            <span class="badge bg-info">{{ $user->class_level }}</span>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">{{ __('No users yet') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 mb-4">
                        <div class="card card-game">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Popular Games') }}</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $popularGames = \App\Models\Game::orderBy('plays_count', 'desc')
                                        ->take(5)
                                        ->get();
                                @endphp
                                
                                @if($popularGames->count() > 0)
                                    @foreach($popularGames as $game)
                                    <div class="d-flex justify-content-between align-items-center {{ !$loop->last ? 'border-bottom pb-2 mb-2' : '' }}">
                                        <div>
                                            <strong>{{ $game->title }}</strong><br>
                                            <small class="text-muted">{{ $game->category->name }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-success">{{ $game->plays_count }} plays</span>
                                        </div>
                                    </div>
                                    @endforeach
                                @else
                                    <p class="text-muted text-center">{{ __('No games yet') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Actions -->
                <div class="row">
                    <div class="col-12">
                        <div class="card card-game">
                            <div class="card-header">
                                <h6 class="mb-0">{{ __('Quick Actions') }}</h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('admin.games.create') }}" class="btn btn-game w-100">
                                            <i class="fas fa-plus me-2"></i>{{ __('Add Game') }}
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('admin.blog-posts.create') }}" class="btn btn-outline-primary w-100">
                                            <i class="fas fa-plus me-2"></i>{{ __('Add Post') }}
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-secondary w-100">
                                            <i class="fas fa-plus me-2"></i>{{ __('Add Category') }}
                                        </a>
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <a href="{{ route('admin.users.create') }}" class="btn btn-outline-info w-100">
                                            <i class="fas fa-plus me-2"></i>{{ __('Add User') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection