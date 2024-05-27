<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\WorkflowService;

class WorkflowController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function addNewWorkflow(Request $request)
    {
        try {
            $result = $this->workflowService->addOrUpdateNewWorkflow(
                $request->input('workflow_request_type_id'),
                $request->input('workflow_name'),
                $request->input('workflow_description'),
                $request->input('workflow_status')
            );

            return response()->json(["message" => 'Successfull added workflow', 'workflow_id' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add new workflow'], 500);
        }
    }

    public function updateWorkflow(Request $request)
    {
        try {
            $result = $this->workflowService->addOrUpdateNewWorkflow(
                $request->input('workflow_request_type_id'),
                $request->input('workflow_name'),
                $request->input('workflow_description'),
                $request->input('workflow_status'),
                $request->input('workflow_id')
            );

            return response()->json(["message" => 'Successfull updated workflow', 'workflow_id' => $result], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add new workflow'], 500);
        }
    }

    public function addNewWorkflowDetails(Request $request)
    {
        try {
            
            $workflowDetailDataObject = json_encode($request->input('workflow_detail_data_object'));

            $message = $this->workflowService->addOrUpdateNewWorkflowDetails(
                $request->input('workflow_id'),
                $request->input('workflow_detail_parent_id'),
                $request->input('workflow_detail_type_id'),
                $request->input('workflow_detail_behavior_type_id'),
                $request->input('workflow_detail_order'),
                $request->input('workflow_detail_level'),
                $workflowDetailDataObject
            );

            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add workflow details'], 500);
        }
    }

    public function updateWorkflowDetails(Request $request)
    {
        try {
            
            $workflowDetailDataObject = json_encode($request->input('workflow_detail_data_object'));

            $message = $this->workflowService->addOrUpdateNewWorkflowDetails(
                $request->input('workflow_id'),
                $request->input('workflow_detail_parent_id'),
                $request->input('workflow_detail_type_id'),
                $request->input('workflow_detail_behavior_type_id'),
                $request->input('workflow_detail_order'),
                $request->input('workflow_detail_level'),
                $workflowDetailDataObject,
                $request->input('workflow_detailI_id'),
            );

            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to add workflow details'], 500);
        }
    }

    public function retrieveWorkflow(Request $request, $workflow_id)
    {
        try {
            $workflowId = (int)$workflow_id;
            $workflowDetails = $this->workflowService->retrieveWorkflow($workflowId);
        
            return response()->json(['data' => $workflowDetails], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows'], 500);
        }
    }

    public function retrieveWorkflowDetails(Request $request, $workflow_id)
    {
        try {
            $workflowId = (int)$workflow_id;
            $workflowDetails = $this->workflowService->retrieveWorkflowDetails( $workflowId);
        
            foreach ($workflowDetails as $workflowDetail) {
                $workflowDetail->workflow_detail_data_object = json_decode($workflowDetail->workflow_detail_data_object);
            }
        
            return response()->json(['data' => $workflowDetails], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows'], 500);
        }
    }

    public function retrieveWorkflowDetailNodes(Request $request, $workflow_detail_id)
    {
        try {
            $workflowDetailId = (int)$workflow_detail_id;
            $workflowDetailNodes = $this->workflowService->retrieveWorkflowDetailNodes( $workflowDetailId);
        
            foreach ($workflowDetailNodes as $workflowDetailNode) {
                $workflowDetailNode->workflow_detail_data_object = json_decode($workflowDetailNode->workflow_detail_data_object);
            }
        
            return response()->json(['data' => $workflowDetailNodes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows'], 500);
        }
    }
    
    public function removeWorkflow(Request $request, $workflow_id)
    {
        try {
            $workflowId = (int)$workflow_id;
            $message = $this->workflowService->removeWorkflow($workflowId);
        
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove workflow'], 500);
        }
    }
    public function removeWorkflowDetails(Request $request, $workflow_detail_id)
    {
        try {
            $workflowDetailId = (int)$workflow_detail_id;
            $message = $this->workflowService->removeWorkflowDetail($workflowDetailId);
        
            return response()->json(['message' => $message], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to remove workflow'], 500);
        }
    }
}