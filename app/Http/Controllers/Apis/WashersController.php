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

        $token = request('token');

        if (Auth::attempt(['email' => $usuario, 'password' => $pass])) {

        	$msg = DB::table('users as u')

				->select('u.id', 'u.nombre','u.username','u.password','u.email','u.remember_token','u.name','u.google_id', 'u.foto','u.token','w.id_washer as idWasher')

				->leftjoin('washers as w', 'w.id_usuario', '=', 'u.id')

				->where('u.email', $usuario)

		        ->get();

		    	$id = $msg[0]->id;



		        $cat_usuario = Usuario::findOrFail($id);

	        	$cat_usuario->token = $token;

	        	DB::beginTransaction();

				try {

					if ($cat_usuario->save()) {

						//$msg = ['status' => 'ok', 'message' => 'Se ha agrego la calificacion'];

					}

				} catch (\Illuminate\Database\QueryException $ex) {

					DB::rollback();

					$msg = ['status' => 'fail', 'message' => 'No se pudo actualizar correcta, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];

					return response()->json($msg, 400);

				} catch (\Exception $ex) {

					DB::rollback();

					

					$msg = ['status' => 'fail', 'message' => 'No se pudo actualizar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];

					return response()->json($msg, 400);

				} finally {

					DB::commit();

				}

		    	

        } else {

            $msg[] = [

            	'nombre'=>'fail'

            ];

        }

        return response()->json($msg);

	}

	//TEMPORAL

	



	public function recupera_pass(Request $request) {

		$email = request('correo');

		$msg = [];

		$user = DB::table('users')->where('email', $email)->first();

		if (!$user) {

			/*$msg[] = [

				'pass' => $user->email

			];*/

			return response()->json($email);

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

					$msg = ['Se Actualizo correctamente su Password: '.$randomString];

					//sendRequest($uri);

					$return = $this->notificaPersonalizada($id_usuario,"Su Nueva Contraseña es: ".$randomString,"Bienvenido");

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

		$correo = $request->Input("correo");

		$passwor = $request->Input("password");

		//$paquete = $request->Input("id_paquete");

		$nombre = $request->Input("nombre");

		$foto = "http://washdryapp.com/oficial/Perfiles/".$request->Input("foto");

        $cat_usuario = new Usuario();

		$cat_usuario->nombre = $request->Input("nombre")." ".$request->Input("app")." ".$request->Input("apm");



		$cat_usuario->name = request("nombre");

		$cat_usuario->fecha_nac = $request->Input("fecha_nac");

		$cat_usuario->telefono = $request->Input("telefono");

		$cat_usuario->email = $correo;

		$cat_usuario->token = $request->Input("token");

		$cat_usuario->foto = $foto;

		$cat_usuario->password = Hash::make($passwor);

		$cat_usuario->google_id = "1111";

		$cat_usuario->username = $nombre;		

		$user_mail = DB::table('users')->where('email', $correo)->first();

        if ($user_mail) {

        	$msg = ['status' => 'ok', 'message' => 'Correo ya registrado con algun usuario'];

        	return response()->json($msg, 400);

        }else{

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

	                	//$cat_washer->id_paquete = $paquete;

	                	$cat_washer->nombre = $request->Input("nombre");

	                	$cat_washer->app = $request->Input("app");

						$cat_washer->apm = $request->Input("apm");

						$cat_washer->fca_nacimiento = $request->Input("fecha_nac");

						$cat_washer->telefono = $request->Input("telefono");

						$cat_washer->foto_ine = $request->Input("ine");

	                	$cat_washer->status_washer = 1;

	                	if ($cat_washer->save()) {

	                		$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente', 'ID' => $idUser];

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

	}

	//Update

	public function update(Request $request) {

		$id = request('id_usuario');

		$passwor = request('password');

        $cat_usuario = Usuario::findOrFail($id);

		$cat_usuario->nombre = request('nombre')." ".request("app")." ".request("apm");

		$cat_usuario->name = request("nombre");

		$cat_usuario->email = request("correo");

		$cat_usuario->password = Hash::make($passwor);

		$cat_usuario->username = $request->Input("nombre");

		$cat_usuario->foto = $request->Input("foto");

		DB::beginTransaction();

		try {

			$msg = DB::table('washers as w')

				->select('w.id_washer')

				->where('w.id_usuario', $id)

		        ->get();

			$idWasher = $msg[0]->id_washer;



			if ($cat_usuario->save()) {

				$cat_washer = Washers::findOrFail($idWasher);

				$cat_washer->id_usuario = $id;

				$cat_washer->nombre = $request->Input("nombre");

				$cat_washer->app = $request->Input("app");

				$cat_washer->apm = $request->Input("apm");

				if($cat_washer->save()){

					$msg = ['status' => 'ok', 'message' => 'Se actualizo correctamente'];	

				}

				

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

		->select('w.id_washer', 'w.nombre', 'w.calificacion', 'w.foto_ine')

        ->get();

		return response()->json($results);

	}



	public function getSolicitud($id)

	{

		$results = DB::table('solicitud as s')

		->select('s.id_solicitud','s.id_washer', 's.id_usuario', 's.id_paquete', 's.id_auto', 's.fecha','s.calificacion', 's.latitud', 's.longitud', 's.fecha', 'u.nombre', 'u.foto', 'a.modelo', 'a.ann', 'a.placas', 'p.tipo_vehiculo', 'p.precio', 'pa.nombre as paquete')

        ->leftjoin('washers as w', 'w.id_usuario', '=', 's.id_usuario')

        ->leftjoin('users as u', 'u.id', '=', 's.id_usuario')

        ->leftjoin('autos as a', 'a.id_auto', '=', 's.id_auto')

        ->leftjoin('paquetes as p', 'p.id', '=', 's.id_paquete')

        ->leftjoin('paquetes_lavado as pa', 'pa.id_paquete', '=', 'p.id_paquete')

        ->where('s.id_washer',$id)

        ->where('s.status','<', 4)

        ->orderBy('s.fecha', 'desc')

		->get();

		return response()->json($results);

	}



	public function getSolicitudLavado($id)

	{

		$results = DB::table('solicitud as s')

		->select('s.id_solicitud','s.id_washer', 's.id_usuario', 's.id_paquete', 's.id_auto', 's.fecha', 's.latitud', 's.longitud', 's.fecha', 'u.nombre', 'u.foto', 'a.modelo', 'a.ann', 'a.placas', 'p.tipo_vehiculo', 'p.precio', 'pa.nombre as paquete', DB::raw('IFNULL( cal.calificacion, 1) as calificacion'))

        ->leftjoin('washers as w', 'w.id_usuario', '=', 's.id_usuario')

        ->leftjoin('users as u', 'u.id', '=', 's.id_usuario')

        ->leftjoin('autos as a', 'a.id_auto', '=', 's.id_auto')

        ->leftjoin('paquetes as p', 'p.id', '=', 's.id_paquete')

        ->leftjoin('paquetes_lavado as pa', 'pa.id_paquete', '=', 'p.id_paquete')

        ->join('calificaciones as cal', 'cal.id_solicitud', '=', 's.id_solicitud')

        ->where('cal.tipo_calificacion','W')

        ->where('s.id_washer',$id)

        ->where('s.status','=', 7)

		->get();

		return response()->json($results);

	}





	public function getPerfilWasher($id)

	{

		$results = DB::table('washers as w')

		->select('w.id_washer as idWasher', 'w.id_usuario', 'w.nombre', 'w.app', 'w.apm', 'w.telefono', DB::raw('IFNULL( w.foto_ine, "") as foto_ine'), 'w.fca_nacimiento', 'u.email', 'u.foto')

		->leftjoin('users as u', 'u.id', '=', 'w.id_usuario')

		->where('w.id_washer',$id)

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



	public function notifica()

	{

		$fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $id_usuario = request('id_usuario');

        $title = request('titulo');

        $mensaje = request('mensaje');

        $msg = DB::table('users as u')

				->select('u.id', 'u.token')

				->where('u.id', $id_usuario)

		        ->get();

		$token = $msg[0]->token;



        $notification = [

            'title' => $title,

            'message' => $mensaje,

            'sound' => true,

        ];

        

        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];



        $fcmNotification = [

            //'registration_ids' => $tokenList, //multple token array

            'to'        => $token, //single token

            'notification' => $notification,

            'data' => $extraNotificationData

        ];



        $headers = [

            'Authorization: key=AAAAJBsH6ro:APA91bEJ9I8FnVFqRSeoRSxNv9mT17C876UrWLRY0d6Ow7jV9pcI9Dizb6hf4A1go3MnzY9V4XpU-25XwTqvc-PMIdHVJz6aTLF9yC0Hp4wc5a3pa7EbUKyV_gv5b_r5lGTQnpas0SOp',

            'Content-Type: application/json'

        ];





        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$fcmUrl);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

        $result = curl_exec($ch);

        curl_close($ch);

        return $result;

	}



	public function notificaPersonalizada($idUser,$title,$mensaje)

	{

		$fcmUrl = 'https://fcm.googleapis.com/fcm/send';

        $id_usuario = $idUser;

        $title = $title;

        $mensaje = $mensaje;

        $msg = DB::table('users as u')

				->select('u.id', 'u.token')

				->where('u.id', $id_usuario)

		        ->get();

		$token = $msg[0]->token;



        $notification = [

            'title' => $title,

            'message' => $mensaje,

            'sound' => true,

        ];

        

        $extraNotificationData = ["message" => $notification,"moredata" =>'dd'];



        $fcmNotification = [

            //'registration_ids' => $tokenList, //multple token array

            'to'        => $token, //single token

            'notification' => $notification,

            'data' => $extraNotificationData

        ];



        $headers = [

            'Authorization: key=AAAAJBsH6ro:APA91bEJ9I8FnVFqRSeoRSxNv9mT17C876UrWLRY0d6Ow7jV9pcI9Dizb6hf4A1go3MnzY9V4XpU-25XwTqvc-PMIdHVJz6aTLF9yC0Hp4wc5a3pa7EbUKyV_gv5b_r5lGTQnpas0SOp',

            'Content-Type: application/json'

        ];





        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL,$fcmUrl);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

        $result = curl_exec($ch);

        curl_close($ch);

        //return $result;

	}



	public function storeImg(Request $request) {

	    

        $uploaddir = 'uploads/autos';

        $ext =  $_FILES['file']['name'];//$request->file('file')->getClientOriginalExtension();



		$filename = $ext;//time().'.'.$ext;



		$upload = $request->file('file')->storeAs(



		    'uploads/autos', $filename



		);

		$file_name = $_FILES['file']['name'];



        //$uploadfile = $uploaddir.$file_name;

    

        

        

        return $uploaddir.'/'.$file_name;

    }

}

