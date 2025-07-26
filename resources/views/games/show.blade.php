@extends('layouts.app')

@section('title', $game->title . ' - Game World')

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Game Info -->
        <div class="col-lg-8">
            <div class="card card-game">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h1 class="fw-bold">{{ $game->title }}</h1>
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <span class="badge" style="background-color: {{ $game->category->color }}">
                                    {{ $game->category->name }}
                                </span>
                                @if($game->is_featured)
                                <span class="badge bg-warning text-dark">
                                    <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                </span>
                                @endif
                            </div>
                            <div class="text-muted">
                                <i class="fas fa-play me-1"></i>{{ $game->plays_count }} {{ __('plays') }}
                            </div>
                        </div>
                        <a href="{{ route('games.play', $game->slug) }}" class="btn btn-game btn-lg">
                            <i class="fas fa-play me-2"></i>{{ __('Play Game') }}
                        </a>
                    </div>
                    
                    @if($game->thumbnail)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                             class="img-fluid rounded" alt="{{ $game->title }}"
                             style="max-height: 300px; width: 100%; object-fit: cover;">
                    </div>
                    @endif
                    
                    @if($game->description)
                    <div class="mb-4">
                        <h5>{{ __('About This Game') }}</h5>
                        <p class="text-muted">{{ $game->description }}</p>
                    </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ __('Game Details') }}</h6>
                            <ul class="list-unstyled">
                                <li><strong>{{ __('Category') }}:</strong> {{ $game->category->name }}</li>
                                <li><strong>{{ __('Class Level') }}:</strong> {{ $game->category->class_level }}</li>
                                <li><strong>{{ __('Dimensions') }}:</strong> {{ $game->width }}x{{ $game->height }}</li>
                                <li><strong>{{ __('Total Plays') }}:</strong> {{ $game->plays_count }}</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Quick Play -->
            <div class="card card-game mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('Ready to Play?') }}</h5>
                    <p class="card-text text-muted">{{ __('Click the button below to start playing!') }}</p>
                    <a href="{{ route('games.play', $game->slug) }}" class="btn btn-game btn-lg w-100">
                        <i class="fas fa-play me-2"></i>{{ __('Play Now') }}
                    </a>
                </div>
            </div>
            
            <!-- Navigation -->
            <div class="card card-game">
                <div class="card-body">
                    <h6 class="card-title">{{ __('Navigation') }}</h6>
                    <div class="d-grid gap-2">
                        <a href="{{ route('games.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Games') }}
                        </a>
                        <a href="{{ route('games.index', ['category' => $game->category->slug]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>{{ __('More from') }} {{ $game->category->name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection