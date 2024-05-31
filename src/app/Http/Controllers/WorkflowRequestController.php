<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\WorkflowRequestService;
use Illuminate\Support\Facades\Auth;

class WorkflowRequestController extends Controller
{
    protected $workflowRequestService;
    public function __construct(WorkflowRequestService $workflowRequestService)
    {
        $this->workflowRequestService = $workflowRequestService;
    }

    public function submitWorkflowRequestData(Request $request)
    {
        $requisitionDataObject = json_encode($request->input('requisition_data_object'));
        try {
            $userId = Auth::id();
            $workflowRequestTypeId = $request->input('workflow_request_type_id');
            $workflowId = $request->input('workflow_id');
            $assetRequisitionId = $request->input('asset_requisition_id');
            $budgetValue = $request->input('budget_value');
            $designationUserId = $request->input('designation_user_id');
            
            $submitWorkflowResult = $this->workflowRequestService->submitWorkflowRequestData(
                $userId,
                $workflowRequestTypeId,
                $workflowId ,
                $assetRequisitionId ,
                $requisitionDataObject
            );

            
            $requestId = $submitWorkflowResult['request_id'];
            
            if ($submitWorkflowResult['status'] === 'SUCCESS') {
                $this->workflowRequestService->workflowRequestProcess(
                    $workflowId,
                    $requestId,
                    $budgetValue ,
                    $designationUserId
                );
                return response()->json(['data' => 'success'], 200);
            } else {
                return response()->json(['error' => $submitWorkflowResult['message']], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e], 500);
        }
    }



    public function retrieveWorkflowRequestTypes(Request $request)
    {
        try {
            $workflowRequestTypes = $this->workflowRequestService->retrieveWorkflowRequestTypes();
        
            return response()->json(['data' => $workflowRequestTypes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows'], 500);
        }
    }

    public function retrieveRelevantWorkflows(Request $request, $workflow_request_type_id)
    {
        try {
            $userId = Auth::id();
            $workflowRequestTypeId = (int)$workflow_request_type_id;
            $workflowRequestTypes = $this->workflowRequestService->retrieveRelevantWorkflows($workflowRequestTypeId ,$userId);
        
            return response()->json(['data' => $workflowRequestTypes], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows'], 500);
        }
    }

    public function retrieveRequestWorkflow(Request $request)
    {
        try {
            $results = $this->workflowRequestService->retrieveRequestWorkflow(
                $request->input('workflow_id'),
                $request->input('variable_values')['value']
            );

            foreach ($results as $result) {
                $result->data = json_decode($result->data);
            }

            return response()->json(['data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows ' . $e->getMessage()], 500);
        }
    }

    public function retrieveWorkflowApprovelAlertData(Request $request)
    {
        try {
            $userId = Auth::id();
            $results = $this->workflowRequestService->retrieveWorkflowApprovelAlertData(
                $userId
            );

            foreach ($results as $result) {
                $result->requested_user = json_decode($result->requested_user);
                $result->requested_data_obj = json_decode($result->requested_data_obj);
                if ($result->next_approver_details) {
                    $result->next_approver_details = json_decode($result->next_approver_details);
                }
                if ($result->previous_user_details) {
                    $result->previous_user_details = json_decode($result->previous_user_details);
                }
            }
    
            return response()->json(['data' => $results], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows ' . $e->getMessage()], 500);
        }
    }

    public function retrieveWorkflowFirstAprover(Request $request)
    {
        try {
            $firstApproverResults = $this->workflowRequestService->retrieveWorkflowFirstAprover(
                $request->input('workflow_id'),
                $request->input('variable_values')['value']
            );

            foreach ($firstApproverResults as $result) {
                $result->data = json_decode($result->data);
            }

            $RequestWorkflowResults = $this->workflowRequestService->retrieveRequestWorkflow(
                $request->input('workflow_id'),
                $request->input('variable_values')['value']
            );

            foreach ($RequestWorkflowResults as $result) {
                $result->data = json_decode($result->data);
            }

            return response()->json(['data' => $firstApproverResults, 'workflow_data'=>$RequestWorkflowResults], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to retrieve workflows ' . $e->getMessage()], 500);
        }
    }


    public function workflowRequestApproved(Request $request)
    {
        try {
            $userId = Auth::id();
            $requestId = $request->input('request_id');
            $workflowNodeId = $request->input('workflow_node_id');
            $requisitionId = $request->input('requisition_id');
            $approverComment = $request->input('approver_comment');
            $designationUserId = $request->input('designation_user_id');
            $workflowId = $request->input('workflow_id');
            
            $approveResult = $this->workflowRequestService->workflowRequestApproved(
                $userId,
                $requestId,
                $workflowId,
                $workflowNodeId,
                $requisitionId,
                $approverComment,
                $designationUserId
            );

            if ($approveResult['status'] === 'SUCCESS') {
                return response()->json(['status' => 'success', 'message'=> $approveResult['message']], 200);
            } else {
                return response()->json(['status' => 'error', 'message'=> $approveResult['message']], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function workflowRequestRejected(Request $request)
    {
        try {
            $userId = Auth::id();
            $requestId = $request->input('request_id');
            $workflowNodeId = $request->input('workflow_node_id');
            $requisitionId = $request->input('requisition_id');
            $approverComment = $request->input('approver_comment');
            $designationUserId = $request->input('designaion_user_id');
            $workflowId = $request->input('workflow_id');
            
            $approveResult = $this->workflowRequestService->workflowRequestRejected(
                $userId,
                $requestId,
                $workflowId,
                $workflowNodeId ,
                $requisitionId ,
                $approverComment,
                $designationUserId
            );
            
            if ($approveResult['status'] === 'SUCCESS') {
                return response()->json(['status' => 'success', 'message'=> $approveResult['message']], 200);
            } else {
                return response()->json(['status' => 'error', 'message'=> $approveResult['message']], 400);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}