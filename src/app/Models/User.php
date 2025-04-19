<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;

use App\Models\Reservation;
use App\Models\Shop;
use App\Models\Review;
use App\Models\Notification;
use App\Models\Role;


/**
 * App\Models\User
 *
 * @method bool hasRole(string $roleName)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
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

    // 多対多（ロール）
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_role');
    }

    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    // 予約
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // お気に入り
    public function favorites()
    {
        return $this->belongsToMany(Shop::class, 'favorites')->withTimestamps();
    }

    // レビュー
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // 通知（受信側）
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'recipient_id');
    }
}
