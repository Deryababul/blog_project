<?php

namespace App\Http\Middleware;

use App\Models\Blog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class activePost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $blog_id = $request->blog_id;

        $blog = Blog::find($blog_id);
        $active = $blog->is_active;
        if ($active==0) {
            return response()->json(['error' => 'Blog aktif değillll'], 400);
        }
        return $next($request);
    
    }
}
