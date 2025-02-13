<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $table = "order";
    protected $guarded = ['id'];

    public function Service(){
        return $this->belongsTo('App\Models\User', 'service_id');
    }
    
    public function User(){
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}
