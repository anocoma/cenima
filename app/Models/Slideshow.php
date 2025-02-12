<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slideshow extends Model
{
    use HasFactory;
    protected $fillable = [
        'img_thumbnail',
        'description',
        'is_active',
    ];
    protected $casts =[
        'img_thumbnail' => 'json',
        'is_active'=>'boolean',
    ];
}
