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

        Route::get("users", [UserController::class, "index"]); 
        Route::post("addusers", [UserController::class, "store"]);
        Route::put('usersupdate/{id}', [UserController::class, 'updateuserdata']);
        Route::put('changeuserstatus/{id}', [UserController::class, 'changestatus']);
        Route::put('userpasswordreset/{id}', [UserController::class, 'passwordreset']);
        Route::delete('userdelete/{id}', [UserController::class, 'destroy']);
        
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
        
    });
});
