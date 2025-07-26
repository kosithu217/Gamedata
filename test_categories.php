<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Category;
use App\Models\Game;

echo "=== CATEGORY SYSTEM TEST ===\n\n";

// Get student user
$student = User::where('email', 'student@gameworld.com')->first();

if (!$student) {
    echo "Student user not found!\n";
    exit;
}

echo "Student: {$student->name} ({$student->email})\n";
echo "Assigned Categories: {$student->categories()->count()}\n\n";

// Show assigned categories
echo "--- ASSIGNED CATEGORIES ---\n";
foreach ($student->categories as $category) {
    $gameCount = $category->games()->where('is_active', true)->count();
    echo "- {$category->name} (ID: {$category->id}, Games: {$gameCount})\n";
}

// Test accessible games
echo "\n--- ACCESSIBLE GAMES ---\n";
$accessibleGames = $student->getAccessibleGames();
echo "Total accessible games: {$accessibleGames->count()}\n";

foreach ($accessibleGames->take(5) as $game) {
    echo "- {$game->title} (Category: {$game->category->name})\n";
}

// Test all categories
echo "\n--- ALL CATEGORIES ---\n";
$allCategories = Category::all();
foreach ($allCategories as $category) {
    $gameCount = $category->games()->where('is_active', true)->count();
    echo "- {$category->name} (ID: {$category->id}, Games: {$gameCount})\n";
}

echo "\n=== TEST COMPLETE ===\n";