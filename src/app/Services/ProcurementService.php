<?php
namespace App\Services;

use App\Repositories\ProcurementRepository;

class ProcurementService
{
    protected $procurementRepository;

    public function __construct(ProcurementRepository $procurementRepository)
    {
        $this->procurementRepository = $procurementRepository;
    }

    public function getProcurementStaffDetails($id=0)
    {
        return $this->procurementRepository->getProcurementStaffDetails($id);
    }

    public function addUpdateMemberToProcurementStaff($userId=0, $assetClassId=0, $staffId=0)
    {
        $result = $this->procurementRepository->addUpdateMemberToProcurementStaff($userId, $assetClassId, $staffId);
        $status = $result->first()->status;
        return $status;;
    }

    public function removeMemberFromProcurementStaff($procurementId=0)
    {
        $status = $this->procurementRepository->removeMemberFromProcurementStaff($procurementId);
        return $status;
    }
}