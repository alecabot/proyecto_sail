<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conductanegativa extends Model
{
    public $timestamps = false;
    protected $table = 'conductanegativas';
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'tipo',
        'habilitado',
    ];

    public function partes()
    {
        return $this->belongsToMany(Parte::class,
         'parte_conductanegativas');
    }

}
