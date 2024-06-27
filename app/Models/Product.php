<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = "product";
    protected $fillable = ['name', 'description', 'price', 'quantity', 'user_id', 'category_id', 'image'];

    function user()       //thằng nào chứa khóa ngoại thì thằng đó dòng belongsTo
    {
        return $this->belongsTo('App\Models\User');
    }
    function category()
    {
        return $this->belongsTo('App\Models\Category');
    }
    function images()
    {
        return $this->hasMany('App\Models\Images');
    }
    function cart()
    {
        return $this->hasMany('App\Models\Cart');
    }
    public function orderItems()
    {
        return $this->hasMany('App\Models\OrderItem');
    }
}
