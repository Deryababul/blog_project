<?php

use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DenemeController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\activePost;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return 'Hello World';
});

Route::controller(DenemeController::class)-> group(function(){
Route::post('/translate','translate');
Route::post('/generation', 'generation');
Route::post('/registerDeneme', 'registerDeneme');
Route::post('/loginDeneme', 'loginDeneme');
Route::get('/logoutDeneme', 'logoutDeneme');
Route::post('/resetPassword', 'resetPassword');
Route::post('/verifyEmail', 'verifyEmail');
Route::get('/authUser', 'authUser');
Route::get('/searchUser', 'searchUser');
Route::post('/updateUser', 'updateUser');
Route::post('/updateUserById', 'updateUserById');
Route::post('/ProductPlan', 'ProductPlan');
});
//usercontroller
Route::controller(UserController::class)->prefix('users')->middleware('auth:sanctum')-> group(function(){
Route::get('/', 'index');
Route::post('/register','register');
Route::post('/login', 'login');
Route::put('/','profileUpdate');
Route::post('/profile_photo','profile_photo')->middleware('auth:sanctum');;
Route::post('/{user}', 'getBlogs');
});
//blogcontroller
Route::controller(BlogController::class)->prefix('blogs')->middleware('auth:sanctum')-> group(function(){
Route::get('/', 'index');
Route::post('/', 'store');
Route::put('/{blog}', 'update');
Route::get('/list', 'list');
Route::delete('/{blog}', 'destroy');
Route::get('/search',  'search');
Route::get('/yourPosts', 'yourPosts');
Route::get('/justActive', 'justActive');
});

//categorycontroller
Route::controller(CategoryController::class)->prefix('categories')->middleware(['auth:sanctum'])-> group(function(){
Route::get('/', 'index' );
Route::get('/{category}','show');
Route::get('/{category}/blogs', 'getBlogs');
});

//labelcontroller
Route::controller(LabelController::class)->prefix('labels')->middleware('auth:sanctum')-> group(function(){
Route::get('/', 'index'); //kullanıcının tüm etiketlerini getir
Route::post('/', 'store');
Route::get('/{label}', 'show');
Route::delete('/{label}', 'delete');
Route::get('/{label}/blogs', 'getBlogs');
});

//CommentController
Route::controller(CommentController::class)->prefix('comments')->middleware(['auth:sanctum', 'active.post'])-> group(function(){
Route::post('/', 'store');
Route::post('/{comment}', 'update');
Route::delete('/{comment}', 'delete');
});

//mediacontroller
Route::controller(MediaController::class)->prefix('medias')->middleware(['auth:sanctum'])-> group(function(){
Route::delete('/{media}','delete');
Route::post('/', 'store');
});