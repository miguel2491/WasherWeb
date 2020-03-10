<?php

namespace App\Http\Controllers\Catalogos;

use App;
use App\Http\Controllers\Controller;
use App\Models\Catalogos\Paquete;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PaquetesController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	/**
	 * Mostrar un listado de los recursos.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		return view('Catalogos/paquetes.index');
	}
	//INSERT
	public function store(Request $request) {
        $cat_paquete = new Paquete();
        $cat_paquete->nombre = $request->Input("nombre");
        $cat_paquete->precio = $request->Input("precio");
        $cat_paquete->status = $request->Input("status");
        DB::beginTransaction();
		try {
			if ($cat_paquete->save()) {
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
		$results = DB::table('paquetes_lavado as p')
		->select('p.id_paquete', 'p.nombre', 'p.precio', 'p.status')
		->where('p.id_paquete',$id)->get();
		return response()->json($results);
    }
	//Update
	public function update(Request $request, $id) {
		$cat_paquete = Paquete::findOrFail($id);
		$cat_paquete->nombre = $request->Input("nombre");
        $cat_paquete->precio = $request->Input("precio");
        $cat_paquete->status = $request->Input("status");
		DB::beginTransaction();
		try {
			if ($cat_paquete->save()) {
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
		$cat_paquete = Paquete::find($id);
		DB::beginTransaction();
		try {
			if ($cat_paquete->delete()) {
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
		$results = DB::table('paquetes_lavado as p')
		->select('p.id_paquete', 'p.nombre', 'p.precio','p.status')
        ->get();
		return response()->json(['data' => $results]);
	}

}
