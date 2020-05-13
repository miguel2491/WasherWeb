<?php

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::get('login/google', 'Auth\GoogleController@redirectToProvider');
Route::get('login/google/callback', 'Auth\GoogleController@handleProviderCallback');
/**PAGOS**/
Route::post('/make-payment', 'PaymentsController@pay');
/*****USUARIO******/
Route::resource('usuario', 'Catalogos\UsuarioController', ['except' => ['create', 'store', 'update', 'destroy', 'edit', 'show']]);
Route::get('usuario/listado', 'Catalogos\UsuarioController@listado');
Route::post('usuario/guardar', 'Catalogos\UsuarioController@store');
Route::get('usuario/datos/{id}', 'Catalogos\UsuarioController@edit');
Route::put('usuario/update/{id}', 'Catalogos\UsuarioController@update');
Route::delete('usuario/delete/{id}', 'Catalogos\UsuarioController@destroy');
Route::get('usuario/lista_usuarios', 'Catalogos\UsuarioController@listar_select_usuario');
Route::get('usuario/lista_roles', 'Catalogos\UsuarioController@listar_roles_select');
/****WASHER*****/
Route::resource('washer', 'Catalogos\WasherController', ['except' => ['create', 'store', 'update', 'destroy', 'edit', 'show']]);
Route::get('washer/listado', 'Catalogos\WasherController@listado');
Route::post('washer/guardar', 'Catalogos\WasherController@store');
Route::get('washer/datos/{id}', 'Catalogos\WasherController@edit');
Route::put('washer/update/{id}', 'Catalogos\WasherController@update');
Route::delete('washer/delete/{id}', 'Catalogos\WasherController@destroy');
/******PAQUETE DE LAVADO********/
Route::resource('paquetes', 'Catalogos\PaquetesController', ['except' => ['create', 'store', 'update', 'destroy', 'edit', 'show']]);
Route::get('paquetes/listado', 'Catalogos\PaquetesController@listado');
Route::post('paquetes/guardar', 'Catalogos\PaquetesController@store');
Route::get('paquetes/datos/{id}', 'Catalogos\PaquetesController@edit');
Route::put('paquetes/update/{id}', 'Catalogos\PaquetesController@update');
Route::delete('paquetes/delete/{id}', 'Catalogos\PaquetesController@destroy');
//------------------
//*********APP******/
//------------------
Route::post('login/app', 'PaymentsController@login_app');
//Autos
Route::get('auto/listado', 'Apis\AutosController@listado');
Route::get('auto/listadoAutoUser/{id}', 'Apis\AutosController@listadoAutoUser');
Route::post('auto/guardar', 'Apis\AutosController@store');
Route::post('auto/imagen', 'PaymentsController@imagen');
Route::get('auto/datos/{id}', 'Apis\AutosController@edit');
Route::get('auto/delete/{id}', 'Apis\AutosController@destroy');
//Direcciones
Route::get('direccion/listado', 'Apis\DireccionesController@listado');
Route::post('direccion/guardar', 'Apis\DireccionesController@guardar');
Route::get('direccion/listado/{id}', 'Apis\DireccionesController@listadoUser');
Route::get('direccion/datos/{id}', 'Apis\DireccionesController@edit');
Route::delete('direccion/delete/{id}', 'Apis\DireccionesController@destroy');
//Solicitudes
Route::post('solicitud/agrega', 'Apis\SolicitudController@store');
Route::get('solicitud/listado/{id}', 'Apis\SolicitudController@listadoSolicitud');
Route::get('solicitud/lista_solicitud/{id}', 'Apis\ClientesController@listadoporSolicitud');
Route::get('solicitud/washer_solicitud/{id}', 'Apis\SolicitudController@listadoporSolicitud');
Route::get('solicitud/lista_all/{id}', 'Apis\SolicitudController@listaSolicitud');
Route::post('solicitud/califica', 'Apis\SolicitudController@calificaSolicitud');
Route::post('solicitud/aceptar_solicitud', 'Apis\SolicitudController@aceptar_solicitud');
Route::get('solicitud/cliente/{id}', 'Apis\SolicitudController@consultaCliente');
Route::post('solicitud/cliente_cancelar', 'Apis\SolicitudController@cancela_solicitud');
Route::post('solicitud/confirmaLlegada', 'Apis\SolicitudController@confirmar_llegada');
//**APP 1**/
Route::post('solicitud/agrega_cliente', 'Apis\SolicitudController@guarda_cliente');
Route::post('solicitud/login_cliente', 'Apis\SolicitudController@login_cliente');
Route::post('solicitud/detalle', 'Apis\SolicitudController@modifica_cal');
Route::post('solicitud/solicitud_img', 'Apis\SolicitudController@agrega_img');
//***APP 2***/
Route::post('washer/login', 'Apis\WashersController@login');
Route::get('washer/loginChema', 'Apis\WashersController@loginChema');
Route::post('washer/recupera_pass', 'Apis\WashersController@recupera_pass');
//Cliente Update
Route::post('cliente/registra', 'Apis\ClientesController@store');
Route::post('cliente/actualiza', 'Apis\ClientesController@update');
Route::post('cliente/califica', 'Apis\CalificacionController@store');
Route::get('califica/getCalifica/{id}', 'Apis\CalificacionController@getCalifica');
Route::post('cliente/califica_washer', 'Apis\CalificacionController@califica_washer');
Route::get('cliente/getPerfil/{id}', 'Apis\ClientesController@getPerfilCliente');
Route::get('cliente/washer_perfil/{id}', 'Apis\ClientesController@getPerfilWasher');
//Paquetes
Route::get('paquete/listado', 'Apis\PaquetesController@listado');
Route::post('paquete/guardar', 'Apis\PaquetesController@store');
Route::get('paquete/datos/{id}', 'Apis\PaquetesController@edit');
Route::put('paquete/update/{id}', 'Apis\PaquetesController@update');
Route::delete('paquete/delete/{id}', 'Apis\PaquetesController@destroy');
Route::get('paquete/individual/{id}', 'Apis\PaquetesController@lis_ind');
Route::post('paquetes/precios', 'Apis\PaquetesController@preciosList');
Route::post('paquete/precio_individual', 'Apis\PaquetesController@lis_ind_esp');
//Registros
Route::get('washer/lista', 'Apis\WashersController@listado');
Route::get('washer/get_solicitud/{id}', 'Apis\WashersController@getSolicitud');
Route::get('washer/get_solicitud_lavado/{id}', 'Apis\WashersController@getSolicitudLavado');
Route::post('washer/guardar', 'Apis\WashersController@store');
Route::post('washer/update', 'Apis\WashersController@update');
Route::get('washer/perfil/{id}', 'Apis\WashersController@getPerfilWasher');
Route::get('washer/autos_lavar/{id}', 'Apis\WashersController@getAutosLavar');
Route::get('washer/autos_lavarDetalle/{id}', 'Apis\WashersController@getAutosLavarDetalle');
Route::post('washer/guardar_img', 'Apis\WashersController@store');
//Pagos
Route::post('pagos/generar', 'Apis\PagosController@store');
Route::post('pagos/listaPagos', 'Apis\PagosController@listadoPagosWasher');
//Notificaciones
Route::post('washer/notificaciones', 'Apis\WashersController@notifica');
Route::post('cliente/notificacliente', 'Apis\ClientesController@notifica');
Route::post('washer/notifica', 'Apis\SolicitudController@notifica');
Route::get('mail/recuperaPass', 'Apis\WashersController@send');
//Agenda
Route::post('agenda/agregar', 'Apis\SolicitudController@agregar_agenda');
Route::get('agenda/listar/{id}', 'Apis\SolicitudController@listar_agenda');
Route::post('agenda/consultar_cliente', 'Apis\SolicitudController@agenda_cliente');
Route::post('agenda/consulta_washer', 'Apis\SolicitudController@agenda_washer');

