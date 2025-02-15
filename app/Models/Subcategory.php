<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;
    protected $guarded = ["id"];
    protected $hidden = ["category_id"];

    public function Category(){
        return $this->belongsTo('App\Models\Category', 'category_id');
    }
}
