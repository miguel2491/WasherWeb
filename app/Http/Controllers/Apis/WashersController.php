<?php

namespace App\Http\Controllers\Apis;

use App;
use App\Http\Controllers\Controller;
use App\Models\Apis\Washers;
use App\Models\Catalogos\Usuario;
use App\Models\RolesUser;
use App\User;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class WashersController extends Controller {
	
	/**
	 * Mostrar un listado de los recursos.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index() {
		//return view('Catalogos/paquetes.index');
	}
	public function login(Request $request)
	{
		$msg = [];
		$usuario = request('usuario');
        $pass = request('pass');
        if (Auth::attempt(['email' => $usuario, 'password' => $pass])) {
        	$msg = DB::table('users as u')
				->select('u.id', 'u.nombre','u.username','u.password','u.email','u.remember_token','u.name','u.google_id')
				->where('u.email', $usuario)
		        ->get();
        } else {
            $msg[] = [
            	'nombre'=>'fail'
            ];
        }
        return response()->json($msg);
	}
	//TEMPORAL
	public function loginChema(Request $request)
	{
		$msg = [];
		$usuario = "washdryappssoporte@gmail.com";
        $pass = "1234";
        if (Auth::attempt(['email' => $usuario, 'password' => $pass])) {
        	$msg = DB::table('users as u')
				->select('u.id', 'u.nombre','u.username','u.password','u.email','u.remember_token','u.name','u.google_id')
				->where('u.email', $usuario)
		        ->get();
        } else {
            $msg[] = [
            	'nombre'=>'fail'
            ];
        }
        return response()->json($msg);
	}

	public function recupera_pass(Request $request) {
		$email = request('correo');
        
        return response()->json($email);
	}
	//INSERT
	public function store(Request $request) {
		$passwor = $request->Input("password");
		$paquete = $request->Input("id_paquete");
        $cat_usuario = new Usuario();
		$cat_usuario->nombre = $request->Input("nombre");
		$cat_usuario->name = $request->Input("nombre");
		$cat_usuario->google_id = "1111";
		$cat_usuario->username = $request->Input("nombre");
		$cat_usuario->email = $request->Input("correo");
		$cat_usuario->password = Hash::make($passwor);
		DB::beginTransaction();
		try {
			if ($cat_usuario->save()) {
                $idUser = $cat_usuario->id;
                $cat_rolUser = new RolesUser();
                $cat_rolUser->id_user = $idUser;
                $cat_rolUser->id_rol = 2;
                if ($cat_rolUser->save()) {
                	$cat_washer = new Washers();
                	$cat_washer->id_usuario = $idUser;
                	$cat_washer->id_paquete = $paquete;
                	$cat_washer->nombre = $request->Input("nombre");
                	$cat_washer->app = $request->Input("nombre");
                	$cat_washer->apm = $request->Input("nombre");
                	$cat_washer->status_washer = 1;
                	if ($cat_washer->save()) {
                		$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];
                	}
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
		$results = DB::table('washers as w')
		->select('w.id_washer', 'w.nombre')
        ->get();
		return response()->json(['data' => $results]);
	}

	public function getSolicitud($id)
	{
		$results = DB::table('solicitud as s')
		->select('s.id_solicitud','s.id_washer', 's.id_usuario', 's.fecha','s.calificacion','w.nombre')
        ->leftjoin('washers as w', 'w.id_usuario', '=', 's.id_usuario')
        ->where('s.id_usuario',$id)
		->get();
		return response()->json($results);
	}

}
