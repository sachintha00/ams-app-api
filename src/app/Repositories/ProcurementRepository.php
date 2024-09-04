<?php
namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProcurementRepository
{
    public function getProcurementStaffDetails($id=0)
    {
        DB::select('CALL STORE_PROCEDURE_GET_PROCUREMENT_STAFF_DETAILS()');
        $procurementIds = DB::table('procurement_staff_details_from_store_procedure')->select('*')->get();
        
        return $procurementIds;
    }

    public function addUpdateMemberToProcurementStaff($userId=0, $assetClassId=0, $staffId=0)
    {
        DB::select('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_PROCUREMENT_STAFF(?, ?, ?)',  [$userId, $assetClassId, $staffId]);
        $procurementStaffResponse = DB::table('procurement_staff_response_from_store_procedure')->select('*')->get();
        
        return $procurementStaffResponse;
    }

    public function removeMemberFromProcurementStaff($procurementId=0)
    {
        try {
            $procurementStaffResponse = DB::select('CALL STORE_PROCEDURE_DELETE_PROCUREMENT_STAFF(?)', [$procurementId]);
            
            return "SUCCESS";
    
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
            
        } catch (\Exception $e) {
            // Catch all other exceptions
            return $e->getMessage();
        }
    }

    public function createProcurement(array $data)
    {

        
        DB::beginTransaction();

        if (isset($data['selected_items'])) {
            if (is_string($data['selected_items'])) {
                $decodedItems = json_decode($data['selected_items'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['selected_items'] = $decodedItems;
                } else {
                    throw new \Exception('Selected items must be a valid JSON string or array');
                }
            }
            if (!is_array($data['selected_items'])) {
                throw new \Exception('Selected items must be a valid array');
            }
        } else {
            $data['selected_items'] = [];
        }

        try {
        
            DB::statement('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_PROCUREMENT(?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $data['requwetsid'],
                Auth::id(),
                $data['date'],
                json_encode($data['selected_items']),
                json_encode($data['selected_suppliers']), 
                json_encode($data['rpf_document']), 
                json_encode($data['attachment']),
                $data['requered_date'],
                $data['comment'],
                $data['status']
            ]);

            DB::statement('CALL STORE_PROCEDURE_UPDATE_ASSET_REQUISITION(?)', [
                json_encode($data['selected_items']),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateProcurement(array $data)
    {

        
        DB::beginTransaction();

        if (isset($data['selected_items'])) {
            if (is_string($data['selected_items'])) {
                $decodedItems = json_decode($data['selected_items'], true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    $data['selected_items'] = $decodedItems;
                } else {
                    throw new \Exception('Selected items must be a valid JSON string or array');
                }
            }
            if (!is_array($data['selected_items'])) {
                throw new \Exception('Selected items must be a valid array');
            }
        } else {
            $data['selected_items'] = [];
        }

        try {
        
            DB::statement('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_PROCUREMENT(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                null,
                Auth::id(),
                $data['date'],
                json_encode($data['selected_items']),
                null, 
                null,
                null,
                null,
                null,
                'SUBMIT',
                $data['procurement_id']
            ]);

            DB::statement('CALL STORE_PROCEDURE_UPDATE_ASSET_REQUISITION(?)', [
                json_encode($data['selected_items']),
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getProcurementIds()
    {
        DB::select('CALL STORE_PROCEDURE_RETRIEVE_PROCUREMENT_IDS()');
        $procurementIds = DB::table('procurement_ids_from_store_procedure')->select('*')->get();
        
        return $procurementIds;
    }

    public function getProcurements($id = 0)
    {
        DB::select(
            'CALL STORE_PROCEDURE_RETRIEVE_PROCUREMENTS(?, ?)',
            [
                $id, 
                null
            ]
        );
        $procurements = DB::table('procurements_from_store_procedure')->select('*')->get();

        foreach ($procurements as $procurement) {            
            if ($procurement->selected_items) { 
                $procurement->selected_items = json_decode($procurement->selected_items);

                if (is_string($procurement->selected_items)) {
                    $procurement->selected_items = json_decode($procurement->selected_items);
                }
            }
            if ($procurement->selected_suppliers) {
                $procurement->selected_suppliers = json_decode($procurement->selected_suppliers);

                if (is_string($procurement->selected_suppliers)) {
                    $procurement->selected_suppliers = json_decode($procurement->selected_suppliers);
                }
            }
            if ($procurement->rpf_document) {
                $procurement->rpf_document = json_decode($procurement->rpf_document);
            }
            if ($procurement->attachment) {
                $procurement->attachment = json_decode($procurement->attachment);
            }
            if ($procurement->quotation_feedbacks) {
                $procurement->quotation_feedbacks = json_decode($procurement->quotation_feedbacks);

                if (is_string($procurement->quotation_feedbacks)) {
                    $procurement->quotation_feedbacks = json_decode($procurement->quotation_feedbacks);
                }
            }
        }

        return $procurements;
    }

    public function getProcurementsByUser($id = 0)
    {
        DB::select(
            'CALL STORE_PROCEDURE_RETRIEVE_PROCUREMENTS_BY_USERID(?, ?, ?)',
            [
                Auth::id(),
                $id, 
                null
            ]
        );
        $procurements = DB::table('procurements_by_userid_from_store_procedure')->select('*')->get();

        foreach ($procurements as $procurement) {            
            if ($procurement->selected_items) {
                $procurement->selected_items = json_decode($procurement->selected_items);

                if (is_string($procurement->selected_items)) {
                    $procurement->selected_items = json_decode($procurement->selected_items);
                }
            }
            if ($procurement->selected_suppliers) {
                $procurement->selected_suppliers = json_decode($procurement->selected_suppliers);

                if (is_string($procurement->selected_suppliers)) {
                    $procurement->selected_suppliers = json_decode($procurement->selected_suppliers);
                }
            }
            if ($procurement->rpf_document) {
                $procurement->rpf_document = json_decode($procurement->rpf_document);
            }
            if ($procurement->attachment) {
                $procurement->attachment = json_decode($procurement->attachment);
            }
            if ($procurement->quotation_feedbacks) {
                $procurement->quotation_feedbacks = json_decode($procurement->quotation_feedbacks);

                if (is_string($procurement->quotation_feedbacks)) {
                    $procurement->quotation_feedbacks = json_decode($procurement->quotation_feedbacks);
                }
            }
        }

        return $procurements;
    }

    public function createQuotationFeedback(array $data)
    {

        
        DB::beginTransaction();

        try {
        
            DB::statement('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_QUOTATION_FEEDBACK(?, ?, ?, ?, ?, ?)', [
                $data['date'],
                $data['procurement_id'],
                $data['selected_supplier_id'],
                json_encode($data['selected_items']),
                $data['AvailableDate'],
                Auth::id()
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateQuotationFeedback(array $data)
    {
        DB::beginTransaction();

        try {
        
            DB::statement('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_QUOTATION_FEEDBACK(?, ?, ?, ?, ?, ?, ?)', [
                $data['date'],
                $data['procurement_id'],
                $data['selected_supplier_id'],
                json_encode($data['selected_items']),
                $data['AvailableDate'],
                Auth::id(),
                $data['quotation_id']
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getQuotationFeedbacks($id = 0)
    {
        DB::select(
            'CALL STORE_PROCEDURE_RETRIEVE_QUOTATION_FEEDBACK(?)',
            [
                $id,
            ]
        );
        $quotationFeedbacks = DB::table('quotation_feedbacks_from_store_procedure')->select('*')->get();

        foreach ($quotationFeedbacks as $quotationFeedback) {            
            if ($quotationFeedback->selected_items) {
                $quotationFeedback->selected_items = json_decode($quotationFeedback->selected_items);

                if (is_string($quotationFeedback->selected_items)) {
                    $quotationFeedback->selected_items = json_decode($quotationFeedback->selected_items);
                }
            }
        }

        return $quotationFeedbacks;
    }

    public function removeQuotationFeedback($quotationFeedbackId=0)
    {
        try {
            $quotationFeedbackResponse = DB::select('CALL STORE_PROCEDURE_REMOVE_QUOTATION_FEEDBACK(?)', [$quotationFeedbackId]);
            
            return "SUCCESS";
    
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
            
        } catch (\Exception $e) {
            // Catch all other exceptions
            return $e->getMessage();
        }
    }

    public function quotationComplete($procurementId=0)
    {
        try {
            DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                '{"procurements": {"procurement_status": "COMPLETE"}}',
                $procurementId,
                'id'
            ]);
            
            return "SUCCESS";
    
        } catch (\Illuminate\Database\QueryException $e) {
            return $e->getMessage();
            
        } catch (\Exception $e) {
            // Catch all other exceptions
            return $e->getMessage();
        }
    }

}