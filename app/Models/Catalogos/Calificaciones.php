<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Calificaciones extends Model {
	public $timestamps = false;
    protected $table = 'calificaciones';
    protected $primaryKey = 'id_calificacion';
	protected $fillable = ['id_calificacion','id_solicitud','calificacion','comentario', 'tipo_calificacion', 'status','created_at'];
}