<?php



namespace App\Http\Controllers\Apis;



use App;

use App\Http\Controllers\Controller;

use App\Models\Catalogos\Paquete;

use App\Models\Catalogos\PaquetesDetalle;

use App\User;

use Auth;

use DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class PaquetesController extends Controller {

	public function __construct() {

		//$this->middleware('auth');

	}

	/**

	 * Mostrar un listado de los recursos.

	 *

	 * @return \Illuminate\Http\Response

	 */

	

	//INSERT

	public function store(Request $request) {

        $cat_paquete = new Paquete();

        $cat_paquete->nombre = $request->Input("nombre");

        $cat_paquete->descripcion = $request->Input("descripcion");

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

	//Edit

	public function edit($id) {

		$results = DB::table('paquetes_lavado as p')

		->select('p.id_paquete', 'p.nombre', 'p.descripcion', 'p.status')

		->where('p.id_paquete',$id)->get();

		return response()->json($results);

    }

	//Update

	public function update(Request $request, $id) {

		$cat_paquete = Paquete::findOrFail($id);

		$cat_paquete->nombre = $request->Input("nombre");

        $cat_paquete->descripcion = $request->Input("descripcion");

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

		$cat_paquete = Paquete::find($id);

		DB::beginTransaction();

		try {

			if ($cat_paquete->delete()) {

				$msg = ['status' => 'ok', 'message' => ''];

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

		$results = DB::table('paquetes_lavado as p')

		->select('p.id_paquete', 'p.nombre', 'p.descripcion','p.status')

        ->get();

		return response()->json($results);

	}



	public function lis_ind($id)

	{

		$results = Paquete::join('paquetes', 'paquetes_lavado.id_paquete', '=', 'paquetes.id_paquete')

			->select(

				'paquetes_lavado.nombre',

				'paquetes_lavado.descripcion',

				'paquetes.tipo_vehiculo',

				'paquetes.duracion',

				'paquetes.precio'

			)

			->where('paquetes_lavado.id_paquete', $id)

			->get();

		

		return response()->json($results);	

	}

	public function lis_ind_esp(Request $request)

	{
		$id_paquete = request("id_paquete");
		$tipo = request('tipo');
		$results = DB::table('paquetes as p')
			->select('p.precio')
			->where('p.id_paquete', $id_paquete)
			->where('p.tipo_vehiculo', 'like', '%' . $tipo.'%')
			->get();

		

		return response()->json($results);	

	}

	public function preciosList(Request $request)
	{
		$id_paquete = request("id_paquete");
		$tipo_vehiculo = request("tipo");
		$results = DB::table('paquetes as p')
		->select('p.precio')
        ->where('id_paquete',$id_paquete)
        ->where('tipo_vehiculo',$tipo_vehiculo)
        ->get();
		return response()->json($results);
	}
}

