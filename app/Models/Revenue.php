<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revenue extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'description',
        'value',
        'date'
    ];
}
