<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProcurementService;
use Illuminate\Support\Facades\Mail;
use App\Mail\ProcurementDataEmail;
use Illuminate\Support\Facades\Storage;

class ProcurementController extends Controller
{

    protected $procurementService;

    public function __construct(ProcurementService $procurementService)
    {
        $this->procurementService = $procurementService;
    }
    public function getProcurementStaffDetails()
    {
        try {
            $results = $this->procurementService->getProcurementStaffDetails();

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve procurement staff ' . $e->getMessage()], 500);
        }
    }

    public function addMemberToProcurementStaff(Request $request)
    {
        try {
            $userId = $request->input('user_id', null);
            $assetClassId = $request->input('asset_class_id');

            $result = $this->procurementService->addUpdateMemberToProcurementStaff(
                $userId,
                $assetClassId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully added or updated procurement staff'], 200);
            }else{
                return response()->json(['error' => 'Failed to add or update '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add or update ' . $e->getMessage()], 500);
        }
    }

    public function updateMemberToProcurementStaff(Request $request)
    {
        try {
            $staffId = $request->input('staff_id', null);
            $userId = $request->input('user_id', null);
            $assetClassId = $request->input('asset_class_id');

            $result = $this->procurementService->addUpdateMemberToProcurementStaff(
                $userId,
                $assetClassId,
                $staffId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully added or updated procurement staff'], 200);
            }else{
                return response()->json(['error' => 'Failed to add or update '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add or update ' . $e->getMessage()], 500);
        }
    }


    public function removeMemberFromProcurementStaff(Request $request, $procurement_id)
    {
        try {
            $procurementId = (int)$procurement_id;

            $result = $this->procurementService->removeMemberFromProcurementStaff(
                $procurementId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully remove procurement staff'], 200);
            }else{
                return response()->json(['error' => 'Failed to remove '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove ' . $e->getMessage()], 500);
        }
    }

    public function createProcurement(Request $request)
    {
        try {
            $data = $request->all();

            $rpfDocuments = [];
            $attachments = [];
            
            if ($request->hasfile('rpf_document')) {
                foreach ($request->file('rpf_document') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads'), $name);
                    $rpfDocuments[] = 'uploads/' . $name;
                }
            }

            if ($request->hasfile('attachment')) {
                foreach ($request->file('attachment') as $file) {
                    $name = time() . '_' . $file->getClientOriginalName();
                    $file->move(public_path('uploads'), $name);
                    $attachments[] = 'uploads/' . $name;
                }
            }

            $data['rpf_document'] = $rpfDocuments;
            $data['attachment'] = $attachments;


            $this->procurementService->createProcurement($data);

            $headers = [
                "Item ID",
                "Item Name",
                "Budget",
                "Priority",
                "Quantity",
                "Period From",
                "Period To",
                "Organization",
                "Required Date",
                "Upgrade or New",
                "Business Impact",
                "Availability Type",
                "Business Purpose",
                "Asset Requisition ID",
                "Created At",
                "Updated At",
                "Supplier Names",
                "Item Details",
                "Consumables KPI",
                "Maintenance KPI",
                "Service Support KPI"
            ];

            $csvFilePath = storage_path('app/public/procurement.csv');
            $file = fopen($csvFilePath, 'w');
            fputcsv($file, $headers);

            if (isset($data['selected_items']) && is_array($data['selected_items'])) {
                foreach ($data['selected_items'] as $selectedItem) {
                    if (isset($selectedItem['request']['items']) && is_array($selectedItem['request']['items'])) {
                        foreach ($selectedItem['request']['items'] as $item) {
                            $supplierNames = implode('; ', $item['suppliers']);
                            $itemDetails = implode('; ', array_map(function ($detail) {
                                return "{$detail['type']}: {$detail['details']}";
                            }, $item['item_details']));
                            $consumablesKpi = implode('; ', array_map(function ($kpi) {
                                return $kpi['details'];
                            }, $item['consumables_kpi']));
                            $maintenanceKpi = implode('; ', array_map(function ($kpi) {
                                return $kpi['details'];
                            }, $item['maintenance_kpi']));
                            $serviceSupportKpi = implode('; ', array_map(function ($kpi) {
                                return $kpi['details'];
                            }, $item['service_support_kpi']));

                            fputcsv($file, [
                                $item['id'],
                                $item['item_name'],
                                $item['budget'],
                                $item['priority'],
                                $item['quantity'],
                                $item['period_from'],
                                $item['period_to'],
                                $item['organization'],
                                $item['required_date'],
                                $item['upgrade_or_new'],
                                $item['business_impact'],
                                $item['availabiity_type'],
                                $item['business_perpose'],
                                $item['asset_requisition_id'],
                                $item['created_at'],
                                $item['updated_at'],
                                $supplierNames,
                                $itemDetails,
                                $consumablesKpi,
                                $maintenanceKpi,
                                $serviceSupportKpi
                            ]);
                        }
                    }
                }
            }

            fclose($file);

            $maxRetries = 3;

            if (isset($data['selected_suppliers']) && is_array($data['selected_suppliers'])) {
                foreach ($data['selected_suppliers'] as $supplier) {
                    $email = $supplier['supplier_primary_email'];

                    if ($email) {
                        $retryCount = 0;
                        $emailSent = false;

                        while ($retryCount < $maxRetries && !$emailSent) {
                            try {
                                Mail::to($email)
                                    ->send(new ProcurementDataEmail($csvFilePath));
                                $emailSent = true;
                            } catch (\Exception $e) {
                                $retryCount++;
                                if ($retryCount == $maxRetries) {
                                    throw new \Exception("Failed to send email to {$email} after {$maxRetries} attempts");
                                }
                            }
                        }
                    }
                }
            }

            if (file_exists($csvFilePath)) {
                unlink($csvFilePath);
            }

            return response()->json(['status' => 'success', 'message' => 'Procurement processed successfully']);

        } catch (\Exception $e) {
            if (isset($file) && file_exists($csvFilePath)) {
                fclose($file);
                unlink($csvFilePath);
            }
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateProcurement(Request $request)
    {
        try {
            $data = $request->all();


            $this->procurementService->updateProcurement($data);

            $headers = [
                "Item ID",
                "Item Name",
                "Budget",
                "Priority",
                "Quantity",
                "Period From",
                "Period To",
                "Organization",
                "Required Date",
                "Upgrade or New",
                "Business Impact",
                "Availability Type",
                "Business Purpose",
                "Asset Requisition ID",
                "Created At",
                "Updated At",
                "Supplier Names",
                "Item Details",
                "Consumables KPI",
                "Maintenance KPI",
                "Service Support KPI"
            ];

            return response()->json(['status' => 'success', 'message' => 'Procurement processed successfully']);

        } catch (\Exception $e) {
            if (isset($file) && file_exists($csvFilePath)) {
                fclose($file);
                unlink($csvFilePath);
            }
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function getProcurementIds()
    {
        try {
            $results = $this->procurementService->getProcurementIds();

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve procurement staff ' . $e->getMessage()], 500);
        }
    }

    public function getProcurements(Request $request, $procurement_id)
    {
        try {
            $procurementId = (int)$procurement_id;
            $results = $this->procurementService->getProcurements($procurementId);

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve procurement ' . $e->getMessage()], 500);
        }
    }
    public function getProcurementsByUser(Request $request, $procurement_id)
    {
        try {
            $procurementId = (int)$procurement_id;
            $results = $this->procurementService->getProcurementsByUser($procurementId);

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve procurement ' . $e->getMessage()], 500);
        }
    }

    public function getQuotationFeedbacks(Request $request, $quotation_id=0)
    {
        try {
            $quotationId = (int)$quotation_id;
            $results = $this->procurementService->getQuotationFeedbacks($quotationId);

            return response()->json(['status'=>"success", 'data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve quotation feedback ' . $e->getMessage()], 500);
        }
    }

    public function createQuotationFeedback(Request $request)
    {
        try {
            $data = $request->all();

            $this->procurementService->createQuotationFeedback($data);

            return response()->json(['status' => 'success', 'message' => 'Quotation feedback processed successfully']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateQuotationFeedback(Request $request)
    {
        try {
            $data = $request->all();

            $this->procurementService->updateQuotationFeedback($data);

            return response()->json(['status' => 'success', 'message' => 'Quotation feedback updated successfully']);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function removeQuotationFeedback(Request $request, $quotation_feedback_id)
    {
        try {
            $quotationFeedbackId = (int)$quotation_feedback_id;

            $result = $this->procurementService->removeQuotationFeedback(
                $quotationFeedbackId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully remove quotation feedback'], 200);
            }else{
                return response()->json(['error' => 'Failed to remove '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove ' . $e->getMessage()], 500);
        }
    }
    
    public function quotationComplete(Request $request, $procurement_id)
    {
        try {
            $procurementId = (int)$procurement_id;

            $result = $this->procurementService->quotationComplete(
                $procurementId
            );

            if($result === "SUCCESS"){
                return response()->json(['status'=>"success", 'message'=>'successfully update quotation feedback status'], 200);
            }else{
                return response()->json(['error' => 'Failed to remove '], 500);
            }

        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update ' . $e->getMessage()], 500);
        }
    }

}