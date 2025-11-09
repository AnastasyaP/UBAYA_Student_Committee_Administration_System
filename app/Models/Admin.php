<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;
use App\Models\Committee;


class Admin extends Authenticatable
{
    use HasFactory;
    protected $table = 'tAdmins';
    protected $primaryKey = 'idAdmins';


    protected $fillable = ['email', 'password', 'is_superAdmin', 'idOrganizerUnits'];

    protected $hidden = ['password']; // di hidden biar nga bisa ditampilkan (harus array)

    

        /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
    * @return string
    */
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function committees(){
        return $this->hasMany(Committee::class, 'idAdmins', 'idAdmins'); // idAdmin di tCommittees = idAdmin di tAdmins
    }

    public function organizerUnits(){
        return $this->belongsTo(OrganizerUnit::class, 'idOrganizerUnits', 'idOrganizerUnits');
    }
}
