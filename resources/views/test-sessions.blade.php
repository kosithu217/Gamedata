@extends('layouts.app')

@section('title', 'Session Testing - Game World')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card card-game">
                <div class="card-body">
                    <h2 class="fw-bold mb-4">
                        <i class="fas fa-cog me-2"></i>Session Testing Dashboard
                    </h2>
                    
                    <!-- Current User Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-user me-2"></i>Current User
                                    </h5>
                                    <p class="mb-1"><strong>Name:</strong> {{ $user->name }}</p>
                                    <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                                    <p class="mb-1"><strong>Role:</strong> 
                                        @if($user->isAdmin())
                                            <span class="badge bg-warning text-dark">Admin</span>
                                        @else
                                            <span class="badge bg-info">Student</span>
                                        @endif
                                    </p>
                                    <p class="mb-0"><strong>Class Levels:</strong> {{ $user->getClassLevelsString() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-desktop me-2"></i>Current Session
                                    </h5>
                                    <p class="mb-1"><strong>Session ID:</strong> {{ Str::limit($currentSession, 20) }}...</p>
                                    <p class="mb-1"><strong>IP Address:</strong> {{ request()->ip() }}</p>
                                    <p class="mb-1"><strong>Browser:</strong> {{ request()->userAgent() ? Str::limit(request()->userAgent(), 30) : 'Unknown' }}...</p>
                                    <p class="mb-0"><strong>Login Time:</strong> {{ $user->last_login_at ? $user->last_login_at->format('M d, Y H:i') : 'Unknown' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Session Rules -->
                    <div class="alert alert-info mb-4">
                        <h5 class="alert-heading">
                            <i class="fas fa-info-circle me-2"></i>Session Rules
                        </h5>
                        @if($user->isAdmin())
                            <p class="mb-0">
                                <strong>Admin Account:</strong> You can login from multiple devices and browsers simultaneously. 
                                No session restrictions apply to admin accounts.
                            </p>
                        @else
                            <p class="mb-0">
                                <strong>Student Account:</strong> You can only be logged in from one device/browser at a time. 
                                If you try to login from another device, you will be logged out from this one.
                            </p>
                        @endif
                    </div>
                    
                    <!-- All User Sessions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-list me-2"></i>All Sessions for {{ $user->name }}
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($userSessions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Session ID</th>
                                                <th>Device/Browser</th>
                                                <th>IP Address</th>
                                                <th>Last Activity</th>
                                                <th>Status</th>
                                                <th>Current</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($userSessions as $session)
                                            <tr class="{{ $session->session_id === $currentSession ? 'table-primary' : '' }}">
                                                <td>
                                                    <code>{{ Str::limit($session->session_id, 15) }}...</code>
                                                </td>
                                                <td>
                                                    @php
                                                        $userAgent = $session->user_agent;
                                                        if (strpos($userAgent, 'Chrome') !== false) {
                                                            $device = 'Chrome Browser';
                                                            $icon = 'fab fa-chrome';
                                                        } elseif (strpos($userAgent, 'Firefox') !== false) {
                                                            $device = 'Firefox Browser';
                                                            $icon = 'fab fa-firefox';
                                                        } elseif (strpos($userAgent, 'Safari') !== false) {
                                                            $device = 'Safari Browser';
                                                            $icon = 'fab fa-safari';
                                                        } elseif (strpos($userAgent, 'Edge') !== false) {
                                                            $device = 'Edge Browser';
                                                            $icon = 'fab fa-edge';
                                                        } else {
                                                            $device = 'Unknown Browser';
                                                            $icon = 'fas fa-globe';
                                                        }
                                                    @endphp
                                                    <i class="{{ $icon }} me-2"></i>{{ $device }}
                                                </td>
                                                <td>{{ $session->ip_address }}</td>
                                                <td>{{ $session->last_activity->diffForHumans() }}</td>
                                                <td>
                                                    @if($session->is_active)
                                                        <span class="badge bg-success">Active</span>
                                                    @else
                                                        <span class="badge bg-secondary">Inactive</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($session->session_id === $currentSession)
                                                        <span class="badge bg-primary">
                                                            <i class="fas fa-check me-1"></i>This Session
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center">No sessions found.</p>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Testing Instructions -->
                    <div class="card mt-4">
                        <div class="card-header">
                            <h5 class="mb-0">
                                <i class="fas fa-flask me-2"></i>How to Test Single Session
                            </h5>
                        </div>
                        <div class="card-body">
                            @if($user->isAdmin())
                                <div class="alert alert-success">
                                    <h6><i class="fas fa-crown me-2"></i>Admin Testing</h6>
                                    <ol class="mb-0">
                                        <li>Open another browser (Chrome → Firefox)</li>
                                        <li>Login with the same admin account</li>
                                        <li><strong>Result:</strong> Both sessions should work simultaneously</li>
                                        <li>Refresh this page to see multiple active sessions</li>
                                    </ol>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <h6><i class="fas fa-user-graduate me-2"></i>Student Testing</h6>
                                    <ol class="mb-0">
                                        <li>Open another browser (Chrome → Firefox)</li>
                                        <li>Try to login with the same student account</li>
                                        <li><strong>Result:</strong> You should see an error message</li>
                                        <li>If you force login, this session will be terminated</li>
                                    </ol>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Actions -->
                    <div class="text-center mt-4">
                        <a href="{{ route('home') }}" class="btn btn-primary me-2">
                            <i class="fas fa-home me-2"></i>Go to Home
                        </a>
                        @if($user->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-success me-2">
                                <i class="fas fa-cog me-2"></i>Admin Panel
                            </a>
                        @endif
                        <button onclick="location.reload()" class="btn btn-outline-secondary">
                            <i class="fas fa-sync me-2"></i>Refresh Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .card-game {
        border: none;
        border-radius: 20px;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    }
    
    .table-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }
    
    code {
        background: rgba(0,0,0,0.1);
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.85em;
    }
</style>
@endpush
@endsection