<?php
namespace App\Services;

use App\Repositories\MasterEntryRepository;

class MasterEntryService
{
    protected $MasterEntryRepository;

    public function __construct(MasterEntryRepository $MasterEntryRepository)
    {
        $this->MasterEntryRepository = $MasterEntryRepository;
    }

    public function getAllAssetsTypes()
    {
        return $this->MasterEntryRepository->getAllAssetsTypes();
    }

    public function getAllItemTypes()
    {
        return $this->MasterEntryRepository->getAllItemTypes();
    }

    public function getAllPeriodTypes()
    {
        return $this->MasterEntryRepository->getAllPeriodTypes();
    }

    public function getAllAvailabilityTypes()
    {
        return $this->MasterEntryRepository->getAllAvailabilityTypes();
    }

    public function getAllPriorityTypes()
    {
        return $this->MasterEntryRepository->getAllPriorityTypes();
    }

    public function getAssetTypes()
    {
        return $this->MasterEntryRepository->getAssetTypes();
    }

    public function getAllAssetCategories()
    {
        return $this->MasterEntryRepository->getAllAssetCategories();
    }

    public function getAllDesignations()
    {
        return $this->MasterEntryRepository->getAllDesignations();
    }
}
