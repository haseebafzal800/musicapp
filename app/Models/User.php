<?php
  
namespace App\Models;
  
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
  
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
  
    // protected $guard_name = 'api';
    /**
     * The attributes that are mass assignable.
     *
     * @var array

     */

    protected $fillable = [
        'name',
        'email',
        'password',
    ];
  
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array

     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
  
    /**
     * The attributes that should be cast.
     *
     * @var array

     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public $approved;

    public function songs()
    {
        return $this->hasMany(App\Models\Song::class);
    }
    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }
    public function recentlyPlayed()
    {
        return $this->hasMany(RecentlyPlayed::class, 'user_id');
    }
}

?>
