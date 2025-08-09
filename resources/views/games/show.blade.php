@extends('layouts.app')

@section('title', $game->title . ' - Game World')

@push('styles')
<style>
    .game-player-container {
        background: #B8B2B0;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(184,178,176,0.3);
        margin-bottom: 2rem;
        border: 1px solid #A8A2A0;
    }

    .game-player-header {
        background: rgba(184,178,176,0.95);
        backdrop-filter: blur(10px);
        padding: 15px;
        color: #333;
        display: flex;
        justify-content: between;
        align-items: center;
        border-bottom: 1px solid #A8A2A0;
    }

    #gameContainer {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #B8B2B0;
        min-height: {{ $game->height + 100 }}px;
        padding: 20px;
    }

    .loading-spinner, .error-message {
        color: #333;
        text-align: center;
        padding: 20px;
    }

    .loading-spinner .spinner-border {
        color: var(--primary-color) !important;
    }

    .game-controls {
        background: rgba(184,178,176,0.3);
        padding: 10px;
        text-align: center;
        color: #333;
        border-top: 1px solid #A8A2A0;
    }

    .btn-fullscreen {
        background: var(--primary-color);
        border: 1px solid var(--primary-color);
        color: white;
        font-size: 0.875rem;
    }

    .btn-fullscreen:hover {
        background: var(--secondary-color);
        border-color: var(--secondary-color);
        color: white;
    }

    .game-info-section {
        margin-top: 2rem;
    }

    /* Override any black backgrounds from Ruffle player */
    #gameContainer ruffle-player {
        background: #B8B2B0 !important;
    }

    #gameContainer canvas {
        background: #B8B2B0 !important;
    }

    /* Ensure the game area has proper styling */
    #gameContainer ruffle-embed {
        background: #B8B2B0 !important;
    }

    /* Additional override for any nested elements */
    #gameContainer * {
        background-color: #B8B2B0 !important;
    }

    /* But allow the actual game content to have its own background */
    #gameContainer ruffle-player canvas {
        background: #B8B2B0 !important;
    }

    /* Additional styling for better appearance */
    .game-player-container {
        box-shadow: 0 10px 30px rgba(184,178,176,0.4);
    }

    /* Ensure loading text is visible on light gray background */
    .loading-spinner {
        color: #333 !important;
    }

    .loading-spinner .text-light {
        color: #333 !important;
    }
