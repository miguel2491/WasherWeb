<?php

namespace App\Models\Apis;

use Illuminate\Database\Eloquent\Model;

class Washers extends Model {
	public $timestamps = false;
    protected $table = 'washers';
    protected $primaryKey = 'id_washer';
	protected $fillable = ['id_washer', 'id_usuario', 'nombre','app','apm','pago_status','status_washer','monto_pago','calificacion','foto_ine','id_paquete','created_at'];
}