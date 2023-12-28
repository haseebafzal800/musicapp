<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RecentlyPlayed;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use DB;

class RecentlyPlayedController extends Controller
{
    public function store(Request $request)
    {
        // $request->merge(['user_id' => auth()->user()->id]);
        
        $validator = Validator::make($request->all(), [
            'song_id' => 'required',
        ]);
        // Assuming you have the authenticated user
        if ($validator->fails())
            $this->responsee(false, $validator->errors());
        else{
            $user = auth()->user();
            // $this->data = $user->recentlyPlayed()->attach($request->song_id);

            $recentlyPlayed = RecentlyPlayed::firstOrNew([
                'user_id' => $user->id,
                'song_id' => $request->song_id,
            ]);
            
            // If the record already exists, update it to the last position
            if ($recentlyPlayed->exists) {
                $recentlyPlayed->update(['updated_at' => Carbon::now()]);
            } else {
                // Save the new record
                $this->data = $recentlyPlayed->save();
                // $this->data = $user->recentlyPlayed()->attach($request->song_id);
                $count = RecentlyPlayed::where('user_id', $user->id)->count();
                if($count>10){

                    $d = RecentlyPlayed::orderBy('updated_at', 'asc')->where('user_id', $user->id)->first();
                    if($d)
                       $d->delete();
                }
            }
            $this->responsee(true);
        }
        
        return json_response($this->resp, $this->httpCode);
    }
    
    public function index()
    {
        $user = auth()->user();
        $this->data = RecentlyPlayed::orderBy('updated_at', 'desc')->where('user_id', $user->id)->with('song')->get();
        $this->data = DB::table('recently_played')
                            ->join('songs', 'recently_played.song_id', '=', 'songs.id')
                            ->select('songs.*')
                            ->get();
        // $recentlyPlayed = $user->recentlyPlayed()->with('song')->orderBy('updated_at', 'desc')->get();
        if($this->data->count()>0)
        $this->responsee(true);
        else
            $this->responsee(false, $this->d_err);
        return json_response($this->resp, $this->httpCode);
    }

    public function deleteRecentPlayed($id=null){
        $user = auth()->user();
        if($id){
            $this->data = $user->recentlyPlayed()->where('song_id', $id)->delete();
            if($this->data)
                $this->responsee(true);
            else
                $this->responsee(false, $this->w_err);
        }
        else{
            $this->data = $user->recentlyPlayed()->delete();
            if($this->data)
                $this->responsee(true);
            else
                $this->responsee(false, $this->w_err);
        }
        return json_response($this->resp, $this->httpCode);
    }
}
