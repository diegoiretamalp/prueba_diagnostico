$(document).ready(function () {

    cargarRegiones();
    cargarCandidatos();

    $('#nombre_completo').keyup(function () {
        ValidaCampos($(this).val(), 'texto', 'nombre_completo');
    });
    $('#alias').keyup(function () {
        ValidaCampos($(this).val(), 'texto', 'alias');
    });
    $('#rut').keyup(function () {
        rsp = ValidaCampos($(this).val(), 'rut', 'rut');
        console.log(rsp);
    });
    $('#email').keyup(function () {
        ValidaCampos($(this).val(), 'correo', 'email');
    });
    $('#region_id').change(function () {
        let region_id = $(this).val();
        cargarComunas();
        ValidaCampos(region_id, 'select', 'region_id');

    });
    $('#comuna_id').change(function () {
        ValidaCampos($(this).val(), 'select', 'comuna_id');
    });
    $('#candidato_id').change(function () {
        ValidaCampos($(this).val(), 'select', 'candidato_id');
    });
    $('input[name="medio_conocimiento[]"]').change(function () {
        ValidaCampos($(this).val(), 'checkbox', 'medio_conocimiento');
    });

    $('#votar').click(function () {

        let valida_nombre_completo = ValidaCampos($('#nombre_completo').val(), 'texto', 'nombre_completo');
        let valida_alias = ValidaCampos($('#alias').val(), 'texto', 'alias');
        let valida_rut = ValidaCampos($('#rut').val(), 'rut', 'rut');
        let valida_email = ValidaCampos($('#email').val(), 'correo', 'email');
        let valida_region_id = ValidaCampos($('#region_id').val(), 'select', 'region_id');
        let valida_candidato_id = ValidaCampos($('#candidato_id').val(), 'select', 'candidato_id');
        let valida_comuna_id = ValidaCampos($('#comuna_id').val(), 'select', 'comuna_id');
        let value_medio_conocimiento = $('input[name="medio_conocimiento[]"]:checked').val();
        let valida_medio_conocimiento = ValidaCampos(value_medio_conocimiento, 'checkbox', 'medio_conocimiento');


        if (valida_nombre_completo == 1 && valida_alias == 1 && valida_rut == 1 && valida_email == 1
            && valida_region_id == 1 && valida_candidato_id == 1 && valida_comuna_id == 1 && valida_medio_conocimiento == 1) {
            NuevoFormulario();
        } else {
            ToastMsg('error', 'Formulario de Votación', '1 o más campos son requeridos, completalos para continuar por favor.');
        }
    });


});

function LimpiarFormulario() {
    $('#nombre_completo').val('');
    $('#alias').val('');
    $('#rut').val('');
    $('#email').val('');
    $('#comuna_id').html('<option value="">Seleccionar</option>');
    $('#comuna_id').prop('disabled', true);
    $('#comuna_id').val('');
    $('#region_id').val('');
    $('#candidato_id').val('');
    $('input[name="medio_conocimiento[]"]').prop('checked', false);
    QuitarEstilo('nombre_completo')
    QuitarEstilo('alias')
    QuitarEstilo('rut')
    QuitarEstilo('email')
    QuitarEstilo('comuna_id')
    QuitarEstilo('region_id')
    QuitarEstilo('candidato_id')
}

function QuitarEstilo(id) {
    let $input = $('#' + id);
    $input.css('border-color', '');
}

function NuevoFormulario() {
    let formulario = $('#formulario');
    $.ajax({
        url: 'back.php',
        type: 'POST',
        dataType: 'json',
        data: { 'tipo': 'formulario', 'data': formulario.serialize() },
        success: function (response) {
            if (response.rsp.tipo == 'success') {
                LimpiarFormulario();
                ToastMsg('success', 'Formulario de Votación', 'Se ha registrado un nuevo formulario de votación.');
            } else {
                ToastMsg('error', 'Formulario de Votación', 'No se ha registrado un nuevo formulario de votación.');
            }
        },
        error: function (xhr, status, error) {
            console.error('Error en la solicitud:', status, error);
        }
    });
}

