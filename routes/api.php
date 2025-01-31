<?php

use App\Http\Controllers\AnalyticsController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManagementRoleController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TenantsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register' , [AuthController::class, 'register']);
Route::post('/login' , [AuthController::class, 'login']);
Route::post('/logout' , [AuthController::class, 'logout']);
Route::post('/refresh' , [AuthController::class, 'refresh']);


Route::resource('/tenants' , TenantsController::class );
Route::post('/tenants/user-assignees' , [ TenantsController::class , 'assigne']);
Route::resource('/tasks' , TaskController::class );
Route::post('/tasks/Task-Assignees' , [ TaskController::class , 'assignee']);
Route::resource('/attachments' , AttachmentController::class );
Route::post('/attachments-up' , [AttachmentController::class , 'update'] );
Route::resource('/roles' , RoleController::class );
Route::resource('/roles/managment' , ManagementRoleController::class );
Route::post('/roles/managment/remove' , [ManagementRoleController::class , 'removeRole'] );
Route::get('/users/analysis' , [AnalyticsController::class , 'AnalysisUser'] );
Route::get('/users/analysis/time' , [AnalyticsController::class , 'AnalysisTime'] );


