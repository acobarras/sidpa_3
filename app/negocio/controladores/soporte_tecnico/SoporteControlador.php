<?php

namespace MiApp\negocio\controladores\soporte_tecnico;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SoporteTecnicoDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\direccionDAO;
use MiApp\persistencia\dao\CiudadDAO;
use MiApp\persistencia\dao\PaisDAO;
use MiApp\persistencia\dao\DepartamentoDAO;
use MiApp\persistencia\dao\ConsCotizacionDAO;
use MiApp\persistencia\dao\SeguimientoDiagSoporteDAO;


use MiApp\negocio\util\Validacion;


class SoporteControlador extends GenericoControlador
{
    private $SoporteTecnicoDAO;
    private $clientes_proveedorDAO;
    private $direccionDAO;
    private $CiudadDAO;
    private $PaisDAO;
    private $DepartamentoDAO;
    private $ConsCotizacionDAO;
    private $SeguimientoDiagSoporteDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->SoporteTecnicoDAO = new SoporteTecnicoDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->direccionDAO = new direccionDAO($cnn);
        $this->CiudadDAO = new CiudadDAO($cnn);
        $this->PaisDAO = new PaisDAO($cnn);
        $this->DepartamentoDAO = new DepartamentoDAO($cnn);
        $this->ConsCotizacionDAO = new ConsCotizacionDAO($cnn);
    }

    public function solicitud_soporte()
    {
        parent::cabecera();
        $this->view(
            'soporte_tecnico/solicitud_soporte',
            [
                "client" => $this->clientes_proveedorDAO->consultar_clientes(),
                "ciud" => $this->CiudadDAO->consultar_ciudad(),
                "paises" => $this->PaisDAO->consultar_pais(),
                "departamento" => $this->DepartamentoDAO->consultar_departamento()
            ]
        );
    }

    public function consulta_datos_empresa()
    {
        header('Content-Type: application/json');
        // SE CONSULTA LA EMPRESA DEL NIT ESCRITO
        $nit = $_POST['nit'];
        $datos_empresa = $this->clientes_proveedorDAO->consultar_nit_clientes($nit);
        $direccion = $this->direccionDAO->consulta_direccion_cliente($nit);
        if (empty($datos_empresa)) {
            $res = [
                'direcciones' => $direccion,
                'data_empresa' => $datos_empresa,
            ];
        } else {
            $res = [
                'direcciones' => $direccion,
                'data_empresa' => $datos_empresa[0],
            ];
        }
        echo json_encode($res);
        return;
    }

    public function agregar_direccion($id_cli_prov, $data)
    {
        // SE REALIZA LA CONSULTA DE LAS DIRECCIONES DEL CLIENTE, SI  NO EXISTE NINGUNA SE CREA
        $id_usuario = $_SESSION['usuario']->getid_usuario();
        $cons_direcciones = $this->clientes_proveedorDAO->consultar_direccion_cliente($id_cli_prov, $id_usuario);
        if (empty($cons_direcciones)) {
            $direccion = [
                'id_cli_prov' => $id_cli_prov,
                'direccion' => $data['direccion_soli'],
                'id_pais' => $data['id_pais'],
                'id_departamento' => $data['id_departamento'],
                'id_ciudad' => $data['id_ciudad'],
                'telefono' => $data['celular'],
                'celular' => $data['telefono'],
                'email' => $data['email'],
                'contacto' => $data['contacto'],
                'cargo' => $data['cargo'],
                'horario' => $data['horario'],
                'link_maps' => $data['link_maps'],
                'ruta' => $data['ruta'],
                'estado_direccion' => 1,
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
                'fecha_crea' => date('Y-m-d'),
            ];
            $agregar_direc = $this->direccionDAO->insertar($direccion);
            // estado 1 es cuando no existe ninguna direccion
            if ($agregar_direc['status'] == 1) {
                $respu = [
                    'estado' => 1,
                    'data' => $agregar_direc,
                ];
            }
            return $respu;
        } else {
            $respu = [
                'estado' => 2,
                'data' => $cons_direcciones,
            ];
        }
        return $respu;
    }

    public function agregar_cli_prov($nit, $data)
    {
        // SI EL NIT DIGITADO NO EXISTE SE CREA LA EMPRESA
        $cons_nit = $this->clientes_proveedorDAO->consultar_nit_clientes($nit);
        $id_usuario = $_SESSION['usuario']->getid_usuario();
        $cons_id_persona = $this->SoporteTecnicoDAO->consultar_id_persona($id_usuario);
        $id_persona = $cons_id_persona[0];
        if (empty($cons_nit)) {
            $usuario = [
                'tipo_documento' => 1,
                'nit' => $nit,
                'dig_verificacion' => $data['dig_verificacion'],
                'nombre_empresa' => $data['nombre_empresa'],
                'tipo_cli_prov' => 1,
                'tipo_prove' => 0,
                'forma_pago' => 1,
                'dias_dados' => 0,
                'dias_max_mora' => 0,
                'cupo_cliente' => 0,
                'pertenece' => 1,
                'lista_precio' => 3,
                'paso_pedido' => 0,
                'id_usuarios_asesor' => $id_persona->id_persona,
                'estado_cli_prov' => 1,
                'fecha_crea' => date('Y-m-d'),
                'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            ];
            $agregar_nit = $this->clientes_proveedorDAO->insertar($usuario);
            //estado 1 significa que no existe la empresa
            if ($agregar_nit['status'] == 1) {
                $respu = [
                    'estado' => 1,
                    'data' => $agregar_nit,
                ];
            }
            return $respu;
        } else {
            $respu = [
                'estado' => 2,
                'data' => $cons_nit,
            ];
        }
        return $respu;
    }

    public function agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro)
    {
        // AQUI SE AGREGA EL DIAGNOSTICO DE SOPORTE
        $num_conse = $this->ConsCotizacionDAO->consultar_cons_especifico(15);
        $nuevo_cons = $num_conse[0]->numero_guardado + 1;
        $edita_consecutivo = [
            'numero_guardado' => $nuevo_cons
        ];
        $condicion = 'id_consecutivo =15';
        $this->ConsCotizacionDAO->editar($edita_consecutivo, $condicion);

        $diagnostico = [
            'id_cli_prov' => $id_cli_prov,
            'id_direccion' => $id_direccion,
            'num_consecutivo' => 'DS-' . $num_conse[0]->numero_guardado,
            'tipo_cobro' => $tipo_cobro,
            'visita_laboratorio' => $visita_laboratorio,
            'req_visita' => $datos['req_visita'],
            'visita_prese' => $visita_prese,
            'cobro_ser' => $cobro_ser,
            'req_cotiza' => $req_cotiza,
            'estado' => $estado,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('Y-m-d'),
            'hora_crea' => date('H:i:s'),
        ];
        $agregar_diag = $this->SoporteTecnicoDAO->insertar($diagnostico);
        // EL ESTADO 76 ES PARA EL SEGUIMIENTO DE DIAGNOSTICO CREADO
        // SE AGREGA EL SEGUIMIENTO
        $id_actividad = 75; //CREACION DE DIAGNOSTICO
        $observacion = 'CREACION DE DIAGNOSTICO';
        $seguimiento = GenericoControlador::agrega_seguimiento_diag($agregar_diag['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
        return $agregar_diag;
    }

    public function agregar_datos()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $nit = $datos['nit'];
        $visita_prese = 0;
        $cobro_ser = 0;
        $id_cli_prov = 0;
        $tipo_cobro = 0;
        $req_cotiza = 0;
        // AQUI SE PREGUNTA POR LOS CHECKBOX SELECCIONADOS EN LA VISTA Y DEPENDIENDO EL VALOR SE CAMBIA EL ESTADO DEL DIAGNOSTICO

        // EL ESTADO 1 ES SI, EL 2 ES NO

        // REQUIERE VISITA?
        if (!empty($nit) && $datos['req_visita'] == 1) {
            $visita_laboratorio = 2;
            $estado = 7; //SI VIENE 1 EL DIAGNOSTICO ES INGRESO A LABORATORIO Y PASA A AGREGAR EQUIPOS 
            $creacion_usuario = SoporteControlador::agregar_cli_prov($nit, $datos);
            // SE PREGUNTA SI EL CLIENTE EXISTE
            if ($creacion_usuario['estado'] == 1) {
                $id_cli_prov = $creacion_usuario['data']['id'];
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $id_direccion = $creadireccion['data']['id'];
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 76; //CREACION DE DIAGNOSTICO
                    $observacion = 'DIAGNOSTICO POR LABORATORIO';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            } else {
                $id_cli_prov = $creacion_usuario['data'][0]->id_cli_prov;
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    if (!empty($datos['direccion_soli'])) {
                        $id_direccion = $creadireccion['data']['id'];
                    } else {
                        $datos_dir = json_decode($datos['direc_solicitud']);
                        $id_direccion = $datos_dir->id_direccion;
                    }
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 76; //CREACION DE DIAGNOSTICO
                    $observacion = 'DIAGNOSTICO POR LABORATORIO';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            }
        }
        // LA VISITA ES PRESENCIAL?
        if (!empty($nit) && $datos['visita_prese'] == 2) {
            $cobro_ser = 0;
            $visita_laboratorio = 1;
            $visita_prese = $datos['visita_prese'];
            $estado = 1; //SI VIENE COMO 1 LA VISITA NO ES PRESENCIAL ESO QUIERE DECIR QUE ES UN SOPORTE DE VISITA REMOTA
            $creacion_usuario = SoporteControlador::agregar_cli_prov($nit, $datos);
            if ($creacion_usuario['estado'] == 1) {
                $id_cli_prov = $creacion_usuario['data']['id'];
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $id_direccion = $creadireccion['data']['id'];
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 84; //DIAGNOSTICO REMOTO
                    $observacion = 'DIAGNOSTICO REMOTO';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            } else {
                $id_cli_prov = $creacion_usuario['data'][0]->id_cli_prov;
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $datos_dir = json_decode($datos['direc_solicitud']);
                    $id_direccion = $datos_dir->id_direccion;
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 84; //DIAGNOSTICO REMOTO
                    $observacion = 'DIAGNOSTICO REMOTO';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            }
        }
        // EL SERVICIO TIENE COBRO?
        if (!empty($nit) && $datos['cobro_ser'] == 2) {
            $cobro_ser = $datos['cobro_ser'];
            $visita_laboratorio = 1;
            $visita_prese = $datos['visita_prese'];
            $estado = 4;
            $creacion_usuario = SoporteControlador::agregar_cli_prov($nit, $datos);
            if ($creacion_usuario['estado'] == 1) {
                $id_cli_prov = $creacion_usuario['data']['id'];
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $id_direccion = $creadireccion['data']['id'];
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 86; //DIAGNOSTICO POR VISITA
                    $observacion = 'DIAGNOSTICO POR VISITA';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            } else {
                $id_cli_prov = $creacion_usuario['data'][0]->id_cli_prov;
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $datos_dir = json_decode($datos['direc_solicitud']);
                    $id_direccion = $datos_dir->id_direccion;
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 86; //DIAGNOSTICO POR VISITA
                    $observacion = 'DIAGNOSTICO POR VISITA';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            }
        }
        // REQUIERE COTIZACION
        if (!empty($nit) && $datos['req_cotiza'] == 2) {
            $cobro_ser = $datos['cobro_ser'];
            $req_cotiza = $datos['req_cotiza'];
            $visita_laboratorio = 1;
            $visita_prese = $datos['visita_prese'];
            $estado = 4;
            $creacion_usuario = SoporteControlador::agregar_cli_prov($nit, $datos);
            if ($creacion_usuario['estado'] == 1) {
                $id_cli_prov = $creacion_usuario['data']['id'];
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $id_direccion = $creadireccion['data']['id'];
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 86; //DIAGNOSTICO POR VISITA
                    $observacion = 'DIAGNOSTICO POR VISITA';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            } else {
                $id_cli_prov = $creacion_usuario['data'][0]->id_cli_prov;
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $datos_dir = json_decode($datos['direc_solicitud']);
                    $id_direccion = $datos_dir->id_direccion;
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 86; //DIAGNOSTICO POR VISITA
                    $observacion = 'DIAGNOSTICO POR VISITA';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            }
        }
        // REQUIERE COTIZACION Y SE GENERA UNA COTIZACION DE VISITA
        if (!empty($nit) && $datos['req_cotiza'] == 1) {
            $cobro_ser = $datos['cobro_ser'];
            $req_cotiza = $datos['req_cotiza'];
            $visita_laboratorio = 1;
            $visita_prese = $datos['visita_prese'];
            $estado = 2;
            $creacion_usuario = SoporteControlador::agregar_cli_prov($nit, $datos);
            if ($creacion_usuario['estado'] == 1) {
                $id_cli_prov = $creacion_usuario['data']['id'];
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $id_direccion = $creadireccion['data']['id'];
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 86; //DIAGNOSTICO POR VISITA
                    $observacion = 'DIAGNOSTICO POR VISITA';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            } else {
                $id_cli_prov = $creacion_usuario['data'][0]->id_cli_prov;
                $creadireccion = SoporteControlador::agregar_direccion($id_cli_prov, $datos);
                if (!empty($creadireccion['data'])) {
                    $datos_dir = json_decode($datos['direc_solicitud']);
                    $id_direccion = $datos_dir->id_direccion;
                    $creadiagno = SoporteControlador::agregar_diagnostico($id_cli_prov, $id_direccion, $datos, $visita_prese, $cobro_ser, $req_cotiza, $visita_laboratorio, $estado, $tipo_cobro);
                    $id_actividad = 86; //DIAGNOSTICO POR VISITA
                    $observacion = 'DIAGNOSTICO POR VISITA';
                    $seguimiento = GenericoControlador::agrega_seguimiento_diag($creadiagno['id'], 0, $id_actividad, $observacion, $_SESSION['usuario']->getid_usuario());
                }
                echo json_encode($creadiagno);
                return;
            }
        }
    }
}
