<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\GameController as AdminGameController;
use App\Http\Controllers\Admin\BlogPostController as AdminBlogPostController;
use App\Http\Controllers\Admin\UserController as AdminUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'mm'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

// Authentication routes
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::get('/register', [LoginController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [LoginController::class, 'register']);
Route::get('/session-conflict', function() {
    return view('auth.session-conflict');
})->name('session.conflict');

// Blog routes (public)
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Games routes (accessible to guests for demo, full access for authenticated users)
Route::get('/games', [GameController::class, 'index'])->name('games.index');
Route::get('/games/{slug}', [GameController::class, 'show'])->name('games.show');
Route::get('/games/{slug}/play', [GameController::class, 'play'])->name('games.play');

// Protected routes (require authentication and single session)
Route::middleware(['auth', 'single.session'])->group(function () {
    // Student Dashboard - accessible at /dashboard
    Route::get('/dashboard', [App\Http\Controllers\Student\DashboardController::class, 'index'])->name('student.dashboard');
    Route::get('/profile', [App\Http\Controllers\Student\ProfileController::class, 'index'])->name('student.profile');
});

// Admin routes (single.session middleware allows multiple sessions for admins)
Route::middleware(['auth', 'admin', 'single.session'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('games', AdminGameController::class);
    Route::resource('blog-posts', AdminBlogPostController::class);
    Route::resource('users', AdminUserController::class);
    
    // Session management
    Route::post('/users/{user}/force-logout', [LoginController::class, 'forceLogoutAllSessions'])->name('users.force-logout');
});

// Temporary debug route for testing game files
Route::get('/test-game-files', function() {
    $games = \App\Models\Game::take(3)->get();
    $results = [];
    
    foreach ($games as $game) {
        $filePath = storage_path('app/public/' . $game->swf_file_path);
        $publicUrl = asset('storage/' . $game->swf_file_path);
        
        $results[] = [
            'id' => $game->id,
            'title' => $game->title,
            'db_path' => $game->swf_file_path,
            'full_path' => $filePath,
            'file_exists' => file_exists($filePath),
            'file_size' => file_exists($filePath) ? filesize($filePath) . ' bytes' : 'N/A',
            'public_url' => $publicUrl,
            'play_url' => route('games.play', $game->slug)
        ];
    }
    
    return response()->json([
        'storage_path' => storage_path('app/public'),
        'public_storage_path' => public_path('storage'),
        'storage_link_exists' => is_link(public_path('storage')),
        'games' => $results
    ], 200, [], JSON_PRETTY_PRINT);
});

// Session testing route (for demonstration)
Route::get('/test-sessions', function() {
    if (!auth()->check()) {
        return redirect()->route('login')->with('info', 'Please login to test session functionality');
    }
    
    $user = auth()->user();
    $currentSession = session()->getId();
    $userSessions = \App\Models\UserSession::where('user_id', $user->id)->get();
    
    return view('test-sessions', compact('user', 'currentSession', 'userSessions'));
})->middleware(['auth', 'single.session'])->name('test-sessions');

// Language testing route (for demonstration)
Route::get('/test-language', function() {
    return response()->json([
        'current_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'translations' => [
            'welcome' => __('Welcome to Game World!'),
            'games' => __('Games'),
            'blog' => __('Blog'),
            'login' => __('Login'),
            'register' => __('Register'),
            'demo_games' => __('Demo Games'),
            'play_now' => __('Play Now'),
            'featured' => __('Featured'),
            'categories' => __('Categories'),
        ]
    ]);
})->name('test-language');


