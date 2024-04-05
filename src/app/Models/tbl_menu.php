<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tbl_menu extends Model
{
    use HasFactory;

    public $table = "tbl_menu";
  
    protected $fillable = [
        'permission_id',
        'parent_id',
        'RightsCode',
        'MenuTxtCode',
        'RightsCode',
        'MenuName',
        'Description',
        'path',
        'MenuLink',
        'MenuOrder',
        'Enabled',
        'MenuPath',
        'icon'
    ];

    public function children()
    {
        return $this->hasMany(tbl_menu::class);
    }
    
    public function parent()
    {
        return $this->belongsTo(tbl_menu::class);
    }
}
