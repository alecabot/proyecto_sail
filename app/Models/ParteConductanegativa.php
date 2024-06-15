<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParteConductanegativa extends Model
{
    public $timestamps = false;
    protected $table = 'parte_conductanegativas';
    protected $fillable = ['parte_id', 'conductanegativas_id'];
    use HasFactory;
}
