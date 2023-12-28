<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;
    public $fillable = ['title', 'status', 'user_id', 'album', 'generous', 'artist', 'favorite', 'lyrics'];
    // public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function playlists()
    {
        return $this->belongsToMany(Playlist::class, 'playlist_songs', 'song_id', 'playlist_id');
    }
    public function recentPlayed()
    {
        return $this->hasOne(RecentlyPlayed ::class);
        // Replace RecentPlayed::class with the actual class of your RecentPlayed model
    }
    protected static function boot()
    {
        parent::boot();

        // Listen for the "deleting" event
        static::deleting(function ($song) {
            // Detach the song from all playlists
            $song->playlists()->detach();
            $song->recentPlayed()->delete();
        });
    }
    // public function playlists()
    // {
    //     return $this->belongsToMany(Playlist::class);
    // }
}
