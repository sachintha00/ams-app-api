<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PermisionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrganizationHierarchiController;
use App\Http\Controllers\sidebarController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\LoginuserController;
use App\Http\Controllers\WorkflowController;
use App\Http\Controllers\TableDrawerItemListController;
use App\Http\Controllers\WorkflowRequestController;
use App\Http\Controllers\AssestRequisitionController;
use App\Http\Controllers\testwijartController;
use App\Http\Controllers\FileWriteController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\ProcurementController;
use App\Http\Controllers\PrefixController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\AssetsManagementController;

use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->group(function(){

    Route::post("register", [AuthController::class, "register"]);
    Route::post("login", [AuthController::class, "login"]);
    Route::post('account/verify', [AuthController::class, 'verifyAccount'])->name('user.verify'); 
    Route::post("write-tenant-env-file", [FileWriteController::class, "writeToFile"]);

    Route::group([
        "middleware" => ["auth:api"]
    ], function(){
        Route::get("allwijart", [testwijartController::class, "index"]);
        Route::post("createwijart", [testwijartController::class, "store"]);

        Route::post("logout", [AuthController::class, "logout"]);
        Route::get("authuserspermission", [LoginuserController::class, "index"]);
        Route::get("sidebar", [sidebarController::class, "index"]);

        Route::post("dashboard/add-new-drawer_item", [TableDrawerItemListController::class, "storeTableDrawerItemList"]); 
        Route::get("dashboard/get-drawer-item-list", [TableDrawerItemListController::class, "retrieveDrawerItemList"]); 

        Route::get("all-assest-requisition", [AssestRequisitionController::class, "index"]);
        Route::post("add-assest-requisition", [AssestRequisitionController::class, "store"]);
        Route::put("update-assest-requisition-status", [AssestRequisitionController::class, "statusupdate"]);
        Route::get("asset-requisition/all-approved", [AssestRequisitionController::class, "getAllApprovedAssetRequisition"]); 

        Route::get("users", [UserController::class, "index"]); 
        Route::post("addusers", [UserController::class, "store"]);
        Route::put('usersupdate/{id}', [UserController::class, 'updateuserdata']);
        Route::put('changeuserstatus/{id}', [UserController::class, 'changestatus']);
        Route::put('userpasswordreset/{id}', [UserController::class, 'passwordreset']);
        Route::delete('userdelete/{id}', [UserController::class, 'destroy']);
        Route::get('profile-image/{filename}', [ImageController::class, 'showProfileImages']);
          
        Route::get("users", [UserController::class, "index"]); 

        Route::get("allpermissions", [PermisionController::class, "index"]);
        Route::post("addpermissions", [PermisionController::class, "store"]); 
        Route::put('permissionsupdate/{id}', [PermisionController::class, 'update']);
        Route::delete('permissionsdelete/{id}', [PermisionController::class, 'destroy']);

        Route::get("allroles", [RoleController::class, "index"]);
        Route::post("addroles", [RoleController::class, "store"]);
        Route::put('rolesupdate/{id}', [RoleController::class, 'update']);
        Route::delete('rolesdelete/{id}', [RoleController::class, 'destroy']);
        Route::get("roles/{id}/give-permissions", [RoleController::class, "addPermissionToRole"]);
        Route::put("roles/{id}/give-permissions", [RoleController::class, "givePermissionToRole"]);
        Route::put("roles/{id}/remove-permissions", [RoleController::class, "removePermissionFromRole"]);

        Route::get("All-Activitys", [ActivityLogController::class, "index"]);

        Route::post("added-new-node-to-organization", [OrganizationHierarchiController::class, "insertNewNodeToOrganizationHierarchi"]); 
        Route::get("retrieve-organization", [OrganizationHierarchiController::class, "retrieveOrganizationHierarchi"]); 
        
        Route::get("users/retrieve-all", [UserController::class, "retrieveAllUserWithPaginate"]); 
        Route::get("users/retrieve/search", [UserController::class, "retrieveAllUserFromQuerySearch"]); 
    
        Route::post("workflow/added-new-workflow-details", [WorkflowController::class, "addNewWorkflowDetails"]); 
        Route::post("workflow/added-new-workflow", [WorkflowController::class, "addNewWorkflow"]); 
        
        Route::put("workflow/update-workflow-details", [WorkflowController::class, "updateWorkflowDetails"]); 
        Route::put("workflow/update-workflow", [WorkflowController::class, "updateWorkflow"]); 
        
        Route::get("workflow/retrieve-workflow/{workflow_id}", [WorkflowController::class, "retrieveWorkflow"]); 
        Route::get("workflow/retrieve-workflow-details/{workflow_id}", [WorkflowController::class, "retrieveWorkflowDetails"]); 
        Route::get("workflow/retrieve-workflow-detail-nodes/{workflow_detail_id}", [WorkflowController::class, "retrieveWorkflowDetailNodes"]); 
        
        Route::delete("workflow/remove-workflow/{workflow_id}", [WorkflowController::class, "removeWorkflow"]); 
        Route::delete("workflow/remove-workflow-details/{workflow_detail_id}", [WorkflowController::class, "removeWorkflowDetails"]); 

        Route::get("workflow/request-process/retrieve-all-request-types", [WorkflowRequestController::class, "retrieveWorkflowRequestTypes"]); 
        Route::get("workflow/request-process/relevant-workflows/{workflow_request_type_id}", [WorkflowRequestController::class, "retrieveRelevantWorkflows"]); 
        Route::post("workflow/request-process/get-request-workflow", [WorkflowRequestController::class, "retrieveRequestWorkflow"]); 
        Route::post("workflow/request-process/submit-data", [WorkflowRequestController::class, "submitWorkflowRequestData"]); 
        Route::get("workflow/approvel-alert", [WorkflowRequestController::class, "retrieveWorkflowApprovelAlertData"]);
        
        Route::get("workflow/retrieve-all-designation", [WorkflowController::class, "retrieveAllDesignation"]); 
        Route::get("workflow/retrieve-all-designation-from-search", [WorkflowController::class, "retrieveAllDesignationsFromQuerySearch"]); 
        Route::post("workflow/retrieve-first-aprover", [WorkflowRequestController::class, "retrieveWorkflowFirstAprover"]); 


        Route::post("workflow/request-approve", [WorkflowRequestController::class, "workflowRequestApproved"]); 
        Route::post("workflow/request-reject", [WorkflowRequestController::class, "workflowRequestRejected"]); 
        
        
        Route::get("asset/types", [AssetController::class, "getAssetTypes"]); 
        
        Route::get("supplier/get-all", [SupplierController::class, "getSuppliers"]); 
        Route::get("supplier/search", [SupplierController::class, "retrieveSupplierFromQuerySearch"]); 
        Route::get("supplier/reg-no", [SupplierController::class, "getSupplierRegNo"]); 
        
        Route::get("procurement/get-all-staff", [ProcurementController::class, "getProcurementStaffDetails"]); 
        Route::post("procurement/add-new-member", [ProcurementController::class, "addMemberToProcurementStaff"]); 
        Route::put("procurement/staff-update", [ProcurementController::class, "updateMemberToProcurementStaff"]); 
        Route::delete("procurement/staff-remove/{procurement_id}", [ProcurementController::class, "removeMemberFromProcurementStaff"]); 
        Route::post("procurement/send-quation", [ProcurementController::class, "createProcurement"]); 
        Route::put("procurement/submit-procurement", [ProcurementController::class, "updateProcurement"]); 
        Route::get("procurement/ids", [ProcurementController::class, "getProcurementIds"]); 
        Route::get("procurement/{procurement_id}", [ProcurementController::class, "getProcurements"]); 
        Route::get("procurement/by-user/{procurement_id}", [ProcurementController::class, "getProcurementsByUser"]); 
        Route::post("procurement/quotation-feedback", [ProcurementController::class, "createQuotationFeedback"]); 
        Route::put("procurement/quotation-feedback/update", [ProcurementController::class, "updateQuotationFeedback"]); 
        Route::put("procurement/quotation-feedback/quotation_complete/{procurement_id}", [ProcurementController::class, "quotationComplete"]); 
        Route::delete("procurement/quotation-feedback/{quotation_feedback_id}", [ProcurementController::class, "removeQuotationFeedback"]); 
        Route::get("procurement/get-all-quotation-feedback/{quotation_id}", [ProcurementController::class, "getQuotationFeedbacks"]); 
        
        Route::get("get-prefixes-data/{process_id}", [PrefixController::class, "getAllPrefixes"]); 

        Route::post("supplier", [SupplierController::class, "addOrUpdateSupplier"]); 
        Route::put("supplier/update", [SupplierController::class, "addOrUpdateSupplier"]); 
        Route::delete("supplier/remove/{supplier_id}", [SupplierController::class, "removeSupplier"]); 

        Route::get("all-assests", [AssetsManagementController::class, "index"]);
        Route::post("add-new-assests", [AssetsManagementController::class, "store"]); 
        Route::post('update-asset', [AssetsManagementController::class, 'update']);
        Route::delete('delete-asset/{id}', [AssetsManagementController::class, 'destroy']);
        Route::get('assets-image/{imagename}', [ImageController::class, 'showassetimages']);
    });
    Route::get("test", function (Request $request){
        // dd($request->header('name'));
        error_log($request->header('email'));
        return response()->json(['status' => 'success', 'message' => $request->header('email')]);
    }); 
});