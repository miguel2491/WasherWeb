<?php



namespace App\Http\Controllers\Apis;



use App;

use App\Http\Controllers\Controller;

use App\Models\Apis\Autos;

use App\Models\Catalogos\Solicitudes;
use App\Models\Catalogos\Usuario;
use App\Models\RolesUser;

use App\User;

use Auth;

use DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class SolicitudController extends Controller {

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
		$idUsuario = $request->Input("id_usuario");
        $cat_solicitud = new Solicitudes();
        $cat_solicitud->id_washer = $request->Input("id_washer");
        $cat_solicitud->id_usuario = $idUsuario;
        $cat_solicitud->id_paquete = $request->Input("id_paquete");
        $cat_solicitud->id_auto = $request->Input("id_auto");
        $cat_solicitud->latitud = $request->Input("latitud");
		$cat_solicitud->longitud = $request->Input("longitud");
		$cat_solicitud->foto = $request->Input("foto");
		$cat_solicitud->fecha = $request->Input("fecha");
		$cat_solicitud->forma_pago = $request->Input("forma_pago");
		$cat_solicitud->cambio = $request->Input("cambio");
		$cat_solicitud->calificacion = $request->Input("calificacion");
		$cat_solicitud->comentario = $request->Input("comentario");
		$cat_solicitud->status = "1";

        DB::beginTransaction();
		try {
			if ($cat_solicitud->save()) {
				$id = $cat_solicitud->id_solicitud;
                $msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente', 'id'=>$id];
                $this->notificaPersonalizada($idUsuario,"Nueva solicitud","Revisa tu nueva Solicitud");

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



	public function listadoSolicitud($id) {

		$results = DB::table('solicitud as s')
		->select('s.id_solicitud', 's.id_paquete', 's.latitud', 's.longitud', 's.foto', 's.fecha', 's.calificacion', 's.comentario', 's.status',
		 'w.nombre', 'w.app', 'w.apm', 'w.foto_ine', 'p.nombre')

		->leftjoin('washers as w', 'w.id_washer', '=', 's.id_washer')
		->leftjoin('paquetes_lavado as p', 'p.id_paquete', '=', 's.id_paquete')

		->where('s.id_usuario',$id)
		
        ->get();

		return response()->json($results);

	}

	public function listadoporSolicitud($id) {
		$results = DB::table('solicitud as s')
		->select('s.id_solicitud', 's.id_usuario', 's.id_washer', 's.id_paquete', 's.latitud', 's.longitud', 's.fecha', 's.forma_pago', 's.cambio', 's.calificacion', 's.comentario', 's.calificacion', 's.foto_washer', 's.status', 'a.placas', 'a.modelo', 'a.ann',
		'w.nombre', 'w.app', 'w.apm', 'w.foto_ine', 'p.nombre as paquete', 'u.nombre as usuario', 'u.foto')
		->leftjoin('users as u', 'u.id', '=', 's.id_usuario')
		->leftjoin('washers as w', 'w.id_washer', '=', 's.id_washer')
		->leftjoin('paquetes_lavado as p', 'p.id_paquete', '=', 's.id_paquete')
		->leftjoin('autos as a', 'a.id_auto', '=', 's.id_auto')
		->where('s.id_solicitud',$id)
       	->get();

		return response()->json($results);

	}

	public function consultaCliente($id) {
		$results = DB::table('solicitud as s')
		->select('s.id_solicitud', 's.id_usuario', 's.id_washer', 'u.foto', 'u.nombre')
		->leftjoin('users as u', 'u.id', '=', 's.id_usuario')
		->where('s.id_solicitud',$id)
       	->get();
		return response()->json($results);
	}

	public function calificaSolicitud(Request $request)

	{

		$id_solicitud = $request->Input("id_solicitud");

		$cat_solicitud = Solicitudes::findOrFail($id_solicitud);

		$cat_solicitud->calificacion = $request->Input("calificacion");

		DB::beginTransaction();

		try {

			if ($cat_solicitud->save()) {

				$msg = ['status' => 'ok', 'message' => 'Se ha agrego la calificacion'];

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


	public function aceptar_solicitud(Request $request)
	{
		$id = $request->Input("id");
		$cat_paquete = Solicitudes::findOrFail($id);
        $cat_paquete->status = $request->Input("status");

		DB::beginTransaction();
		try {
			if ($cat_paquete->save()) {
				$msg = ['status' => 'ok', 'message' => 'Se Actualizo Correctamente'];
			}
		} catch (\Illuminate\Database\QueryException $ex) {
			DB::rollback();
			$msg = ['status' => 'fail', 'message' => 'No se pudo Actualizar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];

			return response()->json($msg, 400);

		} catch (\Exception $ex) {

			DB::rollback();

			$msg = ['status' => 'fail', 'message' => 'No se pudo Actualizar correctamente, por favor consulte con el administrador del sistema.', 'exception' => $ex->getMessage()];

			return response()->json($msg, 400);
		} finally {
			DB::commit();
		}
		return response()->json($msg);
	}

	public function modifica_cal(Request $request)
	{
		$id_solicitud = request('id_solicitud');
		$calificacion = request('calificacion');
		$comentario = request('comentario');
		$cat_solicitud = Solicitudes::findOrFail($id_solicitud);

		$cat_solicitud->calificacion = $calificacion;
		$cat_solicitud->comentario = $comentario;

		DB::beginTransaction();

		try {

			if ($cat_solicitud->save()) {

				$msg = ['status' => 'ok', 'message' => 'Se Modifico la solicitud'];

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

	public function guarda_cliente(Request $request)
	{
		$passwor = $request->Input("password");
		$nombre = $request->Input("nombre");
        
        $cat_usuario = new Usuario();
		$cat_usuario->nombre = $nombre;
		$cat_usuario->name = request("nombre");
		//$cat_usuario->fecha_nac = $request->Input("fecha_nac");
		$cat_usuario->email = $request->Input("email");
		$cat_usuario->token = "XAJKSHJHS";//$request->Input("token");
		$cat_usuario->password = Hash::make($passwor);
		$cat_usuario->google_id = "1111";
		$cat_usuario->username = $request->Input("username");
		
		DB::beginTransaction();
		try {
			if ($cat_usuario->save()) {
                $idUser = $cat_usuario->id;
                $cat_rolUser = new RolesUser();
                $cat_rolUser->id_user = $idUser;
                $cat_rolUser->id_rol = 3;
                if ($cat_rolUser->save()) {
                	$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente', 'id' => $idUser];
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

	public function login_cliente(Request $request)
	{
		$msg = [];
		$usuario = request('email');
        $pass = request('pass');
        if (Auth::attempt(['email' => $usuario, 'password' => $pass])) {
        	$msg = DB::table('users as u')
				->select('u.id', 'u.nombre','u.username','u.password','u.email','u.remember_token','u.name','u.google_id','u.token')
				->where('u.email', $usuario)
		        ->get();
        } else {
            $msg[] = [
            	'nombre'=>'fail'
            ];
        }
        return response()->json($msg);
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
            'Authorization: key=AAAA_YTXHaU:APA91bHMAq95ha-hiwv_trQ9uKdCjNoWpTwZnxuf3q9FCkkFIzuPQz7aYCEwyvfSxl9hrkrnuhLUUTRaou1cJP95Df2zDd4kAFiwJv1uEUP0SCnDmGDEgAoStYq4s7j1NRFeEqFHi2KT',
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

	public function agrega_img(Request $request)
	{
		$id_sol = request('id_solicitud');
		$foto = "http://washdryapp.com/oficial/Autos/".request('foto');
		$cat_solicitud = Solicitudes::findOrFail($id_sol);
		$fecha_atendida = date('Y-m-d H:i:s');

		$cat_solicitud->foto_washer = $foto;
		$cat_solicitud->fecha_atendida = $fecha_atendida;

		DB::beginTransaction();

		try {

			if ($cat_solicitud->save()) {

				$msg = ['status' => 'ok', 'message' => 'Se Modifico la solicitud'];

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

