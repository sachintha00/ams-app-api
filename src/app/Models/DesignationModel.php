<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DesignationModel extends Model
{
    use HasFactory;

    protected $table = 'designations';
    
    protected $fillable = [
        'designation',
    ];
}
