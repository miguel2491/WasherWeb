<?php

namespace App\Models\Apis;

use Illuminate\Database\Eloquent\Model;

class Autos extends Model {
	public $timestamps = false;
    protected $table = 'autos';
    protected $primaryKey = 'id_auto';
	protected $fillable = ['id_auto', 'id_usuario', 'placas','modelo','ann','marca','color','imagen'];
}