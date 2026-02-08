<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AHPCriteria extends Model
{
    use HasFactory;

    protected $table = 'tAHPCriterias';
    protected $primaryKey = 'idAHPCriterias';
    protected $fillable = [
        'name',
        'idDivisions'
    ];
}
