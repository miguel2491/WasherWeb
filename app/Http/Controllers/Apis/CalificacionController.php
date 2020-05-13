<?php



namespace App\Http\Controllers\Apis;



use App;

use App\Http\Controllers\Controller;

use App\Models\Catalogos\Calificaciones;
use App\Models\Catalogos\Solicitudes;

use App\User;

use Auth;

use DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class CalificacionController extends Controller {

	public function __construct() {

		//$this->middleware('auth');

	}

	public function index() {

		//return view('Catalogos/paquetes.index');

	}

	//INSERT

	public function store(Request $request) {
		$id_solicitud = request('id_solicitud');

		$user = DB::table('solicitud')->where('id_solicitud', $id_solicitud)->first();
        if ($user) {
        	$id_usuario = $user->id_usuario;
        	$return = $this->notificaPersonalizadaUsuario($id_usuario,"Obtuviste tu calificación","Mirar");
        }

        $cat_calificacion = new Calificaciones();

        $cat_calificacion->id_solicitud = $request->Input("id_solicitud");

        $cat_calificacion->calificacion = $request->Input("calificacion");

        $cat_calificacion->comentario = $request->Input("comentario");

        DB::beginTransaction();

		try {

			if ($cat_calificacion->save()) {
				$cat_solicitud = Solicitudes::findOrFail($id_solicitud);
                $cat_solicitud->status = 7;
                if ($cat_solicitud->save()) {
                	$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];	
                	//Notifica sobre tu calificacion
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

	public function califica_washer(Request $request) {
		$id_solicitud = request('id_solicitud');

        $cat_calificacion = new Calificaciones();

        $cat_calificacion->id_solicitud = $request->Input("id_solicitud");

        $cat_calificacion->calificacion = $request->Input("calificacion");

        $cat_calificacion->comentario = $request->Input("comentario");

        DB::beginTransaction();

		try {

			if ($cat_calificacion->save()) {
				$msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente'];
				$user = DB::table('solicitud')->where('id_solicitud', $id_solicitud)->first();
		        if ($user) {
		        	$id_washer = $user->id_washer;
		        	$washer = DB::table('washers')->where('id_washer', $id_washer)->first();
		        	if ($washer) {
		        		$id_usuario_washer = $user->id_usuario;
		        		$return = $this->notificaPersonalizadaUsuario($id_usuario_washer,"Obtuviste tu calificación","Mirar");
		        	}
		        }	
                //Notifica sobre tu calificacion
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

	public function getCalifica($id)
	{
		$results = DB::table('calificaciones as c')

		->select('c.id_calificacion', 'c.calificacion', DB::raw('IFNULL( c.comentario, "") as comentario'), 'c.tipo_calificacion', 'c.satus')

		->where('c.id_solicitud',$id)

		->get();

		return response()->json($results);
	}

	public function listado() {

		$results = DB::table('autos as a')

		->select('a.id_auto', 'a.marca')

        ->get();

		return response()->json($results);

	}

	public function notificaPersonalizadaUsuario($idUser,$title,$mensaje)
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
}

