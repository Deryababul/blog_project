<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResources;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Label;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class BlogController extends Controller
{
    public function index()
    {
        try {
            $hello = Blog::orderBy('created_at','desc')->paginate(4); //sayı olarak girdiğğimiz değer kadar veriyi ekrana getirir.
            return $hello;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }


    }
    public function store(Request $request) //blog yazılarını oluşturmak
    {
        // $validatedData = $request->validate([
        try {
            $validatedData = Validator::make($request->all(),[
                'title' => 'required|max:255',
                'text' => 'required',
                'category_id' => 'required|max:5',
                'label_id'=> 'array|exists:labels,id',
                'media_id' => 'array|exists:media,id',
            ]);
                if($validatedData->fails()){
                    return response()->json([
                        'status'=> false,
                        'message'=>'validation error',
                        'erros'=>$validatedData->errors()
                    ]);
                }
                $user = $request->user();
                $blogPost = Blog::create([
                    'title' => $request->title,
                    'text' => $request->text,
                    'user_id' => $user->id,
                    'category_id' =>$request->category_id,
                    'is_active' => 1
                ]);
        
                $blogPost->labels()->sync($request->label_id);
                $blogPost->media()->attach($request->media_id); //belongstomany için bağ kurma satırı
                //labellere
                //blog->labels()->attach('label_id');
                return response()->json(['message' => 'Blog post created!', 'blog' => $blogPost]);        
            
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()],400);
        }
        }

    public function update(Request $request, Blog $blog)
    {
        if ($request->user()->cannot('viewAny', $blog)) {
            abort(403);
        }
        try {
            
            $validator = Validator::make($request->all(), [
                "title" => "min:1|max:50",
                //min 3 max 50 karakter olsun
                "text" => "min:5|max:50",
                "is_active" => "max:5",
                'category_id' => 'max:5',
                'label_id' => 'array|exists:labels,id' //tüm labelları değil blogtaki labelları göstersin diye
            ]);
            if ($validator->fails()) {
                return response()->json([
                    'validation_errors' => $validator->errors()
                ]);
            }

            //bulamazsa işlemi iptal eder
    
            if ($request->has('title')) {
                $blog->title = $request->title; // requestten title geliyo model aracılığıla tabloya yaz
            }
            if ($request->has('text')) {
                $blog->text = $request->text; // request4
            }
            if ($request->has('is_active')) {
                $blog->is_active = $request->is_active;
            }
            if ($request->has('category_id')) {
                $blog->category_id = $request->category_id;
            }
            if($request->has('label_id')){
                $blog->labels()->sync($request->label_id);
            }

            $blog->save();
            return response()->json(['message' => new BlogResources($request)]); //burda
        
        } catch (\Exception $e) {
            return response()->json(['error'=>$e->getMessage()],400);
        }
        }

    public function destroy(Request $request ,Blog $blog)
    {
        if ($request->user()->cannot('viewAny', $blog)) {
            abort(403);
        }
        try {
            $blog->delete();
    
            return response()->json(['message' => 'Blog post deleted!']);     
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
       
    }
    public function list(){
        $blogs = Blog::where('is_active', 1)->get();
        return response()->json([
            'message' => BlogResources::collection($blogs)
        ]);
    }
    
    public function search(Request $request)
    {
        $columns = ['title', 'text'];
        if(!in_array($request->searchcolumn, $columns)){
            $column = 'title'; 
        }else{
            $column = $request->searchcolumn;
        }
        $searchTerm = $request->input('search');
        // if($request->keyword){
        $blogPosts = Blog::where($column, 'LIKE', "%$searchTerm%")
            // ->orWhere('text', 'like', "%$searchTerm%")
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json($blogPosts);
    
    }

    public function yourPosts(){
        $user = Auth::user();
        $blogs = $user->blogs;
        return response()->json($blogs);
    }
    public function justActive(){
        $activeUsers = Blog::all()->where('is_active', 1);
        return $activeUsers;
    }



}

