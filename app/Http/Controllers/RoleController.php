<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    use ApiResponseTrait ;
    
    public function index()
    {
        $user = auth()->user();
        if ($user->can('get all role of tenant')) {
        $roles = Role::where('tenant_id', $user->tenant_id)->get()->pluck('name');
        return $this->ApiResponse($roles , 'roles get successfully' , 200);
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    }


    public function store(Request $request)
    {
        $user = auth()->user();
    if ($user->can('create role')) {
        try{
            
        $role = Role::create([
            'name' => $request->name ,
            'tenant_id' => $user->tenant_id,
        ]);

        return $this->ApiResponse($role , 'role created successfully' , 201);
       }catch(Exception $e){
        return response()->json([
            'error' => 'Something went wrong',
            'message' => $e->getMessage()], 500);
       }
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    }

    
    public function show($id)
    {
        $user = auth()->user();
        if ($user->can('show role')) {
        $role = Role::find($id);
        $users = $role->users->pluck('name');
        $data = [
            'role_name' => $role->name,
            'users' => $users
        ];
        return $this->ApiResponse($data , 'role show successfully' , 200);
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->can('update role')) {
        try{
        
        $role = Role::find($id);
        $role->update([
            'name' => $request->name ,
        ]);
        return $this->ApiResponse($role , 'role updated successfully' , 200);
     }catch(Exception $e){
        return response()->json([
            'error' => 'Something went wrong',
            'message' => $e->getMessage()], 500);
       }
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->can('destroy role')) {
        Role::destroy($id);
        return $this->ApiResponse(null, 'role deleted successfully' , 200);
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    }

}
