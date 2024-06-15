<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    public $timestamps = false;
    protected $table = 'cursos';

    protected $fillable = ['nombre', 'id_anio_academico'];
    use HasFactory;

    public function anioAcademico () {
        return $this->belongsTo(AnioAcademico::class);
    }

    public function unidades () {
        return $this->hasMany(Unidad::class);
    }



}
