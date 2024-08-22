<?php
namespace App\Services;

use App\Repositories\AssetsManagementRepository;

class AssetsManagementService
{
    protected $AssetsManagementRepository;

    public function __construct(AssetsManagementRepository $AssetsManagementRepository)
    {
        $this->AssetsManagementRepository = $AssetsManagementRepository;
    }

    /**
     * Create an asset requisition and related items.
     *
     * @param array $data
     * @return void
     */
    public function createAssetRegister(array $data)
    {
        $this->AssetsManagementRepository->createAssetRegister($data);
    }

    public function getAllAssets()
    {
        return $this->AssetsManagementRepository->getAllAssets();
    }

    public function updateAsset(array $data)
    {
        $this->AssetsManagementRepository->updateAsset($data);
    }

    public function deleteAsset($asset_id)
    {
        $this->AssetsManagementRepository->deleteAsset($asset_id);
    }
}