<?php

namespace App\Models\Apis;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model {
	public $timestamps = false;
    protected $table = 'agendas';
    protected $primaryKey = 'id_agenda';
	protected $fillable = ['id_agenda', 'id_cliente', 'id_solicitud','fecha_agendada','id_washer','status'];
}