</style>
@endpush

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Game Player Section -->
        <div class="col-lg-8">
            <!-- Game Header -->
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="fw-bold">{{ $game->title }}</h1>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="badge" style="background-color: {{ $game->category->color }}">
                            {{ $game->category->name }}
                        </span>
                        @if($game->is_featured)
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-star me-1"></i>{{ __('Featured') }}
                        </span>
                        @endif
                    </div>
                    <div class="text-muted">
                        <i class="fas fa-play me-1"></i>{{ $game->plays_count }} {{ __('plays') }}
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button id="fullscreenBtn" class="btn btn-fullscreen btn-sm">
                        <i class="fas fa-expand me-1"></i>{{ __('Fullscreen') }}
                    </button>
                    <a href="{{ route('games.play', $game->slug) }}" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-external-link-alt me-1"></i>{{ __('Full Page') }}
                    </a>
                </div>
            </div>

            <!-- Game Player -->
            <div class="game-player-container">
                <div id="gameContainer">
                    <div class="loading-spinner">
                        <div class="spinner-border text-light mb-3" role="status">
                            <span class="visually-hidden">{{ __('Loading...') }}</span>
                        </div>
                        <p>{{ __('Loading game...') }}</p>
                        <small class="text-muted">{{ __('This may take a few moments') }}</small>
                    </div>
                </div>
                
                <!-- <div class="game-controls">
                    <small class="text-white-50">
                        {{ __('Use your mouse and keyboard to play. Click fullscreen for better experience.') }}
                    </small>
                </div> -->
            </div>

            <!-- Game Info Section -->
            <div class="game-info-section">
                <div class="card card-game">
                    <div class="card-body">
                        @if($game->description)
                        <div class="mb-4">
                            <h5>{{ __('About This Game') }}</h5>
                            <p class="text-muted">{{ $game->description }}</p>
                        </div>
                        @endif
                        
                        <!-- <div class="row">
                            <div class="col-md-6">
                                <h6>{{ __('Game Details') }}</h6>
                                <ul class="list-unstyled">
                                    <li><strong>{{ __('Category') }}:</strong> {{ $game->category->name }}</li>
                                    <li><strong>{{ __('Class Level') }}:</strong> {{ $game->category->class_level }}</li>
                                    <li><strong>{{ __('Dimensions') }}:</strong> {{ $game->width }}x{{ $game->height }}</li>
                                    <li><strong>{{ __('Total Plays') }}:</strong> {{ $game->plays_count }}</li>
                                </ul>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Game Status -->
            <div class="card card-game mb-4">
                <div class="card-body text-center">
                    <h5 class="card-title">{{ __('Game Status') }}</h5>
                    <div id="gameStatus" class="mb-3">
                        <span class="badge bg-info">{{ __('Loading...') }}</span>
                    </div>
                    <p class="card-text text-muted small">{{ __('The game is loading automatically. No need to click any buttons!') }}</p>
                </div>
            </div>

            @if($game->thumbnail)
            <!-- Game Thumbnail -->
            <div class="card card-game mb-4">
                <div class="card-body">
                    <h6 class="card-title">{{ __('Game Preview') }}</h6>
                    <img src="{{ asset('storage/' . $game->thumbnail) }}" 
                         class="img-fluid rounded" alt="{{ $game->title }}"
                         style="width: 100%; height: 200px; object-fit: cover;">
                </div>
            </div>
            @endif
            
            <!-- Navigation -->
            <div class="card card-game">
                <div class="card-body">
                    <h6 class="card-title">{{ __('Navigation') }}</h6>
                    <div class="d-grid gap-2">
                        <a href="/" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i>{{ __('Back to Games') }}
                        </a>
                        <a href="{{ route('games.index', ['category' => $game->category->slug]) }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-2"></i>{{ __('More from') }} {{ $game->category->name }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Load Ruffle Player -->
<script src="{{ asset('js/ruffle.js') }}"></script>
<script src="{{ asset('js/core.ruffle.edefc3c47ead559992c5.js') }}"></script>
<script src="{{ asset('js/core.ruffle.1dd0cea78253a60e4f1e.js') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const gameContainer = document.getElementById('gameContainer');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const gameStatus = document.getElementById('gameStatus');
    const swfUrl = "{{ asset('storage/' . $game->swf_file_path) }}";

    // Update game status
    function updateStatus(status, type = 'info') {
        const statusClasses = {
            'info': 'bg-info',
            'success': 'bg-success', 
            'warning': 'bg-warning text-dark',
            'error': 'bg-danger'
        };
        gameStatus.innerHTML = `<span class="badge ${statusClasses[type]}">${status}</span>`;
    }

    // Debug information
    console.log('Game Debug Info:');
    console.log('- Game Title:', "{{ $game->title }}");
    console.log('- SWF Path:', "{{ $game->swf_file_path }}");
    console.log('- Full SWF URL:', swfUrl);
    console.log('- Game Dimensions:', "{{ $game->width }}x{{ $game->height }}");

    // Check if SWF file is accessible
    async function checkSwfFile() {
        try {
            updateStatus('{{ __("Checking game file...") }}', 'info');
            const response = await fetch(swfUrl, { method: 'HEAD', cache: 'no-store' });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            console.log('✅ SWF file is accessible');
            updateStatus('{{ __("Game file found") }}', 'success');
            return true;
        } catch (error) {
            console.error('❌ SWF file accessibility error:', error);
            updateStatus('{{ __("Game file error") }}', 'error');
            showError('Game File Error', 'The game file could not be loaded. It may not exist or the server is unreachable.');
            return false;
        }
    }

    // Initialize Ruffle player
    async function initializeRuffle() {
        try {
            updateStatus('{{ __("Initializing player...") }}', 'info');
            console.log('🔄 Waiting for Ruffle to load...');
            
            // Wait for Ruffle to load (max 10 seconds)
            let attempts = 0;
            while (typeof window.RufflePlayer === 'undefined' && attempts < 100) {
                await new Promise(resolve => setTimeout(resolve, 100));
                attempts++;
            }

            if (typeof window.RufflePlayer === 'undefined') {
                throw new Error('Ruffle player failed to load after 10 seconds');
            }

            console.log('✅ Ruffle loaded successfully');

            // Set global Ruffle configuration
            window.RufflePlayer.config = {
                "autoplay": "on",
                "unmuteOverlay": "visible", 
                "backgroundColor": "#B8B2B0",
                "wmode": "transparent",
                "logLevel": "warn",
                "letterbox": "on",
                "forceAlign": false,
                "scale": "showAll",
                "quality": "high",
                "allowScriptAccess": "sameDomain"
            };

            // Get Ruffle instance and create player
            const ruffle = window.RufflePlayer.newest();
            if (!ruffle) {
                throw new Error('Failed to get Ruffle instance');
            }

            const player = ruffle.createPlayer();
            if (!player) {
                throw new Error('Failed to create Ruffle player instance');
            }

            console.log('✅ Ruffle player created');
            updateStatus('{{ __("Player created") }}', 'success');

            // Set player styles
            player.style.width = "{{ $game->width }}px";
            player.style.height = "{{ $game->height }}px";
            player.style.maxWidth = "100%";
            player.style.maxHeight = "100%";
            player.style.display = "block";
            player.style.margin = "0 auto";
            player.style.backgroundColor = "#B8B2B0";

            // Clear the loading content and add player to container
            gameContainer.innerHTML = '';
            gameContainer.appendChild(player);

            console.log('✅ Player added to container');

            // Add event listeners
            player.addEventListener('loadeddata', () => {
                console.log('✅ Game loaded successfully!');
                updateStatus('{{ __("Game ready!") }}', 'success');
            });

            player.addEventListener('loadedmetadata', () => {
                console.log('✅ Game metadata loaded');
                updateStatus('{{ __("Loading game...") }}', 'info');
            });

            player.addEventListener('error', (e) => {
                console.error('❌ Ruffle player error:', e);
                updateStatus('{{ __("Game error") }}', 'error');
                showError('Game Loading Error', 'Failed to load the game. The file may be corrupted or incompatible.');
            });

            player.addEventListener('panic', (e) => {
                console.error('❌ Ruffle panic:', e);
                updateStatus('{{ __("Game crashed") }}', 'error');
                showError('Game Panic Error', 'The game encountered a critical error and cannot continue.');
            });

            // Load the SWF file
            console.log('🔄 Loading SWF file:', swfUrl);
            updateStatus('{{ __("Loading game...") }}', 'info');
            
            try {
                await player.load(swfUrl);
                console.log('✅ SWF load command sent');
                
                // Increment play count via API
                fetch(`{{ route('games.play', $game->slug) }}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                }).catch(error => console.log('Play count update failed:', error));
                
            } catch (loadError) {
                console.error('❌ SWF load error:', loadError);
                console.log('🔄 Trying alternative loading method...');
                player.src = swfUrl;
            }

        } catch (error) {
            console.error('❌ Ruffle initialization error:', error);
            updateStatus('{{ __("Initialization failed") }}', 'error');
            showError('Initialization Error', 'Failed to initialize the game player: ' + error.message);
        }
    }

    // Show error message to user
    function showError(title, message) {
        gameContainer.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                <h5 class="text-dark">${title}</h5>
                <p class="text-muted">${message}</p>
                <div class="mt-3">
                    <button onclick="location.reload()" class="btn btn-warning">
                        <i class="fas fa-redo me-1"></i>{{ __('Retry') }}
                    </button>
                    <a href="{{ route('games.play', $game->slug) }}" class="btn btn-primary ms-2">
                        <i class="fas fa-external-link-alt me-1"></i>{{ __('Full Page Mode') }}
                    </a>
                </div>
            </div>
        `;
    }

    // Fullscreen functionality
    fullscreenBtn.addEventListener('click', async function() {
        try {
            if (!document.fullscreenElement) {
                await gameContainer.requestFullscreen();
                fullscreenBtn.innerHTML = '<i class="fas fa-compress me-1"></i>{{ __("Exit Fullscreen") }}';
            } else {
                await document.exitFullscreen();
                fullscreenBtn.innerHTML = '<i class="fas fa-expand me-1"></i>{{ __("Fullscreen") }}';
            }
        } catch (err) {
            console.error('Fullscreen error:', err);
            alert('{{ __("Unable to toggle fullscreen mode. Please try pressing F11.") }}');
        }
    });

    // Handle fullscreen change
    document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) {
            fullscreenBtn.innerHTML = '<i class="fas fa-expand me-1"></i>{{ __("Fullscreen") }}';
        }
    });

    // Auto-start the game loading process
    updateStatus('{{ __("Starting...") }}', 'info');
    if (await checkSwfFile()) {
        await initializeRuffle();
    }
});
</script>
@endpush