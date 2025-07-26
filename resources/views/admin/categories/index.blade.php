@extends('layouts.app')

@section('title', 'Categories - Admin Panel')

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
                    <h2 class="fw-bold">{{ __('Categories') }}</h2>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-game">
                        <i class="fas fa-plus me-2"></i>{{ __('Add Category') }}
                    </a>
                </div>
                
                @if($categories->count() > 0)
                <div class="card card-game">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Class Level') }}</th>
                                        <th>{{ __('Color') }}</th>
                                        <th>{{ __('Games Count') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Sort Order') }}</th>
                                        <th width="200">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categories as $category)
                                    <tr>
                                        <td>
                                            <strong>{{ $category->name }}</strong>
                                            @if($category->name_mm)
                                                <br><small class="text-muted">{{ $category->name_mm }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $category->class_level }}</td>
                                        <td>
                                            <span class="badge" style="background-color: {{ $category->color }}; color: white;">
                                                {{ $category->color }}
                                            </span>
                                        </td>
                                        <td>{{ $category->games_count ?? 0 }}</td>
                                        <td>
                                            @if($category->is_active)
                                                <span class="badge bg-success">{{ __('Active') }}</span>
                                            @else
                                                <span class="badge bg-secondary">{{ __('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $category->sort_order }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.categories.show', $category) }}" 
                                                   class="btn btn-sm btn-outline-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('admin.categories.destroy', $category) }}" 
                                                      class="d-inline" onsubmit="return confirm('Are you sure?')">
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
                    {{ $categories->links() }}
                </div>
                @else
                <div class="text-center py-5">
                    <div class="card card-game">
                        <div class="card-body py-5">
                            <i class="fas fa-tags fa-4x text-muted mb-3"></i>
                            <h3 class="text-muted">{{ __('No categories found') }}</h3>
                            <p class="text-muted">{{ __('Create your first category to get started.') }}</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-game">
                                <i class="fas fa-plus me-2"></i>{{ __('Add Category') }}
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
@endsection