function ObtenerDatos(tipo, region_id = null) {
    return new Promise(function (resolve, reject) {
        $.ajax({
            url: 'back.php',
            type: 'POST',
            dataType: 'json',
            data: {
                'tipo': tipo,
                'region_id': region_id
            },
            success: function (response) {
                resolve(response.rsp);
            },
            error: function (xhr, status, error) {
                console.error('Error en la solicitud:', status, error);
                reject(error)
            }
        });

    });
}

function ValidaCampos(value, tipo, id, obligatorio = true) {

    let valida = true;

    if (obligatorio == true) {
        if (value !== '') {
            switch (tipo) {
                case 'texto':
                    if (value.length > 3) {
                        modificar_input(id, 'success');
                    } else {
                        modificar_input(id, 'error');
                        valida = false;
                    }
                    break;
                case 'rut':
                    if (Rut(value, 'rut')) {
                        modificar_input(id, 'success');
                    } else {
                        modificar_input(id, 'error', 'Ingrese RUT válido por favor');
                        valida = false;
                    }

                    break;
                case 'correo':
                    if (ValidarCorreo(value)) {
                        modificar_input(id, 'success');
                    } else {
                        modificar_input(id, 'error', 'Ingrese Correo válido por favor.');
                        valida = false;
                    }
                    break;
                case 'select':
                    if (parseInt(value) > 0) {
                        modificar_input(id, 'success');
                    } else {
                        modificar_input(id, 'error');
                        valida = false;
                    }
                    break;
                case 'checkbox':
                    if (value !== undefined) {
                        modificar_input(id, 'success');
                    } else {
                        modificar_input(id, 'error', 'Selecciona una opcion...');
                        valida = false;
                    }
                    break;

                default:
                    break;
            }
        } else {
            modificar_input(id, 'error');
            valida = false
        }
    } else {
        if (value !== '') {
            modificar_input(id, 'success');
        } else {
            modificar_input(id);
        }
    }
    return valida;
}
function ValidarCorreo(correo) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(correo) ? true : false;
}

function modificar_input(id, tipo, msg = 'Campo Obligatorio') {
    $input = $('#' + id);
    $valida = $('#invalid_' + id);

    if (tipo == 'error') {
        $input.css('border-color', ' red');
        $valida.html(msg);
    } else if (tipo == 'success') {
        $input.css('border-color', ' green');
        $valida.html('');
    }
}

function cargarRegiones() {
    $select_regiones = $('#region_id');


    let regiones = ObtenerDatos('regiones');
    regiones.then(function (data) {
        if (data.tipo == 'success') {
            let options = '<option value="">Seleccionar</option>';
            data.data.forEach(function (r) {
                options += "<option value='" + r.id + "'>" + r.nombre + "</option>";
            });
            $select_regiones.html(options);
        }
    });
}
function cargarComunas() {
    $select_regiones = $('#region_id');
    $select_comunas = $('#comuna_id');

    let regiones = ObtenerDatos('region', parseInt($select_regiones.val()));
    regiones.then(function (data) {
        if (data.tipo == 'success') {
            let options = '<option value="">Seleccionar</option>';
            data.data.forEach(function (r) {
                options += "<option value='" + r.id + "'>" + r.nombre + "</option>";
            });
            $select_comunas.prop('disabled', false);
            $select_comunas.html(options);
        }
    });
}
function cargarCandidatos() {
    $select_candidatos = $('#candidato_id');


    let candidatos = ObtenerDatos('candidatos');
    candidatos.then(function (data) {
        if (data.tipo == 'success') {
            let options = '<option value="">Seleccionar</option>';
            data.data.forEach(function (r) {
                options += "<option value='" + r.id + "'>" + r.nombre + "</option>";
            });
            $select_candidatos.html(options);
        }
    });
}

function ToastMsg(type, title, msg) {
    toastr[type](msg, title);
}