<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnioAcademico extends Model
{
    public $timestamps = false;
    protected $table = 'anios_academicos';
    protected $fillable = ['anio_academico'];
    use HasFactory;

    public function cursos () {
        return $this->hasMany(Curso::class);
    }

}
