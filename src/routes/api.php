<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\PermisionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\OrganizationHierarchiController;

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

    Route::group([
        "middleware" => ["auth:api","is_verify_email"]
    ], function(){
        Route::get("profile", [AuthController::class, "profile"]);
        Route::get("logout", [AuthController::class, "logout"]);

        Route::get("users", [UserController::class, "index"]);
        Route::post("addusers", [UserController::class, "store"]);

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

        Route::get("All-Activitys", [ActivityLogController::class, "index"]);

    });
    Route::post("added-new-node-to-organization", [OrganizationHierarchiController::class, "insertNewNodeToOrganizationHierarchi"]); 
    Route::get("retrieve-organization", [OrganizationHierarchiController::class, "retrieveOrganizationHierarchi"]); 


});