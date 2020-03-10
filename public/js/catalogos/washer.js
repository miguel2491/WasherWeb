$(document).ready(function() {
    var dataTable_datos;
    var id = 0;

    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        positionClass: "toast-top-full-width",
        timeOut: 3000
    };

    $('.i-checks').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
    });
    /*SELECT USUARIO*/
    $.ajax({
        url: $("#url_usuario_list").val(),
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var rol = data;
            var itemobjeto = [];
                itemobjeto.push({
                    id: 0,
                    text: 'Todos'
                });
            rol.forEach(function(element) {
                var nombre = element.nombre;
                itemobjeto.push({
                    id: element.id,
                    text: nombre
                });
            });
            $("#usuario").select2({
                width: '100%',
                allowClear: true,
                data: itemobjeto
                    //dropdownParent: $('#ModalSave')
            });

            $("#usuario").val(0).change();
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var data = jqXHR.responseJSON;
            if (jqXHR.status == 401) {
                location.reload();
            }

        }
    });
    /**FIN DE SELECT*/
    dataTable_datos =
        $("#table-datos").dataTable({
            "bDeferRender": true,
            "iDisplayLength": 10,
            "bProcessing": true,
            "sAjaxSource": $('#url_listado').val(),
            "fnServerData": function(sSource, aoData, fnCallback, oSettings) {
                oSettings.jqXHR = $.ajax({
                    "dataType": 'json',
                    "type": "GET",
                    "url": sSource,
                    "success": fnCallback,
                    "error": function(jqXHR, textStatus, errorThrown) {
                        var data = jqXHR.responseJSON;
                        /*
                        if (jqXHR.status == 401 || jqXHR.status == 500) {
                            location.reload();
                        }*/
                    }
                });
            },
            "bAutoWidth": false,
            //"bFilter": false,
            "aoColumns": [{
                "mData": "id"
            }, {
                "mData": "name",
                "bSortable": true,
                "mRender": function(data, type, full) {
                    var nombre = full.usuario;
                    return nombre;
                }
            }, {
                "mData": "nombre",
                "bSortable": true,
                "mRender": function(data, type, full) {
                    var nombre = full.nombre+" "+full.app+" "+full.apm;
                    return nombre;
                }
            },{
                "mData": "status_washer",
                "bSortable": true,
                "mRender": function(data, type, full) {
                    var Activo = full.status_washer == 1 ? '<i class="fa fa-check text-navy"></i>' : '<i class="fa fa-times text-navy"></i>';
                    return Activo;
                }
            }, {
                "mData": "Acciones",
                "bSortable": false,
                "mRender": function(data, type, full) {
                    var bttnDelete = '<button class="btn btn-danger btn-xs" id="bttn_modal_delete" data-id="' + full.id + '" data-target="#modal_delete"  data-toggle="modal" title="Eliminar"><i class="glyphicon glyphicon-trash"></i></button>';
                    var bttnUpdate = '<button class="btn btn-warning btn-xs" id="bttn_modal_update"  data-id="' + full.id + '"  data-target="#ModalSave" data-toggle="modal" title="Editar"><i class="glyphicon glyphicon-edit"></i></button> ';
                    return bttnUpdate + bttnDelete;
                }
            }, ],
            "fnRowCallback": function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
                $("td:eq(0), td:eq(2)", nRow).attr('align', 'center');

            },
            "aLengthMenu": [
                [5, 10, -1],
                [5, 10, "Todo"]
            ],
            "language": {
                "sProcessing": "Procesando...",
                "sLengthMenu": "Mostrar _MENU_ registros",
                "sZeroRecords": "No se encontraron resultados",
                "sEmptyTable": "Ningún dato disponible en esta tabla",
                "sInfo": "Registros del _START_ al _END_  total: _TOTAL_ ",
                "sInfoEmpty": "Sin registros",
                "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
                "sInfoPostFix": "",
                "sSearch": "Buscar:",
                "sUrl": "",
                "sInfoThousands": ",",
                "sLoadingRecords": "Cargando...",
                "oPaginate": {
                    "sFirst": "Primero",
                    "sLast": "Último",
                    "sNext": "Siguiente",
                    "sPrevious": "Anterior"
                },
                "oAria": {
                    "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                    "sSortDescending": ": Activar para ordenar la columna de manera descendente"
                }
            }
        });

    $('#ModalSave').on('shown.bs.modal', function() {
        $(this).find('[autofocus]').focus();
    });
    $("#ModalSave").on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        id = typeof button.data('id') != "undefined" ? button.data('id') : 0;
        if (id != 0) {
            $.ajax({
                url: $('#url_datosget').val() + '/' + id,
                type: 'GET',
                data: '',
                success: function(data) {
                    var obj = data;
                    console.log(obj);
                    id = obj[0].id;
                    $('#usuario').val(obj[0].id_usuario).change();
                    $('#nombre').val(obj[0].nombre);
                    $('#app').val(obj[0].app);
                    $("#apm").val(obj[0].apm);
                    $("#calificacion").val(obj[0].calificacion);
                    $("input[name=washer_s]").iCheck(obj[0].status_washer == 1 ? 'check' : 'uncheck');
                    $("input[name=pago_s]").iCheck(obj[0].pago_status == 1 ? 'check' : 'uncheck');
                }

            });
        }

    });
    $("#modal_delete").on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        id = typeof button.data('id') != "undefined" ? button.data('id') : 0;
    });
    $('#ModalSave').on('show.bs.modal', function(e) {
        $('#usuario').val(0).change();
        $('#nombre').val("");
        $('#app').val("");
        $('#apm').val("");
        $('#calificacion').val("");
        $("input[name=pago_s]").iCheck('uncheck');
        $("input[name=washer_s]").iCheck('uncheck');
    });
    $('#saveform').click(function() {
        var msg = '';
        
            data_request = {
                id: id,
                id_usuario: $('#usuario option:selected').val()==undefined?'0':$('#usuario option:selected').val(),
                nombre: $('#nombre').val(),
                app: $('#app').val(),
                apm: $('#apm').val(),
                calificacion: $("#calificacion").val(),
                pago_status: $('input:checkbox[name=pago_s]:checked').val() == undefined ? 0 : 1,
                status_washer: $('input:checkbox[name=washer_s]:checked').val() == undefined ? 0 : 1
            }
        
        var get_url = id == 0 ? $("#url_guardar").val() : $("#url_actualizar").val() + '/' + id;
        var type_method = id == 0 ? 'POST' : 'PUT';

        $.ajax({
            url: get_url,
            type: type_method,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            data: data_request,
            success: function(data) {
                if (data.status == 'fail') {
                    toastr.error(data.message);
                } else {
                    toastr.success(data.message);
                    dataTable_datos._fnAjaxUpdate();
                    $("#ModalSave").modal('hide');
                    setTimeout(function() {
                        location.reload();
                    }, 2000);
                }
            },

            error: function(jqXHR, textStatus, errorThrown) {
                var data = JSON.parse(jqXHR.responseText);

                if (jqXHR.status == 400) {
                    toastr.error('', data.message);
                }

                if (jqXHR.status == 401) {
                    location.reload();
                }

                if (jqXHR.status == 422) {
                    $.each(data.errors, function(key, value) {
                        if (msg == '') {
                            msg = value[0] + '<br>';
                        } else {
                            msg += value[0] + '<br>';
                        }

                    });

                    toastr.error(msg);
                }
            }
        });
    });
    $('#btn_eliminar').click(function() {
        //var id = $('#delpoliza').val();

        $.ajax({
            url: $("#url_eliminar").val() + '/' + id,
            type: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                if (data.status == 'fail') {
                    toastr.error(data.message);
                } else {
                    dataTable_datos._fnAjaxUpdate();
                }

                $("#modal_delete").modal('hide');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var data = JSON.parse(jqXHR.responseText);
                if (jqXHR.status == 400) {
                    toastr.error('', data.message);
                }

                if (jqXHR.status == 401) {
                    location.reload();
                }
            }
        });
    });
});