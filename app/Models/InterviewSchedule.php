<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Registration;
use App\Models\ListDivision;



class InterviewSchedule extends Model
{
    use HasFactory;

    protected $table = 'tInterviewSchedules';
    protected $primaryKey = 'idInterviewSchedules';
    protected $fillable = [
        'date',
        'start_time',
        'end_time',
        'place',
        'link',
        'idDivisions',
        'idCommittees',
    ];

    public function registration(){
        return $this->hasMany(Registration::class, 'idInterviewSchedules', 'idInterviewSchedules');
    }

    public function division()
    {
        return $this->belongsTo(ListDivision::class, 'idDivisions', 'idDivisions');
    }

    public function committee()
    {
        return $this->belongsTo(ListDivision::class, 'idCommittees', 'idCommittees');
    }

}
