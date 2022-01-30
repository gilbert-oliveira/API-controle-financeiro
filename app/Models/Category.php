<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function expenses()
    {
        return $this->hasMany(Expense::class)->getResults();
    }

    protected $fillable = [
        'name'
    ];
}
