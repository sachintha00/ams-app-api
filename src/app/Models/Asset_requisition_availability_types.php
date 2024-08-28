<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset_requisition_availability_types extends Model
{
    use HasFactory;

    public $table = "asset_requisition_availability_types";

    protected $fillable = [
        'name',
        'description'
    ];
}