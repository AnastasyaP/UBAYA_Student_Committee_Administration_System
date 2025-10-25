<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Committee;
use App\Models\OrganizerUnit;

class CommitteeOrganizer extends Model
{
    use HasFactory;

    protected $table = 'tCommitteeOrganizers';
    protected $primaryKey = ['idCommittees', 'idOrganizerUnits'];
    public $incremeting = false;
    protected $keyType = 'array';
    protected $fillable = [
        'idCommittees',
        'idOrganizerUnits'
    ];
    
    public function committees(){
        return $this->belongsTo(Committee::class, 'idCommittees', 'idCommittees');
    }

    public function organizerUnits(){
        return $this->belongsTo(OrganizerUnit::class, 'idOrganizerUnits', 'idOrganizerUnits');
    }
}
