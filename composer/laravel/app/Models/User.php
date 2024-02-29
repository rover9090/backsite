<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    // //
    //  protected $table = 'users';

    // //主鍵名稱
    // protected $primaryKey = 'id';

    // //可變動欄位
    // protected $fillable = [
    //     'name',
    //     'account',
    //     'password',
    //     'type',
    //     'sex',
    //     'height',
    //     'weight',
    //     'interest',
    //     'introduce',
    //     'picture',
    //     'enabled',
    // ];
    // //
    protected $table = 'client_user_data2';

    //主鍵名稱
    protected $primaryKey = 'nId';

    //可變動欄位
    protected $fillable = [
        'sName',
        'sAccount',
        'sPassword',
        'sType',
        'sSex',
        'nSex',
        'nHeight',
        'nWeight',
        'nInterest',
        'sIntroduce',
        'sPicture',
        'nOnline',
    ];


    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    // ];

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
}
