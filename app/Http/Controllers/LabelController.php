<?php

namespace App\Http\Controllers;

use App\Http\Resources\BlogResources;
use App\Http\Resources\LabelResources;
use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LabelController extends Controller
{
    public function index(){ //tüm labelları getircek
        $user = Auth::user();
        $labels = $user->labels; //tabloda ne kadar veir varsa json fomratında döndürür

        return response()->json(['labels' => LabelResources::collection($labels)]);
    }
    public function store(Request $request) //create
{
    try {
        $validatedData = Validator::make($request->all(),[
            'name' => 'required|max:255',
        ]);

            if($validatedData->fails()){
                return response()->json([
                    'status'=> false,
                    'message'=>'validation error',
                    'erros'=>$validatedData->errors()
                ]);
            }
            $user = $request->user();
            $label = Label::create([
                'name' => $request->name,
                'user_id' => $user->id

            ]);

            return response()->json(['message' => 'Label created!', 'label' => new LabelResources ($label)]);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 400);
    }

    }

    public function show(Label $label, Request $request)
    {
        if ($request->user()->cannot('viewAny', $label)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        return response()->json(['label' => new LabelResources($label)]);
    }
    public function getBlogs(Label $label){
        $blogs = $label->blogs ;
        return response()->json([
            'label' => new LabelResources($label),
            'blogs' => BlogResources::collection($blogs)]);
    }
    public function delete(Label $label, Request $request)
    {
        if ($request->user()->cannot('viewAny', $label)) {
            return response()->json(['error' => 'Forbidden'], 403);
        }
        $deleted = $label->delete();
        if($deleted){
            return response()->json(['message' => 'Label deleted!']);
        }
        return response()->json(['message' => 'Label deleted unsuccessful!']);

    }

}
