<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowRequestService
{
    public function submitWorkflowRequestData($userId, $workflowRequestTypeId, $workflowId, $requisitionId, $requisitionDataObject)
    {
        try {
            if($workflowRequestTypeId === 1){
                DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                    '{"asset_requisitions": {"requisition_status": "PENDING"}}',
                    $requisitionId,
                    'requisition_id'
                ]);
            } else if($workflowRequestTypeId === 2){
                DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                    '{"supplair": {"supplier_reg_status": "PENDING"}}',
                    $requisitionId,
                    'supplier_reg_no'
                ]);
            }else if($workflowRequestTypeId === 3){
                DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                    '{"procurements": {"procurement_status": "PENDING"}}',
                    $requisitionId,
                    'request_id'
                ]);
            }
            
            DB::statement("CALL STORE_PROCEDURE_WORKFLOW_REQUEST_SUBMIT(?, ?, ?, ?, ?)", [
                $userId,
                $workflowRequestTypeId,
                $workflowId,
                $requisitionId ,
                $requisitionDataObject,
            ]);

            $result = DB::table('response')->select(['status', 'message', 'request_id'])->get();

            if (!empty($result)) {
                return (array) $result[0];
            } else {
                Log::error('Stored procedure did not return any result.');
                return ['status' => 'ERROR', 'message' => 'Stored procedure did not return any result'];
            }
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            throw new \Exception('Failed to add or update workflow details');
        }
    }

    public function workflowRequestProcess($workflowId, $requestId, $value=0, $designationUserId=NULL)
    {
        try {
            DB::statement("CALL STORE_PROCEDURE_WORKFLOW_PROCESS(?, ?, ?, ?)", [
                $workflowId,
                $requestId,
                $value,
                $designationUserId 
            ]);

            // $result = DB::table('response')->select(['status', 'message'])->get();

            // if (!empty($result)) {
            //     return (array) $result[0];
            // } else {
            //     Log::error('Stored procedure did not return any result.');
            //     return ['status' => 'ERROR', 'message' => 'Stored procedure did not return any result'];
            // }
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            throw new \Exception('Failed to add or update workflow details');
        }
    }



    public function retrieveWorkflowRequestTypes(
        $workflowRequestTypeId = 0
    ){
        try {
            DB::statement("CALL STORE_PROCEDURE_LIST_WORKFLOW_REQUEST_TYPES(?)", [
                $workflowRequestTypeId
            ]);
            $workflowRequestTypeArray = DB::table('workflow_request_types_from_store_procedure')->select(['id', 'request_type'])->get();
            
            return $workflowRequestTypeArray ;
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            
            throw new \Exception('Failed to add or update workflow details');
        }
    }

    public function retrieveRelevantWorkflows(
        $workflowRequestTypeId = 0,
        $userId = 0
    ){

        try {
            DB::statement("CALL STORE_PROCEDURE_LIST_RELEVANT_WORKFLOWS(?,?)", [
                $workflowRequestTypeId,
                $userId
            ]);
            $workflowRequestTypeArray = DB::table('all_relevant_workflows_from_store_procedure')->select('*')->get();
            
            return $workflowRequestTypeArray ;
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            
            throw new \Exception('Failed to add or update workflow details');
        }
    }

    public function retrieveRequestWorkflow(
        $workflowId,
        $value
    ){

        try {
             
            DB::statement("CALL STORE_PROCEDURE_GET_REQUEST_WORKFLOW(?,?)", [
                $workflowId,
                $value
            ]);

            return DB::table('workflow_request_process_path_data_from_store_procedure')->select('*')->get();
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            
            throw new \Exception('Failed to add or update workflow details');
        }
    }

    public function retrieveWorkflowApprovelAlertData(
        $userId,
    ){

        try {
            DB::statement("CALL STORE_PROCEDURE_WORKFLOW_ALERT_DATA_RELEVANT_APPROVER(?)", [
                $userId,
            ]);

            return DB::table('workflow_alert_data_from_store_procedure')->select('*')->get();
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            
            throw new \Exception('Failed to add or update workflow details');
        }
    }

    public function retrieveWorkflowFirstAprover(
        $workflowId,
        $value
    ){

        try {
             
            DB::statement("CALL STORE_PROCEDURE_GET_WORKFLOW_REQUEST_FIRST_APPROVER(?,?)", [
                $workflowId,
                $value
            ]);

            return DB::table('workflow_request_first_approver_data_from_store_procedure')->select('*')->get();
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            
            throw new \Exception('Failed to retrieve first aprover');
        }
    }

    public function workflowRequestApproved(
        $userId,
        $requestId,
        $workflowId,
        $workflowNodeId,
        $requisitionId,
        $approverComment,
        $designationUserId,
        $requestTypeId,
        $status
    ){
        try {
            // dd($requestId);
            // Log::info($requisitionId, $requestTypeId);
            
            if($requestTypeId === 1 && $status === "APPROVED"){
                DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                    '{"asset_requisitions": {"requisition_status": "APPROVED"}}',
                    $requisitionId,
                    'requisition_id'
                ]);
            } else if($requestTypeId === 2 && $status === "APPROVED"){
                DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                    '{"supplair": {"supplier_reg_status": "APPROVED"}}',
                    $requisitionId,
                    'supplier_reg_no'
                ]);
            }
            DB::statement("CALL STORE_PROCEDURE_WORKFLOW_REQUEST_APPROVED(?, ?, ?, ?)", [
                $userId,
                $requestId,
                $workflowNodeId,
                $approverComment
            ]);
    
            $result = DB::table('response')->select(['status', 'message'])->get();
    
            if (!empty($result)) {
                $value = DB::table('workflow_request_queues')
                        ->select(DB::raw('CAST(value AS bigint) AS value'))
                        ->where('id', $requestId)
                        ->first();
                DB::statement("CALL STORE_PROCEDURE_WORKFLOW_PROCESS(?, ?, ?, ?)", [
                    $workflowId,
                    $requestId,
                    $value->value,
                    $designationUserId
                ]);
                return (array) $result[0];
            } else {
                Log::error('Stored procedure did not return any result.');
                return ['status' => 'ERROR', 'message' => 'Stored procedure did not return any result'];
            }
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            throw new \Exception('Failed to approve request'. $e->getMessage());
        }
    }
    

    public function workflowRequestRejected(
        $userId,
        $requestId,
        $workflowId,
        $workflowNodeId,
        $requisitionId,
        $approverComment,
        $designationUserId,
        $requestTypeId,
        $status
    ){

        try {

            if($requestTypeId === 1 && $status === "REJECT"){
                DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                    '{"asset_requisitions": {"requisition_status": "REJECT"}}',
                    $requisitionId,
                    'requisition_id'
                ]);
            } else if($requestTypeId === 2 && $status === "REJECT"){
                DB::statement('CALL STORE_PROCEDURE_UPDATE_DATA(?, ?, ?)', [
                    '{"supplair": {"supplier_reg_status": "REJECT"}}',
                    $requisitionId,
                    'supplier_reg_no'
                ]);
            }
             
            DB::statement("CALL STORE_PROCEDURE_WORKFLOW_REQUEST_REJECTED(?, ?, ?, ?, ?)", [
                $userId,
                $requestId,
                $workflowNodeId,
                $requisitionId,
                $approverComment
            ]);
            
            $result = DB::table('response')->select(['status', 'message'])->get();
            

            if (!empty($result)) {
                return (array) $result[0];
            } else {
                Log::error('Stored procedure did not return any result.');
                return ['status' => 'ERROR', 'message' => 'Stored procedure did not return any result'];
            }
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            throw new \Exception('Failed to reject request'. $e->getMessage());
        }
    }
}