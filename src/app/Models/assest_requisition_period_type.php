<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class assest_requisition_period_type extends Model
{
    use HasFactory; 

    public $table = "assest_requisition_period_type";

    protected $fillable = [
        'name',
        'description'
    ];
}