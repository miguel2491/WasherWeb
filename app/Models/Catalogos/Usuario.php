<?php

namespace App\Models\Catalogos;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model {
	public $timestamps = false;
	protected $table = 'users';
	protected $fillable = ['id', 'nombre','username','email', 'password','remember_token'];
}