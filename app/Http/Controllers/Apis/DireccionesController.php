<?php

namespace App\Http\Controllers\Apis;

use App;
use App\Http\Controllers\Controller;
use App\Models\Apis\Direcciones;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DireccionesController extends Controller {
	
	/**
	 * Mostrar un listado de los recursos.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//return view('Catalogos/paquetes.index');
	}
	//INSERT
	public function store(Request $request) {
        $id_usuario = $_POST["id_usuario"];
        $latitud = $_POST["latitude"];
        $longitud = $_POST["longitude"];
        $descripcion = $_POST["descripcion"];
        $charge = Direcciones::create([
            'id_usuario' => $id_usuario,
            'latitud' => $latitud,
            'longitud' => $longitud,
            'descripcion' => $descripcion,
        ]);
        $msg = ['status' => 'ok', 'mensaje' => 'Almacenado Correctamente'];

        return response()->json($msg);
	}
	//Edit
	public function edit($id) {
		$results = DB::table('direcciones as d')
		->select('d.id_direccion', 'd.latitud', 'd.longitud', 'd.descripcion')
		->where('d.id_direccion',$id)->get();
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
		$cat_direccion = Direcciones::find($id);
		DB::beginTransaction();
		try {
			if ($cat_direccion->delete()) {
				$msg = ['status' => 'ok', 'message' => 'Se elimino correctamente'];
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
		$results = DB::table('direcciones as d')
		->select('d.id_direccion', 'd.descripcion')
        ->get();
		return response()->json(['data' => $results]);
	}

}
