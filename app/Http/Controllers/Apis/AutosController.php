<?php

namespace App\Http\Controllers\Apis;

use App;
use App\Http\Controllers\Controller;
use App\Models\Apis\Autos;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AutosController extends Controller {
	public function __construct() {
		//$this->middleware('auth');
	}
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
        $cat_autos = new Autos();
        $cat_autos->placas = $request->Input("placas");
        $cat_autos->modelo = $request->Input("modelo");
        $cat_autos->ann = $request->Input("ann");
        $cat_autos->marca = $request->Input("marca");
        $cat_autos->color = $request->Input("color");
        //$cat_autos->imagen = $request->Input("imagen");
        $ext =  $request->file('image')->getClientOriginalExtension();
		$filename = time().'.'.$ext;
		$upload = $request->file('image')->storeAs(
		    'uploads/autos', $filename
		);	
		if($upload){
            $cat_autos->imagen = $request->Input("imagen");
        }
                
        DB::beginTransaction();
		try {
			if ($cat_autos->save()) {
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
		$results = DB::table('autos as a')
		->select('a.id_auto', 'a.id_usuario', 'a.placas', 'a.modelo','a.ann','a.marca','a.color','a.imagen')
		->where('a.id_auto',$id)->get();
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
		$cat_auto = Autos::find($id);
		DB::beginTransaction();
		try {
			if ($cat_auto->delete()) {
				$msg = ['status' => 'ok', 'message' => 'Se elimino correctamente!'];
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
		$results = DB::table('autos as a')
		->select('a.id_auto', 'a.marca')
        ->get();
		return response()->json(['data' => $results]);
	}


	public function listadoAutoUser($id) {
		$results = DB::table('autos as a')
		->select('a.id_auto', 'a.marca')
		->where('a.id_usuario', $id)
        ->get();
		return response()->json(['data' => $results]);
	}


}
