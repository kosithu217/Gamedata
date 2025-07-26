@extends('layouts.app')

@section('title', 'Games - Admin Panel')

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
                    <h2 class="fw-bold">{{ __('Games') }}</h2>
                    <a href="{{ route('admin.games.create') }}" class="btn btn-game">
                        <i class="fas fa-plus me-2"></i>{{ __('Add Game') }}
                    </a>
                </div>
                
                @if($games->count() > 0)
                <div class="card card-game">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Game') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Plays') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Featured') }}</th>
                                        <th width="200">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($games as $game)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($game->thumbnail)
                                                    <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                                                         alt="{{ $game->title }}" 
                                                         class="rounded me-3" 
                                                         style="width: 60px; height: 45px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                         style="width: 60px; height: 45px;">
                                                        <i class="fas fa-gamepad text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $game->title }}</strong>
                                                    @if($game->title_mm)
                                                        <br><small class="text-muted">{{ $game->title_mm }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $game->category->color }}; color: white;">
                                                {{ $game->category->name }}
                                            </span>
                                        </td>
                                        <td>{{ $game->plays_count }}</td>
                                        <td>
                                            @if($game->is_active)
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($game->is_featured)
                                                <span class="badge bg-warning text-dark">
                                                    <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                                                </span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('games.show', $game->slug) }}" 
                                                   class="btn btn-sm btn-outline-success" target="_blank">
                                                    <i class="fas fa-play"></i>
                                                </a>
                                                <a href="{{ route('admin.games.show', $game) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.games.edit', $game) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.games.destroy', $game) }}" 
                                                      class="d-inline" onsubmit="return confirm('Are you sure? This will delete the game and its files.')">
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
                    {{ $games->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <div class="card card-game">
                        <div class="card-body py-5">
                            <i class="fas fa-gamepad fa-4x text-muted mb-3"></i>
                            <h3 class="text-muted">{{ __('No games found') }}</h3>
                            <p class="text-muted">{{ __('Upload your first game to get started.') }}</p>
                            <a href="{{ route('admin.games.create') }}" class="btn btn-game">
                                <i class="fas fa-plus me-2"></i>{{ __('Add Game') }}
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