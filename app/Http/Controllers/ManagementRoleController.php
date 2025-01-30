<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ManagementRoleController extends Controller
{
    use ApiResponseTrait;
    public function store(Request $request){
        $user = auth()->user();
        if ($user->can('assigne user to role')) {
        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);
        $user->assignRole($role->name); 

        return $this->ApiResponse(null , 'assign Role successfully' , 200);
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }


    public function removeRole(Request $request){
        $user = auth()->user();
        if ($user->can('remove user to role')) {
        $user = User::find($request->user_id);
        $role = Role::find($request->role_id);
        $user->removeRole($role->name);
        return $this->ApiResponse(null , 'remove Role successfully' , 200);
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 


    }
}
