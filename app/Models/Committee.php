<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin;

class Committee extends Model
{
    use HasFactory;

    protected $table = 'tCommittees';
    protected $primaryKey = 'idCommittees';
    protected $fillable = [
        'emailAdmins',
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
    ];

    public function admins(){
        return $this->belongsTo(Admin::class, 'idAdmins', 'idAdmins');
    }
}
