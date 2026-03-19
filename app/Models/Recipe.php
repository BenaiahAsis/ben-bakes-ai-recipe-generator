<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    use HasFactory;

    // This line is REQUIRED to allow saving
    protected $fillable = ['ingredients', 'recipe_text'];
}