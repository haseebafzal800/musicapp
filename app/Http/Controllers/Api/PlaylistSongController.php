<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\PlaylistSong;
use App\Models\Song;
use Illuminate\Support\Facades\Validator;

class PlaylistSongController extends Controller
{
    public function index($playlistId)
    {
        $playlist = Playlist::findOrFail($playlistId);
        if($playlist){
            // $songs = $playlist->songs()->get();
            // $this->data = $playlist->songs()->paginate(10);
            $songs = $playlist->songs()->paginate(10);
            if($songs->count()){
                $this->data = $songs;
                $this->responsee(true);
            }
            else
                $this->responsee(false, $this->d_err);
        }else{
            $this->d_err = 'Playlist not found';
            $this->responsee(false, $this->d_err);
        }
        return json_response($this->resp);
    }

    public function store(Request $request, $playlistId)
    {
        $validator = Validator::make($request->all(), [
            'playlist_id' => 'required|numeric',
            'song_id' => 'required|numeric',
        ]);

        if ($validator->fails())
            $this->responsee(false, $validator->errors());
        else{
            $playlist = Playlist::findOrFail($request->playlist_id);
            if($playlist){
                $song = Song::findOrFail($request->song_id);
                if($song->count()){
                    $this->data = $playlist->songs()->attach($request->song_id);
                    if($this->data)
                        // $this->responsee(false, $this->data);
                        $this->responsee(true);
                    // else
                    //     $this->responsee(false, $this->w_err);
                }else{
                    $this->d_err = 'Error! Song not found';
                    $this->responsee(false, $this->d_err);
                }
            }else{
                $this->d_err = 'Error! Playlist not found';
                $this->responsee(false, $this->d_err);
            }
        }
        return json_response($this->resp);
    }
    public function delete($playlist_id, $song_id)
    {
        if($playlist_id && $song_id){
            $playlist = Playlist::find($id);
            if($playlist){
                if($this->data->delete())
                    $this->responsee(true);
                else
                    $this->responsee(false, $this->w_err);
            }else
                $this->responsee(false, $this->d_err);
        }else
            $this->responsee(false, $this->id_err);
        return json_response($this->resp);
    }
    public function destroy($playlistId, $songId)
    {
        $playlist = Playlist::findOrFail($playlistId);

        // Detach the song from the playlist
        $playlist->songs()->detach($songId);

        return response()->json(['message' => 'Song removed from playlist successfully']);
        return json_response($this->resp);
    }
}
