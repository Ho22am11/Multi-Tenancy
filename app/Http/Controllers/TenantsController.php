<?php

namespace App\Http\Controllers;

use App\Http\Requests\TenantRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Validator;

class TenantsController extends Controller
{
    use ApiResponseTrait ;

    public function index()
    {
        $user = auth()->user();

       
        if ($user->can('get all tenants')) {
        $tenant = Tenant::all();
        return $this->ApiResponse($tenant , 'get all tenants successfully' , 201 );
        }else {
            return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
        
    }



    public function store(TenantRequest $request)
    {
        $user = auth()->user();

        
        if ($user->can('create tenant')) {
            $tenant = Tenant::create($request->all());

        
            return $this->ApiResponse($tenant , 'store tenant successfully' , 201 );  
        }
        
        else {
            return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }


    public function show($id)
    {
        $user = auth()->user();

        
        if ($user->can('show tenant')) {
        $tenant = Tenant::find($id);
        return $this->ApiResponse($tenant , 'get tenants successfully' , 201 );
        } else {
            return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    }


    public function update(TenantRequest $request, $id)
    {
        $user = auth()->user();

        
        if ($user->can('update tenant')) {
        $tenant = Tenant::find($id);
        $tenant->update($request->all());
        return $this->ApiResponse($tenant , 'updated tenants successfully' , 201 );
        } else {
            return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }


    public function destroy($id)
    {
        $user = auth()->user();

        
        if ($user->can('destroy tenant')) {
        Tenant::destroy($id);
        return $this->ApiResponse( null , 'deleted tenants successfully' , 201 );
        } else {
            return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }

    public function assigne(Request $request){
        $user = auth()->user();
        if ($user->can('assigne user to tenant')) {
        $user = User::find($request->user_id);
        $user->update(['tenant_id' => $request->tenant_id]);
        return $this->ApiResponse( $user , 'update tenant user successfully' , 201 );
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    }
}
