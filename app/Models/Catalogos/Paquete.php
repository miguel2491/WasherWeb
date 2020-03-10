<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Paquete extends Model {
	public $timestamps = false;
    protected $table = 'paquetes_lavado';
    protected $primaryKey = 'id_paquete';
	protected $fillable = ['id_paquete', 'nombre','descripcion','status'];
}