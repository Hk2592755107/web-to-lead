<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
       'brand_name','device_info','first_name','last_name','email','phone','message'
    ];

    protected $casts = [
        'device_info' => 'array',

    ];
}
