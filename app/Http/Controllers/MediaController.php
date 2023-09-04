<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use League\CommonMark\Normalizer\UniqueSlugNormalizer;

class MediaController extends Controller
{
    public function store(Request $request)
    { 

        $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', 
            'type' => 'required|in:profile,blog,generate'
        ]);
        $user = $request->user();
        $file = $request->file('file');

        if ($file) {
            $fileName =Str::random(16).'.' . $file->getClientOriginalExtension();

            $userr = $user->id;
            if($request->type == 'blog'){ 
                $pathName = 'images/'.$userr.'/blogs/'.$fileName;
                $file->move(public_path(
                    'images/'.$userr.'/blogs'), $fileName);
            }
            else{
                $pathName = 'images/'.$userr.'/profile/'.$fileName;
                $file->move(public_path('images/'.$userr.'/profile'), $fileName);
            }
            //veritabanına kaydetme kısmı
            $media = new Media();
            $media->path_name = $pathName;
            $media->type=$request->type;
            $media->save();

            return response()->json([
                'message' => new MediaResource($request),
                'file_path' => $pathName,
                'url' => asset($pathName)]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    
        }
    
    public function delete(Media $media)
    {
        $media_path= $media->path_name;
        if(file_exists($media_path)){
            unlink(public_path($media_path));
            $media->delete();
            echo "aa";
        }else{
            echo "bb";
        }

    }
}
