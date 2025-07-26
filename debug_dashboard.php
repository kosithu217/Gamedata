<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;

echo "=== DASHBOARD DEBUG ===\n\n";

// Get student user
$user = User::where('email', 'student@gameworld.com')->first();

if (!$user) {
    echo "Student user not found!\n";
    exit;
}

echo "User: {$user->name}\n";
echo "Is Admin: " . ($user->isAdmin() ? 'Yes' : 'No') . "\n\n";

// Get user's accessible categories
$userCategories = $user->getAccessibleCategories();
echo "User Categories Count: {$userCategories->count()}\n";

foreach ($userCategories as $category) {
    echo "- {$category->name} (ID: {$category->id})\n";
}

// Get user's accessible games
$userGames = $user->getAccessibleGames();
echo "\nUser Games Count: {$userGames->count()}\n";

// Take first 5 games
$topGames = $userGames->sortByDesc('plays_count')->take(8);
echo "Top Games Count: {$topGames->count()}\n";

foreach ($topGames->take(3) as $game) {
    echo "- {$game->title} (Plays: {$game->plays_count})\n";
}

// Get featured games
$categoryIds = $userCategories->pluck('id');
echo "\nCategory IDs: " . $categoryIds->implode(', ') . "\n";

if ($categoryIds->isNotEmpty()) {
    $featuredGames = \App\Models\Game::where('is_active', true)
        ->where('is_featured', true)
        ->whereIn('category_id', $categoryIds)
        ->with('category')
        ->orderBy('sort_order')
        ->take(6)
        ->get();
    
    echo "Featured Games Count: {$featuredGames->count()}\n";
} else {
    echo "No category IDs, so no featured games\n";
}

echo "\n=== DEBUG COMPLETE ===\n";