@extends('layouts.app')

@section('title', trans('messages.Edu Game Kabar- Fun Learning Games for Students'))

@push('styles')
<style>
    /* Full Screen Slider Styles */
    .fullscreen-slider {
        position: relative;
        width: 100vw;
        height: calc(100vh - 90px);
        margin-left: calc(-50vw + 50%);
        margin-top: -90px;
        overflow: hidden;
        z-index: 1;
        /* Allow normal scrolling behavior */
        touch-action: auto;
    }
    
    /* Mobile slider adjustments */
    @media (max-width: 768px) {
        .fullscreen-slider {
            height: calc(100vh - 70px);
            margin-top: -70px;
            /* Allow scrolling on mobile */
            touch-action: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        /* Ensure slider doesn't block scrolling */
        .slider-container {
            touch-action: auto;
        }
        
        .slide {
            touch-action: auto;
        }
        
        /* Disable slider interactions on mobile for single slide */
        .slider-nav {
            display: none !important;
        }
        
        .slider-dots {
            display: none !important;
        }
        
        /* Ensure content is scrollable */
        body {
            overflow-x: hidden;
            overflow-y: auto;
        }
    }
    
    /* Demo Games Section Styles */
    .demo-game-card {
        animation: fadeInUp 0.6s ease-out;
        animation-fill-mode: both;
    }
    
    .demo-game-card:nth-child(1) { animation-delay: 0.1s; }
    .demo-game-card:nth-child(2) { animation-delay: 0.2s; }
    .demo-game-card:nth-child(3) { animation-delay: 0.3s; }
    .demo-game-card:nth-child(4) { animation-delay: 0.4s; }
    .demo-game-card:nth-child(5) { animation-delay: 0.5s; }
    .demo-game-card:nth-child(6) { animation-delay: 0.6s; }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .demo-game-card .card-game {
        position: relative;
        overflow: hidden;
    }
    
    .demo-game-card .card-game::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #ff6b6b, #4ecdc4, #45b7d1);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .demo-game-card .card-game:hover::before {
        opacity: 1;
    }
    
    .demo-game-card .btn-game {
        background: linear-gradient(45deg, #28a745, #20c997);
        border: none;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .demo-game-card .btn-game:hover {
        background: linear-gradient(45deg, #218838, #1ea080);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(40, 167, 69, 0.3);
    }
    
    .slider-container {
        position: relative;
        width: 100%;
        height: 100%;
    }
    
    .slider-track {
        display: flex;
        width: 500%;
        height: 100%;
        transition: transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    .slide {
        width: 20%;
        height: 100%;
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
    }
    
    .slide:nth-child(1) {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .slide:nth-child(2) {
        background: linear-gradient(135deg, #ff6b6b 0%, #4ecdc4 100%);
    }
    
    .slide:nth-child(3) {
        background: linear-gradient(135deg, #4ecdc4 0%, #45b7d1 100%);
    }
    
    .slide:nth-child(4) {
        background: linear-gradient(135deg, #f9ca24 0%, #f0932b 100%);
    }
    
    .slide:nth-child(5) {
        background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 100%);
    }
    
    .slide-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.1);
        z-index: 2;
    }
    
    /* GIF Container Styles */
    .slide-gif-container {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 1;
        overflow: hidden;
    }
    
    .slide-gif {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        opacity: 1;
        filter: none;
        animation: gifPulse 4s ease-in-out infinite;
    }
    
    @keyframes gifPulse {
        0%, 100% { 
            opacity: 1; 
            transform: scale(1);
            filter: brightness(1);
        }
        50% { 
            opacity: 1; 
            transform: scale(1.01);
            filter: brightness(1.05);
        }
    }
    
    .slide-icon-container {
        position: relative;
        z-index: 4;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 2rem;
    }
    
    .slide-content {
        position: relative;
        z-index: 3;
        text-align: center;
        color: white;
        max-width: 800px;
        padding: 0 2rem;
    }
    
    .slide-icon {
        font-size: 120px;
        margin-bottom: 2rem;
        opacity: 0.9;
        animation: float 4s ease-in-out infinite;
        filter: drop-shadow(0 10px 30px rgba(0,0,0,0.5));
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        25% { transform: translateY(-15px) rotate(2deg); }
        50% { transform: translateY(-30px) rotate(0deg); }
        75% { transform: translateY(-15px) rotate(-2deg); }
    }
    
    .slide-title {
        font-size: 4rem;
        font-weight: 900;
        margin-bottom: 1.5rem;
        text-shadow: 0 4px 8px rgba(0,0,0,0.7);
        line-height: 1.1;
    }
    
    .slide-subtitle {
        font-size: 1.5rem;
        margin-bottom: 2rem;
        opacity: 0.9;
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        font-weight: 300;
    }
    
    .slide-buttons {
        display: flex;
        gap: 1.5rem;
        justify-content: center;
        flex-wrap: wrap;
    }
    
    .slide-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.4);
        color: white;
        border-radius: 50px;
        padding: 1rem 2.5rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.4s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        backdrop-filter: blur(10px);
        position: relative;
        overflow: hidden;
    }
    
    .slide-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
        transition: left 0.6s ease;
    }
    
    .slide-btn:hover::before {
        left: 100%;
    }
    
    .slide-btn:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.6);
        color: white;
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .slide-btn-primary {
        background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
        border-color: transparent;
    }
    
    .slide-btn-primary:hover {
        background: linear-gradient(45deg, #ff5252, #26d0ce);
        transform: translateY(-3px) scale(1.05);
    }
    
    /* Navigation Controls */
    .slider-nav {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background: rgba(255,255,255,0.1);
        border: 2px solid rgba(255,255,255,0.2);
        color: white;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.4s ease;
        z-index: 10;
        backdrop-filter: blur(10px);
    }
    
    .slider-nav:hover {
        background: rgba(255,255,255,0.2);
        border-color: rgba(255,255,255,0.4);
        transform: translateY(-50%) scale(1.1);
        box-shadow: 0 5px 20px rgba(0,0,0,0.3);
    }
    
    .slider-nav.prev {
        left: 30px;
    }
    
    .slider-nav.next {
        right: 30px;
    }
    
    .slider-nav i {
        font-size: 1.5rem;
    }
    
    /* Dots Indicator */
    .slider-dots {
        position: absolute;
        bottom: 40px;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 15px;
        z-index: 10;
    }
    
    .dot {
        width: 15px;
        height: 15px;
        border-radius: 50%;
        background: rgba(255,255,255,0.3);
        cursor: pointer;
        transition: all 0.4s ease;
        border: 2px solid rgba(255,255,255,0.5);
        position: relative;
    }
    
    .dot::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 0;
        height: 0;
        background: white;
        border-radius: 50%;
        transition: all 0.4s ease;
    }
    
    .dot.active::before {
        width: 8px;
        height: 8px;
    }
    
    .dot.active {
        background: rgba(255,255,255,0.6);
        transform: scale(1.3);
        box-shadow: 0 0 15px rgba(255,255,255,0.5);
    }
    
    .dot:hover {
        background: rgba(255,255,255,0.5);
        transform: scale(1.1);
    }
    
    /* Scroll Indicator */
    .scroll-indicator {
        position: absolute;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%);
        color: rgba(255,255,255,0.7);
        font-size: 0.9rem;
        z-index: 10;
        animation: bounce 2s infinite;
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
        40% { transform: translateX(-50%) translateY(-10px); }
        60% { transform: translateX(-50%) translateY(-5px); }
    }
    
    /* Mobile Responsive */
    @media (max-width: 768px) {
        .slide-title {
            font-size: 2.5rem;
        }
        
        .slide-subtitle {
            font-size: 1.2rem;
        }
        
        .slide-icon {
            font-size: 80px;
            margin-bottom: 1.5rem;
        }
        
        .slide-buttons {
            flex-direction: column;
            align-items: center;
        }
        
        .slide-btn {
            width: 80%;
            justify-content: center;
            padding: 0.8rem 2rem;
            font-size: 1rem;
        }
        
        .slider-nav {
            width: 50px;
            height: 50px;
        }
        
        .slider-nav.prev {
            left: 15px;
        }
        
        .slider-nav.next {
            right: 15px;
        }
        
        .slider-nav i {
            font-size: 1.2rem;
        }
        
        .dot {
            width: 12px;
            height: 12px;
        }
        
        .dot.active::before {
            width: 6px;
            height: 6px;
        }
        
        /* Mobile GIF Styles */
        .slide-gif {
            opacity: 0.7;
            filter: none;
        }
        
        .slide-overlay {
            background: rgba(0,0,0,0.2);
        }
    }
    
    @media (max-width: 576px) {
        .slide-title {
            font-size: 2rem;
        }
        
        .slide-subtitle {
            font-size: 1rem;
        }
        
        .slide-icon {
            font-size: 60px;
        }
        
        .slide-content {
            padding: 0 1rem;
        }
        
        /* Small Mobile GIF Styles */
        .slide-gif {
            opacity: 0.6;
            filter: none;
        }
        
        .slide-overlay {
            background: rgba(0,0,0,0.3);
        }
    }
    
    /* GIF Hover Effects */
    .slide:hover .slide-gif {
        opacity: 0.95;
        filter: brightness(1.2);
        transform: scale(1.05);
        transition: all 0.6s ease;
    }
    
    /* GIF Loading Animation */
    .slide-gif {
        transition: all 0.8s ease;
    }
    
    .slide.active .slide-gif {
        animation: gifEntrance 1s ease-out;
    }
    
    @keyframes gifEntrance {
        0% {
            opacity: 0;
            transform: scale(1.1);
            filter: brightness(0.5);
        }
        100% {
            opacity: 0.8;
            transform: scale(1);
            filter: brightness(1);
        }
    }
    
    /* Alternative GIF positioning for different slides */
    .slide:nth-child(1) .slide-gif {
        object-position: center center;
    }
    
    /* Clean GIF display without overlay */
    .slide-gif-container::after {
        display: none;
    }
    
    /* Slide Entrance Animations */
    .slide-content > * {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
    }
    
    .slide.active .slide-content > * {
        opacity: 1;
        transform: translateY(0);
    }
    
    .slide.active .slide-content .slide-icon {
        transition-delay: 0.2s;
    }
    
    .slide.active .slide-content .slide-title {
        transition-delay: 0.4s;
    }
    
    .slide.active .slide-content .slide-subtitle {
        transition-delay: 0.6s;
    }
    
    .slide.active .slide-content .slide-buttons {
        transition-delay: 0.8s;
    }
