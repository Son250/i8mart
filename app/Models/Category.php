<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $table = "product_categories";   //đặt tên bảng cho giống DB
    protected $fillable = ['name', 'slug', 'description', 'user_id', 'parent_id'];

    // public function parent()
    // {
    //     return $this->belongsTo(Category::class, 'parent_id');
    // }

    // public function children()
    // {
    //     return $this->hasMany(Category::class, 'parent_id');
    // }

    function user()
    {
        return $this->belongsTo('App\Models\User');   //bảng nào có khóa ngoại thì bảng đó dùng beLongsTo
    }
    function product(){
        return $this->hasMany('App\Models\Product');
    }
    function categoryBig(){
        return $this->belongsTo('App\Models\User');  
    }
}
