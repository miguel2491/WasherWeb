<?php



namespace App\Http\Controllers\Apis;



use App;

use App\Http\Controllers\Controller;

use App\Models\Catalogos\Pagos;
use App\Models\Catalogos\Solicitudes;
use Stripe\Stripe;

use Stripe\Charge;

use App\User;

use Auth;

use DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class PagosController extends Controller {

	public function __construct() {

		//$this->middleware('auth');

	}

	public function index() {

	}

	//INSERT

	public function store(Request $request) {
		$id_sol = request("id_solicitud");
		$email = request('email');
        $monto = request("monto");
        $tipo_pago = request("tipo_pago");
        $montoStripe = $monto * 100;
        if($tipo_pago == "Efectivo")
        {
        	$estatuss = 1;
        }else{
        	Stripe::setApiKey(config('services.stripe.secret'));
        	$token = request('stripeToken');
        	$charge = Charge::create([

            'amount' => $montoStripe,

            'currency' => 'mxn',

            'description' => 'Test Washers',

            'source' => $token,

        	]);

        	$estatuss = $charge['status'] == "succeeded" ? 1:0;	
        }
        
        $fecha_a =  date('Y-m-d');
        if($estatuss == 1){
        	$cat_pagos = new Pagos();

        $cat_pagos->id_usuario = request("id_usuario");

        $cat_pagos->id_washer = request("id_washer");

        $cat_pagos->id_solicitud = $id_sol;

        $cat_pagos->monto = request("monto");

        $cat_pagos->cambio = request("cambio");

        $cat_pagos->tipo_pago = request("tipo_pago");

        $cat_pagos->status = 1;

		$cat_pagos->created_at = $fecha_a;        

        DB::beginTransaction();

		try {

			if ($cat_pagos->save()) {
                $id_usuario = request("id_usuario");
                $id_usuario_w = request("id_washer");
                $return = $this->notificaPersonalizada($id_usuario,"Pago realizado Correctamente","Bienvenido");
                $userW = DB::table('washers')->where('id_washer', $id_usuario_w)->first();
                if ($userW) {
                	$id_usuarioWash = $userW->id_usuario;
                	$return = $this->notificaPersonalizada($id_usuarioWash,"Se realizo el pago por tu servicio","Mirar");
                }
                $cat_solicitud = Solicitudes::findOrFail($id_sol);
                $cat_solicitud->status = 5;
                if ($cat_solicitud->save()) {
                	$msg = ['status' => 'ok', 'message' => 'Se agrego su pago correctamente','ID USUARIO'=>$id_usuario,'ID WASH'=>$id_usuarioWash,'Solicitud'=>$id_sol];
                }else{
                	$msg = ['status' => 'ok', 'message' => 'Se agrego su pago correctamente','ID USUARIO'=>$id_usuario,'ID WASH'=>$id_usuarioWash];
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
        }
		return response()->json($msg);
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

		return response()->json($results);

	}

	public function listadoPagosWasher(Request $request) {
		$id = request('id_washer');
		$fecha = request('fecha');

		$results = DB::table('pagos as p')

		//->select('p.id_pago', 'p.id_usuario', 'p.id_solicitud', 'p.monto','p.cambio','p.tipo_pago','p.status','p.comentario')
		->select(DB::raw("IFNULL(sum(monto),0) as ganancias"))
		->where('p.id_washer', $id)
		->where('p.created_at', $fecha)
        ->get();
		return response()->json($results);
	}

	//$return = $this->notificaPersonalizada($id_usuario,"Su Nueva Contraseña es: ".$randomString,"Bienvenido");

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


}

