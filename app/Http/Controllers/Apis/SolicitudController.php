<?php
namespace App\Http\Controllers\Apis;

use App;
use App\Http\Controllers\Controller;
use App\Models\Apis\Agenda;
use App\Models\Apis\Autos;
use App\Models\Catalogos\Calificaciones;
use App\Models\Catalogos\Solicitudes;
use App\Models\Catalogos\Usuario;
use Auth;
use DB;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{
    public function __construct()
    {

    }
    /**
     * Mostrar un listado de los recursos.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return view('Catalogos/paquetes.index');
    }
    public function store(Request $request)
    {

        $idUsuario                   = $request->Input("id_usuario");
        $idWasher                    = $request->Input("id_washer");
        $cat_solicitud               = new Solicitudes();
        $cat_solicitud->id_washer    = $request->Input("id_washer");
        $cat_solicitud->id_usuario   = $idUsuario;
        $cat_solicitud->id_paquete   = $request->Input("id_paquete");
        $cat_solicitud->id_auto      = $request->Input("id_auto");
        $cat_solicitud->fragancia    = $request->Input("fragancia");
        $cat_solicitud->latitud      = $request->Input("latitud");
        $cat_solicitud->longitud     = $request->Input("longitud");
        $cat_solicitud->direccion    = $request->Input("direccion");
        $cat_solicitud->foto         = $request->Input("foto");
        $cat_solicitud->fecha        = $request->Input("fecha");
        $cat_solicitud->forma_pago   = $request->Input("forma_pago");
        $cat_solicitud->cambio       = 0.00;
        $cat_solicitud->calificacion = 0; //$request->Input("calificacion");
        $cat_solicitud->comentario   = $request->Input("comentario");
        $cat_solicitud->status       = "1";
        DB::beginTransaction();
        try {
            if ($cat_solicitud->save()) {
                $id = $cat_solicitud->id_solicitud;
                //Notis Usuario Washers
                $user = DB::table('washers')->where('id_washer', $idWasher)->first();
                if ($user) {
                    $id_usuario_w = $user->id_usuario;
                    $return       = $this->notificaPersonalizada($id_usuario_w, "Nueva Solicitud", "ENtra Ya");
                }
                $msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente', 'id' => $id, 'id_wa' => $id_usuario_w];
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

    public function edit($id)
    {

        $results = DB::table('autos as a')

            ->select('a.id_auto', 'a.id_usuario', 'a.placas', 'a.modelo', 'a.ann', 'a.marca', 'a.color', 'a.imagen')

            ->where('a.id_auto', $id)->get();

        return response()->json($results);
    }

    //Update
    public function update(Request $request, $id)
    {

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
    public function destroy($id)
    {

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

    public function listadoSolicitud($id)
    {

        $results = DB::table('solicitud as s')

            ->select('s.id_solicitud', 's.id_paquete', 's.latitud', 's.longitud', 's.foto', 's.fecha', 's.calificacion', DB::raw('IFNULL( s.comentario, "") as comentario'), 's.status',

                'w.nombre', 'w.app', 'w.apm', DB::raw('IFNULL( w.foto_ine, "") as foto_ine'), 'p.nombre')

            ->leftjoin('washers as w', 'w.id_washer', '=', 's.id_washer')

            ->leftjoin('paquetes_lavado as p', 'p.id_paquete', '=', 's.id_paquete')

            ->where('s.id_usuario', $id)

            ->where('s.status', 1)

            ->get();

        return response()->json($results);

    }

    public function listaSolicitud($id)
    {

        $results = DB::table('solicitud as s')

            ->select('s.id_solicitud', 's.id_paquete', 's.latitud', 's.longitud', 's.foto', 's.fecha', 's.calificacion', DB::raw('IFNULL( s.comentario, "") as comentario'), 's.status',

                'w.nombre', 'w.app', 'w.apm', DB::raw('IFNULL( w.foto_ine, "") as foto_ine'), 'p.nombre as lavado', 'pa.precio')

            ->leftjoin('washers as w', 'w.id_washer', '=', 's.id_washer')

            ->leftjoin('paquetes_lavado as p', 'p.id_paquete', '=', 's.id_paquete')

            ->leftjoin('paquetes as pa', 'pa.id', '=', 's.id_paquete')

            ->where('s.id_usuario', $id)

            ->get();

        return response()->json($results);

    }

    public function listadoporSolicitud($id)
    {
        $results = DB::table('solicitud as s')
            ->select('s.id_solicitud', 's.id_usuario', 's.id_washer', 's.id_paquete', 's.latitud', 's.longitud', 's.fecha', 's.forma_pago', 's.cambio', 's.calificacion', DB::raw('IFNULL( s.comentario, "") as comentario'), DB::raw('IFNULL( s.foto_washer, "") as foto_washer'), 's.status', 'a.placas', 'a.modelo', 'a.ann', 'pa.nombre as paquete', 'u.nombre as usuario', 'u.foto', 'p.precio', DB::raw('IFNULL( cal.calificacion, 0) as calificacion'), DB::raw('IFNULL( pago.monto, 0) as monto'))
            ->leftjoin('users as u', 'u.id', '=', 's.id_usuario')
            ->leftjoin('washers as w', 'w.id_washer', '=', 's.id_washer')
            ->leftjoin('paquetes as p', 'p.id', '=', 's.id_paquete')
            ->leftjoin('paquetes_lavado as pa', 'pa.id_paquete', '=', 'p.id_paquete')
            ->leftjoin('pagos as pago', 'pago.id_solicitud', '=', 's.id_solicitud')
            ->leftjoin('autos as a', 'a.id_auto', '=', 's.id_auto')
            ->join('calificaciones as cal', 'cal.id_solicitud', '=', 's.id_solicitud')
            ->where('cal.tipo_calificacion', 'W')
            ->where('s.id_solicitud', $id)
            ->get();
        return response()->json($results);
    }

    public function cancela_solicitud(Request $request)
    {

        $id = $request->Input("id_solicitud");

        $comentario = $request->Input("comentario");

        $user = DB::table('solicitud')->where('id_solicitud', $id)->first();

        if ($user) {

            $id_usuario = $user->id_usuario;

            $return = $this->notificaPersonalizadaUsuario($id_usuario, "Tu Solicitud fue Cancelada", "Mirar");

            $id_washer = $user->id_washer;

            $washer = DB::table('washers')->where('id_washer', $id_washer)->first();

            if ($washer) {

                $id_usuario_w = $washer->id_usuario;

                $return = $this->notificaPersonalizada($id_usuario_w, "Solicitud Cancelada por el Cliente, lo sentimos", "Entra");

            }

        }

        $id_solicitud = $request->Input("id_solicitud");

        $cat_solicitud = Solicitudes::findOrFail($id_solicitud);

        $cat_solicitud->comentario = $comentario;

        $cat_solicitud->status = 6;

        DB::beginTransaction();

        try {

            if ($cat_solicitud->save()) {

                $msg = ['status' => 'ok', 'message' => 'Se ha cancelado la solicitud'];

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

    public function consultaCliente($id)
    {
        $results = DB::table('solicitud as s')
            ->select('s.id_solicitud', 's.id_usuario', 's.id_washer', 'u.foto', 'u.nombre')
            ->leftjoin('users as u', 'u.id', '=', 's.id_usuario')
            ->where('s.id_solicitud', $id)
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

        $cat_paquete->status = 2;

        $user = DB::table('solicitud')->where('id_solicitud', $id)->first();

        if ($user) {

            $id_usuario = $user->id_usuario;

            $return = $this->notificaPersonalizadaUsuario($id_usuario, "Tu Solicitud fue Aceptada", "Mirar");

        }

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

                $calif = DB::table('calificaciones')->where('id_solicitud', $id_solicitud)->first();

                if ($calif) {

                    $id_cal = $calif->id_calificacion;

                    $cat_calificacion = Calificaciones::findOrFail($id_cal);

                    $cat_calificacion->calificacion = $calificacion;

                    $cat_calificacion->comentario = $comentario;

                    if ($cat_calificacion->save()) {

                        $msg = ['status' => 'ok', 'message' => 'Se Modifico la solicitud', 'ID' => $id_cal];

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

    public function login_cliente(Request $request)
    {

        $msg = [];

        $usuario = request('email');

        $pass = request('pass');

        $token = request('token');

        if (Auth::attempt(['email' => $usuario, 'password' => $pass])) {

            $msg = DB::table('users as u')

                ->select('u.id', 'u.nombre', 'u.username', 'u.password', 'u.email', 'u.remember_token', 'u.name', 'u.google_id', 'u.token')

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

                'nombre' => 'fail',

            ];

        }

        return response()->json($msg);

    }

    public function notificaPersonalizada($idUser, $title, $mensaje)
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

            'title'   => $title,

            'message' => $mensaje,

            'sound'   => true,

        ];

        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [

            //'registration_ids' => $tokenList, //multple token array

            'to'           => $token, //single token

            'notification' => $notification,

            'data'         => $extraNotificationData,

        ];

        $headers = [

            'Authorization: key=AAAAJBsH6ro:APA91bEJ9I8FnVFqRSeoRSxNv9mT17C876UrWLRY0d6Ow7jV9pcI9Dizb6hf4A1go3MnzY9V4XpU-25XwTqvc-PMIdHVJz6aTLF9yC0Hp4wc5a3pa7EbUKyV_gv5b_r5lGTQnpas0SOp',

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $fcmUrl);

        curl_setopt($ch, CURLOPT_POST, true);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));

        $result = curl_exec($ch);

        curl_close($ch);

        //return $result;
    }

    public function notificaPersonalizadaUsuario($idUser, $title, $mensaje)
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

            'title'   => $title,

            'message' => $mensaje,

            'sound'   => true,

        ];

        $extraNotificationData = ["message" => $notification, "moredata" => 'dd'];

        $fcmNotification = [

            //'registration_ids' => $tokenList, //multple token array

            'to'           => $token, //single token

            'notification' => $notification,

            'data'         => $extraNotificationData,

        ];

        $headers = [

            'Authorization: key=AAAAJBsH6ro:APA91bEJ9I8FnVFqRSeoRSxNv9mT17C876UrWLRY0d6Ow7jV9pcI9Dizb6hf4A1go3MnzY9V4XpU-25XwTqvc-PMIdHVJz6aTLF9yC0Hp4wc5a3pa7EbUKyV_gv5b_r5lGTQnpas0SOp',

            'Content-Type: application/json',

        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $fcmUrl);

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

        $foto = "http://washdryapp.com/oficial/Autos/" . request('foto');

        $cat_solicitud = Solicitudes::findOrFail($id_sol);

        $fecha_atendida = date('Y-m-d H:i:s');

        $cat_solicitud->foto_washer = $foto;

        $cat_solicitud->fecha_atendida = $fecha_atendida;

        $cat_solicitud->status = 4;

        $id_usuario = $cat_solicitud->id_usuario;

        DB::beginTransaction();

        try {

            if ($cat_solicitud->save()) {

                $return = $this->notificaPersonalizada($id_usuario, "Solicitud Finalizada", "Entra");

                $msg = ['status' => 'ok', 'message' => 'Se Modifico la solicitud', 'idUsuario' => $id_usuario];

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

    public function confirmar_llegada(Request $request)
    {

        $id = $request->Input("id_solicitud");

        $status = 0;

        $user = DB::table('solicitud')->where('id_solicitud', $id)->first();

        if ($user) {

            $id_usuario = $user->id_usuario;

            $status = $user->status;

            $return = $this->notificaPersonalizadaUsuario($id_usuario, "El Washer ha llegado", "Mirar");

        }

        if ($status == 6) {

            $msg = ['status' => 'fail'];

            return response()->json($msg, 400);

        }

        $cat_solicitud = Solicitudes::findOrFail($id);

        $cat_solicitud->status = 3;

        DB::beginTransaction();

        try {

            if ($cat_solicitud->save()) {

                $msg = ['status' => 'ok', 'message' => 'Lavado en Proceso', 'status_sol' => $status];

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

    public function agregar_agenda(Request $request)
    {
        $idUsuario                   = $request->Input("id_usuario");
        $idWasher                    = $request->Input("id_washer");
        $cat_solicitud               = new Solicitudes();
        $cat_solicitud->id_washer    = $request->Input("id_washer");
        $cat_solicitud->id_usuario   = $idUsuario;
        $cat_solicitud->id_paquete   = $request->Input("id_paquete");
        $cat_solicitud->id_auto      = $request->Input("id_auto");
        $cat_solicitud->fragancia    = $request->Input("fragancia");
        $cat_solicitud->latitud      = $request->Input("latitud");
        $cat_solicitud->longitud     = $request->Input("longitud");
        $cat_solicitud->direccion    = $request->Input("direccion");
        $cat_solicitud->foto         = $request->Input("foto");
        $cat_solicitud->fecha        = $request->Input("fecha");
        $cat_solicitud->forma_pago   = $request->Input("forma_pago");
        $cat_solicitud->cambio       = 0.00;
        $cat_solicitud->calificacion = 0; //$request->Input("calificacion");
        $cat_solicitud->comentario   = $request->Input("comentario");
        $cat_solicitud->status       = "8";
        DB::beginTransaction();
        try {
            if ($cat_solicitud->save()) {
                $id                         = $cat_solicitud->id_solicitud;
                $cat_agenda                 = new Agenda();
                $cat_agenda->id_cliente     = $request->Input("id_usuario");
                $cat_agenda->id_solicitud   = $id;
                $cat_agenda->fecha_agendada = $request->Input("fecha_agendada");
                $cat_agenda->id_washer      = $request->Input("id_washer");
                $cat_agenda->status         = 1;
                if ($cat_agenda->save()) {
                    $user = DB::table('washers')->where('id_washer', $idWasher)->first();
                    if ($user) {
                        $id_usuario_w = $user->id_usuario;
                        $return       = $this->notificaPersonalizada($id_usuario_w, "Se agendo nueva solicitud", "ENtra Ya");
                    }
                    $msg = ['status' => 'ok', 'message' => 'Se ha guardado correctamente', 'id' => $id, 'id_wa' => $id_usuario_w];
                }
                //Notis Usuario Washers

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

    public function listar_agenda($id)
    {
        $results = DB::table('agendas as a')
            ->select('a.id_agenda', 'a.id_cliente', 'a.id_washer', 'a.id_solicitud', 'a.fecha_agendada', 'a.status')
            ->where('a.id_washer', $id)
            ->get();
        return response()->json($results);
    }

    public function agenda_cliente()
    {
        $id      = request('id_usuario');
        $fecha   = request('fecha');
        $results = DB::table('agendas as a')
            ->select('a.id_agenda', 'a.id_cliente', 'a.id_washer', 'a.id_solicitud', 'a.fecha_agendada', 'a.status')
            ->where('a.id_cliente', $id)
            ->whereBetween('a.fecha_agendada', [$fecha . ' 00:00:00', $fecha . ' 23:59:59'])
            ->first();
        if ($results) {
            $id_solicitud          = $results->id_solicitud;
            $cat_solicitud         = Solicitudes::findOrFail($id_solicitud);
            $cat_solicitud->status = 0;
            $msg                   = ['status' => 'ok', 'message' => 'Correcto', 'ID_SOLIC' => $id_solicitud];
            DB::beginTransaction();
            try {
                if ($cat_solicitud->save()) {
                    $agenda    = DB::table('agendas')->where('id_solicitud', $id_solicitud)->first();
                    $id_agenda = $agenda->id_agenda;
                    if ($agenda) {
                        $id_agenda          = $agenda->id_agenda;
                        $cat_agenda         = Agenda::findOrFail($id_agenda);
                        $cat_agenda->status = 0;
                        if ($cat_agenda->save()) {
                            $return = $this->notificaPersonalizada($id, "Se agendo nueva solicitud", "ENtra Ya");
                            $msg    = ['status' => 'ok', 'message' => 'Correcto', 'ID_SOLIC' => $id_solicitud, 'Agenda' => $id_agenda, 'id_w_u' => $id];
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
        } else {
            $msg = ['status' => 'fail', 'message' => 'Sin Infos hoy', 'id' => $id, 'fecha' => $fecha];
        }
        return response()->json($msg);
    }

    public function agenda_washer(Request $request)
    {
        $id      = request('id_washer');
        $fecha   = request('fecha');
        $results = DB::table('agendas as a')
            ->select('a.id_agenda', 'a.id_cliente', 'a.id_washer', 'a.id_solicitud', 'a.fecha_agendada', 'a.status')
            ->where('a.id_washer', $id)
            ->whereBetween('a.fecha_agendada', [$fecha . ' 00:00:00', $fecha . ' 23:59:59'])
            ->first();
        if ($results) {
            $id_solicitud          = $results->id_solicitud;
            $cat_solicitud         = Solicitudes::findOrFail($id_solicitud);
            $cat_solicitud->status = 0;
            $msg                   = ['status' => 'ok', 'message' => 'Correcto', 'ID_SOLIC' => $id_solicitud];
            DB::beginTransaction();
            try {
                if ($cat_solicitud->save()) {
                    $agenda    = DB::table('agendas')->where('id_solicitud', $id_solicitud)->first();
                    $id_agenda = $agenda->id_agenda;
                    if ($agenda) {
                        $id_agenda          = $agenda->id_agenda;
                        $cat_agenda         = Agenda::findOrFail($id_agenda);
                        $cat_agenda->status = 0;
                        if ($cat_agenda->save()) {
                            $user = DB::table('washers')->where('id_washer', $id)->first();
                            if ($user) {
                                $id_usuario_w = $user->id_usuario;
                                $return       = $this->notificaPersonalizada($id_usuario_w, "Se agendo nueva solicitud", "ENtra Ya");
                            }
                            $msg = ['status' => 'ok', 'message' => 'Correcto', 'ID_SOLIC' => $id_solicitud, 'Agenda' => $id_agenda, 'id_w_u' => $id_usuario_w];
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
        } else {
            $msg = ['status' => 'fail', 'message' => 'Sin Infos hoy', 'id' => $id, 'fecha' => $fecha];
        }
        return response()->json($msg);
    }
}
