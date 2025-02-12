<?php

namespace App\Models;

use App\Notifications\CustomResetPassword;
use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasRoles;
    use HasApiTokens, HasFactory, Notifiable;


    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail());
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPassword($token)); // Sử dụng thông báo tùy chỉnh của bạn
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'img_thumbnail',
        'phone',
        'email',
        'email_verified_at',
        'password',
        'gender',
        'birthday',
        'address',
        'service_id',
        'service_name',
        'type',
        'cinema_id'
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
        'password' => 'hashed',
    ];
    const TYPE_ADMIN = 'admin';
    const TYPE_MEMBER = 'member';

    const ROLE = [
        'System Admin',
        'Cinema Manager',
        'Staff'
    ];
    // const
    const GENDERS = [
        'Nam',
        'Nữ',
        'Khác'
    ];

    public function isAdmin()
    {
        return $this->type === self::TYPE_ADMIN;
    }



    public function vouchers()
    {
        return $this->belongsToMany(Voucher::class, 'user_vouchers')
            ->withPivot('usage_count')
            ->withTimestamps();
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function movieReview()
    {
        return $this->hasMany(MovieReview::class, 'user_id');
    }
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
    public function membership()
    {
        return $this->hasOne(Membership::class);
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class);
    }
    // public function hasRole($role)
    // {
    //     return $this->roles->contains('name', $role);
    // }
}
