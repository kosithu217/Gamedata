<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_mm',
        'slug',
        'description',
        'description_mm',
        'swf_file_path',
        'thumbnail',
        'category_id',
        'width',
        'height',
        'plays_count',
        'is_featured',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'plays_count' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($game) {
            if (empty($game->slug)) {
                $game->slug = Str::slug($game->title);
            }
        });
    }

    public function getTitleAttribute($value)
    {
        return app()->getLocale() === 'mm' && $this->title_mm ? $this->title_mm : $value;
    }

    public function getDescriptionAttribute($value)
    {
        return app()->getLocale() === 'mm' && $this->description_mm ? $this->description_mm : $value;
    }

    public function incrementPlays()
    {
        $this->increment('plays_count');
    }
}
