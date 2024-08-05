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

    public function createProcurement(array $data)
    {
        $this->procurementRepository->createProcurement($data);
    }

    public function updateProcurement(array $data)
    {
        $this->procurementRepository->updateProcurement($data);
    }
    
    public function getProcurementIds()
    {
        $results = $this->procurementRepository->getProcurementIds();
        return $results;
    }
    
    public function getProcurements($id=0)
    {
        $results = $this->procurementRepository->getProcurements($id);
        return $results;
    }
    public function getProcurementsByUser($id=0)
    {
        $results = $this->procurementRepository->getProcurementsByUser($id);
        return $results;
    }

     public function getQuotationFeedbacks($id=0)
    {
        $results = $this->procurementRepository->getQuotationFeedbacks($id);
        return $results;
    }

    public function createQuotationFeedback(array $data)
    {
        $this->procurementRepository->createQuotationFeedback($data);
    }

    public function updateQuotationFeedback(array $data)
    {
        $this->procurementRepository->updateQuotationFeedback($data);
    }

    public function removeQuotationFeedback($quotationFeedbackId=0)
    {
        $status = $this->procurementRepository->removeQuotationFeedback($quotationFeedbackId);
        return $status;
    }

    public function quotationComplete($procurementId=0)
    {
        $status = $this->procurementRepository->quotationComplete($procurementId);
        return $status;
    }
}