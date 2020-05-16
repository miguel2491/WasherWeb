<?php



namespace App\Http\Controllers\Apis;



use App;

use App\Http\Controllers\Controller;

use App\Models\Apis\Direcciones;

use App\User;

use Auth;

use DB;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;



class DireccionesController extends Controller {

	public function __construct()
{
    //Session::flush();
}

	public function index() {

		//return view('Catalogos/paquetes.index');

	}

	//INSERT

	public function guardar(Request $request) {
		$cat_direcciones = new Direcciones();
		$cat_direcciones->id_usuario = $request->Input("id_usuario");
		$cat_direcciones->latitud = $request->Input("latitude");
		$cat_direcciones->longitud = $request->Input("longitude");
		$cat_direcciones->direccion = $request->Input("direccion_gp");
		$cat_direcciones->descripcion = $request->Input("descripcion");
		DB::beginTransaction();
		try {
			if ($cat_direcciones->save()) {
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

		$results = DB::table('direcciones as d')

		->select('d.id_direccion', 'd.descripcion')

        ->get();

		return response()->json($results);

	}

	

	public function listadoUser($id) {

		$results = DB::table('direcciones as d')

		->select('d.id_direccion', 'd.descripcion','d.longitud','d.latitud')

		->where('d.id_usuario', $id)

        ->get();

		return response()->json($results);

	}



}

