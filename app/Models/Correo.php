<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    public $timestamps = false;
    protected $table = 'correos';

    protected $fillable = ['alumno_dni', 'correo','tipo'];
    use HasFactory;

    public function alumno () {
        return $this->belongsTo(Alumno::class);
    }

}
