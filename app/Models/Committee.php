<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
