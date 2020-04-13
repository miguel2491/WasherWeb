<?php



namespace App\Http\Controllers\Apis;



use App;

use App\Http\Controllers\Controller;

use App\Models\Catalogos\Pagos;
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

		Stripe::setApiKey(config('services.stripe.secret'));

        $token = request('stripeToken');
        $email = request('email');
        $monto = request("monto");
        $montoStripe = $monto * 100;

        $charge = Charge::create([

            'amount' => $montoStripe,

            'currency' => 'mxn',

            'description' => 'Test Washers',

            'source' => $token,

        ]);

        $estatuss = $charge['status'] == "succeeded" ? 1:0;
        $fecha_a =  date('Y-m-d');
        if($estatuss == 1){
        	$cat_pagos = new Pagos();

        $cat_pagos->id_usuario = request("id_usuario");

        $cat_pagos->id_washer = request("id_washer");

        $cat_pagos->id_solicitud = request("id_solicitud");

        $cat_pagos->monto = request("monto");

        $cat_pagos->cambio = request("cambio");

        $cat_pagos->tipo_pago = request("tipo_pago");

        $cat_pagos->status = 1;

		$cat_pagos->created_at = $fecha_a;        

        DB::beginTransaction();

		try {

			if ($cat_pagos->save()) {

                $msg = ['status' => 'ok', 'message' => 'Se agrego su pago correctamente'];

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

}