</style>
@endpush

@section('content')<br><br>
<!-- Full Screen Hero Slider -->
<section class="fullscreen-slider" style="display:none">
    <div class="slider-container" id="heroSlider">
        <div class="slider-track" id="sliderTrack">
            <!-- Slide 1: Clean GIF Background -->
            <div class="slide active">
                <!-- Animated GIF Background -->
                <div class="slide-gif-container">
                    <img src="{{ asset('image/2.gif') }}" alt="{{ trans('messages.Game Animation') }}" class="slide-gif">
                </div>
            </div>
            
            <!-- Slide 2: Educational Games -->
            <!-- <div class="slide">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <i class="fas fa-graduation-cap slide-icon"></i>
                    <h1 class="slide-title">{{ __('Educational Excellence') }}</h1>
                    <p class="slide-subtitle">{{ __('Games designed by educators for all grade levels. From kindergarten to high school.') }}</p>
                    <div class="slide-buttons">
                        <a href="{{ route('games.index') }}" class="slide-btn slide-btn-primary">
                            <i class="fas fa-book-open"></i>{{ __('Explore Learning') }}
                        </a>
                        @guest
                        <a href="{{ route('register') }}" class="slide-btn">
                            <i class="fas fa-user-plus"></i>{{ __('Get Started') }}
                        </a>
                        @endguest
                    </div>
                </div>
            </div> -->
            
            <!-- Slide 3: Track Progress -->
            <!-- <div class="slide">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <i class="fas fa-chart-line slide-icon"></i>
                    <h1 class="slide-title">{{ __('Track Your Progress') }}</h1>
                    <p class="slide-subtitle">{{ __('Monitor your learning journey with detailed analytics and achievements.') }}</p>
                    <div class="slide-buttons">
                        @auth
                            <a href="{{ route('student.dashboard') }}" class="slide-btn slide-btn-primary">
                                <i class="fas fa-tachometer-alt"></i>{{ __('View Dashboard') }}
                            </a>
                        @else
                            <a href="{{ route('register') }}" class="slide-btn slide-btn-primary">
                                <i class="fas fa-chart-bar"></i>{{ __('Start Tracking') }}
                            </a>
                        @endauth
                        <a href="{{ route('blog.index') }}" class="slide-btn">
                            <i class="fas fa-blog"></i>{{ __('Learn More') }}
                        </a>
                    </div>
                </div>
            </div> -->
            
            <!-- Slide 4: Community -->
            <!-- <div class="slide">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <i class="fas fa-users slide-icon"></i>
                    <h1 class="slide-title">{{ __('Join Our Community') }}</h1>
                    <p class="slide-subtitle">{{ __('Connect with students and teachers worldwide. Share experiences and learn together.') }}</p>
                    <div class="slide-buttons">
                        @guest
                            <a href="{{ route('register') }}" class="slide-btn slide-btn-primary">
                                <i class="fas fa-user-friends"></i>{{ __('Join Community') }}
                            </a>
                            <a href="{{ route('login') }}" class="slide-btn">
                                <i class="fas fa-sign-in-alt"></i>{{ __('Sign In') }}
                            </a>
                        @else
                            <a href="{{ route('games.index') }}" class="slide-btn slide-btn-primary">
                                <i class="fas fa-gamepad"></i>{{ __('Play Together') }}
                            </a>
                        @endguest
                    </div>
                </div>
            </div> -->
            
            <!-- Slide 5: Premium Features -->
            <!-- <div class="slide">
                <div class="slide-overlay"></div>
                <div class="slide-content">
                    <i class="fas fa-crown slide-icon"></i>
                    <h1 class="slide-title">{{ __('Premium Experience') }}</h1>
                    <p class="slide-subtitle">{{ __('Unlock exclusive games, advanced features, and personalized learning paths.') }}</p>
                    <div class="slide-buttons">
                        @guest
                            <a href="{{ route('register') }}" class="slide-btn slide-btn-primary">
                                <i class="fas fa-star"></i>{{ __('Get Premium') }}
                            </a>
                            <a href="{{ route('games.index') }}" class="slide-btn">
                                <i class="fas fa-eye"></i>{{ __('Preview Games') }}
                            </a>
                        @else
                            <a href="{{ route('student.dashboard') }}" class="slide-btn slide-btn-primary">
                                <i class="fas fa-crown"></i>{{ __('Access Premium') }}
                            </a>
                        @endguest
                    </div>
                </div>
            </div> -->
        </div>
        
        <!-- Navigation Arrows -->
        <button class="slider-nav prev" id="prevBtn">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-nav next" id="nextBtn">
            <i class="fas fa-chevron-right"></i>
        </button>
        
        <!-- Dots Indicator -->
       
        
        <!-- Scroll Indicator -->
        <div class="scroll-indicator">
            <i class="fas fa-chevron-down"></i>
            <div>{{ __('Scroll to explore') }}</div>
        </div>
    </div>
