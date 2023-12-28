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
            // if($data->license_key=='0'){
            if('0'=='0'){
                if($data->is_online=='1'){
                    // $this->data = $songs;
                    $this->w_err = 'Already loggedin on another device';
                    $this->responsee(false, $this->w_err);
                }else{
                    $data->is_online = '1';
                    $data->save();
                    $data['api_token'] = $user->createToken('auth_token')->plainTextToken;
                    $this->data = $data;
                    $this->responsee(true);
                }
            }elseif('1'=='-1'){
            // }elseif($data->license_key=='-1'){
                $this->w_err = 'Please verify your license key first';
                $this->w_err = 'Please get your license key from admin and verify to login';
                $this->responsee(false, $this->w_err);
            }else{
                $this->w_err = 'Please verify your license key first';
                $this->responsee(false, $this->w_err);
            }
        } else {
            $this->d_err = 'These credentials do not match our records.';
            $this->responsee(false, $this->d_err);
        }
        return json_response($this->resp, $this->httpCode);
    }

    public function logout(Request $request)
    {
        if ($request->is('api*')) {
            $id = Auth::user()->id;
            $user = User::find($id);
            // var_dump($user); die;
            $user->is_online = '1';
            $user->save();
            $user->update(['is_online' => '0']);
            $request->user()->currentAccessToken()->delete();
            $this->responsee(true);
            return json_response($this->resp, $this->httpCode);
        }
    }
}
