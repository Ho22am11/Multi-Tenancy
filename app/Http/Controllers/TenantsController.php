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
        $tenant = Tenant::all();
        return $this->ApiResponse($tenant , 'get all tenants successfully' , 201 );
        
    }



    public function store(TenantRequest $request)
    {

        $tenant = Tenant::create($request->all());

        
        return $this->ApiResponse($tenant , 'store tenants successfully' , 201 );

    }


    public function show($id)
    {
        $tenant = Tenant::find($id);
        return $this->ApiResponse($tenant , 'get tenants successfully' , 201 );
    }


    public function update(TenantRequest $request, $id)
    {
        $tenant = Tenant::find($id);
        $tenant->update($request->all());
        return $this->ApiResponse($tenant , 'updated tenants successfully' , 201 );

    }


    public function destroy($id)
    {
        Tenant::destroy($id);
        return $this->ApiResponse( null , 'deleted tenants successfully' , 201 );

    }

    public function assigne(Request $request){
        $user = User::find($request->user_id);
        $user->update(['tenant_id' => $request->tenant_id]);
        return $this->ApiResponse( $user , 'update tenant user successfully' , 201 );
    }
}
