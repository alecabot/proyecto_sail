<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramohorario extends Model
{
    public $timestamps = false;
    protected $table = 'tramohorarios';
    use HasFactory;
}
