<?php

namespace App\Http\Controllers\Catalogos;

use App;
use App\Http\Controllers\Controller;
use App\Models\Catalogos\Washer;
use App\Models\RolesUser;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WasherController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Mostrar un listado de los recursos.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('Catalogos/washer.index');
	}
	//INSERT
	public function store(Request $request) {
        $cat_washer = new Washer();
        $cat_washer->id_usuario = $request->Input("id_usuario");
        $cat_washer->nombre = $request->Input("nombre");
        $cat_washer->app = $request->Input("app");
        $cat_washer->apm = $request->Input("apm");
		$cat_washer->calificacion = $request->Input("calificacion");
        $cat_washer->pago_status = $request->Input("pago_status");
        $cat_washer->status_washer = $request->Input("status_washer");
        DB::beginTransaction();
		try {
			if ($cat_washer->save()) {
                $msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];
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
		$results = DB::table('washers as w')
		->select('w.id_washer as id', 'w.id_usuario', 'w.nombre', 'w.app', 'w.apm', 'w.calificacion', 'w.pago_status','w.status_washer')
		->where('w.id_washer',$id)->get();
		return response()->json($results);
    }
	//Update
	public function update(Request $request, $id) {
		$cat_washer = Washer::findOrFail($id);
		$cat_washer->id_usuario = $request->Input("id_usuario");
        $cat_washer->nombre = $request->Input("nombre");
        $cat_washer->app = $request->Input("app");
        $cat_washer->apm = $request->Input("apm");
		$cat_washer->calificacion = $request->Input("calificacion");
        $cat_washer->pago_status = $request->Input("pago_status");
        $cat_washer->status_washer = $request->Input("status_washer");
		DB::beginTransaction();
		try {
			if ($cat_washer->save()) {
				$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];
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
		$cat_washer = Washer::find($id);
		DB::beginTransaction();
		try {
			if ($cat_washer->delete()) {
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
		$results = DB::table('washers as w')
		->select('w.id_washer as id', 'w.nombre', 'w.app','w.apm','w.status_washer','u.nombre as usuario')
        ->leftjoin('users as u', 'u.id', '=', 'w.id_usuario')
		->get();
		return response()->json(['data' => $results]);
	}

	public function listar_select_alumnos(){
		$results = DB::table('users')
		->select('users.id', 'users.nombre')
		->leftjoin('roles_user as ru', 'ru.id_user', '=', 'users.id')
		->where('ru.id_rol','3')->get();
		return response()->json($results);
    }

    public function listar_roles_select(){
		$results = DB::table('roles as r')
		->select('r.idRol', 'r.nombre')
		->get();
		return response()->json($results);
    }
}
