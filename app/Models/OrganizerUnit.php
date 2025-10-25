<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\CommitteeOrganizer;


class OrganizerUnit extends Model
{
    use HasFactory;

    protected $table = 'tOrganizerUnits';
    protected $primaryKey = 'idOrganizerUnits';
    protected $fillable = ['name'];

    public function committeeOrganizers(){
        return $this->hasMany(CommitteeOrganizer::class, 'idOrganizerUnits', 'idOrganizerUnits');
    }
}
