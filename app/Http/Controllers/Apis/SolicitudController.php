<?php



namespace App\Http\Controllers\Apis;



use App;

use App\Http\Controllers\Controller;

use App\Models\Apis\Autos;

use App\Models\Catalogos\Solicitudes;

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

        $cat_solicitud = new Solicitudes();

        $cat_solicitud->id_washer = $request->Input("id_washer");
        $cat_solicitud->id_usuario = $request->Input("id_usuario");
        $cat_solicitud->id_paquete = $request->Input("id_paquete");
        $cat_solicitud->id_auto = $request->Input("id_auto");
        $cat_solicitud->latitud = $request->Input("latitud");
		$cat_solicitud->longitud = $request->Input("longitud");
		$cat_solicitud->foto = $request->Input("foto");
		$cat_solicitud->fecha = $request->Input("fecha");
		$cat_solicitud->calificacion = $request->Input("calificacion");
		$cat_solicitud->comentario = $request->Input("comentario");
		$cat_solicitud->status = "1";

        DB::beginTransaction();
		try {
			if ($cat_solicitud->save()) {
				$id = $cat_solicitud->id_solicitud;
                $msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente', 'id'=>$id];
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
		->select('s.id_solicitud', 's.id_paquete', 's.latitud', 's.longitud', 's.foto', 's.fecha', 's.calificacion', 's.comentario', 's.calificacion', 's.status',
		'w.nombre', 'w.app', 'w.apm', 'w.foto_ine', 'p.nombre as paquete')
		->leftjoin('washers as w', 'w.id_washer', '=', 's.id_washer')
		->leftjoin('paquetes_lavado as p', 'p.id_paquete', '=', 's.id_paquete')
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
}

