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
                    <h2 class="fw-bold">{{ __('Games') }} 
                        @if(request('search') || request('category') || request('status') || request('featured'))
                            <small class="text-muted">({{ $games->total() }} {{ __('filtered results') }})</small>
                        @else
                            <small class="text-muted">({{ $games->total() }} {{ __('total') }})</small>
                        @endif
                    </h2>
                    <a href="{{ route('admin.games.create') }}" class="btn btn-game">
                        <i class="fas fa-plus me-2"></i>{{ __('Add Game') }}
                    </a>
                </div>
                
                <!-- Search and Filter Form -->
                <div class="card card-game mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.games.index') }}" class="row g-3">
                            <!-- Search Input -->
                            <div class="col-md-4">
                                <label for="search" class="form-label">{{ __('Search Games') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="{{ __('Search by title, description, or category...') }}">
                                </div>
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="col-md-2">
                                <label for="category" class="form-label">{{ __('Category') }}</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">{{ __('All Categories') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" 
                                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Status Filter -->
                            <div class="col-md-2">
                                <label for="status" class="form-label">{{ __('Status') }}</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="">{{ __('All Status') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                        {{ __('Active') }}
                                    </option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>
                                        {{ __('Inactive') }}
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Featured Filter -->
                            <div class="col-md-2">
                                <label for="featured" class="form-label">{{ __('Featured') }}</label>
                                <select class="form-select" id="featured" name="featured">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="yes" {{ request('featured') == 'yes' ? 'selected' : '' }}>
                                        {{ __('Featured') }}
                                    </option>
                                    <option value="no" {{ request('featured') == 'no' ? 'selected' : '' }}>
                                        {{ __('Not Featured') }}
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Sort Options -->
                            <div class="col-md-2">
                                <label for="sort" class="form-label">{{ __('Sort By') }}</label>
                                <div class="input-group">
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>
                                            {{ __('Date Created') }}
                                        </option>
                                        <option value="updated_at" {{ request('sort') == 'updated_at' ? 'selected' : '' }}>
                                            {{ __('Last Updated') }}
                                        </option>
                                        <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>
                                            {{ __('Title') }}
                                        </option>
                                        <option value="plays_count" {{ request('sort') == 'plays_count' ? 'selected' : '' }}>
                                            {{ __('Plays') }}
                                        </option>
                                    </select>
                                    <select class="form-select" name="order" style="max-width: 80px;">
                                        <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>↓</option>
                                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>↑</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>{{ __('Search') }}
                                    </button>
                                    <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>{{ __('Clear') }}
                                    </a>
                                    @if(request()->hasAny(['search', 'category', 'status', 'featured']))
                                        <span class="btn btn-outline-info disabled">
                                            <i class="fas fa-filter me-1"></i>{{ __('Filters Active') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                @if($games->count() > 0)
                <div class="card card-game">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'title', 'order' => request('sort') == 'title' && request('order', 'desc') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ __('Game') }}
                                                @if(request('sort') == 'title')
                                                    <i class="fas fa-sort-{{ request('order', 'desc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>{{ __('Category') }}</th>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'plays_count', 'order' => request('sort') == 'plays_count' && request('order', 'desc') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ __('Plays') }}
                                                @if(request('sort') == 'plays_count')
                                                    <i class="fas fa-sort-{{ request('order', 'desc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Featured') }}</th>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => request('sort') == 'created_at' && request('order', 'desc') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ __('Date') }}
                                                @if(request('sort', 'created_at') == 'created_at')
                                                    <i class="fas fa-sort-{{ request('order', 'desc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
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
                                                    <br><small class="badge badge-sm {{ $game->isIframeGame() ? 'bg-info' : 'bg-secondary' }}">
                                                        {{ $game->isIframeGame() ? 'Iframe' : 'SWF' }}
                                                    </small>
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
                                            <button class="btn btn-sm toggle-status {{ $game->is_active ? 'btn-success' : 'btn-secondary' }}" 
                                                    data-game-id="{{ $game->id }}"
                                                    data-current-status="{{ $game->is_active ? 'true' : 'false' }}"
                                                    title="{{ $game->is_active ? __('Click to deactivate') : __('Click to activate') }}">
                                                @if($game->is_active)
                                                    <i class="fas fa-check me-1"></i>{{ __('Active') }}
                                                @else
                                                    <i class="fas fa-times me-1"></i>{{ __('Inactive') }}
                                                @endif
                                            </button>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm toggle-featured {{ $game->is_featured ? 'btn-warning' : 'btn-outline-warning' }}" 
                                                    data-game-id="{{ $game->id }}"
                                                    data-current-featured="{{ $game->is_featured ? 'true' : 'false' }}"
                                                    title="{{ $game->is_featured ? __('Click to unfeature') : __('Click to feature') }}">
                                                <i class="fas fa-star me-1"></i>
                                                @if($game->is_featured)
                                                    {{ __('Featured') }}
                                                @else
                                                    {{ __('Feature') }}
                                                @endif
                                            </button>
                                        </td>
                                        <td>
                                            <small class="text-muted">
                                                {{ $game->created_at->format('M d, Y') }}<br>
                                                {{ $game->created_at->format('H:i') }}
                                            </small>
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
    
    .table th a {
        display: block;
        padding: 0.5rem 0.75rem;
        margin: -0.5rem -0.75rem;
    }
    
    .table th a:hover {
        background-color: rgba(0,0,0,0.05);
        border-radius: 4px;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change (except search input)
    const filterSelects = document.querySelectorAll('#category, #status, #featured, #sort, select[name="order"]');
    filterSelects.forEach(select => {
        select.addEventListener('change', function() {
            this.form.submit();
        });
    });
    
    // Search on Enter key
    const searchInput = document.getElementById('search');
    if (searchInput) {
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.form.submit();
            }
        });
    }
    
    // Highlight search terms in results
    const searchTerm = '{{ request("search") }}';
    if (searchTerm) {
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        document.querySelectorAll('td').forEach(cell => {
            if (cell.innerHTML && !cell.querySelector('img') && !cell.querySelector('form') && !cell.querySelector('button')) {
                cell.innerHTML = cell.innerHTML.replace(regex, '<mark>$1</mark>');
            }
        });
    }
    
    // Toggle status functionality
    document.querySelectorAll('.toggle-status').forEach(button => {
        button.addEventListener('click', function() {
            const gameId = this.dataset.gameId;
            const currentStatus = this.dataset.currentStatus === 'true';
            
            // Disable button during request
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Updating...") }}';
            
            fetch(`{{ url('admin/games') }}/${gameId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    const newStatus = data.is_active;
                    this.dataset.currentStatus = newStatus ? 'true' : 'false';
                    this.className = `btn btn-sm toggle-status ${newStatus ? 'btn-success' : 'btn-secondary'}`;
                    this.innerHTML = newStatus ? 
                        '<i class="fas fa-check me-1"></i>{{ __("Active") }}' : 
                        '<i class="fas fa-times me-1"></i>{{ __("Inactive") }}';
                    this.title = newStatus ? '{{ __("Click to deactivate") }}' : '{{ __("Click to activate") }}';
                    
                    // Show success message
                    showToast(data.message, 'success');
                } else {
                    showToast('{{ __("Error updating status") }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('{{ __("Error updating status") }}', 'error');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
    // Toggle featured functionality
    document.querySelectorAll('.toggle-featured').forEach(button => {
        button.addEventListener('click', function() {
            const gameId = this.dataset.gameId;
            const currentFeatured = this.dataset.currentFeatured === 'true';
            
            // Disable button during request
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Updating...") }}';
            
            fetch(`{{ url('admin/games') }}/${gameId}/toggle-featured`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update button appearance
                    const newFeatured = data.is_featured;
                    this.dataset.currentFeatured = newFeatured ? 'true' : 'false';
                    this.className = `btn btn-sm toggle-featured ${newFeatured ? 'btn-warning' : 'btn-outline-warning'}`;
                    this.innerHTML = `<i class="fas fa-star me-1"></i>${newFeatured ? '{{ __("Featured") }}' : '{{ __("Feature") }}'}`;
                    this.title = newFeatured ? '{{ __("Click to unfeature") }}' : '{{ __("Click to feature") }}';
                    
                    // Show success message
                    showToast(data.message, 'success');
                } else {
                    showToast('{{ __("Error updating featured status") }}', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('{{ __("Error updating featured status") }}', 'error');
            })
            .finally(() => {
                this.disabled = false;
            });
        });
    });
    
    // Simple toast notification function
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
            </div>
        `;
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }
});
</script>
@endpush
@endsection