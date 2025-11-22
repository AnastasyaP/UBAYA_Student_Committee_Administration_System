<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class InterviewSchedule extends Model
{
    use HasFactory;

    protected $table = 'tInterviewSchedules';
    protected $primaryKey = 'idInterviewSchedules';
    protected $fillable = [
        'date',
        'time',
        'place',
        'link',
        'idDivisions',
        'idCommittees',
    ];
}
