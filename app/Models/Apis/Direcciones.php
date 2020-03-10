<?php

namespace App\Models\Apis;

use Illuminate\Database\Eloquent\Model;

class Direcciones extends Model {
	public $timestamps = false;
    protected $table = 'direcciones';
    protected $primaryKey = 'id_direccion';
	protected $fillable = ['id_direccion', 'id_usuario', 'latitud','longitud','descripcion'];
}