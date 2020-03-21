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
		$msg = [];
		$user = DB::table('users')->where('email', $email)->first();
		if (!$user) {
			$msg[] = [
				'pass' => 'NUEVA'
			];
			return response()->json($msg);
		}else{
			$id_usuario = $user->id;
			$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
			$randomString = ''; 
		
			for ($i = 0; $i < 8; $i++) { 
				$index = rand(0, strlen($characters) - 1); 
				$randomString .= $characters[$index]; 
			} 
			$tmpPass = Hash::make($randomString);
			$cat_usuario = Usuario::findOrFail($id_usuario);
			$cat_usuario->password = $tmpPass;
			DB::beginTransaction();
			try {
				if ($cat_usuario->save()) {
					$msg = ['status' => 'ok', 'message' => 'Se Actualizo correctamente su Password, se enviara un correo '];
				}
			} catch (\Illuminate\Database\QueryException $ex) {
				DB::rollback();
				$msg = ['status' => 'fail', 'message' => 'No se pudo actualizar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
				return response()->json($msg, 400);
			} catch (\Exception $ex) {
				DB::rollback();
				$msg = ['status' => 'fail', 'message' => 'No se pudo actualizar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
				return response()->json($msg, 400);
			} finally {
				DB::commit();
			}
			return response()->json($msg);
		}
	}
	//Registro Washer
	//INSERT
	public function store(Request $request) {
		$passwor = $request->Input("password");
		$paquete = $request->Input("id_paquete");
        $cat_usuario = new Usuario();
		$cat_usuario->nombre = $request->Input("nombre")." ".$request->Input("app")." ".$request->Input("apm");
		$cat_usuario->name = $request->Input("nombre");
		$cat_usuario->email = $request->Input("correo");
		$cat_usuario->password = Hash::make($passwor);
		$cat_usuario->google_id = "1111";
		$cat_usuario->username = $request->Input("nombre");
		
		
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
                	$cat_washer->app = $request->Input("app");
					$cat_washer->apm = $request->Input("apm");
					$cat_washer->telefono = $request->Input("telefono");
					$cat_washer->foto_ine = $request->Input("ine");
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
	public function update(Request $request) {
		$id = request('id_usuario');
		$paquete = $request->Input("id_paquete");
		$passwor = $request->Input("password");
        $cat_usuario = Usuario::Where('id_usuario',$id);
		$cat_usuario->nombre = $request->Input("nombre")." ".$request->Input("app")." ".$request->Input("apm");
		$cat_usuario->name = $request->Input("nombre");
		$cat_usuario->email = $request->Input("correo");
		$cat_usuario->password = Hash::make($passwor);
		$cat_usuario->username = $request->Input("nombre");
		DB::beginTransaction();
		try {
			if ($cat_usuario->save()) {
				$cat_washer = Washers::findOrFail($id);
				$cat_washer->id_usuario = $idUser;
				$cat_washer->id_paquete = $paquete;
				$cat_washer->nombre = $request->Input("nombre");
				$cat_washer->app = $request->Input("app");
				$cat_washer->apm = $request->Input("apm");
				$cat_washer->telefono = $request->Input("telefono");
				$cat_washer->foto_ine = $request->Input("ine");
				$msg = ['status' => 'ok', 'message' => 'Se actualizo correctamente'];
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo actualizar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
			return response()->json($msg, 400);
		} catch (\Exception $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo actualizar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];
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
		return response()->json($results);
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

	public function getPerfilWasher($id)
	{
		$results = DB::table('washers as w')
		->select('w.id_washer', 'w.id_usuario', 'w.nombre', 'w.app', 'w.apm', 'w.telefono', 'w.foto_ine', 'w.id_paquete', 'w.fca_nacimiento', 'u.email')
		->leftjoin('users as u', 'u.id', '=', 'w.id_usuario')
		->where('u.id',$id)
		->get();
		return response()->json($results);
	}

	public function getAutosLavar($id)
	{
		$results = DB::table('solicitud as s')
		->select('s.id_solicitud', 's.id_washer', 's.id_usuario', 's.fecha', 's.calificacion', 'a.id_auto', 'a.placas', 'a.modelo', 'a.ann', 'a.marca', 'a.color', 'u.nombre')
		->leftjoin('autos as a', 'a.id_usuario', '=', 's.id_usuario')
		->leftjoin('users as u', 'u.id', '=', 's.id_usuario')
		->where('s.id_washer',$id)
		->get();
		return response()->json($results);	
	}

	public function getAutosLavarDetalle($id)
	{
		$results = DB::table('solicitud as s')
		->select('s.id_solicitud', 's.id_washer', 's.id_usuario', 's.fecha', 's.calificacion', 's.comentario', 'a.id_auto', 'a.placas', 'a.modelo', 'a.ann', 'a.marca', 'a.color', 'u.nombre')
		->join('autos as a', 'a.id_usuario', '=', 's.id_usuario')
		->join('users as u', 'u.id', '=', 's.id_usuario')
		->join('paquetes_lavado as pl', 's.id_paquete', '=', 'pl.id_paquete')
		->where('s.id_solicitud',$id)
		->get();
		return response()->json($results);	
	}

}
