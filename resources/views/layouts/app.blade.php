<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', __('Edu Game Kabar - Fun Learning Games'))</title>
    
    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="shortcut icon" type="image/png" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <meta name="msapplication-TileImage" content="{{ asset('image/logo-6ZHKaEM-.png') }}">
    <meta name="msapplication-TileColor" content="#ff6b6b">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet" />
    
    <!-- Bootstrap CSS -->
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet" />    <!-- Font Awesome -->
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #ff6b6b;
            --secondary-color: #4ecdc4;
            --accent-color: #45b7d1;
            --warning-color: #f9ca24;
            --success-color: #6c5ce7;
            --bg-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
        }
        
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            min-height: 90px;
            padding: 1rem 0;
        }
        
        .navbar-brand {
            font-weight: 700;
            color: var(--primary-color) !important;
            font-size: 1.5rem;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 20px;
            margin: 0 5px;
            padding: 8px 16px !important;
        }
        
        .nav-link:hover {
            background: var(--primary-color);
            color: white !important;
            transform: translateY(-2px);
        }
        
        .btn-game {
            background: linear-gradient(45deg, var(--primary-color), var(--secondary-color));
            border: none;
            border-radius: 25px;
            padding: 12px 30px;
            font-weight: 600;
            color: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }
        
        .btn-game:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.3);
            color: white;
        }
        
        .card-game {
            border: none;
            border-radius: 20px;
            overflow: hidden;
            transition: all 0.3s ease;
            background: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        
        .card-game:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        
        .language-switcher {
            display: flex;
            gap: 10px;
        }
        
        .language-switcher a {
            padding: 5px 10px;
            border-radius: 15px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        
        .language-switcher a.active {
            background: var(--accent-color);
            color: white;
        }
        
        .footer {
            background: rgba(0,0,0,0.8);
            color: white;
            padding: 40px 0;
            margin-top: 50px;
        }
        
        .alert {
            border-radius: 15px;
            border: none;
        }
        
        /* Main content positioning */
        .main-content {
            margin-top: 90px;
        }
        
        /* Navbar responsive adjustments */
        @media (max-width: 768px) {
            .navbar {
                min-height: 70px;
                padding: 0.5rem 0;
            }
            
            .navbar-brand {
                font-size: 1.3rem;
            }
            
            .main-content {
                margin-top: 70px;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('image/logo-6ZHKaEM-.png') }}" alt="{{ __('edu game kabar Logo') }}" height="30" class="me-2">{{ __('Edu Game Kabar') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-home me-1"></i>{{ trans('messages.Home') }}
                        </a>
                    </li>
                    @auth
                    @if(Auth::user()->isStudent())
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('student.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-1"></i>{{ trans('messages.Dashboard') }}
                        </a>
                    </li>
                    @endif
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('games.index') }}">
                            <i class="fas fa-gamepad me-1"></i>{{ trans('messages.Games') }}
                        </a>
                    </li>
                    @endauth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('blog.index') }}">
                            <i class="fas fa-blog me-1"></i>{{ trans('messages.Blog') }}
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center">
                    <!-- Language Switcher -->
                    <div class="language-switcher me-3">
                        <a href="{{ route('language.switch', 'en') }}" 
                           class="{{ app()->getLocale() == 'en' ? 'active' : '' }}">EN</a>
                        <a href="{{ route('language.switch', 'mm') }}" 
                           class="{{ app()->getLocale() == 'mm' ? 'active' : '' }}">မြန်မာ</a>
                    </div>
                    
                    @guest
                        <a href="{{ route('login') }}" class="btn btn-outline-primary me-2">{{ trans('messages.Login') }}</a>
                        <!-- <a href="{{ route('register') }}" class="btn btn-game">{{ __('Register') }}</a> -->
                    @else
                        <div class="dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->isAdmin())
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                    <i class="fas fa-cog me-1"></i>{{ trans('messages.Admin Panel') }}
                                </a></li>
                                @else
                                <li><a class="dropdown-item" href="{{ route('student.dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i>{{ trans('messages.My Dashboard') }}
                                </a></li>
                                @endif
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-1"></i>{{ trans('messages.Logout') }}
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endguest
                </div>
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-content">
        @if(session('success'))
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @if(session('error'))
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><img src="{{ asset('image/logo-6ZHKaEM-.png') }}" alt="{{ __('edu game kabar Logo') }}" height="30" class="me-2">{{ __('edu game kabar') }}</h5>
                    <p>{{ __('Fun and educational games for students of all levels.') }}</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p>&copy; {{ date('Y') }} {{ __('edu game kabar') }}. {{ __('All rights reserved.') }}</p>
                </div>
            </div>
        </div>
    </footer>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>