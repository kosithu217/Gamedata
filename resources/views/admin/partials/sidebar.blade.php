<div class="admin-sidebar p-4">
    <h5 class="fw-bold mb-4">
        <i class="fas fa-cog me-2"></i>{{ __('Admin Panel') }}
    </h5>
    
    <ul class="nav nav-pills flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
               href="{{ route('admin.dashboard') }}">
                <i class="fas fa-tachometer-alt me-2"></i>{{ __('Dashboard') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
               href="{{ route('admin.categories.index') }}">
                <i class="fas fa-tags me-2"></i>{{ __('Categories') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.games.*') ? 'active' : '' }}" 
               href="{{ route('admin.games.index') }}">
                <i class="fas fa-gamepad me-2"></i>{{ __('Games') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.blog-posts.*') ? 'active' : '' }}" 
               href="{{ route('admin.blog-posts.index') }}">
                <i class="fas fa-blog me-2"></i>{{ __('Blog Posts') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
               href="{{ route('admin.users.index') }}">
                <i class="fas fa-users me-2"></i>{{ __('Users') }}
            </a>
        </li>
        <li class="nav-item mt-3">
            <a class="nav-link text-muted" href="{{ route('home') }}">
                <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Site') }}
            </a>
        </li>
    </ul>
</div>

<style>
    .nav-pills .nav-link {
        border-radius: 10px;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .nav-pills .nav-link:hover {
        background: var(--primary-color);
        color: white;
    }
    
    .nav-pills .nav-link.active {
        background: var(--primary-color);
    }
</style>