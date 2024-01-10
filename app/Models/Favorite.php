<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use HasFactory;
    protected $guarded=[];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class, 'favorite_medicine', 'id');
        // Assuming 'favorite_medicine' is the foreign key in the favorites table
        // and 'id' is the primary key in the medicines table
    }

}
