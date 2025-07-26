<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;

class CategoryUserSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get the student user
        $student = User::where('email', 'student@gameworld.com')->first();
        
        if ($student) {
            // Get some categories to assign
            $categories = Category::where('is_active', true)->take(3)->get();
            
            if ($categories->count() > 0) {
                // Assign categories to the student
                $student->categories()->sync($categories->pluck('id'));
                
                $this->command->info('Assigned ' . $categories->count() . ' categories to student user.');
                $this->command->info('Categories: ' . $categories->pluck('name')->implode(', '));
            } else {
                $this->command->warn('No categories found to assign.');
            }
        } else {
            $this->command->warn('Student user not found.');
        }
    }
}