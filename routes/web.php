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
Route::post('auto/guardar', 'PaymentsController@store');
Route::post('auto/imagen', 'PaymentsController@imagen');
Route::get('auto/datos/{id}', 'Apis\AutosController@edit');
Route::delete('auto/delete/{id}', 'Apis\AutosController@destroy');
//Direcciones
Route::post('direccion/guardar', 'Apis\DireccionesController@store');
Route::get('direccion/listado', 'Apis\DireccionesController@listado');
Route::get('direccion/listado/{id}', 'Apis\DireccionesController@listadoUser');
Route::get('direccion/datos/{id}', 'Apis\DireccionesController@edit');
Route::delete('direccion/delete/{id}', 'Apis\DireccionesController@destroy');
//

//***APP 2***/
Route::post('washer/login', 'Apis\WashersController@login');
Route::post('washer/recupera_pass', 'Apis\WashersController@recupera_pass');
//Paquetes
Route::get('paquete/listado', 'Apis\PaquetesController@listado');
Route::post('paquete/guardar', 'Apis\PaquetesController@store');
Route::get('paquete/datos/{id}', 'Apis\PaquetesController@edit');
Route::put('paquete/update/{id}', 'Apis\PaquetesController@update');
Route::delete('paquete/delete/{id}', 'Apis\PaquetesController@destroy');
//Registros
Route::post('washer/guardar', 'Apis\WashersController@store');
Route::get('washer/lista', 'Apis\WashersController@listado');