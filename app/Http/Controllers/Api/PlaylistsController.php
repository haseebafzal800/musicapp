<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Playlist;

class PlaylistsController extends Controller
{
    function index()
    {
        $this->data = Playlist::where('user_id', auth()->user()->id)->get();
        if($this->data)
            $this->responsee(true);
        else
            $this->responsee(false, $this->d_err);
        return json_response($this->resp, $this->httpCode);
    }
    function store(Request $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails())
            $this->responsee(false, $validator->errors());
        else{
            $this->data = Playlist::create($request->all());
            if($this->data)
                $this->responsee(true);
            else
                $this->responsee(false, $this->d_err);
        }
        return json_response($this->resp, $this->httpCode);
    }
    
    
    public function edit($id)
    {
        if($id){
            $this->data = Playlist::where('user_id', auth()->user()->id)->find($id);
            if($this->data)
                $this->responsee(true);
            else
                $this->responsee(false, $this->d_err);
        }else
            $this->responsee(false, $this->id_err);
        return json_response($this->resp, $this->httpCode);
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails())
            $this->responsee(false, $validator->errors());
        else{
            $this->data = Playlist::where('user_id', auth()->user()->id)->where('id', $request->id)->first();
            if($this->data){
                if($this->data->update($request->all()))
                   $this->responsee(true);
                else
                    $this->responsee(false, $this->w_err);
            }else
                $this->responsee(false, $this->d_err);
        }
        return json_response($this->resp, $this->httpCode);
    }

    public function delete($id)
    {
        if($id){
            $this->data = Playlist::find($id);
            if($this->data){
                if($this->data->delete())
                    $this->responsee(true);
                else
                    $this->responsee(false, $this->w_err);
            }else
                $this->responsee(false, $this->d_err);
        }else
            $this->responsee(false, $this->id_err);
        return json_response($this->resp, $this->httpCode);
    }
}