</section>

@if($featuredGames->count() > 0)
<!-- Featured Games Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            @guest
                <h2 class="fw-bold text-white">{{ __('Demo Games') }}</h2>
                <p class="text-white-50">{{ __('Try these 6 sample games - No login required!') }}</p>
                <div class="alert alert-success d-inline-block">
                    <i class="fas fa-play-circle me-2"></i>
                    {{ __('Play 6 demo games instantly! Register to access 50+ educational games!') }}
                </div>
            @else
                <h2 class="fw-bold text-white">{{ __('Featured Games') }}</h2>
                <p class="text-white-50">{{ __('Popular games loved by students') }}</p>
            @endguest
        </div>
        
        <div class="row">
            @foreach($featuredGames as $game)
            <div class="col-lg-4 col-md-6 mb-4 demo-game-card">
                <div class="card card-game h-100">
                    @if($game->thumbnail)
                        <img src="{{ asset('storage/' . $game->thumbnail) }}" class="card-img-top" alt="{{ $game->title }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 200px;">
                            <i class="fas fa-gamepad fa-3x text-muted"></i>
                        </div>
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $game->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($game->description, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="fas fa-play me-1"></i>{{ $game->plays_count }} {{ __('plays') }}
                            </small>
                            <span class="badge" style="background-color: {{ $game->category->color }}">
                                {{ $game->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        @auth
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-game w-100">
                                <i class="fas fa-play me-2"></i>{{ __('Play Now') }}
                            </a>
                        @else
                            <!-- Demo games can be played without login -->
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-game w-100">
                                <i class="fas fa-play me-2"></i>{{ __('Play Demo') }}
                            </a>
                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>{{ __('No login required') }}
                            </small>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @auth
        <div class="text-center mt-4">
            <a href="{{ route('games.index') }}" class="btn btn-outline-light btn-lg">
                {{ __('View All Games') }} <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
        @else
        <!-- Registration Encouragement for Guests -->
        <div class="text-center mt-4">
            <div class="card" style="background: rgba(255,255,255,0.1); backdrop-filter: blur(10px); border: none;">
                <div class="card-body py-4">
                    <h4 class="text-white mb-3">
                        <i class="fas fa-rocket me-2"></i>{{ __('Enjoyed the Demo Games?') }}
                    </h4>
                    <p class="text-white-50 mb-4">
                        {{ __('You\'ve tried 6 demo games! Register now to unlock 50+ educational games, track your progress, and access exclusive content!') }}
                    </p>
                    
                    <div class="row text-white-50 mb-4">
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-gamepad fa-2x text-success mb-2"></i>
                            <h6>{{ __('50+ Games') }}</h6>
                            <small>{{ __('Full game library access') }}</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-chart-line fa-2x text-info mb-2"></i>
                            <h6>{{ __('Progress Tracking') }}</h6>
                            <small>{{ __('Monitor your learning') }}</small>
                        </div>
                        <div class="col-md-4 mb-3">
                            <i class="fas fa-trophy fa-2x text-warning mb-2"></i>
                            <h6>{{ __('Achievements') }}</h6>
                            <small>{{ __('Earn rewards & badges') }}</small>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-center gap-3">
                        <!-- <a href="{{ route('register') }}" class="btn btn-game btn-lg">
                            <i class="fas fa-user-plus me-2"></i>{{ __('Register Free') }}
                        </a> -->
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">
                            <i class="fas fa-sign-in-alt me-2"></i>{{ __('Login') }}
                        </a>
                    </div>
                    
                    <p class="text-white-50 mt-3 mb-0">
                        <small>{{ __('Registration is completely free and takes less than 2 minutes!') }}</small>
                    </p>
                </div>
            </div>
        </div>
        @endauth
    </div>
</section>
@endif

@if($categories->count() > 0)
<!-- Categories Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white">{{ __('Game Categories') }}</h2>
            <p class="text-white-50">{{ __('Choose games by your class level') }}</p>
        </div>
        
        <div class="row">
            @foreach($categories as $category)
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="card card-game text-center">
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="rounded-circle mx-auto d-flex align-items-center justify-content-center" 
                                 style="width: 80px; height: 80px; background-color: {{ $category->color }};">
                                <i class="fas fa-graduation-cap fa-2x text-white"></i>
                            </div>
                        </div>
                        <h5 class="card-title">{{ $category->name }}</h5>
                        <p class="card-text text-muted">{{ $category->class_level }}</p>
                        <p class="text-muted">
                            <i class="fas fa-gamepad me-1"></i>{{ $category->games_count }} {{ __('games') }}
                        </p>
                        @auth
                            <a href="{{ route('games.index', ['category' => $category->slug]) }}" class="btn btn-outline-primary">
                                {{ __('Explore') }}
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($recentPosts->count() > 0)
<!-- Recent Blog Posts -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="fw-bold text-white">{{ __('Latest News & Updates') }}</h2>
            <p class="text-white-50">{{ __('Stay updated with our latest posts') }}</p>
        </div>
        
        <div class="row">
            @foreach($recentPosts as $post)
            <div class="col-lg-4 mb-4">
                <div class="card card-game h-100">
                    @if($post->featured_image)
                        <img src="{{ asset('storage/' . $post->featured_image) }}" class="card-img-top" alt="{{ $post->title }}" style="height: 200px; object-fit: cover;">
                    @endif
                    
                    <div class="card-body">
                        <h5 class="card-title">{{ $post->title }}</h5>
                        <p class="card-text text-muted">{{ Str::limit($post->excerpt, 100) }}</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                {{ $post->published_at->format('M d, Y') }}
                            </small>
                            <span class="badge" style="background-color: {{ $post->category->color }}">
                                {{ $post->category->name }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-outline-primary w-100">
                            {{ __('Read More') }}
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <a href="{{ route('blog.index') }}" class="btn btn-outline-light btn-lg">
                {{ __('View All Posts') }} <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>
    </div>
</section>
@endif

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const slider = document.getElementById('heroSlider');
    const sliderTrack = document.getElementById('sliderTrack');
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    
    let currentSlide = 0;
    let isAutoPlaying = true;
    let autoPlayInterval;
    let touchStartX = 0;
    let touchEndX = 0;
    let isTransitioning = false;
    
    // Initialize slider
    function initSlider() {
        // If only one slide, disable slider functionality
        if (slides.length <= 1) {
            isAutoPlaying = false;
            // Hide navigation elements
            const navElements = document.querySelectorAll('.slider-nav, .slider-dots');
            navElements.forEach(el => el.style.display = 'none');
        }
        
        updateSlider();
        startAutoPlay();
        addEventListeners();
        addParallaxEffect();
    }
    
    // Update slider position and active states
    function updateSlider() {
        if (isTransitioning) return;
        
        isTransitioning = true;
        
        // Move slider track
        sliderTrack.style.transform = `translateX(-${currentSlide * 20}%)`;
        
        // Update active slide
        slides.forEach((slide, index) => {
            slide.classList.toggle('active', index === currentSlide);
        });
        
        // Update active dot
        dots.forEach((dot, index) => {
            dot.classList.toggle('active', index === currentSlide);
        });
        
        // Reset transition flag after animation
        setTimeout(() => {
            isTransitioning = false;
        }, 800);
    }
    
    // Go to specific slide
    function goToSlide(slideIndex) {
        if (isTransitioning || slideIndex === currentSlide) return;
        currentSlide = slideIndex;
        updateSlider();
        resetAutoPlay();
    }
    
    // Go to next slide
    function nextSlide() {
        if (isTransitioning || slides.length <= 1) return;
        currentSlide = (currentSlide + 1) % slides.length;
        updateSlider();
    }
    
    // Go to previous slide
    function prevSlide() {
        if (isTransitioning || slides.length <= 1) return;
        currentSlide = (currentSlide - 1 + slides.length) % slides.length;
        updateSlider();
    }
    
    // Auto play functionality
    function startAutoPlay() {
        // Only start autoplay if we have multiple slides
        if (isAutoPlaying && slides.length > 1) {
            autoPlayInterval = setInterval(nextSlide, 5000);
        }
    }
    
    function stopAutoPlay() {
        clearInterval(autoPlayInterval);
    }
    
    function resetAutoPlay() {
        stopAutoPlay();
        if (isAutoPlaying) {
            startAutoPlay();
        }
    }
    
    // Touch/Swipe support
    function handleTouchStart(e) {
        touchStartX = e.touches[0].clientX;
    }
    
    function handleTouchMove(e) {
        // Only prevent default if we have multiple slides
        if (slides.length > 1) {
            e.preventDefault();
        }
    }
    
    function handleTouchEnd(e) {
        touchEndX = e.changedTouches[0].clientX;
        handleSwipe();
    }
    
    function handleSwipe() {
        // Only handle swipe if we have multiple slides
        if (slides.length <= 1) return;
        
        const swipeThreshold = 80;
        const diff = touchStartX - touchEndX;
        
        if (Math.abs(diff) > swipeThreshold) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
            resetAutoPlay();
        }
    }
    
    // Keyboard navigation
    function handleKeyPress(e) {
        switch(e.key) {
            case 'ArrowLeft':
                prevSlide();
                resetAutoPlay();
                break;
            case 'ArrowRight':
                nextSlide();
                resetAutoPlay();
                break;
            case ' ':
                e.preventDefault();
                isAutoPlaying = !isAutoPlaying;
                if (isAutoPlaying) {
                    startAutoPlay();
                } else {
                    stopAutoPlay();
                }
                break;
            case 'Escape':
                stopAutoPlay();
                isAutoPlaying = false;
                break;
        }
    }
    
    // Parallax effect for slide content
    function addParallaxEffect() {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            
            slides.forEach(slide => {
                const slideContent = slide.querySelector('.slide-content');
                if (slideContent) {
                    slideContent.style.transform = `translateY(${rate}px)`;
                }
            });
        });
    }
    
    // Mouse wheel navigation
    function handleWheel(e) {
        // Only handle wheel navigation if we have multiple slides
        if (slides.length <= 1) return;
        
        if (Math.abs(e.deltaY) > 50) {
            e.preventDefault();
            if (e.deltaY > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
            resetAutoPlay();
        }
    }
    
    // Add all event listeners
    function addEventListeners() {
        // Navigation buttons
        nextBtn.addEventListener('click', () => {
            nextSlide();
            resetAutoPlay();
        });
        
        prevBtn.addEventListener('click', () => {
            prevSlide();
            resetAutoPlay();
        });
        
        // Dots navigation
        dots.forEach((dot, index) => {
            dot.addEventListener('click', () => {
                goToSlide(index);
            });
        });
        
        // Touch events (only if multiple slides)
        if (slides.length > 1) {
            slider.addEventListener('touchstart', handleTouchStart, { passive: true });
            slider.addEventListener('touchmove', handleTouchMove, { passive: false });
            slider.addEventListener('touchend', handleTouchEnd, { passive: true });
        }
        
        // Mouse events
        slider.addEventListener('mouseenter', stopAutoPlay);
        slider.addEventListener('mouseleave', () => {
            if (isAutoPlaying) startAutoPlay();
        });
        
        // Keyboard navigation
        document.addEventListener('keydown', handleKeyPress);
        
        // Mouse wheel navigation (only if multiple slides)
        if (slides.length > 1) {
            slider.addEventListener('wheel', handleWheel, { passive: false });
        }
        
        // Visibility API
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                stopAutoPlay();
            } else if (isAutoPlaying) {
                startAutoPlay();
            }
        });
        
        // Window focus events
        window.addEventListener('blur', stopAutoPlay);
        window.addEventListener('focus', () => {
            if (isAutoPlaying) startAutoPlay();
        });
        
        // Resize handler
        window.addEventListener('resize', () => {
            updateSlider();
        });
    }
    
    // Add smooth scroll to next section
    function addSmoothScroll() {
        const scrollIndicator = document.querySelector('.scroll-indicator');
        if (scrollIndicator) {
            scrollIndicator.addEventListener('click', () => {
                const nextSection = slider.nextElementSibling;
                if (nextSection) {
                    nextSection.scrollIntoView({ 
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        }
    }
    
    // Add loading animation
    function addLoadingAnimation() {
        slides.forEach((slide, index) => {
            slide.style.opacity = '0';
            slide.style.transform = 'scale(1.1)';
            
            setTimeout(() => {
                slide.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
                slide.style.opacity = '1';
                slide.style.transform = 'scale(1)';
            }, index * 200);
        });
    }
    
    // Add intersection observer for performance
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                if (!isAutoPlaying) {
                    isAutoPlaying = true;
                    startAutoPlay();
                }
            } else {
                stopAutoPlay();
            }
        });
    }, { threshold: 0.5 });
    
    observer.observe(slider);
    
    // Initialize everything
    addLoadingAnimation();
    addSmoothScroll();
    
    setTimeout(() => {
        initSlider();
    }, 1000);
    
    // Cleanup on page unload
    window.addEventListener('beforeunload', () => {
        stopAutoPlay();
        observer.disconnect();
    });
    
    // Add dynamic background particles (optional enhancement)
    function addBackgroundParticles() {
        slides.forEach(slide => {
            for (let i = 0; i < 20; i++) {
                const particle = document.createElement('div');
                particle.style.cssText = `
                    position: absolute;
                    width: ${Math.random() * 4 + 1}px;
                    height: ${Math.random() * 4 + 1}px;
                    background: rgba(255,255,255,0.3);
                    border-radius: 50%;
                    top: ${Math.random() * 100}%;
                    left: ${Math.random() * 100}%;
                    animation: float ${Math.random() * 6 + 4}s ease-in-out infinite;
                    animation-delay: ${Math.random() * 2}s;
                    pointer-events: none;
                `;
                slide.appendChild(particle);
            }
        });
    }
    
    // Add particles after a delay
    setTimeout(addBackgroundParticles, 2000);
});
</script>
@endpush
@endsection