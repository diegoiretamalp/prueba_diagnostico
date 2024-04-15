<?php

$server = "localhost";
$user = "root";
$password = "";
$db = "diagnostico_bbdd";

$conn = new mysqli($server, $user, $password, $db);
$regiones_ret = retornarRegiones($conn);

if ($conn->connect_error) {
    die("Error al conectar: " . $conn->connect_error);
}
$query_regiones = "SELECT * FROM d_regiones;";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = $_POST;
    if (empty($data)) {
        $rsp = [
            'tipo' => 'error',
            'msg' => 'Datos no recibidos por el servidor'
        ];
    } else {
        $tipo = $data['tipo'];
        $datos = [];
        switch ($tipo) {
            case 'regiones':
                $datos = retornarRegiones($conn);
                $rsp = [
                    'tipo' => !empty($datos) ? 'success' : 'warning',
                    'data' => !empty($datos) ? $datos : []
                ];
                break;
            case 'comunas':
                $datos = retornarComunas($conn);
                $rsp = [
                    'tipo' => !empty($datos) ? 'success' : 'warning',
                    'data' => !empty($datos) ? $datos : []
                ];
                break;
            case 'candidatos':
                $datos = retornarCandidatos($conn);
                $rsp = [
                    'tipo' => !empty($datos) ? 'success' : 'warning',
                    'data' => !empty($datos) ? $datos : []
                ];
                break;
            case 'formulario':
                $data = $data;
                $data2 = [];
                parse_str($data['data'], $data2);

                if (!empty($data2)) {

                    $nuevo_formulario = [
                        'nombre_completo' => !empty($data2['nombre_completo']) ? $data2['nombre_completo'] : NULL,
                        'alias' => !empty($data2['alias']) ? $data2['alias'] : NULL,
                        'rut' => !empty($data2['rut']) ? str_replace('.', '', $data2['rut']) : NULL,
                        'email' => !empty($data2['email']) ? $data2['email'] : NULL,
                        'region_id' => !empty($data2['region_id']) ? $data2['region_id'] : NULL,
                        'comuna_id' => !empty($data2['comuna_id']) ? $data2['comuna_id'] : NULL,
                        'candidato_id' => !empty($data2['candidato_id']) ? $data2['candidato_id'] : NULL,
                        'medio_conocimiento' => !empty($data2['medio_conocimiento']) ? implode(', ', $data2['medio_conocimiento']) : NULL,
                    ];
                    $rsp_formulario = NuevoFormulario($conn, $nuevo_formulario);

                    $rsp = [
                        'tipo' => $rsp_formulario == true ? 'success' : 'error',
                        'data' => []
                    ];
                } else {
                    $rsp = [
                        'tipo' => 'error',
                        'data' => []
                    ];
                }
                break;
            case 'region':
                $region_id = $data['region_id'];
                $datos = obtenerComunas($conn, $region_id);
                $rsp = [
                    'tipo' => !empty($datos) ? 'success' : 'warning',
                    'data' => !empty($datos) ? $datos : []
                ];
                break;
            default:
                $rsp = [
                    'tipo' => 'success',
                    'data' => []
                ];
                break;
        }
    }

    echo json_encode(['rsp' => $rsp]);
}

function NuevoFormulario($conn, $data)
{
    try {
        $query = "INSERT INTO d_formularios (nombre_completo, alias, rut, email, region_id, comuna_id, candidato_id, medio_conocimiento)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        $db = $conn->prepare($query);

        $db->bind_param(
            "ssssiiis",
            $data['nombre_completo'],
            $data['alias'],
            $data['rut'],
            $data['email'],
            $data['region_id'],
            $data['comuna_id'],
            $data['candidato_id'],
            $data['medio_conocimiento']
        );

        $db->execute();

        return $db->affected_rows > 0;
    } catch (Exception $ex) {
        return $ex->getMessage();
    }
}


function obtenerComunas($conn, $region_id)
{
    $query = "SELECT * FROM d_comunas WHERE region_id = $region_id;";

    $data = $conn->query($query);

    $datos_serializados = $data->fetch_all(MYSQLI_ASSOC);

    return $datos_serializados;
}

function retornarRegiones($conn)
{
    $query_regiones = "SELECT * FROM d_regiones;";
    $data = $conn->query($query_regiones);

    $datos_serializados = $data->fetch_all(MYSQLI_ASSOC);

    return $datos_serializados;
}
function retornarComunas($conn)
{
    $query_comunas = "SELECT * FROM d_comunas;";
    $data = $conn->query($query_comunas);

    $datos_serializados = $data->fetch_all(MYSQLI_ASSOC);

    return $datos_serializados;
}
function retornarCandidatos($conn)
{
    $query_candidatos = "SELECT * FROM d_candidatos;";
    $data = $conn->query($query_candidatos);

    $datos_serializados = $data->fetch_all(MYSQLI_ASSOC);

    return $datos_serializados;
}
