<?php

namespace App\Http\Controllers\Catalogos;

use App;
use App\Http\Controllers\Controller;
use App\Models\Catalogos\Usuario;
use App\Models\RolesUser;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Mostrar un listado de los recursos.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('Catalogos/usuarios.index');
	}
	//INSERT
	public function store(Request $request) {
        $passwor = $request->Input("password");
		$cat_usuario = new Usuario();
		$cat_usuario->nombre = $request->Input("nombre");
		$cat_usuario->username = $request->Input("username");
		$cat_usuario->email = $request->Input("email");
		$cat_usuario->password = Hash::make($passwor);
		DB::beginTransaction();
		try {
			if ($cat_usuario->save()) {
                $idUser = $cat_usuario->id;
                $cat_rolUser = new RolesUser();
                $cat_rolUser->id_user = $idUser;
                $cat_rolUser->id_rol = $request->Input("id_rol");
                if ($cat_rolUser->save()) {
                    $msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];
                }
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo guardar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
			return response()->json($msg, 400);
		} catch (\Exception $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo guardar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
			return response()->json($msg, 400);
		} finally {
			DB::commit();
		}
		return response()->json($msg);
	}
	//Edit
	public function edit($id) {
		$results = DB::table('users as u')
		->select('u.id', 'u.nombre', 'u.username', 'u.email', 'u.password', 'r.nombre as Rol','r.idRol as idRol')
		->leftjoin('roles_user as ru', 'ru.id_user', '=', 'u.id')
        ->leftjoin('roles as r', 'r.idRol', '=', 'ru.id_rol')
		->where('u.id',$id)->get();
		return response()->json($results);
    }
	//Update
	public function update(Request $request, $id) {
		$cat_usuario = Usuario::findOrFail($id);
		$cat_usuario->nombre = $request->Input("nombre");
		$cat_usuario->email = $request->Input("email");
		$cat_usuario->username = $request->Input("username");
		DB::beginTransaction();
		try {
			if ($cat_usuario->save()) {
				$idU = $id;
				$cat_rolUser = RolesUser::where('id_user', $id);
				$cat_rolUser->id_rol = $request->Input("id_rol");
				$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];
				/*if ($cat_rolUser->save()) {
                    $msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];
				}*/
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo guardar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
			return response()->json($msg, 400);
		} catch (\Exception $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo guardar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
			return response()->json($msg, 400);
		} finally {
			DB::commit();
		}

		return response()->json($msg);
	}
	//Eliminar
	public function destroy($id) {
		$msg = [];
		$cat_alumno = Usuario::find($id);
		DB::beginTransaction();
		try {
			if ($cat_alumno->delete()) {
				$msg = ['status' => 'ok', 'message' => ''];
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo eliminar , por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
			return response()->json($msg, 400);
		} catch (\Exception $e) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo eliminar, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
			return response()->json($msg, 400);
		} finally {
			DB::commit();
		}

		return response()->json($msg);
	}

	public function listado() {
		$results = DB::table('users as u')
		->select('u.id', 'u.nombre','u.username','u.email','r.nombre as Rol','r.idRol as idRol')
        ->leftjoin('roles_user as ru', 'ru.id_user', '=', 'u.id')
        ->leftjoin('roles as r', 'r.idRol', '=', 'ru.id_rol')
		->get();
		return response()->json(['data' => $results]);
	}

	public function listar_select_usuario(){
		$results = DB::table('users')
		->select('users.id', 'users.nombre', 'users.username')
		->leftjoin('roles_user as ru', 'ru.id_user', '=', 'users.id')
		->get();
		return response()->json($results);
    }

    public function listar_roles_select(){
		$results = DB::table('roles as r')
		->select('r.idRol', 'r.nombre')
		->get();
		return response()->json($results);
    }
}
