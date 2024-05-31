<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierModel extends Model
{
    use HasFactory;
    protected $table = 'supplair';
    
    protected $fillable = [
        'supplier_asset_classes',
        'supplier_rating',
        'supplier_bussiness_name',
        'supplier_bussiness_register_no',
        'supplier_primary_email',
        'supplier_secondary_email',
        'supplier_br_attachment',
        'supplier_website',
        'supplier_tel_no',
        'supplier_mobile',
        'supplier_fax',
        'supplier_city',
        'supplier_location_latitude',
        'supplier_location_longitude',
        'contact_no',
        'supplier_reg_no',
        'supplier_reg_status',
    ];
    protected $casts = [
        'supplier_asset_classes' => 'array',
        'contact_no' => 'array',
    ];
}