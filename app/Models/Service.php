<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    protected $table = "services";
    protected $hidden = ["user_id"];

    public function User(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    
    public function Category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }

    public function Subcategory(){
        return $this->belongsTo('App\Models\Subcategory', 'subcategory_id');
    }

}
