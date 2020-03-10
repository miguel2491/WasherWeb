<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Washer extends Model {
	public $timestamps = false;
    protected $table = 'washers';
    protected $primaryKey = 'id_washer';
	protected $fillable = ['id_washer', 'id_usuario','nombre','app', 'apm','pago_status','status_washer','monto_pago','calificacion'];
}