<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'role_id',
        'avatar', 'address', 'phone', 'dob'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @param FormRequest $request
     * @return bool
     */
    public function updateUserData(FormRequest $request): bool
    {
        $data = $request->all();

        $avatar = null;
        if($request->hasFile('avatar'))
            $avatar = storeAvatar($request->file('avatar'));

        return $this->where('id', $data['id'])->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'avatar' => $avatar,
            'dob' => $data['dob'],
        ]);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
