<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{

    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'description',
        'value',
        'date'
    ];
}
