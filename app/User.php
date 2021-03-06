<?php

namespace App;

use App\Appointments\Appointment;
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
     * @return User $user
     */
    public function updateUserData(FormRequest $request): User
    {
        $data = $request->all();

        $avatar = null;
        if($request->hasFile('avatar'))
            $avatar = storeAvatar($request->file('avatar'));

        $this->where('id', $data['id'])->update([
            'name' => $data['name'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'avatar' => $avatar,
            'dob' => $data['dob'],
        ]);

        return $this->find($data['id']);
    }

    /**
     * @param int $id
     * @return User
     */
    public function getById(int $id): User
    {
        return $this->find($id);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }

    public function schedules()
    {
        return $this->hasMany(Appointment::class, 'doctor_id');
    }
}
