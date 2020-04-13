<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Pagos extends Model {
	public $timestamps = false;
    protected $table = 'pagos';
    protected $primaryKey = 'id_pago';
	protected $fillable = ['id_pago', 'id_usuario', 'monto','id_washer','cambio','tipo_pago','status','comentario'];
}