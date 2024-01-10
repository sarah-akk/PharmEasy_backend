<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order_medicine extends Model
{
    use HasFactory;
    protected $fillable=['order_id','medicine_id','request_quantity'
    ];
    public function medicines(){
        return $this->hasMany(Medicine::class);
    }
//    public function orders(){
//    return $this->belongsTo(Order::class);
//}

}
