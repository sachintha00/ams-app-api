<?php
namespace App\Services;

use App\Repositories\AssetRequisitionRepository;

class AssetRequisitionService
{
    protected $AssetRequisitionRepository;

    public function __construct(AssetRequisitionRepository $AssetRequisitionRepository)
    {
        $this->AssetRequisitionRepository = $AssetRequisitionRepository;
    }

    /**
     * Create an asset requisition and related items.
     *
     * @param array $data
     * @return void
     */
    public function createAssetRequisition(array $data)
    {
        $this->AssetRequisitionRepository->createAssetRequisition($data);
    }

    public function submitAssetRequisition(array $data)
    {
        $this->AssetRequisitionRepository->submitAssetRequisition($data);
    }

    public function getUserAssetRequisition($id)
    {
        return $this->AssetRequisitionRepository->getUserAssetRequisition($id);
    }
}
