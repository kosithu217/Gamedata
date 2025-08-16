@extends('layouts.app')

@section('title', 'Users - Admin Panel')

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
                    <h2 class="fw-bold">{{ __('Users') }} 
                        @if(request('search') || request('role') || request('status') || request('online') || request('category') || request('class_level'))
                            <small class="text-muted">({{ $users->total() }} {{ __('filtered results') }})</small>
                        @else
                            <small class="text-muted">({{ $users->total() }} {{ __('total') }})</small>
                        @endif
                    </h2>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-game">
                        <i class="fas fa-plus me-2"></i>{{ __('Add User') }}
                    </a>
                </div>
                
                <!-- Search and Filter Form -->
                <div class="card card-game mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-3">
                            <!-- Search Input -->
                            <div class="col-md-4">
                                <label for="search" class="form-label">{{ __('Search Users') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" 
                                           class="form-control" 
                                           id="search" 
                                           name="search" 
                                           value="{{ request('search') }}" 
                                           placeholder="{{ __('Search by name, email, phone, or address...') }}">
                                </div>
                            </div>
                            
                            <!-- Role Filter -->
                            <div class="col-md-2">
                                <label for="role" class="form-label">{{ __('Role') }}</label>
                                <select class="form-select" id="role" name="role">
                                    <option value="">{{ __('All Roles') }}</option>
                                    <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>
                                        {{ __('Admin') }}
                                    </option>
                                    <option value="student" {{ request('role') == 'student' ? 'selected' : '' }}>
                                        {{ __('Student') }}
                                    </option>
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
                            
                            <!-- Online Status Filter -->
                            <div class="col-md-2">
                                <label for="online" class="form-label">{{ __('Online') }}</label>
                                <select class="form-select" id="online" name="online">
                                    <option value="">{{ __('All') }}</option>
                                    <option value="yes" {{ request('online') == 'yes' ? 'selected' : '' }}>
                                        {{ __('Online') }}
                                    </option>
                                    <option value="no" {{ request('online') == 'no' ? 'selected' : '' }}>
                                        {{ __('Offline') }}
                                    </option>
                                </select>
                            </div>
                            
                            <!-- Sort Options -->
                            <div class="col-md-2">
                                <label for="sort" class="form-label">{{ __('Sort By') }}</label>
                                <div class="input-group">
                                    <select class="form-select" id="sort" name="sort">
                                        <option value="created_at" {{ request('sort', 'created_at') == 'created_at' ? 'selected' : '' }}>
                                            {{ __('Date Joined') }}
                                        </option>
                                        <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>
                                            {{ __('Name') }}
                                        </option>
                                        <option value="email" {{ request('sort') == 'email' ? 'selected' : '' }}>
                                            {{ __('Email') }}
                                        </option>
                                        <option value="last_login_at" {{ request('sort') == 'last_login_at' ? 'selected' : '' }}>
                                            {{ __('Last Login') }}
                                        </option>
                                    </select>
                                    <select class="form-select" name="order" style="max-width: 80px;">
                                        <option value="desc" {{ request('order', 'desc') == 'desc' ? 'selected' : '' }}>↓</option>
                                        <option value="asc" {{ request('order') == 'asc' ? 'selected' : '' }}>↑</option>
                                    </select>
                                </div>
                            </div>
                            
                            <!-- Category Filter -->
                            <div class="col-md-3">
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
                            
                            <!-- Class Level Filter -->
                            <div class="col-md-3">
                                <label for="class_level" class="form-label">{{ __('Class Level') }}</label>
                                <select class="form-select" id="class_level" name="class_level">
                                    <option value="">{{ __('All Class Levels') }}</option>
                                    @foreach($classLevels as $level)
                                        <option value="{{ $level }}" 
                                                {{ request('class_level') == $level ? 'selected' : '' }}>
                                            {{ $level }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-1"></i>{{ __('Search') }}
                                    </button>
                                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>{{ __('Clear') }}
                                    </a>
                                    @if(request()->hasAny(['search', 'role', 'status', 'online', 'category', 'class_level']))
                                        <span class="btn btn-outline-info disabled">
                                            <i class="fas fa-filter me-1"></i>{{ __('Filters Active') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
                @if($users->count() > 0)
                <div class="card card-game">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'name', 'order' => request('sort') == 'name' && request('order', 'desc') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ __('User') }}
                                                @if(request('sort') == 'name')
                                                    <i class="fas fa-sort-{{ request('order', 'desc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Class Level') }}</th>
                                        <th>{{ __('Categories') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'last_login_at', 'order' => request('sort') == 'last_login_at' && request('order', 'desc') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ __('Last Login') }}
                                                @if(request('sort') == 'last_login_at')
                                                    <i class="fas fa-sort-{{ request('order', 'desc') == 'asc' ? 'up' : 'down' }} ms-1"></i>
                                                @else
                                                    <i class="fas fa-sort ms-1 text-muted"></i>
                                                @endif
                                            </a>
                                        </th>
                                        <th>
                                            <a href="{{ request()->fullUrlWithQuery(['sort' => 'created_at', 'order' => request('sort') == 'created_at' && request('order', 'desc') == 'asc' ? 'desc' : 'asc']) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ __('Joined') }}
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
                                    @foreach($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($user->avatar)
                                                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                                                         alt="{{ $user->name }}" 
                                                         class="rounded-circle me-3" 
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-primary rounded-circle me-3 d-flex align-items-center justify-content-center text-white" 
                                                         style="width: 40px; height: 40px;">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                @endif
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    <br><small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if($user->role === 'admin')
                                                <span class="badge bg-danger">{{ __('Admin') }}</span>
                                            @else
                                                <span class="badge bg-info">{{ __('Student') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->getClassLevelsString() }}</td>
                                        <td>
                                            @if($user->categories->count() > 0)
                                                @foreach($user->categories as $category)
                                                    <span class="badge me-1" style="background-color: {{ $category->color ?? '#6c757d' }};">
                                                        {{ $category->name }}
                                                    </span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">{{ __('No categories assigned') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->id !== auth()->id())
                                                <button class="btn btn-sm toggle-status {{ $user->is_active ? 'btn-success' : 'btn-secondary' }}" 
                                                        data-user-id="{{ $user->id }}"
                                                        data-current-status="{{ $user->is_active ? 'true' : 'false' }}"
                                                        title="{{ $user->is_active ? __('Click to deactivate') : __('Click to activate') }}">
                                                    @if($user->is_active)
                                                        <i class="fas fa-check me-1"></i>{{ __('Active') }}
                                                    @else
                                                        <i class="fas fa-times me-1"></i>{{ __('Inactive') }}
                                                    @endif
                                                </button>
                                            @else
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                                <br><small class="text-muted">{{ __('(Your account)') }}</small>
                                            @endif
                                            
                                            @if($user->current_session_id)
                                                <br><small class="text-success">
                                                    <i class="fas fa-circle me-1" style="font-size: 8px;"></i>{{ __('Online') }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($user->last_login_at)
                                                {{ $user->last_login_at->diffForHumans() }}
                                            @else
                                                <span class="text-muted">{{ __('Never') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $user->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.users.show', $user) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                @if($user->current_session_id)
                                                <button type="button" class="btn btn-sm btn-outline-warning" 
                                                        onclick="forceLogout({{ $user->id }}, '{{ $user->name }}')"
                                                        title="Force logout all sessions">
                                                    <i class="fas fa-sign-out-alt"></i>
                                                </button>
                                                @endif
                                                @if($user->id !== auth()->id())
                                                <form method="POST" action="{{ route('admin.users.destroy', $user) }}" 
                                                      class="d-inline" onsubmit="return confirm('Are you sure? This will delete the user and all their data.')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                                @endif
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
                    {{ $users->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <div class="card card-game">
                        <div class="card-body py-5">
                            <i class="fas fa-users fa-4x text-muted mb-3"></i>
                            <h3 class="text-muted">{{ __('No users found') }}</h3>
                            <p class="text-muted">{{ __('Create your first user to get started.') }}</p>
                            <a href="{{ route('admin.users.create') }}" class="btn btn-game">
                                <i class="fas fa-plus me-2"></i>{{ __('Add User') }}
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
    
    .toggle-status {
        min-width: 80px;
    }
    
    mark {
        background-color: #fff3cd;
        padding: 0.1em 0.2em;
        border-radius: 0.2em;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-submit form when filters change (except search input)
    const filterSelects = document.querySelectorAll('#role, #status, #online, #category, #class_level, #sort, select[name="order"]');
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
            const userId = this.dataset.userId;
            const currentStatus = this.dataset.currentStatus === 'true';
            
            // Disable button during request
            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("Updating...") }}';
            
            fetch(`{{ url('admin/users') }}/${userId}/toggle-status`, {
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
                    showToast(data.message || '{{ __("Error updating status") }}', 'error');
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

function forceLogout(userId, userName) {
    if (confirm(`Are you sure you want to force logout all sessions for ${userName}? This will immediately disconnect them from all devices.`)) {
        fetch(`{{ url('/admin/users') }}/${userId}/force-logout`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                user_id: userId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000); // Refresh after showing message
            } else {
                showToast('Error: ' + (data.message || 'Failed to logout user'), 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while trying to logout the user.', 'error');
        });
    }
}
</script>
@endpush
@endsection