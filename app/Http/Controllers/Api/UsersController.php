<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => request('email'), 'password' => request('password'), 'status'=>'active'])) {
            $user = Auth::user();

            $data = User::where('id', $user->id)->first();
            if($data->is_online=='1'){
                $this->resp = ['status'=>false, 'code'=>201, 'message'=>'Already loggedin on another device', 'data'=>null];
            }else{
                $data->is_online = '1';
                $data->save();
                $data['api_token'] = $user->createToken('auth_token')->plainTextToken;
                $this->resp = ['status'=>true, 'code'=>200, 'message'=>'Login Successfully', 'data'=>$data];
            }
        } else {
            $this->resp = ['status'=>false, 'code'=>201, 'message'=>'These credentials do not match our records.', 'data'=>''];
        }
        return json_response($this->resp);
    }

    public function logout(Request $request)
    {
        if ($request->is('api*')) {
            $request->user()->currentAccessToken()->delete();
            $this->resp = [
                'status' => true,
                'data' => null,
                'message' => "Successfully Logging out",
                'code'=>200,
            ];
            return json_response($this->resp);
        }
    }
}
