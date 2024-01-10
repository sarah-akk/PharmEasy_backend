<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $guarded = [];
//    public function users(){
//        return $this->belongsTo(User::class);
//    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function medicines(){
        return $this->belongsToMany(Medicine::class,'order_medicines')->withpivot('request_quantity');
    }
    public function favorite_medicine($id){
        $favorite=Favorite::create([
            'medicine_id'=>$id,
            'user_id'=>1
        ]);
        $favorite->save();
        return $this->apiResponse($favorite, 'done!!', 200);
    }
    public function unfavorite_medicine($id){
        $fav=Favorite::find($id);
        $fav->delete();
        return response()->json("done !!");
    }
    public function list(){
        $id=1;
        $fav=Favorite::where('user_id',1)->get();
        return response()->json($fav);
    }
}

