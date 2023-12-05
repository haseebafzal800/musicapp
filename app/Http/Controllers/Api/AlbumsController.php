<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Album;


class AlbumsController extends Controller
{
    function index()
    {
        $albums = Album::all();
        return response()->json([
            'data' => $albums,
            'type' => 'success'
        ]);
    }
    function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $album = Album::create($validatedData);
        return response()->json([
            'data' => $album,
            'type' => 'success'
        ]);
    }
    public function edit($id)
    {
        $album = Album::find($id);
        return response()->json([
            'data' => $album,
            'type' => 'success'
        ]);
    }
    public function update(Request $request)
    {
        $input = $request->all();
        
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'type' => 'error',
                'message' => $validator->errors()
            ], 422);
        }
        
        $album = Album::where('id', $input['id'])->update([
            'title'    => $input['title'],
        ]);
        $album_data = Album::find($input['id']);
        return response()->json([
            'data' => $album_data,
            'type' => 'success'
        ]);
    }
    public function delete($id)
    {
        $album = Album::find($id);
        $album->delete();
        return response()->json([
            'data' => $album,
            'type' => 'success'
        ]);
    }
}
