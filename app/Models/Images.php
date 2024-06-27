<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    use HasFactory;
    protected $fillable = ['image_url', 'product_id'];
    function product()
    {
        return $this->belongsTo('App\Models\Product');
    }
}
