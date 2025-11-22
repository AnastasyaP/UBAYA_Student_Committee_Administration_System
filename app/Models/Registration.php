<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Registration extends Model
{
    use HasFactory;

    protected $table = 'tRegistrations';
    protected $primaryKey = ['idRegistrations'];
    protected $fillable = [
        'idUsers',
        'idDivisions',
        'idCommittees',
        'status',
        'percentage',
        'position',
        'idInterviewSchedules',
    ];
}
