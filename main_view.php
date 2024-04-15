<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diagnóstico</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">

</head>

<body>
    <div class="container">
        <div class="row pt-4">
            <div class="col-12">
                <div class="card card-body">
                    <div class="col-12 text-center">
                        <h5>Formulario de Votación</h5>
                    </div>
                    <form action="" id="formulario" method="post">
                        <div class="row pt-5">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nombre_completo">Nombre Completo</label>
                                    <input type="text" name="nombre_completo" id="nombre_completo" placeholder="Ingrese Nombre Completo..." class="form-control">
                                    <span id="invalid_nombre_completo" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="alias">Alias</label>
                                    <input type="text" name="alias" id="alias" placeholder="Ingrese Alias..." class="form-control">
                                    <span id="invalid_alias" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="rut">Rut</label>
                                    <input type="text" name="rut" id="rut" placeholder="Ingrese Rut..." class="form-control">
                                    <span id="invalid_rut" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="text" name="email" id="email" placeholder="Ingrese Email..." class="form-control">
                                    <span id="invalid_email" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="region_id">Región</label>
                                    <select name="region_id" id="region_id" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <?php if (!empty($regiones)) : ?>
                                            <?php foreach ($regiones as $region) : ?>
                                                <option value="<?= $region['id'] ?>"><?= !empty($region['nombre']) ? $region['nombre'] : 'Sin Información...' ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <span id="invalid_region_id" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="comuna_id">Comuna</label>
                                    <select name="comuna_id" id="comuna_id" class="form-control" disabled>
                                        <option value="">Seleccionar</option>
                                    </select>
                                    <span id="invalid_comuna_id" class="text-danger"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="candidato_id">Candidato</label>
                                    <select name="candidato_id" id="candidato_id" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <?php if (!empty($candidatos)) : ?>
                                            <?php foreach ($candidatos as $candidato) : ?>
                                                <option value="<?= $candidato->id ?>"><?= !empty($candidato->nombre) ? $candidato->nombre : 'Sin Información...' ?></option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                    <span id="invalid_candidato_id" class="text-danger"></span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="">Como se enteró de nosotros</label>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medio_conocimiento[]" id="web" value="web">
                                            <label class="form-check-label" for="web">web</label>
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medio_conocimiento[]" id="tv" value="tv">
                                            <label class="form-check-label" for="tv">tv</label>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medio_conocimiento[]" id="redes_sociales" value="redes_sociales">
                                            <label class="form-check-label" for="redes_sociales">Redes Sociales</label>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="medio_conocimiento[]" id="amigo" value="amigo">
                                            <label class="form-check-label" for="amigo">Amigo</label>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <span id="invalid_medio_conocimiento" class="text-danger"></span>
                                    </div>
                                </div>

                            </div>

                        </div>
                        <div class="row">
                            <div class="col-2">
                                <button type="button" id="votar" class="btn btn-success">Votar</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Scripts de JavaScript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script>
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
        }
    </script>
    <script src="rut.js"></script>
    <script src="validaciones.js"></script>
</body>

</html>