<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Committee;
use App\Models\Division;


class ListDivision extends Model
{
    use HasFactory;

    protected $table = 'tListDivisions';
    protected $primaryKey = ['idCommittees', 'idDivisions'];
    public $incrementing = false;
    protected $keyType = 'array';
    protected $fillable = [
        'idCommittees',
        'idDivisions',
        'is_open',
        'description',
        'picture',
        'is_consistent',
        'num_member'
    ];

    public function committees(){
        return $this->belongsTo(Committee::class, 'idCommittees', 'idCommittees');
    }

    public function divisions(){
        return $this->belongsTo(Division::class, 'idDivisions', 'idDivisions');
    }
}
