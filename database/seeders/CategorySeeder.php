<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Math Games',
                'name_mm' => 'သင်္ချာဂိမ်းများ',
                'slug' => 'math-games',
                'description' => 'Fun mathematical games to improve calculation skills',
                'description_mm' => 'တွက်ချက်မှုစွမ်းရည်တိုးတက်စေရန် ပျော်ရွှင်စရာ သင်္ချာဂိမ်းများ',
                'class_level' => 'Grade 1-5',
                'color' => '#ff6b6b',
                'sort_order' => 1,
            ],
            [
                'name' => 'English Learning',
                'name_mm' => 'အင်္ဂလိပ်သင်ခန်းစာ',
                'slug' => 'english-learning',
                'description' => 'Interactive games to learn English vocabulary and grammar',
                'description_mm' => 'အင်္ဂလိပ်စာလုံးများနှင့် သဒ္ဒါသင်ယူရန် အပြန်အလှန်ဂိမ်းများ',
                'class_level' => 'Grade 1-8',
                'color' => '#4ecdc4',
                'sort_order' => 2,
            ],
            [
                'name' => 'Science Explorer',
                'name_mm' => 'သိပ္ပံရှာဖွေခြင်း',
                'slug' => 'science-explorer',
                'description' => 'Discover the wonders of science through interactive games',
                'description_mm' => 'အပြန်အလှန်ဂိမ်းများမှတစ်ဆင့် သိပ္ပံ၏အံ့ဖွယ်များကို ရှာဖွေပါ',
                'class_level' => 'Grade 3-10',
                'color' => '#45b7d1',
                'sort_order' => 3,
            ],
            [
                'name' => 'Geography Adventures',
                'name_mm' => 'ပထဝီဝင်စွန့်စားခန်း',
                'slug' => 'geography-adventures',
                'description' => 'Explore the world through geography games',
                'description_mm' => 'ပထဝီဝင်ဂိမ်းများမှတစ်ဆင့် ကမ္ဘာကြီးကို လေ့လာပါ',
                'class_level' => 'Grade 4-9',
                'color' => '#f9ca24',
                'sort_order' => 4,
            ],
            [
                'name' => 'History Quest',
                'name_mm' => 'သမိုင်းရှာဖွေခြင်း',
                'slug' => 'history-quest',
                'description' => 'Journey through time with historical games',
                'description_mm' => 'သမိုင်းဂိမ်းများဖြင့် အချိန်ခရီးထွက်ပါ',
                'class_level' => 'Grade 5-10',
                'color' => '#6c5ce7',
                'sort_order' => 5,
            ],
            [
                'name' => 'Art & Creativity',
                'name_mm' => 'အနုပညာနှင့်ဖန်တီးမှု',
                'slug' => 'art-creativity',
                'description' => 'Express creativity through art and design games',
                'description_mm' => 'အနုပညာနှင့်ဒီဇိုင်းဂိမ်းများမှတစ်ဆင့် ဖန်တီးမှုကို ဖော်ပြပါ',
                'class_level' => 'Grade 1-8',
                'color' => '#fd79a8',
                'sort_order' => 6,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
