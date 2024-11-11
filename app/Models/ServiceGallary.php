<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceGallary extends Model
{
    use HasFactory;
    protected $table = "service_gallary";
    protected $guarded = ['id'];
}
