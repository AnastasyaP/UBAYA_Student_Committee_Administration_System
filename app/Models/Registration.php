<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\interviewschedule;
use App\Models\User;

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

    public function interviewschedule(){
        return $this->belongsTo(InterviewSchedule::class, 'idInterviewSchedules','idInterviewSchedules');
    }

    public function user(){
        return $this->belongsTo(User::class, 'idUsers', 'idUsers');
    }
}
