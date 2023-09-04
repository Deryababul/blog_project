<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResources;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{
    public function index(){

    }
    public function store(Request $request){
        $validatedData = Validator::make($request->all(),[
            'user_id' => 'required',
            'text' => 'required',
            'blog_id' => 'required|max:5'
        ]);
            if($validatedData->fails()){
                return response()->json([
                    'status'=> false,
                    'message'=>'validation error',
                    'erros'=>$validatedData->errors()
                ]);
            }
            $user = $request->user();
            $comments = Comment::create([
                'text' => $request->text,
                'user_id' => $user->id,
                'blog_id' =>$request->blog_id
            ]);
      
        return response()->json(['message' => 'Comment created!', 'comment' => $comments]);
    
}
    public function update(Request $request,$comment){
        $validator =Validator::make($request->all(),[
            "text" =>"min:1|max:100",
            "user_id" =>'required',
            "blog_id" =>'required|max:5'
        ]);
        if($validator->fails()){
            return response()->json([
                'validation_errors' =>$validator->errors()
            ]);
        }

        $comments = Comment::findOrFail($comment);
        
        if($request->has('text')){
            $comments->text = $request->text;
        }
        if($request->has('user_id')){
            $comments->user_id = $request->user_id;
        }
        if($request->has('blog_id')){
            $comments->blog_id = $request->blog_id;
        }
        $comments->save();
        return response()->json(['message' => new CommentResources($request)]);
    }
    public function delete(Comment $comment){
        $comment->delete();
        return response()->json(['message' => 'comment deleted']);        
    }
    
}
