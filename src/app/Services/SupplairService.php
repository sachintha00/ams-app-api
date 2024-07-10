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
}