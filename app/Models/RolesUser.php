<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolesUser extends Model {
	public $timestamps = false;
	protected $table = 'roles_user';
	protected $fillable = ['idRolUser', 'id_rol','id_user'];
}