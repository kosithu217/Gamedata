@extends('layouts.app')

@section('title', 'Account Already Logged In - Game World')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-game shadow-lg">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                        <h2 class="fw-bold text-danger">{{ __('Account Already Logged In') }}</h2>
                    </div>
                    
                    <div class="alert alert-warning" role="alert">
                        <h5 class="alert-heading">
                            <i class="fas fa-shield-alt me-2"></i>{{ __('Security Notice') }}
                        </h5>
                        <p class="mb-0">
                            {{ __('Your account is currently logged in from another device or browser. For security reasons, only one active session is allowed per account.') }}
                        </p>
                    </div>
                    
                    @if(session('device_info'))
                    <div class="card bg-light mb-4">
                        <div class="card-body">
                            <h6 class="card-title">{{ __('Current Active Session') }}</h6>
                            <div class="row text-start">
                                <div class="col-sm-4"><strong>{{ __('Device') }}:</strong></div>
                                <div class="col-sm-8">{{ session('device_info') }}</div>
                            </div>
                            @if(session('ip_address'))
                            <div class="row text-start">
                                <div class="col-sm-4"><strong>{{ __('IP Address') }}:</strong></div>
                                <div class="col-sm-8">{{ session('ip_address') }}</div>
                            </div>
                            @endif
                            @if(session('login_time'))
                            <div class="row text-start">
                                <div class="col-sm-4"><strong>{{ __('Last Activity') }}:</strong></div>
                                <div class="col-sm-8">{{ session('login_time') }}</div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    
                    <div class="mb-4">
                        <h5>{{ __('What can you do?') }}</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <i class="fas fa-sign-out-alt fa-2x text-primary mb-2"></i>
                                        <h6>{{ __('Logout Other Device') }}</h6>
                                        <p class="small text-muted">{{ __('Go to your other device/browser and logout from there') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <i class="fas fa-phone fa-2x text-success mb-2"></i>
                                        <h6>{{ __('Contact Support') }}</h6>
                                        <p class="small text-muted">{{ __('If you believe this is an error, contact your teacher or administrator') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary">
                            <i class="fas fa-redo me-2"></i>{{ __('Try Login Again') }}
                        </a>
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-home me-2"></i>{{ __('Go to Home') }}
                        </a>
                    </div>
                    
                    <div class="mt-4">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            {{ __('This security measure helps protect your account from unauthorized access.') }}
                        </small>
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
    
    .alert-warning {
        border-radius: 15px;
        border: none;
        background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
    }
    
    .card.bg-light {
        border-radius: 10px;
        border: 1px solid #e9ecef;
    }
    
    .btn {
        border-radius: 25px;
        padding: 10px 25px;
        font-weight: 500;
    }
</style>
@endpush
@endsection