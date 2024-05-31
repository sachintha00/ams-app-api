<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class test_wijart extends Model
{
    use HasFactory; 

    public $table = "test_wijart";
  
    protected $fillable = [
        'name',
        'componant',
    ];
}
