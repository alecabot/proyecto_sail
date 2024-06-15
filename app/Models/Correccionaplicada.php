<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Correccionaplicada extends Model
{
    public $timestamps = false;

    protected $table = 'correccionaplicadas';
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'habilitado',
    ];

    public function parte () {
        return $this->hasMany(Parte::class);
    }

}
