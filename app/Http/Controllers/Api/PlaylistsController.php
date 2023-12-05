<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Playlist;
use App\models\Generous;

class PlaylistsController extends Controller
{
    function index()
    {
        $playlist = Playlist::all();
        $this->resp['data'] = $playlist;
        return json_response($this->resp);
    }
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $this->resp = ['status'=>false, 'code'=>201, 'message'=>$validator->errors(), 'data'=>''];
        }
        else{
            $validatedData = $validator->validated();
            $Playlist = Playlist::create($validatedData);
            $this->resp['data'] = $Playlist;
        }
        return json_response($this->resp);
    }
    
    
    public function edit($id)
    {
        if($id){
            $Playlist = Playlist::find($id);
            $this->resp['data'] = $Playlist;
        }else{
            $this->resp = ['status'=>false, 'code'=>201, 'message'=>'Data not found', 'data'=>''];
        }
        return json_response($this->resp);
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'status' => 'required',
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            $this->resp = ['status'=>false, 'code'=>201, 'message'=>$validator->errors(), 'data'=>''];
        }
        else{
            $Playlist = Playlist::where('id', $input['id'])->update($request->all());
            $Playlist_data = Playlist::find($input['id']);
            $this->resp['data'] = $Playlist_data;
        }
        return json_response($this->resp);
    }

    public function delete($id)
    {
        if($id){
            $Playlist = Playlist::find($id);
            if($Playlist){
                $Playlist->delete();
                $this->resp['data'] = $Playlist;
            }else{
                $this->resp = ['status'=>false, 'code'=>201, 'message'=>'Data not found', 'data'=>''];
            }
        }else{
            $this->resp = ['status'=>false, 'code'=>201, 'message'=>'Data not found', 'data'=>''];
        }
        return json_response($this->resp);

    }
}

