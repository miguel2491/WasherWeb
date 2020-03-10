$(document).ready(function() {
    $.fn.modal.Constructor.prototype.enforceFocus = function () {};
    var bt_tooltip;
    var empresa_sel = "";
    var id = "";
    toastr.options = {
        closeButton: true,
        progressBar: true,
        showMethod: 'slideDown',
        positionClass: "toast-top-full-width",
        timeOut: 3000
    };
    $("#ModalEmpresa").on('hidden.bs.modal', function() {
        $("#company_id").val(0).change();
    });

    $("#btn_guardar").click(function() {
        var msg = '';
        var get_url = $("#url_actualizar").val();
        var type_method = 'PUT';
        data_request = {
            empresa: $("#company_id").val(),
        };
        $.ajax({
            url: get_url,
            type: type_method,
            headers: { 'X-CSRF-TOKEN': $('meta[name=csrf-token]').attr('content') },
            data: data_request,
            dataType: 'json',
            success: function(data) {
                if (data.Estatus == 'fail') {
                    toastr.error(data.Mensaje);
                }else{
                    toastr.success(data.Mensaje);
                    $("#ModalEmpresa").modal('hide');
                    setTimeout(function() {
                        location.reload();
                        localStorage.setItem("sesion_empresa", 1);
                    }, 3000);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                var data = JSON.parse(jqXHR.responseText);

                if (jqXHR.status == 400) {
                    toastr.error('', data.Mensaje);
                }

                if (jqXHR.status == 422) {
                    $.each(data, function(key, value) {
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

    $.ajax({
        url: $("#url_empresa").val(),
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            var empresa = data;
            empresa_sel = empresa.comercial_name;
            $("#empresa_activa").text(empresa.comercial_name);
            setTimeout(function() {
                empresas();
            }, 1000);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            var data = jqXHR.responseJSON;
            if (jqXHR.status == 401) {
                //location.reload();
            }

        }
    });

    $("#ModalEmpresaPass").on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        id = typeof button.data('id') != "undefined" ? button.data('id') : 0;
    });

    $('#btn_cambiarE').click(function() {
        var msg = '';
        data_request = {
            company_id: id,
            password: $("#password").val(),
        }
        var get_url = $("#url_cambiar_empresa").val();
        var type_method = 'POST';
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
                    toastr.error(data.Mensaje);
                } else {
                    toastr.success(data.Mensaje);
                    $("#ModalEmpresaPass").modal('hide');
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
        
    function empresas(){
        $.ajax({
            url: $("#url_lista_empresas").val(),
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                var empresa = data;
                var stat = "";
                itemEmpresa = null;

                itemEmpresa = empresa.map(function(item) {
                    if(empresa_sel == item.comercial_name){
                        stat = "fa-check";
                    }else{
                        stat = "fa-times";
                    }
                    $("#cargo_empresas").append("<li><a data-id="+item.id+" data-toggle='modal' data-target='#ModalEmpresaPass' ><div><i class='fa fa-building fa-fw'></i> "+item.comercial_name+"<span class='pull-right text-muted small'><i class='fa "+stat+" text-navy'></i></span></div></a></li><li class='divider'></li>");
                    return { id: item.id, text: item.comercial_name };
                });
                
                if(itemEmpresa.length > 1 && localStorage.sesion_empresa == 0){
                    $("#ModalEmpresa").modal('show');
                    
                }
                $("#company_id").select2({
                    placeholder: {
                        id: 0,
                        text: 'Empresa...'
                    },
                    width: '100%',
                    allowClear: true,
                    data: itemEmpresa,
                });

                $("#company_id").val(0).change();

            },
            error: function(jqXHR, textStatus, errorThrown) {
                var data = jqXHR.responseJSON;
                if (jqXHR.status == 401) {
                    //location.reload();
                }

            }
        });
    }    
});