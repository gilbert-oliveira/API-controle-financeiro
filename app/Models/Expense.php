<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'description',
        'value',
        'date'
    ];
}
