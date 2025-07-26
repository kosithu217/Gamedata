<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_mm',
        'slug',
        'description',
        'description_mm',
        'class_level',
        'color',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function games()
    {
        return $this->hasMany(Game::class);
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function getNameAttribute($value)
    {
        return app()->getLocale() === 'mm' && $this->name_mm ? $this->name_mm : $value;
    }

    public function getDescriptionAttribute($value)
    {
        return app()->getLocale() === 'mm' && $this->description_mm ? $this->description_mm : $value;
    }
}
