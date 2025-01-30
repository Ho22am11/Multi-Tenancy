<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{

    use ApiResponseTrait ;
    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->can('create attachment')) {
        $file = $request->file('file');
        $type = $file->getClientOriginalExtension();
        $file_name = uniqid().'.'.$type;
        $path = 'files' ;
        $file->storeAs($path , $file_name , 'attechment');

        $attachment = Attachment::create([
            'name' => $file_name ,
            'type' => $type ,
            'task_id' => $request->task_id ,
            'user_id' => $user->id ,
        ]);

        return $this->ApiResponse($attachment , 'store attachment successfully' , 201 ) ;
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    }

    
    public function show($id)
    {
        $user = auth()->user();
        if ($user->can('show attachment')) {
        $attachment = Attachment::find($id);
        return $this->ApiResponse($attachment , 'show attachment successfully' , 200 ) ;
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }



    public function update(Request $request )
    {
        $user = auth()->user();
        if ($user->can('update attachment')) {
          $attachment = Attachment::find($request->attachment_id);
          $path = 'E:/laragon/www/task_project/storage/files/'.$attachment->name; 

            if (file_exists($path)) {
                unlink($path);
            } else {
                return "File not found or invalid path: " . $path;
            } 


            $file = $request->file('img');

            if (!$file) {
                return $this->ApiResponse($request->task_id , 'No file uploaded', 400);
            }

            $type = $file->getClientOriginalExtension();
            $file_name = uniqid().'.'.$file->getClientOriginalExtension();
            $path = 'files' ;
            $file->storeAs($path , $file_name , 'attechment');
    
            $attachment->update([
                'name' => $file_name ,
                'type' => $type ,
            ]);
    
            return $this->ApiResponse($attachment , 'store attachment successfully' , 201 ) ;
        } else {
            return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 
    
            
    }


    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->can('destroy attachment')) {
        $attachment = Attachment::find($id);
        $path = 'E:/laragon/www/task_project/storage/files/'.$attachment->name; 

            if (file_exists($path)) {
                unlink($path);
            } else {
                return "File not found or invalid path: " . $path;
            }
            Attachment::destroy($id);

        return $this->ApiResponse( null , 'deleted attachment successfully' , 200 ) ;
    } else {
        return $this->ApiResponse( null , 'you do not have permission  ' , 400 ); } 

    }
}
