@extends('layouts.app')

@section('title', 'Create Category - Admin Panel')

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
                    <h2 class="fw-bold">{{ __('Create Category') }}</h2>
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Categories') }}
                    </a>
                </div>
                
                <div class="card card-game">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.categories.store') }}">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">{{ __('Name') }} (English) *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="name_mm" class="form-label">{{ __('Name') }} (Myanmar)</label>
                                    <input type="text" class="form-control @error('name_mm') is-invalid @enderror" 
                                           id="name_mm" name="name_mm" value="{{ old('name_mm') }}">
                                    @error('name_mm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="class_level" class="form-label">{{ __('Class Level') }} *</label>
                                    <select class="form-select @error('class_level') is-invalid @enderror" 
                                            id="class_level" name="class_level" required>
                                        <option value="">{{ __('Select Class Level') }}</option>
                                        <option value="Grade 1" {{ old('class_level') == 'Grade 1' ? 'selected' : '' }}>{{ __('Grade 1') }}</option>
                                        <option value="Grade 2" {{ old('class_level') == 'Grade 2' ? 'selected' : '' }}>{{ __('Grade 2') }}</option>
                                        <option value="Grade 3" {{ old('class_level') == 'Grade 3' ? 'selected' : '' }}>{{ __('Grade 3') }}</option>
                                        <option value="Grade 4" {{ old('class_level') == 'Grade 4' ? 'selected' : '' }}>{{ __('Grade 4') }}</option>
                                        <option value="Grade 5" {{ old('class_level') == 'Grade 5' ? 'selected' : '' }}>{{ __('Grade 5') }}</option>
                                        <option value="Grade 6" {{ old('class_level') == 'Grade 6' ? 'selected' : '' }}>{{ __('Grade 6') }}</option>
                                        <option value="Grade 7" {{ old('class_level') == 'Grade 7' ? 'selected' : '' }}>{{ __('Grade 7') }}</option>
                                        <option value="Grade 8" {{ old('class_level') == 'Grade 8' ? 'selected' : '' }}>{{ __('Grade 8') }}</option>
                                        <option value="Grade 9" {{ old('class_level') == 'Grade 9' ? 'selected' : '' }}>{{ __('Grade 9') }}</option>
                                        <option value="Grade 10" {{ old('class_level') == 'Grade 10' ? 'selected' : '' }}>{{ __('Grade 10') }}</option>
                                        <option value="Grade 1-5" {{ old('class_level') == 'Grade 1-5' ? 'selected' : '' }}>Grade 1-5</option>
                                        <option value="Grade 6-10" {{ old('class_level') == 'Grade 6-10' ? 'selected' : '' }}>Grade 6-10</option>
                                        <option value="All Grades" {{ old('class_level') == 'All Grades' ? 'selected' : '' }}>All Grades</option>
                                    </select>
                                    @error('class_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="color" class="form-label">{{ __('Color') }} *</label>
                                    <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                           id="color" name="color" value="{{ old('color', '#ff6b6b') }}" required>
                                    @error('color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="description" class="form-label">{{ __('Description') }} (English)</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="description_mm" class="form-label">{{ __('Description') }} (Myanmar)</label>
                                    <textarea class="form-control @error('description_mm') is-invalid @enderror" 
                                              id="description_mm" name="description_mm" rows="3">{{ old('description_mm') }}</textarea>
                                    @error('description_mm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('Active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-game">
                                    <i class="fas fa-save me-2"></i>{{ __('Create Category') }}
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