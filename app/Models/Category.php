<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    protected $appends = ['image_url'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get the category image URL with fallback.
     */
    public function getImageUrlAttribute()
    {
        if ($this->image && \Storage::disk('public')->exists($this->image)) {
            return \Storage::url($this->image);
        }
        
        return asset('images/default-category.svg');
    }
}
