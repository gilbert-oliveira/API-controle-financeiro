<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{

    use HasFactory;

    public $timestamps = false;

    public function category()
    {
        return $this->belongsTo(Category::class)->first();
    }

    protected $fillable = [
        'description',
        'value',
        'date',
        'category_id'
    ];
}
