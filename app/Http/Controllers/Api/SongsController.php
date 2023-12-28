<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Song;

class SongsController extends Controller
{
    
    function index($searchStr=null)
    {
        $perPage = request('per_page', 1);
        if($searchStr){
            $this->data = Song::where('user_id', auth()->user()->id)
            ->whereRaw('LOWER(title) LIKE ?', ['%' . strtolower($searchStr) . '%'])
            ->orderBy('id', 'desc')
            ->paginate($perPage);
        }else{
            $this->data = Song::orderBy('id', 'desc')->where('user_id', auth()->user()->id)->paginate($perPage);
        }
        if($this->data){
            $this->responsee(true);
        }
        else{
            $this->responsee(false, $this->d_err);
        }
        return json_response($this->resp, $this->httpCode);

        // $paginationData = [
        //     'current_page' => $songs->currentPage(),
        //     'per_page' => $songs->perPage(),
        //     'total' => $songs->total(),
        //     'last_page' => $songs->lastPage(),
        // ];

        // $links = [
        //     'first' => $songs->url(1),
        //     'last' => $songs->url($songs->lastPage()),
        //     'prev' => $songs->previousPageUrl(),
        //     'next' => $songs->nextPageUrl(),
        // ];

        // $meta = [
        //     'pagination' => $paginationData,
        // ];
        // $this->resp['data'] = ['songs' => $songs, 'links' => $links, 'meta' => $meta];
    }
    // function index()
    // {
    //     $data = Song::where('user_id', auth()->user()->id)->get();
    //     // $data = Song::where('user_id', auth()->user()->id)->with('user')->orderBy('id','DESC')->get();
    //     $this->resp['data'] = $data;
    //     return json_response($this->resp, $this->httpCode);
    // }
    function store(Request $request)
    {
        $request->merge(['user_id' => auth()->user()->id]);
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'user_id' => 'required',
        ]);

        if ($validator->fails())
            $this->responsee(false, $validator->errors());
        else{
            $this->data = Song::create($request->all());
            if($this->data){
                $this->responsee(true);
            }
            else{
                $this->responsee(false);
            }
        }
        return json_response($this->resp, $this->httpCode);
    }
    
    
    public function edit($id)
    {
        if($id){
            $this->data = Song::where('user_id', auth()->user()->id)->find($id);
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
            $this->responsee(false,$validator->errors());
        else{
            $this->data = Song::where('user_id', auth()->user()->id)->find($request->id);
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
            $this->data = Song::where('user_id', auth()->user()->id)->find($id);
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

