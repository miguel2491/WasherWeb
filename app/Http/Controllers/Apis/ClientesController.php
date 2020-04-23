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



class ClientesController extends Controller {

	

	/**

	 * Mostrar un listado de los recursos.

	 *

	 * @return \Illuminate\Http\Response

	 */

	public function index() {

	

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

		$passwor = $request->Input("password");

		$nombre = $request->Input("nombre");

		$foto = "http://washdryapp.com/oficial/Perfiles/".$request->Input("foto");

        $cat_usuario = new Usuario();

		$cat_usuario->nombre = $request->Input("nombre")." ".$request->Input("app")." ".$request->Input("apm");



		$cat_usuario->name = request("nombre");

		$cat_usuario->fecha_nac = $request->Input("fecha_nac");

		$cat_usuario->email = $request->Input("email");

		$cat_usuario->token = $request->Input("token");

		$cat_usuario->foto = $foto;

		$cat_usuario->password = Hash::make($passwor);

		$cat_usuario->google_id = "121212";

		$cat_usuario->username = $nombre;		

		

		DB::beginTransaction();

		try {

			if ($cat_usuario->save()) {

                $idUser = $cat_usuario->id;

                $cat_rolUser = new RolesUser();

                $cat_rolUser->id_user = $idUser;

                $cat_rolUser->id_rol = 3;

                if ($cat_rolUser->save()) {

                	$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente', 'ID' => $idUser];

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

			if ($cat_usuario->save()) {

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



	public function getPerfilWasher($id)

	{

		$results = DB::table('washers as w')

		->select('w.id_washer as id_washer', 'w.id_usuario', 'w.nombre', 'w.app', 'w.apm', 'w.telefono', DB::raw('IFNULL( w.foto_ine, "") as foto_ine'), 'w.fca_nacimiento', 'w.calificacion', 'u.email', 'u.foto')

		->leftjoin('users as u', 'u.id', '=', 'w.id_usuario')

		->where('u.id',$id)

		->get();

		return response()->json($results);

	}



	public function getPerfilCliente($id)

	{

		$results = DB::table('users as u')

		->select('u.id', 'u.nombre', 'u.foto', 'u.fecha_nac', 'u.username', 'u.email', 'u.name')

		->where('u.id',$id)

		->get();

		return response()->json($results);

	}



	public function listadoporSolicitud($id) {

		$results = DB::table('solicitud as s')



		->select('s.id_solicitud', 's.id_usuario', 's.id_washer', 's.id_paquete', 's.latitud', 's.longitud', 's.fecha', 's.forma_pago', 's.cambio', DB::raw('IFNULL( s.comentario, "") as comentario'), DB::raw('IFNULL( s.foto_washer, "") as foto_washer'), 's.status', 'a.placas', 'a.modelo', 'a.ann', 'pa.nombre as paquete', 'u.nombre as usuario', 'u.foto', 'p.precio')



		->leftjoin('users as u', 'u.id', '=', 's.id_usuario')



		->leftjoin('washers as w', 'w.id_washer', '=', 's.id_washer')



		->leftjoin('paquetes as p', 'p.id', '=', 's.id_paquete')



		->leftjoin('paquetes_lavado as pa', 'pa.id_paquete', '=', 'p.id_paquete')



		->leftjoin('autos as a', 'a.id_auto', '=', 's.id_auto')



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

}

