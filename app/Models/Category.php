<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'parent_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    public static function getCategories(){
        return Category::with('subCategories')->where('parent_id', 0)->get();
    }

    public function subCategories(){
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function parentCategory(){
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function auctions(){
        return $this->hasMany(Auction::class);
    }
}
