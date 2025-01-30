<?php

namespace App\Http\Controllers;

use App\Http\Requests\TaskRequest;
use App\Models\Task;
use App\Models\TaskAssignee;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    use ApiResponseTrait ;
    public function index()
    {
        $user = auth()->user();
        $tasks = Task::where('tenant_id' , $user->tenant_id)->get();
        return $this->ApiResponse($tasks , 'get all task successfully' , 201 );

    }

   
    public function store(TaskRequest $request)
    {
        $user = auth()->user();
        $data = $request->all();
        $data['create_by'] = $user->id ;
        $task = Task::create($data);
        return $this->ApiResponse($task , 'store task successfully' , 201 );
    }

   
    public function show($id)
    {
        $task = Task::find($id);
        return $this->ApiResponse($task , 'show task successfully' , 201 );

    }

    public function update(TaskRequest $request, string $id)
    {
        $task = Task::find($id);
        $task->update($request->all());
        return $this->ApiResponse($task , 'updated task successfully' , 201 );
    }


    public function destroy($id)
    {
        Task::destroy($id);
        return $this->ApiResponse(null, 'deleted task successfully' , 201 );
    }


    public function assignee(Request $request){
        TaskAssignee::create($request->all());
        return $this->ApiResponse(null, 'Task Assignee successfully' , 201 );
    }
}
