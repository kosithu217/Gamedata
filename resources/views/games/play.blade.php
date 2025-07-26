@extends('layouts.app')

@section('title', 'Playing ' . $game->title . ' - Game World')

@push('styles')
<style>
    body {
        background: #000;
        margin: 0;
        height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .game-container {
        background: #000;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        max-width: 1200px;
        margin: auto;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .game-header {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        padding: 15px;
        color: white;
    }

    .game-controls {
        background: rgba(255,255,255,0.05);
        padding: 10px;
        text-align: center;
    }

    #gameContainer {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        background: #222;
        min-height: 600px;
    }

    .loading-spinner, .error-message {
        color: white;
        text-align: center;
        padding: 20px;
    }

    .btn-fullscreen {
        background: rgba(255,255,255,0.2);
        border: 1px solid rgba(255,255,255,0.3);
        color: white;
    }

    .btn-fullscreen:hover {
        background: rgba(255,255,255,0.3);
        color: white;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="game-container">
                <!-- Game Header -->
                <div class="game-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">{{ $game->title }}</h4>
                            <small class="text-white-50">{{ $game->category->name }}</small>
                            <small class="text-muted">
                                <a href="{{ asset('storage/' . $game->swf_file_path) }}" target="_blank" class="text-decoration-underline">
                                    Debug: SWF File
                                </a>
                            </small>
                        </div>
                        <div class="d-flex gap-2">
                            <button id="fullscreenBtn" class="btn btn-fullscreen btn-sm">
                                <i class="fas fa-expand me-1"></i>{{ __('Fullscreen') }}
                            </button>
                            <a href="{{ route('games.show', $game->slug) }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-info-circle me-1"></i>{{ __('Info') }}
                            </a>
                            <a href="{{ route('games.index') }}" class="btn btn-outline-light btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>{{ __('Back') }}
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Game Container -->
                <div id="gameContainer">
                    <div class="loading-spinner">
                        <div class="spinner-border text-light mb-3" role="status">
                            <span class="visually-hidden">{{ __('Loading...') }}</span>
                        </div>
                        <p>{{ __('Loading game...') }}</p>
                        <small class="text-muted">{{ __('This may take a few moments') }}</small>
                    </div>
                </div>

                <!-- Game Controls -->
                <div class="game-controls">
                    <small class="text-white-50">
                        {{ __('Use your mouse and keyboard to play. Press F11 for fullscreen mode.') }}
                    </small>
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
<script src="{{ asset('js/core.ruffle.1dd0cea78253a60e4f1e.js.map') }}"></script>
<script src="{{ asset('js/ruffle.js.map') }}"></script>
<script src="{{ asset('js/LICENSE_APACHE') }}"></script>
<script src="{{ asset('js/LICENSE_MIT') }}"></script>
<script src="{{ asset('js/README.md') }}"></script>

<script>
document.addEventListener('DOMContentLoaded', async function() {
    const gameContainer = document.getElementById('gameContainer');
    const fullscreenBtn = document.getElementById('fullscreenBtn');
    const swfUrl = "{{ asset('storage/' . $game->swf_file_path) }}";

    // Debug information (for developers, not shown to users)
    console.log('Game Debug Info:');
    console.log('- Game Title:', "{{ $game->title }}");
    console.log('- SWF Path in DB:', "{{ $game->swf_file_path }}");
    console.log('- Full SWF URL:', swfUrl);
    console.log('- Game Dimensions:', "{{ $game->width }}x{{ $game->height }}");

    // Check if SWF file is accessible
    async function checkSwfFile() {
        try {
            const response = await fetch(swfUrl, { method: 'HEAD', cache: 'no-store' });
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }
            console.log('✅ SWF file is accessible');
            return true;
        } catch (error) {
            console.error('❌ SWF file accessibility error:', error);
            showError('Game File Error', 'The game file could not be loaded. It may not exist or the server is unreachable.');
            return false;
        }
    }

    // Initialize Ruffle player
    async function initializeRuffle() {
        try {
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

            // Set global Ruffle configuration first
            window.RufflePlayer.config = {
                "autoplay": "on",
                "unmuteOverlay": "visible", 
                "backgroundColor": "#000000",
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

            // Set player styles
            player.style.width = "{{ $game->width }}px";
            player.style.height = "{{ $game->height }}px";
            player.style.maxWidth = "100%";
            player.style.maxHeight = "100%";
            player.style.display = "block";
            player.style.margin = "0 auto";
            player.style.backgroundColor = "#000000";

            // Clear the loading content and add player to container
            gameContainer.innerHTML = '';
            gameContainer.appendChild(player);

            console.log('✅ Player added to container');

            // Add event listeners
            player.addEventListener('loadeddata', () => {
                console.log('✅ Game loaded successfully!');
            });

            player.addEventListener('loadedmetadata', () => {
                console.log('✅ Game metadata loaded');
            });

            player.addEventListener('error', (e) => {
                console.error('❌ Ruffle player error:', e);
                showError('Game Loading Error', 'Failed to load the game. The file may be corrupted or incompatible.');
            });

            player.addEventListener('panic', (e) => {
                console.error('❌ Ruffle panic:', e);
                showError('Game Panic Error', 'The game encountered a critical error and cannot continue.');
            });

            // Load the SWF file
            console.log('🔄 Loading SWF file:', swfUrl);
            
            // Use the load method properly
            try {
                await player.load(swfUrl);
                console.log('✅ SWF load command sent');
            } catch (loadError) {
                console.error('❌ SWF load error:', loadError);
                // Try alternative loading method
                console.log('🔄 Trying alternative loading method...');
                player.src = swfUrl;
            }

        } catch (error) {
            console.error('❌ Ruffle initialization error:', error);
            showError('Initialization Error', 'Failed to initialize the game player: ' + error.message);
        }
    }

    // Show error message to user
    function showError(title, message) {
        gameContainer.innerHTML = `
            <div class="error-message">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                <h5>${title}</h5>
                <p>${message}</p>
                <div class="mt-3">
                    <a href="{{ route('games.show', $game->slug) }}" class="btn btn-outline-light me-2">
                        <i class="fas fa-arrow-left me-1"></i>{{ __('Go Back') }}
                    </a>
                    <button onclick="location.reload()" class="btn btn-outline-warning">
                        <i class="fas fa-redo me-1"></i>{{ __('Retry') }}
                    </button>
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
            showError('Fullscreen Error', 'Unable to toggle fullscreen mode. Please try pressing F11.');
        }
    });

    // Handle fullscreen change
    document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) {
            fullscreenBtn.innerHTML = '<i class="fas fa-expand me-1"></i>{{ __("Fullscreen") }}';
        }
    });

    // Start the process
    if (await checkSwfFile()) {
        await initializeRuffle();
    }
});
</script>
@endpush