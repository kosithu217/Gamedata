<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Category;
use Illuminate\Database\Seeder;

class GameSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        
        if ($categories->count() === 0) {
            $this->command->info('No categories found. Please run CategorySeeder first.');
            return;
        }

        $games = [
            [
                'title' => 'Math Adventure',
                'title_mm' => 'သင်္ချာစွန့်စားခန်း',
                'description' => 'Learn basic math operations through fun adventures',
                'description_mm' => 'ပျော်ရွှင်စရာစွန့်စားခန်းများမှတစ်ဆင့် အခြေခံသင်္ချာလုပ်ဆောင်ချက်များကို သင်ယူပါ',
                'category_id' => $categories->where('name', 'Math Games')->first()->id ?? 1,
                'swf_file_path' => 'games/demo_math.swf', // Demo path
                'width' => 800,
                'height' => 600,
                'is_featured' => true,
                'is_active' => true,
                'plays_count' => 150,
                'sort_order' => 1,
            ],
            [
                'title' => 'English Word Quest',
                'title_mm' => 'အင်္ဂလိပ်စာလုံးရှာဖွေခြင်း',
                'description' => 'Improve your English vocabulary with this exciting word game',
                'description_mm' => 'ဤစိတ်လှုပ်ရှားဖွယ်စာလုံးဂိမ်းဖြင့် သင့်အင်္ဂလိပ်စာလုံးများကို တိုးတက်စေပါ',
                'category_id' => $categories->where('name', 'English Learning')->first()->id ?? 2,
                'swf_file_path' => 'games/demo_english.swf', // Demo path
                'width' => 800,
                'height' => 600,
                'is_featured' => true,
                'is_active' => true,
                'plays_count' => 200,
                'sort_order' => 2,
            ],
            [
                'title' => 'Science Lab Explorer',
                'title_mm' => 'သိပ္ပံဓာတ်ခွဲခန်းရှာဖွေသူ',
                'description' => 'Conduct virtual science experiments and learn about chemistry',
                'description_mm' => 'ကြိုးမဲ့သိပ္ပံစမ်းသပ်မှုများပြုလုပ်ပြီး ဓာတုဗေဒအကြောင်း သင်ယူပါ',
                'category_id' => $categories->where('name', 'Science Explorer')->first()->id ?? 3,
                'swf_file_path' => 'games/demo_science.swf', // Demo path
                'width' => 800,
                'height' => 600,
                'is_featured' => true,
                'is_active' => true,
                'plays_count' => 120,
                'sort_order' => 3,
            ],
            [
                'title' => 'Geography Challenge',
                'title_mm' => 'ပထဝီဝင်စိန်ခေါ်မှု',
                'description' => 'Test your knowledge of world geography and countries',
                'description_mm' => 'ကမ္ဘာ့ပထဝီဝင်နှင့် နိုင်ငံများအကြောင်း သင့်အသိပညာကို စမ်းသပ်ပါ',
                'category_id' => $categories->where('name', 'Geography Adventures')->first()->id ?? 4,
                'swf_file_path' => 'games/demo_geography.swf', // Demo path
                'width' => 800,
                'height' => 600,
                'is_featured' => true,
                'is_active' => true,
                'plays_count' => 90,
                'sort_order' => 4,
            ],
            // Non-featured games (only accessible to logged-in users)
            [
                'title' => 'Advanced Algebra',
                'title_mm' => 'အဆင့်မြင့်အက္ခရာသင်္ချာ',
                'description' => 'Master advanced algebraic concepts and equations',
                'description_mm' => 'အဆင့်မြင့်အက္ခရာသင်္ချာအယူအဆများနှင့် ညီမျှခြင်းများကို ကျွမ်းကျင်စေပါ',
                'category_id' => $categories->where('name', 'Math Games')->first()->id ?? 1,
                'swf_file_path' => 'games/advanced_algebra.swf',
                'width' => 800,
                'height' => 600,
                'is_featured' => false,
                'is_active' => true,
                'plays_count' => 45,
                'sort_order' => 5,
            ],
            [
                'title' => 'Creative Art Studio',
                'title_mm' => 'ဖန်တီးမှုအနုပညာစတူဒီယို',
                'description' => 'Express your creativity through digital art and design',
                'description_mm' => 'ဒစ်ဂျစ်တယ်အနုပညာနှင့် ဒီဇိုင်းမှတစ်ဆင့် သင့်ဖန်တီးမှုကို ဖော်ပြပါ',
                'category_id' => $categories->where('name', 'Art & Creativity')->first()->id ?? 6,
                'swf_file_path' => 'games/art_studio.swf',
                'width' => 800,
                'height' => 600,
                'is_featured' => false,
                'is_active' => true,
                'plays_count' => 75,
                'sort_order' => 6,
            ],
        ];

        foreach ($games as $gameData) {
            Game::create($gameData);
        }

        $this->command->info('Sample games created successfully!');
    }
}
