<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Playlist;
use App\Models\PlaylistSong;
use App\Models\Song;
use Illuminate\Support\Facades\Validator;
use App\Rules\UniquePlaylistSong;

class PlaylistSongController extends Controller
{
    public function index($playlistId)
    {
        $playlist = Playlist::find($playlistId);
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
        return json_response($this->resp, $this->httpCode);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'playlist_id' => 'required|numeric',
            'song_id' => [
                'required',
                'numeric',
                new UniquePlaylistSong($request->playlist_id),
            ],
        ]);

        if ($validator->fails())
            $this->responsee(false, $validator->errors());
        else{
            $playlist = Playlist::find($request->playlist_id);
            // var_dump($playlist); die;
            if($playlist){
                $song = Song::find($request->song_id);
                if($song){
                    $this->data = $playlist->songs()->attach($song->id);
                    // if($playlist->songs()->attach($song->id))
                    $this->responsee(true);
                    // else
                        // $this->responsee(false, $this->w_err);
                }else{
                    $this->d_err = 'Error! Song not found';
                    $this->responsee(false, $this->d_err);
                }
            }else{
                $this->d_err = 'Error! Playlist not found';
                $this->responsee(false, $this->d_err);
            }
        }
        return json_response($this->resp, $this->httpCode);
    }
    public function delete($playlist_id, $song_id)
    {
        if($playlist_id && $song_id){
            $playlist = Playlist::find($playlist_id);
            if($playlist){
                if($playlist->songs()->detach($song_id))
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
