@extends('layouts.app')

@section('title', 'My Profile - Game World')

@push('styles')
<style>
    .profile-card {
        background: rgba(255,255,255,0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
    }
    
    .profile-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 20px 20px 0 0;
        padding: 2rem;
    }
    
    .avatar-circle {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 1rem;
        border: 4px solid rgba(255,255,255,0.3);
    }
    
    .class-level-badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        margin: 0.25rem;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    .info-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #eee;
    }
    
    .info-item:last-child {
        border-bottom: none;
    }
    
    .info-label {
        font-weight: 600;
        color: #666;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-value {
        color: #333;
        text-align: right;
    }
    
    .session-card {
        border: none;
        border-radius: 15px;
        margin-bottom: 1rem;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .session-current {
        border-left: 4px solid #28a745;
        background: rgba(40, 167, 69, 0.05);
    }
    
    .session-inactive {
        border-left: 4px solid #6c757d;
        background: rgba(108, 117, 125, 0.05);
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <!-- Profile Header -->
            <div class="profile-card mb-4">
                <div class="profile-header text-center">
                    <div class="avatar-circle">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        @endif
                    </div>
                    <h2 class="mb-2">{{ $user->name }}</h2>
                    <p class="mb-3">{{ $user->email }}</p>
                    
                    <!-- Class Levels -->
                    <div class="mb-3">
                        <strong>{{ __('Your Class Levels') }}:</strong><br>
                        @forelse($user->getClassLevels() as $level)
                            <span class="class-level-badge bg-warning text-dark">
                                <i class="fas fa-graduation-cap me-1"></i>{{ $level }}
                            </span>
                        @empty
                            <span class="class-level-badge bg-secondary">{{ __('No class assigned') }}</span>
                        @endforelse
                    </div>
                    
                    <!-- Quick Actions -->
                    <div class="mt-4">
                        <a href="{{ route('student.dashboard') }}" class="btn btn-light me-2">
                            <i class="fas fa-tachometer-alt me-2"></i>{{ __('Dashboard') }}
                        </a>
                        <a href="{{ route('games.index') }}" class="btn btn-outline-light">
                            <i class="fas fa-gamepad me-2"></i>{{ __('Browse Games') }}
                        </a>
                    </div>
                </div>
                
                <!-- Profile Information -->
                <div class="p-4">
                    <h5 class="mb-4">
                        <i class="fas fa-user me-2"></i>{{ __('Account Information') }}
                    </h5>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-envelope"></i>{{ __('Email') }}
                        </div>
                        <div class="info-value">{{ $user->email }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-user-tag"></i>{{ __('Role') }}
                        </div>
                        <div class="info-value">
                            <span class="badge bg-info">{{ ucfirst($user->role) }}</span>
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-graduation-cap"></i>{{ __('Class Levels') }}
                        </div>
                        <div class="info-value">{{ $user->getClassLevelsString() }}</div>
                    </div>
                    
                    @if($user->date_of_birth)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-birthday-cake"></i>{{ __('Date of Birth') }}
                        </div>
                        <div class="info-value">{{ $user->date_of_birth->format('F d, Y') }}</div>
                    </div>
                    @endif
                    
                    @if($user->phone)
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-phone"></i>{{ __('Phone') }}
                        </div>
                        <div class="info-value">{{ $user->phone }}</div>
                    </div>
                    @endif
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-plus"></i>{{ __('Member Since') }}
                        </div>
                        <div class="info-value">{{ $user->created_at->format('F d, Y') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-clock"></i>{{ __('Last Login') }}
                        </div>
                        <div class="info-value">
                            @if($user->last_login_at)
                                {{ $user->last_login_at->diffForHumans() }}
                            @else
                                {{ __('Never') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-shield-alt"></i>{{ __('Account Status') }}
                        </div>
                        <div class="info-value">
                            @if($user->is_active)
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Session Information -->
            <div class="profile-card">
                <div class="p-4">
                    <h5 class="mb-4">
                        <i class="fas fa-desktop me-2"></i>{{ __('Session Information') }}
                    </h5>
                    
                    <!-- Current Session -->
                    @if($activeSession)
                    <div class="session-card session-current">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title text-success">
                                        <i class="fas fa-circle me-2" style="font-size: 0.8rem;"></i>{{ __('Current Session') }}
                                    </h6>
                                    <p class="card-text">
                                        <strong>{{ __('Device') }}:</strong> 
                                        @php
                                            $userAgent = $activeSession->user_agent;
                                            if (strpos($userAgent, 'Chrome') !== false) {
                                                echo '<i class="fab fa-chrome me-1"></i>Chrome Browser';
                                            } elseif (strpos($userAgent, 'Firefox') !== false) {
                                                echo '<i class="fab fa-firefox me-1"></i>Firefox Browser';
                                            } elseif (strpos($userAgent, 'Safari') !== false) {
                                                echo '<i class="fab fa-safari me-1"></i>Safari Browser';
                                            } elseif (strpos($userAgent, 'Edge') !== false) {
                                                echo '<i class="fab fa-edge me-1"></i>Edge Browser';
                                            } else {
                                                echo '<i class="fas fa-globe me-1"></i>Unknown Browser';
                                            }
                                        @endphp
                                        <br>
                                        <strong>{{ __('IP Address') }}:</strong> {{ $activeSession->ip_address }}<br>
                                        <strong>{{ __('Last Activity') }}:</strong> {{ $activeSession->last_activity->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="badge bg-success">{{ __('Active') }}</span>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Session History -->
                    @if($userSessions->where('is_active', false)->count() > 0)
                    <h6 class="mt-4 mb-3">{{ __('Recent Sessions') }}</h6>
                    @foreach($userSessions->where('is_active', false)->take(3) as $session)
                    <div class="session-card session-inactive">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h6 class="card-title text-muted">
                                        <i class="fas fa-history me-2"></i>{{ __('Previous Session') }}
                                    </h6>
                                    <p class="card-text">
                                        <strong>{{ __('Device') }}:</strong> 
                                        @php
                                            $userAgent = $session->user_agent;
                                            if (strpos($userAgent, 'Chrome') !== false) {
                                                echo '<i class="fab fa-chrome me-1"></i>Chrome Browser';
                                            } elseif (strpos($userAgent, 'Firefox') !== false) {
                                                echo '<i class="fab fa-firefox me-1"></i>Firefox Browser';
                                            } elseif (strpos($userAgent, 'Safari') !== false) {
                                                echo '<i class="fab fa-safari me-1"></i>Safari Browser';
                                            } elseif (strpos($userAgent, 'Edge') !== false) {
                                                echo '<i class="fab fa-edge me-1"></i>Edge Browser';
                                            } else {
                                                echo '<i class="fas fa-globe me-1"></i>Unknown Browser';
                                            }
                                        @endphp
                                        <br>
                                        <strong>{{ __('IP Address') }}:</strong> {{ $session->ip_address }}<br>
                                        <strong>{{ __('Last Activity') }}:</strong> {{ $session->last_activity->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @endif
                    
                    <!-- Security Notice -->
                    <div class="alert alert-info mt-4">
                        <h6 class="alert-heading">
                            <i class="fas fa-shield-alt me-2"></i>{{ __('Security Notice') }}
                        </h6>
                        <p class="mb-0">
                            {{ __('For your security, you can only be logged in from one device at a time. If you see any suspicious activity, please contact your administrator immediately.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection