<?php

namespace App\Http\Controllers;

use App\Models\Media;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use App\Services\ApiService;

class DenemeController extends Controller
{
    public function translate(Request $request){
       $response = Http::asForm()->withHeaders([
        "X-RapidAPI-Host"=>"text-translator2.p.rapidapi.com",
        "X-RapidAPI-Key"=>"ca2039792dmshf31f87b7d4c9408p10b292jsn1db627e825a5"
       ])
       ->withOptions(['verify' => false])
       ->post('https://text-translator2.p.rapidapi.com/translate',[
        'source_language' => $request->source_language,
        'target_language' => $request->target_language,
        'text' => $request->message,
       ]);
       if($response->status() != 200) {
        return 'hata';
       }
       $objectData = $response->object();
       $translateMsg = $objectData->data->translatedText;
       return response()->json([
        'orgMsg' => $request->message,
        'translatedMsg' => $translateMsg,
       ]);
}


    public function generation(Request $request){
        $apiUrl = 'https://api.stability.ai/v1/generation/stable-diffusion-xl-1024-v1-0/text-to-image';
        $apiKey = 'sk-DFBBGISbTiVGXt0s68tkRC29oTbM5ppYpxpkPSm3lvhZvTqP';
        $response = Http::timeout(5000000)->withHeaders(
            [
                "Content-Type"=> "application/json",
                "Authorization"=>"Bearer sk-DFBBGISbTiVGXt0s68tkRC29oTbM5ppYpxpkPSm3lvhZvTqP"
            ])->withOptions(['verify'=>false])
            ->post('https://api.stability.ai/v1/generation/stable-diffusion-v1-5/text-to-image',[
                "text_prompts"=> [
                    [
                        "text"=> "ship in the ocean"
                    ]
                  ],
                  "cfg_scale"=> 7,
                  "height"=> 1024,
                  "width"=> 1024,
                  "samples"=> 1,
                  "steps"=> 30
                ]);

      
        if ($response->successful()) {
            $userId = $request->input('user_id');
            $data = $response->object();
            $imageorg = $data->artifacts[0]->base64; //base64 kısmını çektik
            $image_base64 = base64_decode($imageorg);
            $imageData = $image_base64;

            //dosya oluşturma
            if (!file_exists(public_path('images/'.$userId.'/generate/'))) {
                mkdir(public_path('images/'.$userId.'/generate/'), 0777, true);
            }
            // Dosya yolu ve adı
            $imageName = Str::random(10) . '.jpg'; 
            $pathName = 'images/'.$userId.'/generate/'.$imageName;
            $imagePath = public_path($pathName);
            Image::make($imageData)->save($imagePath);// Resmi kaydet
            //veritabanına kaydetme kısmı
            $image = new Media();
            $image->path_name = $pathName;
            $image->save();

            return [
                'image_url' => asset('images/'.$userId.'/generate/'.$imageName), //kaydedilen resim urlsi için
                'message' => 'Image generated and saved successfully.'
            ];
        } else {
            return [
                'error' => 'Failed to generate the image. API returned an error.',
                'status_code' => $response->status()
            ];
        }
              }
public function registerDeneme(){
    $registerResponse = Http::withOptions(['verify'=>false])->post("https://auth.ronservice.co/register?project=ecommerce", [
        "email" => "babulderya626@gmail.com",
        "password" => "password",
        "given_name" => "Derya",
        "family_name" => "Babul",
        "user_data[organization]" => "Ron Digital"
    ]);
    if ($registerResponse->status() != 200) {
        return $registerResponse->object()->error;
    }
    return $registerResponse->object();
}
public function loginDeneme(){

    $registerResponse = Http::withOptions(['verify' => false])->post("https://auth.ronservice.co/login?project=ecommerce", [
        "username" => "babulderya626@gmail.com",
        "password" => "12345678",
    ]);
    if ($registerResponse->status() != 200) {
        return $registerResponse->object()->error;
    }
    return $registerResponse->object();
}

public function logoutDeneme(Request $request){

    $logoutResponse = Http::withOptions(['verify' => false])->withToken($request->bearerToken())->withHeaders(["Content-Type" => "application/json"])->get("https://auth.ronservice.co/logout?project=ecommerce");
    if ($logoutResponse->getStatusCode() != 200) {
        return $logoutResponse->object()->error;;
    }
    return $logoutResponse->object();
}
public function resetPassword(){
    $resetPasswordResponse = Http::withOptions(['verify'=>false])->post("https://auth.ronservice.co/reset-password?project=ecommerce",[
        'email' => 'headers@gmail.com'
    ]);
    if ($resetPasswordResponse->getStatusCode() != 200) {
        return $resetPasswordResponse->object()->error;
    }
    return $resetPasswordResponse->object();
}
public function verifyEmail(Request $request){
    $emailVerifyResponse = Http::withOptions(['verify' => false])->withToken($request->bearerToken())->withHeaders(["content-type" => "application/json", ])->post("https://auth.ronservice.co/verify-email?project=ecommerce", [
        'userId' => "auth0|64d21e0b357f8c34c72bddfa"
    ]);
    if ($emailVerifyResponse->getStatusCode() != 200) {
        return $emailVerifyResponse->object()->error;
    }
    return $emailVerifyResponse->object();
}
public function authUser(Request $request){
    $authResponse = Http::withOptions(['verify' => false])->withToken($request->bearerToken())->withHeaders(["content-type" => "application/json"
    ])->get("https://auth.ronservice.co/auth-user?project=ecommerce");
    if ($authResponse->getStatusCode() != 200) {
        return $authResponse->object()->error;
    }
    return $authResponse->object();
}
public function updateUser(Request $request){
    $updateResponse = Http::withOptions(['verify' => false])
    ->withToken($request->bearerToken())
    ->withHeaders(["conntent-type" => "application/json"])
    ->post("https://auth.ronservice.co/update?project=ecommerce", [
        "user_data[github]" => "github.com/cerenozkurt",
        "nickname" => "cerenimo"
    ]);
    if ($updateResponse->getStatusCode() != 200) {
        return $updateResponse->object()->error;
    }
    return $updateResponse->object();
}
public function searchUser(Request $request){
    $searchResponse = Http::withOptions(['verify' => false])
    ->withToken($request->bearerToken())
    ->withHeaders(["content-type" => "application/json"])
    ->get("https://auth.ronservice.co/search?project=ecommerce&detailName=subscriptionID&detailValue=1");
    if ($searchResponse->getStatusCode() != 200) {
        return $searchResponse->object()->error;
    }
    return $searchResponse->object();
}
public function updateUserById(Request $request){
    $updateResponse = Http::withOptions(['verify' => false])
    ->withHeaders(["Content-Type" => "application/json"])
    ->post("https://auth.ronservice.co/auth0|64d21e0b357f8c34c72bddfa/update?project=ecommerce", [
        "user_data[github]" => "github.com/cerenozkurt",
        "nickname" => "cerenimo"
    ]);
    if ($updateResponse->getStatusCode() != 200) {
        return $updateResponse->object()->error;
    }
    return $updateResponse->object();
}
}