<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Documentation\Post;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'itop_id','itop_cfg','first_name','last_name',
        'adress','city','postal_code','country','latitude','longitude',
        'department','service',
        'role_id','guid','username',
        'org_id','loc_id',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    //Relations
    //Lien avec les Posts
    public function posts()
    {
        return $this->hasMany(Post::class,'author_id');
    }


    public function adminlte_image()
    {
        return 'https://picsum.photos/300/300';
    }

    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }

    public function adminlte_profile_url()
    {
        return '/profile/'.$this->id;
    }

    public function getAvatarAttribute($value)
    {
//        return $value ?? config('app.user.default_avatar', 'users/default.png');
        return isset($value) ? $value : config('app.user.default_avatar', 'users/default.png');
    }

 /* plus utilisé, reliquat du package Voyager
 public function setSettingsAttribute($value)
    {
        $this->attributes['settings'] = $value ? $value->toJson() : json_encode([]);
    }

    public function getSettingsAttribute($value)
    {
        return collect(json_decode($value));
    }
*/
    public function setLocaleAttribute($value)
    {
        save_user_preference($this->id,'locale',$value);
    }

    public function getLocaleAttribute()
    {
        return get_user_preference($this->id, 'locale');
    }
}
