<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogPostSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();
        $adminUser = User::where('role', 'admin')->first();
        
        if (!$adminUser) {
            $this->command->info('No admin user found. Please run AdminUserSeeder first.');
            return;
        }

        $posts = [
            [
                'title' => 'Welcome to Game World!',
                'title_mm' => 'Edu Game Kabarသို့ ကြိုဆိုပါတယ်!',
                'content' => 'Welcome to our educational gaming platform! Here you can find fun and engaging games designed specifically for students of all grade levels. Our games are carefully curated to help you learn while having fun.

Whether you\'re interested in math, science, English, or creative arts, we have something for everyone. Each game is designed to be both educational and entertaining, making learning an enjoyable experience.

Start your learning journey today by exploring our game categories and finding games that match your grade level and interests!',
                'content_mm' => 'ကျွန်ုပ်တို့၏ ပညာရေးဂိမ်းပလပ်ဖောင်းသို့ ကြိုဆိုပါတယ်! ဤနေရာတွင် အတန်းအဆင့်အားလုံးရှိ ကျောင်းသားများအတွက် အထူးဒီဇိုင်းထုတ်ထားသော ပျော်ရွှင်ဖွယ်နှင့် စိတ်ဝင်စားဖွယ်ဂိမ်းများကို တွေ့ရှိနိုင်ပါသည်။

သင်္ချာ၊ သိပ္ပံ၊ အင်္ဂလိပ်စာ သို့မဟုတ် ဖန်တီးမှုအနုပညာများကို စိတ်ဝင်စားသည်ဖြစ်စေ၊ လူတိုင်းအတွက် တစ်ခုခုရှိပါသည်။ ဂိမ်းတစ်ခုစီသည် ပညာရေးနှင့် ဖျော်ဖြေမှုနှစ်မျိုးလုံးဖြစ်အောင် ဒီဇိုင်းထုတ်ထားပြီး သင်ယူမှုကို ပျော်ရွှင်ဖွယ်အတွေ့အကြုံတစ်ခုဖြစ်စေပါသည်။',
                'excerpt' => 'Discover our educational gaming platform with fun games for all grade levels.',
                'excerpt_mm' => 'အတန်းအဆင့်အားလုံးအတွက် ပျော်ရွှင်ဖွယ်ဂိမ်းများပါရှိသော ကျွန်ုပ်တို့၏ ပညာရေးဂိမ်းပလပ်ဖောင်းကို ရှာဖွေပါ။',
                'category_id' => $categories->first()->id,
                'author_id' => $adminUser->id,
                'is_published' => true,
                'is_featured' => true,
                'published_at' => now(),
                'views_count' => 25,
            ],
            [
                'title' => 'Benefits of Educational Gaming',
                'title_mm' => 'ပညာရေးဂိမ်းများ၏ အကျိုးကျေးဇူးများ',
                'content' => 'Educational games offer numerous benefits for students of all ages. Research has shown that learning through games can improve retention, engagement, and understanding of complex concepts.

Here are some key benefits:
- Enhanced problem-solving skills
- Improved critical thinking
- Better retention of information
- Increased motivation to learn
- Development of digital literacy skills

Our platform is designed to harness these benefits while providing a safe and fun learning environment for students.',
                'content_mm' => 'ပညာရေးဂိမ်းများသည် အသက်အရွယ်အားလုံးရှိ ကျောင်းသားများအတွက် များစွာသော အကျိုးကျေးဇူးများကို ပေးပါသည်။ ဂိမ်းများမှတစ်ဆင့် သင်ယူခြင်းသည် မှတ်ဉာဏ်၊ ပါဝင်မှုနှင့် ရှုပ်ထွေးသောအယူအဆများကို နားလည်မှုတို့ကို တိုးတက်စေနိုင်သည်ဟု သုတေသနများက ပြသခဲ့သည်။',
                'excerpt' => 'Learn about the educational benefits of gaming and how it enhances learning.',
                'excerpt_mm' => 'ဂိမ်းများ၏ ပညာရေးအကျိုးကျေးဇူးများနှင့် ၎င်းသည် သင်ယူမှုကို မည်သို့တိုးတက်စေသည်ကို လေ့လာပါ။',
                'category_id' => $categories->skip(1)->first()->id ?? $categories->first()->id,
                'author_id' => $adminUser->id,
                'is_published' => true,
                'is_featured' => false,
                'published_at' => now()->subDays(2),
                'views_count' => 15,
            ],
        ];

        foreach ($posts as $postData) {
            BlogPost::create($postData);
        }

        $this->command->info('Sample blog posts created successfully!');
    }
}
