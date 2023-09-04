<?php

namespace App\Http\Controllers;
use App\Http\Resources\BlogResources;
use App\Http\Resources\CategoryResources;
use App\Models\Blog;
use App\Models\Category;
use App\Http\Controllers\BlogController;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){ //tüm kategorileri getircem
        try {
            $categories = Category::all();
            return response()->json(['categories' =>CategoryResources::collection($categories)]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);

        }

    }

    public function show(Category $category){
        try {
           return response()->json(['category' => new CategoryResources($category)]);
                }
            catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 400);

        }
        
    }
    public function getBlogs(Category $category)
    {
        try {
            $blogs = $category->blogs ;
            return response()->json([
                'category' => new CategoryResources($category),
                'blogs' => BlogResources::collection($blogs)]);
        }        
        catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);

        }
    }
    
}

