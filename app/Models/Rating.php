<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    protected $table = "rating";
    protected $hidden = ["user_id"];

    public function User(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }

}
