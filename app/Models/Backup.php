<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Backup extends Model
{
    protected $fillable = [
        'ip',
        'porta',
        'banco',
        'usuario',
        'senha',
        'frequencia',
    ];
}
