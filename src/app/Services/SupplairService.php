<?php
namespace App\Services;

use App\Repositories\SupplairRepository;

class SupplairService
{
    protected $SupplairRepository;

    public function __construct(SupplairRepository $SupplairRepository)
    {
        $this->SupplairRepository = $SupplairRepository;
    }

    public function getAllSupplair()
    {
        return $this->SupplairRepository->getAllSupplair();
    }

    public function getSupplierRegNo()
    {
        return $this->SupplairRepository->getSupplierRegNo();
    }

    public function addNewSupplier(
        $p_name,
        $p_address,
        $p_description,
        $p_supplier_asset_classes,
        $p_supplier_rating,
        $p_supplier_bussiness_name,
        $p_supplier_bussiness_register_no,
        $p_supplier_primary_email,
        $p_supplier_secondary_email,
        $p_supplier_br_attachment,
        $p_supplier_website,
        $p_supplier_tel_no,
        $p_supplier_mobile,
        $p_supplier_fax,
        $p_supplier_city,
        $p_supplier_location_latitude,
        $p_supplier_location_longitude,
        $p_contact_no,
        $p_id,
        $p_supplier_register_status
    )
    {
        $result = $this->SupplairRepository->addNewSupplier(
            $p_name,
            $p_address,
            $p_description,
            $p_supplier_asset_classes,
            $p_supplier_rating,
            $p_supplier_bussiness_name,
            $p_supplier_bussiness_register_no,
            $p_supplier_primary_email,
            $p_supplier_secondary_email,
            $p_supplier_br_attachment,
            $p_supplier_website,
            $p_supplier_tel_no,
            $p_supplier_mobile,
            $p_supplier_fax,
            $p_supplier_city,
            $p_supplier_location_latitude,
            $p_supplier_location_longitude,
            $p_contact_no,
            $p_id,
            $p_supplier_register_status
        );
        $status = $result->first()->status;
        return $status;
    }
}
