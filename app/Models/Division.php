<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\ListDivision;


class Division extends Model
{
    use HasFactory;

    protected $table = 'tDivisions';
    protected $primaryKey = 'idDivisions';
    protected $fillable = [
        'name',
    ];

    public function listDivisions(){
        return $this->hasMany(ListDivision::class, 'idDivisions', 'idDivisions');
    }
}
