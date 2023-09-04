<?php

namespace App\Http\Controllers;

use App\Models\Media;
use App\Models\User;
use App\Http\Resources\UserResources;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
class UserController extends Controller
{
    public function index(){
        $user = Auth::user();
        return [
            'user' => new UserResources($user),

        ];
    }

    public function register(Request $request){
        try{
            $validateUser = Validator::make($request->all(),
            [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required'
            ]);
            if($validateUser->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'validation failed',
                    'errors' => $validateUser->errors(),
                ]);
            }
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password)
            ]);
            return response()->json([
                'status' => true,
                'message' => 'user completed',
                'user'=> new UserResources($user),
                'token'=> $user->createToken("API TOKEN")->plainTextToken, //plainText Token düz metin değerine çevirir

            ]);

        }catch(\Throwable $th){
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ]);
        }
        }
    public function login(Request $request){
        try{
            $validateUser = Validator::make($request->all(),
        [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if($validateUser->fails()){
            return response()->json([
                'status'=> false,
                'message'=>'validation error',
                'erros'=>$validateUser->errors()
            ]);
        }
        if(!Auth::attempt( //kimlik bilgileriyle eşleşen kullanıcıyı sorgular
            $request->only(['email','password'])

        )){
            return response()->json([
                'status'=>false,
                'message'=>'email password does not match with our record',
        ]);
        }

        $user = User::where('email', $request->email)->first();
        return response()->json([
            'status'=> true,
            'message'=>'login completed',
            'token'=> $user->createToken("API TOKEN")->plainTextToken //plainText Token düz metin değerine çevirir

        ]);
        
        }catch(\Throwable $th){
            return response()->json([
                'status'=>false,
                'message' => $th->getMessage()
            ]);
        }
    }

    public function profileUpdate(Request $request)
    {
        // $user = $request->user();
        // return $user;

        $validateUser = Validator::make($request->all(),[
            'name' => 'required|max:255',
            'password' => 'required'
        ]);
        if ($validateUser->fails()) {
            return response()->json([
                'validation_errors' => $validateUser->errors()
            ]);
        }
        $user = User::findOrFail(Auth::user()->id);
        

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }
        $user->save();
        echo $user;
        return response()->json(['message:', 'Profile updated.']);
    }

    public function profile_photo(Request $request){
        $user = Auth()->user(); 
        //oturum açmış kullanıcıyı alır. Giriş yapmış kullanıcının olması gerek önemli
        // $profilePhotoPath = $request->file('profile_photo')->store('profile-photos', 'public');
        $media = new Media();
        //     'path_name' => $profilePhotoPath,
        // ]);
        // $media->save();
        // echo($user);
        // $user->update([
        //     'media_id' =>$media->id,
        // ]);
        $user->media()->associate($media); // Kullanıcının media_id'sini yeni media ile ilişkilendirir
        $user->media_id = $request->media_id;
        $user->save();

        echo $user;        
    }
    public function getBlogs($user){
        $users = User::find($user);
        $blogs = $users->blogs; //where aktiflik kontrol
        return response()->json($blogs);

    }

}
