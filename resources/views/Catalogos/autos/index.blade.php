@extends('layouts.admin')
@section('main-title')
   AUTOS
@endsection
@section('main-css')
<link href="{{ asset('font-awesome/css/all.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/dataTables/datatables.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/iCheck/custom.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/select2/select2.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/plugins/datapicker/datepicker3.css') }}" rel="stylesheet">
<style type="text/css">
    .dropdown-menu{
        left: auto;
        right: 0;
    }
    .select2{
        width: 100% !important;
    }
    span.select2-container {
        z-index:10050;
    }
    .contenidocuotas{
        height: 350px;
        overflow: auto;
    }
    .dropdown-menu>li>a{
        margin-top: 0px;
        margin-bottom: 0px;
        padding-top: 0px;
        padding-bottom: 0px;
    }
    .dataTables_info{
        margin-top: 15px;
    }
    #table-datos_paginate{
        margin-top: 15px;
    }
</style>
@endsection
@section('main-content')
<input type="hidden" name="_token" value="{{ csrf_token() }}" id="_token">
<input type="hidden" name="id_usuario" value="{{ Auth::user()->id }}" id="hdd_IdUsuario">
<input type="hidden" id="url_listado" value="{{ url('autos/listado') }}">
<input type="hidden" id="url_datosget" value="{{ url('autos/datos') }}">
<input type="hidden" id="url_guardar" value="{{ url('autos/guardar') }}">
<input type="hidden" id="url_actualizar" value="{{ url('autos/update') }}">
<input type="hidden" id="url_eliminar" value="{{ url('autos/delete') }}">
<input type="hidden" id="url_usuario_list" value="{{ url('usuario/lista_usuarios') }}">
<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-12">
			<div class="ibox float-e-margins">
				<div class="ibox-title">
					<div class="row">
						<div class="col-md-7">
							<h5><i class="glyphicon glyphicon-align-justify"></i> Paquete de Autos</h5>
						</div>
						<div class="col-md-5">
							<div class="pull-right">
								<a class="btn btn-xs btn-primary" data-toggle="modal" data-target="#ModalSave">
									Nuevo
									<i class="fa fa-plus"></i>
								</a>
							</div>
						</div>
					</div>
				</div>
				<div class="ibox-content">
					<!-- Inicia Cuerpo  de la Vista -->
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="table-datos">
							<thead>
								<tr>
									<th class="text-center">Clave</th>
									<th class="text-center">Nombre</th>
									<th class="text-center">Precio</th>
									<th class="text-center">Status</th>
									<th class="text-center">Acciones</th>
								</tr>
							</thead>
							<tbody>
							</tbody>
						</table>
					</div>
					<!-- Fin Cuerpo de la Vista -->
				</div>
			</div>
		</div>
	</div>
</div>
<!--Modal guardar y editar-->
<div class="modal fade" id="ModalSave" tabindex="-1" role="dialog" aria-labelledby="ModalEditar">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="ModalEditar">Autos</h4>
			</div>
			<div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
						<div class="form-group">
							<label for="descripcion-field">Usuario</label>
							
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="descripcion-field">Placas</label>
							<input type="text" id="placas" name="placas" class="form-control">
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-md-6">
						<div class="form-group">
							<label for="descripcion-field">Modelo</label>
							<input type="text" id="modelo" name="modelo" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label for="descripcion-field">Año</label>
							<input type="text" id="ann" name="ann" class="form-control">
						</div>
					</div>
				</div>
				<div class="row">
                    <div class="col-md-3">
						<div class="form-group">
							<label for="descripcion-field">Marca</label>
							<input type="text" id="marca" name="marca" class="form-control">
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label for="descripcion-field">Color</label>
							<input type="text" id="color" name="color" class="form-control">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<span class="btn btn-primary btn-file">
								<span class="fileinput-new">Foto</span>
								<span class="fileinput-exists">Cambiar</span>
								<input type="file" accept=".jpg" name="file_foto" id="file_foto">
							</span>
						</div>
					</div>
				</div>    
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-warning" data-dismiss="modal"><li class="fa fa-times"></li> Cancelar</button>
				<button class="btn btn-success" id="saveform"><li class="fa fa-check"></li> Aceptar</button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal_delete" data-backdrop="static" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="bootbox-body" style="text-align: center;">¿Desea eliminar el registro?</div>
				<input type="hidden" name="delpoliza" id="delpoliza">
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"><li class="fa fa-times"></li> Cancelar</button>
				<button type="submit" class="btn btn-success" id="btn_eliminar"><li class="fa fa-check"></li> Aceptar</button>
			</div>
		</div>
	</div>
</div>
@endsection
@section('main-scripts')
<script src="{{ asset('js/plugins/datapicker/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('js/plugins/dataTables/datatables.min.js') }}"></script>
	<script src="{{ asset('js/plugins/toastr/toastr.min.js')}}"></script>
	<script src="{{ asset('js/plugins/iCheck/icheck.min.js') }}"></script>
	<script src="{{ asset('js/plugins/select2/select2.full.min.js') }}"></script>
	<script src="{{ asset('js/plugins/autonumeric/autoNumeric.js') }}"></script>
	<script src="{{ asset('js/plugins/touchspin/jquery.bootstrap-touchspin.min.js') }}"></script>
	<script src="{{ asset('js/plugins/summernote/summernote.min.js') }}"></script>
	<script src="{{ asset('js/catalogos/autos.js') }}"></script>
@endsection