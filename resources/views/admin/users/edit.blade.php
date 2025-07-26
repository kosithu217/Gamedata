@extends('layouts.app')

@section('title', 'Edit User - Admin Panel')

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
                    <h2 class="fw-bold">{{ __('Edit User') }}: {{ $user->name }}</h2>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Users') }}
                    </a>
                </div>
                
                <div class="card card-game">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">{{ __('Full Name') }} *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">{{ __('Email Address') }} *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">{{ __('New Password') }}</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password">
                                    <div class="form-text">{{ __('Leave empty to keep current password') }}</div>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="password_confirmation" class="form-label">{{ __('Confirm New Password') }}</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="role" class="form-label">{{ __('Role') }} *</label>
                                    <select class="form-select @error('role') is-invalid @enderror" 
                                            id="role" name="role" required>
                                        <option value="">{{ __('Select Role') }}</option>
                                        <option value="student" {{ old('role', $user->role) == 'student' ? 'selected' : '' }}>{{ __('Student') }}</option>
                                        <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>{{ __('Admin') }}</option>
                                    </select>
                                    @error('role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="class_levels" class="form-label">{{ __('Class Levels') }}</label>
                                    <select class="form-select @error('class_levels') is-invalid @enderror" 
                                            id="class_levels" name="class_levels[]" multiple size="5">
                                        @php
                                            $userClassLevels = old('class_levels', $user->getClassLevels());
                                        @endphp
                                        <option value="Grade 1" {{ in_array('Grade 1', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 1') }}</option>
                                        <option value="Grade 2" {{ in_array('Grade 2', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 2') }}</option>
                                        <option value="Grade 3" {{ in_array('Grade 3', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 3') }}</option>
                                        <option value="Grade 4" {{ in_array('Grade 4', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 4') }}</option>
                                        <option value="Grade 5" {{ in_array('Grade 5', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 5') }}</option>
                                        <option value="Grade 6" {{ in_array('Grade 6', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 6') }}</option>
                                        <option value="Grade 7" {{ in_array('Grade 7', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 7') }}</option>
                                        <option value="Grade 8" {{ in_array('Grade 8', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 8') }}</option>
                                        <option value="Grade 9" {{ in_array('Grade 9', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 9') }}</option>
                                        <option value="Grade 10" {{ in_array('Grade 10', $userClassLevels) ? 'selected' : '' }}>{{ __('Grade 10') }}</option>
                                    </select>
                                    <div class="form-text">{{ __('Hold Ctrl/Cmd to select multiple class levels. Leave empty for admin users.') }}</div>
                                    @error('class_levels')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3" id="categories-section">
                                <label for="categories" class="form-label">{{ __('Assign Categories') }}</label>
                                <div class="row">
                                    @php
                                        $userCategoryIds = old('categories', $user->categories->pluck('id')->toArray());
                                    @endphp
                                    @foreach($categories as $category)
                                    <div class="col-md-4 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="category_{{ $category->id }}" 
                                                   name="categories[]" 
                                                   value="{{ $category->id }}"
                                                   {{ in_array($category->id, $userCategoryIds) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="category_{{ $category->id }}">
                                                <span class="badge me-2" style="background-color: {{ $category->color ?? '#6c757d' }};">
                                                    {{ $category->name }}
                                                </span>
                                            </label>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="form-text">{{ __('Select categories that this user can access. Students will only see games from assigned categories.') }}</div>
                                @error('categories')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="date_of_birth" class="form-label">{{ __('Date of Birth') }}</label>
                                    <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">{{ __('Phone Number') }}</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">{{ __('Address') }}</label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                           {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ __('Active') }}
                                    </label>
                                </div>
                            </div>
                            
                            <!-- User Stats -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ __('User Information') }}</h6>
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <strong>{{ __('Member Since') }}:</strong> {{ $user->created_at->format('M d, Y') }}
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('Last Login') }}:</strong> 
                                                    @if($user->last_login_at)
                                                        {{ $user->last_login_at->format('M d, Y H:i') }}
                                                    @else
                                                        {{ __('Never') }}
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('Status') }}:</strong> 
                                                    @if($user->current_session_id)
                                                        <span class="badge bg-success">{{ __('Online') }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ __('Offline') }}</span>
                                                    @endif
                                                </div>
                                                <div class="col-md-3">
                                                    <strong>{{ __('Account Status') }}:</strong> 
                                                    @if($user->is_active)
                                                        <span class="badge bg-success">{{ __('Active') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ __('Inactive') }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($user->role === 'student')
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <strong>{{ __('Blog Posts') }}:</strong> {{ $user->blogPosts()->count() }}
                                                </div>
                                                <div class="col-md-6">
                                                    <strong>{{ __('Active Sessions') }}:</strong> {{ $user->userSessions()->where('is_active', true)->count() }}
                                                </div>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($user->current_session_id)
                            <!-- Session Management -->
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="card border-warning">
                                        <div class="card-body">
                                            <h6 class="card-title text-warning">
                                                <i class="fas fa-exclamation-triangle me-2"></i>{{ __('Active Session') }}
                                            </h6>
                                            <p class="mb-2">{{ __('This user is currently logged in.') }}</p>
                                            <p class="mb-0"><strong>{{ __('Session ID') }}:</strong> {{ Str::limit($user->current_session_id, 20) }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endif
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-game">
                                    <i class="fas fa-save me-2"></i>{{ __('Update User') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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