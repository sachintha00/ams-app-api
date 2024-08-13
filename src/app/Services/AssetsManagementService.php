<?php
namespace App\Services;

use App\Repositories\AssetsManagementRepository;
use Illuminate\Support\Facades\Log;

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
        Log::info($data);
        $this->AssetsManagementRepository->createAssetRegister($data);
    }
}