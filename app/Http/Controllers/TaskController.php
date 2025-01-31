<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Models\TaskHistor;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponseTrait ;
    public function index()
    {
        $user = auth()->user();
        if ($user->can('get all task')) {
        $tasks = Task::where('tenant_id' , $user->tenant_id)->get();
        return $this->ApiResponse($tasks , 'get all task successfully' , 201 );
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }

   
    public function store(TaskRequest $request)
    {
        $user = auth()->user();
        if ($user->can('create task')) {
        $data = $request->all();
        $data['create_by'] = $user->id ;
        $task = Task::create($data);
        return $this->ApiResponse($task , 'store task successfully' , 201 );
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }

   
    public function show($id)
    {
        $user = auth()->user();
        if ($user->can('show task')) {
        $task = Task::find($id);
        return $this->ApiResponse($task , 'show task successfully' , 201 );
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }

    public function update(TaskRequest $request, string $id)
    {
        $user = auth()->user();
        if ($user->can('update task')) {
        $task = Task::find($id);
        TaskHistor::create([
            'task_id' => $task->id ,
            'changed_by' => $user->id , 
            'old_value' => $task->state ,
            'new_value' => $request->state
        ]);
        if ($request->state == 3){
            $data = $request->all();
            $data['complete_at'] = now();
            $task->update($data);
            return $this->ApiResponse($data , 'updated task successfully' , 201 );

        }
        else{
            $task->update($request->all());
            return $this->ApiResponse($task , 'updated task successfully' , 201 );

        }
       
       
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }


    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->can('destroy task')) {
        Task::destroy($id);
        return $this->ApiResponse(null, 'deleted task successfully' , 201 );
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }


    public function assignee(Request $request){
        $user = auth()->user();
        if ($user->can('assigne user to task')) {
        TaskAssignee::create($request->all());
        return $this->ApiResponse(null, 'Task Assignee successfully' , 201 );
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }
}
