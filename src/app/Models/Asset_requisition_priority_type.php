<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset_requisition_priority_type extends Model
{
    use HasFactory;

    public $table = "asset_requisition_priority_types";

    protected $fillable = [
        'name',
        'description'
    ];
}