<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class extraController extends Controller
{
    public function deneme(){
        $user = User::find(1);
        $subcription_status = User::all()->where('subcription_status',1);
        $project_id = User::all()->where('project_id',1);
        $deneme = User::all()->where('mail_verified_users',1);
        return[
            'deneme' => new UserResource($user)
        ];
    }
    public function users_by_projectid($id){
        $users = User::all()->where('project_id',$id);
        return response()->json($users);
    }
    public function bugunKaydolan(){
        $today = Carbon::now()->format('Y-m-d');
        $users = User::whereDate('created_at', $today)->get();
        return response()->json($users);
        
    }
    public function index2(){
        $response =Http::withHeaders(['token'=> request()->header('token')])->get("192.168.68.88:8000/api/users");
        $today = Carbon::now()->format('Y-m-d');
        if ($response->successful()) {
            $responseData = $response->json();
            $users = $responseData['data']['users'];
            $users_count = count($users);
            $users_email_verify = [];
            $users_subscription = [];
            $today_users=[];
        
            foreach ($users as $user) {
                if($user['email_verified']==true){
                    $users_email_verify[]=$user;
                    $email_verified_count = count($users_email_verify);
                    }
                    
                $created_at = date('Y-m-d', strtotime($user['created_at']));
                if ($created_at == $today) {
                    $today_users[] = $user;
                    $today_users_count=count($today_users);
                }
                }
            foreach ($users as $user) {
                if (isset($user['subscription']) && $user['subscription']['subscription_status'] == "ACTIVE") {
                    $users_subscription[] = $user;
                    $subscription_count = count($users_subscription);
                }
            }
            
        }else{
            return response()->json([
                'error' => 'error'
            ]);
        }
                return response()->json([
                    'users_count' => $users_count,
                    'today_users_count' =>$today_users_count,
                    'today_users'=> $today_users,
                    'email_verified_count' => $email_verified_count,
                    'users_email_verified'=> $users_email_verify,
                    'subscription_count' => $subscription_count,
                    'users_subscription' => $users_subscription
                ]);
}
}
