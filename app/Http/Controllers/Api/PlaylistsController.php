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
        $Playlist = Playlist::all();
        return response()->json([
            'data' => $Playlist,
            'type' => 'success'
        ]);
    }
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $Playlist = Playlist::create($validatedData);
        return response()->json([
            'data' => $Playlist,
            'type' => 'success'
        ]);
    }
    public function edit($id)
    {
        $Playlist = Playlist::find($id);
        return response()->json([
            'data' => $Playlist,
            'type' => 'success'
        ]);
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
        
        $Playlist = Playlist::where('id', $input['id'])->update([
            'title'    => $input['title'],
            'status'    => $input['status'],
        ]);
        $Playlist_data = Playlist::find($input['id']);
        return response()->json([
            'data' => $Playlist_data,
            'type' => 'success'
        ]);
    }
    public function delete($id)
    {
        $Playlist = Playlist::find($id);
        $Playlist->delete();
        return response()->json([
            'data' => $Playlist,
            'type' => 'success'
        ]);
    }
}
