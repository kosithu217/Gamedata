<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_mm',
        'slug',
        'content',
        'content_mm',
        'excerpt',
        'excerpt_mm',
        'featured_image',
        'category_id',
        'author_id',
        'is_published',
        'is_featured',
        'published_at',
        'views_count',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'is_featured' => 'boolean',
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($post) {
            if (empty($post->slug)) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function getTitleAttribute($value)
    {
        return app()->getLocale() === 'mm' && $this->title_mm ? $this->title_mm : $value;
    }

    public function getContentAttribute($value)
    {
        return app()->getLocale() === 'mm' && $this->content_mm ? $this->content_mm : $value;
    }

    public function getExcerptAttribute($value)
    {
        return app()->getLocale() === 'mm' && $this->excerpt_mm ? $this->excerpt_mm : $value;
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }
}
