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
                    <h2 class="fw-bold">{{ __('Users') }}</h2>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-game">
                        <i class="fas fa-plus me-2"></i>{{ __('Add User') }}
                    </a>
                </div>
                
                @if($users->count() > 0)
                <div class="card card-game">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Class Level') }}</th>
                                        <th>{{ __('Categories') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Last Login') }}</th>
                                        <th>{{ __('Joined') }}</th>
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
                                            @if($user->is_active)
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
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
</style>
@endpush

@push('scripts')
<script>
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
                alert(data.message);
                location.reload(); // Refresh the page to update the online status
            } else {
                alert('Error: ' + (data.message || 'Failed to logout user'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while trying to logout the user.');
        });
    }
}
</script>
@endpush
@endsection