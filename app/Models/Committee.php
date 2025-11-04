<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin;
use App\Models\ListDivision;

class Committee extends Model
{
    use HasFactory;

    protected $table = 'tCommittees';
    protected $primaryKey = 'idCommittees';
    protected $fillable = [
        'idAdmins',
        'name',
        'start_period',
        'end_period',
        'description',
        'requirements',
        'picture',
        'contact',
        'start_regis',
        'end_regis',
        'evaluation',
        'is_active',
    ];

    public function admins(){
        return $this->belongsTo(Admin::class, 'idAdmins', 'idAdmins');
    }

    public function listDivision(){
        return $this->hasMany(ListDivision::class, 'idCommittees', 'idCommittees');
    }
}
