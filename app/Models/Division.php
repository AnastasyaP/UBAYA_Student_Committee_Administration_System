<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Division extends Model
{
    use HasFactory;

    protected $table = 'tDivisions';
    protected $primaryKey = 'idDivisions';
    protected $fillable = [
        'name',
    ];
}
