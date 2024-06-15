<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AlumnoParte extends Model
{
    use HasFactory;
    protected $table = "alumno_partes";
    protected $fillable = ['alumno_dni', 'parte_id'];

}
