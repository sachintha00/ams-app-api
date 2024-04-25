<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationHierarchi extends Model
{
    use HasFactory;

    protected $table = 'organization';

    protected $fillable = [
        'parent_node_id',
        'level',
        'relationship',
        'data',
    ];

    protected $casts = [
        'data' => 'json',
    ];
}
