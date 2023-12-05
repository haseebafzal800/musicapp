<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\models\Generous;

class GenerousController extends Controller
{
    function index()
    {
        $Generous = Generous::all();
        return response()->json([
            'data' => $Generous,
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
        $Generous = Generous::create($validatedData);
        return response()->json([
            'data' => $Generous,
            'type' => 'success'
        ]);
    }
    public function edit($id)
    {
        $generous = Generous::find($id);
        return response()->json([
            'data' => $generous,
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
        
        $Generous = Generous::where('id', $input['id'])->update([
            'title'    => $input['title'],
            'status'    => $input['status'],
        ]);
        $Generous_data = Generous::find($input['id']);
        return response()->json([
            'data' => $Generous_data,
            'type' => 'success'
        ]);
    }
    public function delete($id)
    {
        $Generous = Generous::find($id);
        $Generous->delete();
        return response()->json([
            'data' => $Generous,
            'type' => 'success'
        ]);
    }
}
