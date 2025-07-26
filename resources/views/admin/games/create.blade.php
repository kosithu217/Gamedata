@extends('layouts.app')

@section('title', 'Create Game - Admin Panel')

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
                    <h2 class="fw-bold">{{ __('Create Game') }}</h2>
                    <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Games') }}
                    </a>
                </div>
                
                <div class="card card-game">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.games.store') }}" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label">{{ __('Title') }} (English) *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                           id="title" name="title" value="{{ old('title') }}" required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="title_mm" class="form-label">{{ __('Title') }} (Myanmar)</label>
                                    <input type="text" class="form-control @error('title_mm') is-invalid @enderror" 
                                           id="title_mm" name="title_mm" value="{{ old('title_mm') }}">
                                    @error('title_mm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">{{ __('Category') }} *</label>
                                <select class="form-select @error('category_id') is-invalid @enderror" 
                                        id="category_id" name="category_id" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->class_level }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                    <label for="swf_file" class="form-label">{{ __('SWF Game File') }} *</label>
                                    <input type="file" class="form-control @error('swf_file') is-invalid @enderror" 
                                           id="swf_file" name="swf_file" accept=".swf" required>
                                    <div class="form-text">{{ __('Upload .swf file (max 50MB)') }}</div>
                                    @error('swf_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="thumbnail" class="form-label">{{ __('Thumbnail Image') }}</label>
                                    <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                                           id="thumbnail" name="thumbnail" accept="image/*">
                                    <div class="form-text">{{ __('Upload image (max 5MB)') }}</div>
                                    @error('thumbnail')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="width" class="form-label">{{ __('Width') }} (px)</label>
                                    <input type="number" class="form-control @error('width') is-invalid @enderror" 
                                           id="width" name="width" value="{{ old('width', 800) }}" min="100" max="2000">
                                    @error('width')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="height" class="form-label">{{ __('Height') }} (px)</label>
                                    <input type="number" class="form-control @error('height') is-invalid @enderror" 
                                           id="height" name="height" value="{{ old('height', 600) }}" min="100" max="2000">
                                    @error('height')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label for="sort_order" class="form-label">{{ __('Sort Order') }}</label>
                                    <input type="number" class="form-control @error('sort_order') is-invalid @enderror" 
                                           id="sort_order" name="sort_order" value="{{ old('sort_order', 0) }}">
                                    @error('sort_order')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-3 mb-3">
                                    <label class="form-label">{{ __('Options') }}</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                               {{ old('is_featured') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_featured">
                                            {{ __('Featured') }}
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            {{ __('Active') }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="{{ route('admin.games.index') }}" class="btn btn-outline-secondary">
                                    {{ __('Cancel') }}
                                </a>
                                <button type="submit" class="btn btn-game">
                                    <i class="fas fa-save me-2"></i>{{ __('Create Game') }}
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