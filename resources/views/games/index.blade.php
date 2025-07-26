@extends('layouts.app')

@section('title', 'Games - Game World')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-md-8">
            @if($isDemo ?? false)
                <h1 class="fw-bold text-white">
                    <i class="fas fa-gamepad me-2"></i>{{ __('Demo Games') }}
                </h1>
                <p class="text-white-50">{{ __('Try these sample games - Register to unlock all games!') }}</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    {{ __('You are viewing demo games only. Register to access 50+ educational games!') }}
                </div>
            @else
                <h1 class="fw-bold text-white">
                    <i class="fas fa-gamepad me-2"></i>{{ __('Games') }}
                </h1>
                <p class="text-white-50">{{ __('Choose from our collection of educational games') }}</p>
            @endif
        </div>
        <div class="col-md-4">
            <form method="GET" action="{{ route('games.index') }}">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" 
                           placeholder="{{ __('Search games...') }}" 
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
                        <a href="{{ route('games.index') }}" 
                           class="btn {{ !request('category') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            {{ __('All Games') }}
                        </a>
                        @foreach($categories as $category)
                        <a href="{{ route('games.index', ['category' => $category->slug]) }}" 
                           class="btn {{ request('category') == $category->slug ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                            {{ $category->name }} ({{ $category->games_count }})
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    <!-- Games Grid -->
    @if($games->count() > 0)
    <div class="row">
        @foreach($games as $game)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card card-game h-100">
                @if($game->thumbnail)
                    <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                         class="card-img-top" alt="{{ $game->title }}" 
                         style="height: 200px; object-fit: cover;">
                @else
                    <div class="card-img-top d-flex align-items-center justify-content-center bg-light" 
                         style="height: 200px;">
                        <i class="fas fa-gamepad fa-3x text-muted"></i>
                    </div>
                @endif
                
                <div class="card-body">
                    <h5 class="card-title">{{ $game->title }}</h5>
                    <p class="card-text text-muted">{{ Str::limit($game->description, 100) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="text-muted">
                            <i class="fas fa-play me-1"></i>{{ $game->plays_count }} {{ __('plays') }}
                        </small>
                        <span class="badge" style="background-color: {{ $game->category->color }}">
                            {{ $game->category->name }}
                        </span>
                    </div>
                    
                    @if($game->is_featured)
                    <div class="mb-2">
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                        </span>
                    </div>
                    @endif
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
    
    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $games->appends(request()->query())->links() }}
    </div>
    @else
    <div class="text-center py-5">
        <div class="card card-game">
            <div class="card-body py-5">
                <i class="fas fa-gamepad fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">{{ __('No games found') }}</h3>
                <p class="text-muted">{{ __('Try adjusting your search or filter criteria.') }}</p>
                <a href="{{ route('games.index') }}" class="btn btn-primary">
                    {{ __('View All Games') }}
                </a>
            </div>
        </div>
    </div>
    @endif
    
    @if($isDemo ?? false)
    <!-- Registration Encouragement Section for Demo Users -->
    <div class="row justify-content-center mt-5">
        <div class="col-lg-8">
            <div class="card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: none;">
                <div class="card-body text-center py-5">
                    <i class="fas fa-unlock-alt fa-4x text-warning mb-4"></i>
                    <h3 class="text-white mb-3">{{ __('Unlock All Games!') }}</h3>
                    <p class="text-white-50 mb-4 lead">
                        {{ __('You\'ve seen our demo games. Ready for the full experience?') }}<br>
                        {{ __('Register now to access 50+ educational games, track your progress, and unlock exclusive content!') }}
                    </p>
                    
                    <div class="row text-white-50 mb-4">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-gamepad fa-2x text-primary mb-2"></i>
                            <h6>{{ __('50+ Games') }}</h6>
                            <small>{{ __('Educational games for all levels') }}</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-chart-line fa-2x text-success mb-2"></i>
                            <h6>{{ __('Track Progress') }}</h6>
                            <small>{{ __('Monitor your learning journey') }}</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-star fa-2x text-warning mb-2"></i>
                            <h6>{{ __('Exclusive Content') }}</h6>
                            <small>{{ __('Access premium features') }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('register') }}" class="btn btn-game btn-lg">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Register Free') }}
                        </a>
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                        </a>
                    </div>
                    
                    <p class="text-white-50 mt-3 mb-0">
                        <small>{{ __('Registration is completely free and takes less than 2 minutes!') }}</small>
                    </p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection