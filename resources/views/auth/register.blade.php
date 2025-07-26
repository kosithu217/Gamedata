@extends('layouts.app')

@section('title', 'Register - Game World')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card card-game shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-user-plus fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold">{{ __('Join Game World!') }}</h2>
                        <p class="text-muted">{{ __('Create your account to start playing') }}</p>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('Full Name') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <input id="name" type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                </div>
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input id="email" type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           name="email" value="{{ old('email') }}" required autocomplete="email">
                                </div>
                                @error('email')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="class_levels" class="form-label">{{ __('Class Levels') }} *</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-graduation-cap"></i>
                                    </span>
                                    <select id="class_levels" 
                                            class="form-select @error('class_levels') is-invalid @enderror" 
                                            name="class_levels[]" multiple required size="5">
                                        <option value="Grade 1" {{ in_array('Grade 1', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 1') }}</option>
                                        <option value="Grade 2" {{ in_array('Grade 2', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 2') }}</option>
                                        <option value="Grade 3" {{ in_array('Grade 3', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 3') }}</option>
                                        <option value="Grade 4" {{ in_array('Grade 4', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 4') }}</option>
                                        <option value="Grade 5" {{ in_array('Grade 5', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 5') }}</option>
                                        <option value="Grade 6" {{ in_array('Grade 6', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 6') }}</option>
                                        <option value="Grade 7" {{ in_array('Grade 7', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 7') }}</option>
                                        <option value="Grade 8" {{ in_array('Grade 8', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 8') }}</option>
                                        <option value="Grade 9" {{ in_array('Grade 9', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 9') }}</option>
                                        <option value="Grade 10" {{ in_array('Grade 10', old('class_levels', [])) ? 'selected' : '' }}>{{ __('Grade 10') }}</option>
                                    </select>
                                </div>
                                <div class="form-text">{{ __('Hold Ctrl/Cmd to select multiple class levels') }}</div>
                                @error('class_levels')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="date_of_birth" class="form-label">{{ __('Date of Birth') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <input id="date_of_birth" type="date" 
                                           class="form-control @error('date_of_birth') is-invalid @enderror" 
                                           name="date_of_birth" value="{{ old('date_of_birth') }}" required>
                                </div>
                                @error('date_of_birth')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password" type="password" 
                                           class="form-control @error('password') is-invalid @enderror" 
                                           name="password" required autocomplete="new-password">
                                </div>
                                @error('password')
                                    <div class="invalid-feedback d-block">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input id="password-confirm" type="password" 
                                           class="form-control" name="password_confirmation" 
                                           required autocomplete="new-password">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-game w-100 mb-3">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Create Account') }}
                        </button>
                        
                        <div class="text-center">
                            <p class="mb-0">{{ __('Already have an account?') }} 
                                <a href="{{ route('login') }}" class="text-decoration-none">
                                    {{ __('Login here') }}
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection