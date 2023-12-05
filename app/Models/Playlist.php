<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;
    protected $albums = 'playlists';
    public $fillable = ['title', 'status'];
    public $timestamps = false;
}
