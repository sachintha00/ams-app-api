<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WorkflowService
{
    public function addOrUpdateNewWorkflow(
        $workflowRequestTypeId,
        $workflowName,
        $workflowDescription,
        $workflowStatus,
        $workflowId=NULL,
    ){
        try {
            $result = DB::select('CALL STORE_PROCEDURE_INSERT_OR_UPDATE_WORKFLOW(?, ?, ?, ?, ?, ?)', [
                $workflowRequestTypeId,
                $workflowName,
                $workflowDescription,
                NULL,
                $workflowId,
                $workflowStatus
            ]);

            $insertedOrUpdatedWorkflowId = $result[0]->p_inserted_or_updated_workflow_id ?? null;
            
            return $insertedOrUpdatedWorkflowId;
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            
            throw new \Exception('Failed to add or update workflow ');
        }
    }

    public function addOrUpdateNewWorkflowDetails(
        $workflowId,
        $workflowDetailParentId,
        $workflowDetailTypeId,
        $workflowDetailBehaviorTypeId,
        $workflowDetailOrder,
        $workflowDetailLevel,
        $workflowDetailDataObject,
        $workflowDetailId = NULL,
    ){

        try {
            DB::statement("CALL STORE_PROCEDURE_INSERT_OR_UPDATE_WORKFLOW_DETAILS(?,?,?,?,?,?,?,?)", [
                $workflowId,
                $workflowDetailParentId,
                $workflowDetailTypeId,
                $workflowDetailBehaviorTypeId,
                $workflowDetailOrder,
                $workflowDetailLevel,
                $workflowDetailDataObject,
                $workflowDetailId,
            ]);
            
            return 'Workflow added successfully';
        } catch (\Exception $e) {
            Log::error('Error while adding new workflow: ' . $e->getMessage());
            
            throw new \Exception('Failed to add or update workflow details');
        }
    }
    
    public function retrieveWorkflow($workflowId){
        try {
            DB::select('CALL STORE_PROCEDURE_RETRIEVE_WORKFLOW(?)', [$workflowId]);
            $allWorkFlowsAsArray = DB::table('workflow_from_store_procedure')->select('*')->get();
            
            return $allWorkFlowsAsArray;
        } catch (\Exception $e) {
            Log::error('Error while retrieving workflows: ' . $e->getMessage());
            
            return 'Failed to retrieve workflows';
        }
    }

    public function retrieveWorkflowDetails($workflowId){
        try {
            DB::select('CALL STORE_PROCEDURE_RETRIEVE_WORKFLOW_DETAILS(?)', [$workflowId]);
            $allWorkFlowsAsArray = DB::table('workflow_details_from_store_procedure')->select('*')->get();
            
            return $allWorkFlowsAsArray;
        } catch (\Exception $e) {
            Log::error('Error while retrieving workflows: ' . $e->getMessage());
            
            return 'Failed to retrieve workflows';
        }
    }

    public function retrieveWorkflowDetailNodes($workflowDetailId){
        try {
            DB::select('CALL STORE_PROCEDURE_RETRIEVE_WORKFLOW_DETAILS_NODES(?)', [$workflowDetailId]);
            $allWorkFlowsAsArray = DB::table('workflow_details_nodes_from_store_procedure')->select('*')->get();
            
            return $allWorkFlowsAsArray;
        } catch (\Exception $e) {
            Log::error('Error while retrieving workflows: ' . $e->getMessage());
            
            return 'Failed to retrieve workflows';
        }
    }
    
    public function removeWorkflow($workflowId){
        try {
            DB::statement("CALL STORE_PROCEDURE_DELETE_WORKFLOW(p_workflow_id := ?)", [$workflowId]);
            
            return 'Workflow removed successfully';
        } catch (\Exception $e) {
            Log::error('Error while removing workflow: ' . $e->getMessage());
            
            return 'Failed to remove workflow';
        }
    }
    public function removeWorkflowDetail($workflowDetailId){
        try {
            DB::statement("CALL STORE_PROCEDURE_DELETE_WORKFLOW_DETAILS(p_workflow_detail_id := ?)", [$workflowDetailId]);
            
            return 'Workflow details removed successfully';
        } catch (\Exception $e) {
            Log::error('Error while removing workflow: ' . $e->getMessage());
            
            return 'Failed to remove workflow details';
        }
    }

    public function retrieveAllDesignation (){ 
        try {
            DB::select('CALL STORE_PROCEDURE_RETRIEVE_DESIGNATIONS()');
            $designations = DB::table('designations_from_store_procedure')->select(['id', 'designation'])->get();
            
            return $designations;
        } catch (\Exception $e) {
            Log::error('Error while retrieving workflows: ' . $e->getMessage());
            
            return 'Failed to retrieve workflows'. $e->getMessage();
        }
    }
}