<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class PaquetesDetalle extends Model {
	public $timestamps = false;
    protected $table = 'paquetes';
    protected $primaryKey = 'id';
	protected $fillable = ['id','id_paquete', 'tipo_vehiculo','duracion','precio','status','created_at'];
}