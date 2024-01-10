<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
   public function orders(){
        return $this->belongsToMany(Order::class,'order_medicines')->withPivot('request_quantity');
   }

    public function favorite(){
        return $this->belongsToMany(User::class,'favorite');
    }
}
