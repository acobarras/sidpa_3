<?php

namespace MiApp\negocio\controladores\Contabilidad;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\control_facturacionDAO;
use MiApp\persistencia\dao\trmDAO;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\PortafolioDAO;
use MiApp\persistencia\dao\PedidosDAO;



class PortafolioControlador extends GenericoControlador
{
    private $control_facturacionDAO;
    private $trmDAO;
    private $UsuarioDAO;
    private $clientes_proveedorDAO;
    private $PortafolioDAO;
    private $pedidosDAO;



    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->control_facturacionDAO = new control_facturacionDAO($cnn);
        $this->trmDAO = new trmDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->PortafolioDAO = new PortafolioDAO($cnn);
        $this->pedidosDAO = new PedidosDAO($cnn);
    }

    public function portafolio()
    {
        parent::cabecera();

        $this->view(
            'Contabilidad/vista_portafolio'
        );
    }
    public function valida_factura()
    {
        header('Content-Type:application/json');
        $facturas = $this->control_facturacionDAO->ConsultarNumFactura($_POST['num_factura']);
        $data = [];
        $total_etiquetas = 0;
        $total_cintas = 0;
        $total_tecnologia = 0;
        $total_alquiler = 0;
        $total_soporte = 0;
        $total_m_prima = 0;
        $total_fletes = 0;
        if (empty($facturas)) {
            $data = [
                'num_factura' => $_POST['num_factura'],
                'status' => -1
            ];
            $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura']);
            if (!empty($factura_portafolio)) {
                $data = [
                    'num_factura' => $_POST['num_factura'],
                    'status' => -2
                ];
            }
        } else {
            foreach ($facturas as  $value) {
                if (trim($value->moneda) == "Pesos") { //pesos
                    if ($value->id_tipo_articulo == 1) { // si el id_tipo articulo es 1->Etiquetas o 4->Bobinas

                        $total_etiquetas = floatval($total_etiquetas) + floatval($value->cantidad_factura) * floatval($value->v_unidad);
                    } elseif ($value->id_tipo_articulo == 2) { // si el id_tipo articulo es 2->Cintas

                        $total_cintas = floatval($total_cintas) + floatval($value->cantidad_factura) * floatval($value->v_unidad);
                    } elseif ($value->id_tipo_articulo == 13) { // si el id_tipo articulo es 13->alquiler

                        $total_alquiler = floatval($total_alquiler) + floatval($value->cantidad_factura) * floatval($value->v_unidad);
                    } elseif ($value->id_tipo_articulo == 14) { // si el id_tipo articulo es 14->soporte

                        $total_soporte = floatval($total_soporte) + floatval($value->cantidad_factura) * floatval($value->v_unidad);
                    } elseif ($value->id_tipo_articulo == 4 || $value->id_tipo_articulo == 15) { // si el id_tipo articulo es bobinas e insumos

                        $total_m_prima = floatval($total_m_prima) + floatval($value->cantidad_factura) * floatval($value->v_unidad);
                    } elseif ($value->id_tipo_articulo == 16) { // si el id_tipo articulo es fletes

                        $total_fletes = floatval($total_fletes) + floatval($value->cantidad_factura) * floatval($value->v_unidad);
                    } else { //otra tecnologia

                        $total_tecnologia = floatval($total_tecnologia) + floatval($value->cantidad_factura) * floatval($value->v_unidad);
                    }
                } else { // dolares
                    $ConsultaUltimoRegistro = $this->trmDAO->ConsultaUltimoRegistro();
                    $trm = $ConsultaUltimoRegistro[0]->valor_trm;
                    if ($value->id_tipo_articulo == 1) { // si el id_tipo articulo es 1->Etiquetas

                        $total_etiquetas = floatval($total_etiquetas) + floatval(($value->v_unidad * $trm)) * floatval($value->cantidad_factura);
                    } elseif ($value->id_tipo_articulo == 2) { // si el id_tipo articulo es 2->Cintas

                        $total_cintas = floatval($total_cintas) + floatval(($value->v_unidad * $trm)) * floatval($value->cantidad_factura);
                    } elseif ($value->id_tipo_articulo == 13) { // si el id_tipo articulo es 13->alquiler

                        $total_alquiler = floatval($total_alquiler) + floatval(($value->v_unidad * $trm)) * floatval($value->cantidad_factura);
                    } elseif ($value->id_tipo_articulo == 14) { // si el id_tipo articulo es 14->

                        $total_soporte = floatval($total_soporte) + floatval(($value->v_unidad * $trm)) * floatval($value->cantidad_factura);
                    } elseif ($value->id_tipo_articulo == 4 || $value->id_tipo_articulo == 15) { // si el id_tipo articulo es bobinas e insumos

                        $total_m_prima = floatval($total_m_prima) + floatval(($value->v_unidad * $trm)) * floatval($value->cantidad_factura);
                    } elseif ($value->id_tipo_articulo == 16) { // si el id_tipo articulo es fletes

                        $total_fletes = floatval($total_fletes) + floatval(($value->v_unidad * $trm)) * floatval($value->cantidad_factura);
                    } else { //otra tecnologia
                        $total_tecnologia = floatval($total_tecnologia) + floatval(($value->v_unidad * $trm)) * floatval($value->cantidad_factura);
                    }
                }
            }
            $dias_dados = 0;
            $fecha_vencimiento = '';
            if ($facturas[0]->forma_pago == 4) { // forma esta especificado en un array en las constantes de php "4" es credito
                foreach (SOLO_DIAS_DADOS as $key => $dias) {
                    if ($facturas[0]->dias_dados == $key) {
                        $dias_dados = $dias;
                        $fecha = date_create($facturas[0]->fecha_factura);
                        $fecha_vencimiento = Validacion::aumento_fechas($fecha, $dias_dados);
                    }
                }
            }

            if ($dias_dados == 0) {
                $fecha_vencimiento = $facturas[0]->fecha_factura;
            }
            $total_etiquetas_iva = $total_etiquetas;
            $total_cintas_iva = $total_cintas;
            $total_tecnologia_iva = $total_tecnologia;
            $total_alquiler_iva = $total_alquiler;
            $total_soporte_iva = $total_soporte;
            $total_m_prima_iva = $total_m_prima;
            $total_fletes_iva = $total_fletes;

            if ($facturas[0]->iva == 1) {
                $total_etiquetas_iva = ($total_etiquetas * IVA) + $total_etiquetas;
                $total_cintas_iva = ($total_cintas * IVA) + $total_cintas;
                $total_tecnologia_iva = ($total_tecnologia * IVA) + $total_tecnologia;
                $total_alquiler_iva = ($total_alquiler * IVA) + $total_alquiler;
                $total_soporte_iva = ($total_soporte * IVA) + $total_soporte;
                $total_m_prima_iva = ($total_m_prima * IVA) + $total_m_prima;
                $total_fletes_iva = ($total_fletes * IVA) + $total_fletes;
            }
            $data = [
                'id_cli_prov' => $facturas[0]->id_cli_prov,
                'num_factura' => $facturas[0]->num_factura,
                'nit' => $facturas[0]->nit,
                'nombre_empresa' => $facturas[0]->nombre_empresa,
                'fecha_factura' => $facturas[0]->fecha_factura,
                'dias_dados' => $dias_dados,
                'empresa' => $facturas[0]->pertenece,
                'fecha_vencimiento' => $fecha_vencimiento,
                'id_asesor' => $facturas[0]->id_usuario,
                'id_persona' => $facturas[0]->id_persona,
                'id_usuarios_asesor' => $facturas[0]->id_usuarios_asesor,
                'iva' => $facturas[0]->iva,
                'total_etiquetas' => round($total_etiquetas, 2),
                'total_etiquetas_iva' => round($total_etiquetas_iva, 2),
                'total_cintas' => round($total_cintas, 2),
                'total_cintas_iva' => round($total_cintas_iva, 2),
                'total_alquiler' => round($total_alquiler, 2),
                'total_alquiler_iva' => round($total_alquiler_iva, 2),
                'total_tecnologia' => round($total_tecnologia, 2),
                'total_tecnologia_iva' => round($total_tecnologia_iva, 2),
                'total_soporte' => round($total_soporte, 2),
                'total_soporte_iva' => round($total_soporte_iva, 2),
                'total_m_prima' => round($total_m_prima, 2),
                'total_m_prima_iva' => round($total_m_prima_iva, 2),
                'total_fletes' => round($total_fletes, 2),
                'total_fletes_iva' => round($total_fletes_iva, 2),
                'status' => 1
            ];
        }
        echo json_encode($data);
    }

    public function consulta_empresa_nit()
    {
        header('Content-Type:cation/json');
        $empresa = $this->clientes_proveedorDAO->consultar_nit_clientes($_POST['nit']);
        $dias_dados = 0;
        $fecha_vencimiento = '';
        if ($empresa[0]->forma_pago == 4) { // forma esta especificado en un array en las constantes de php "4" es credito
            foreach (SOLO_DIAS_DADOS as $key => $dias) {
                if ($empresa[0]->dias_dados == $key) {
                    $dias_dados = $dias;
                    $fecha = date_create(date('Y-m-d'));
                    $fecha_vencimiento = Validacion::aumento_fechas($fecha, $dias_dados);
                }
            }
        }
        $data = [
            'nombre_empresa' => $empresa[0]->nombre_empresa,
            'dias_dados' => $dias_dados,
            'fecha_factura' => date('Y-m-d'),
            'fecha_vencimiento' => $fecha_vencimiento,
            'id_usuarios_asesor' => $empresa[0]->id_usuarios_asesor,
            'empresa' => $empresa[0]->pertenece,
            'id_cli_prov' => $empresa[0]->id_cli_prov,

        ];
        echo json_encode($data);
    }
    public function consulta_asesores()
    {
        header('Content-Type:cation/json');
        $data = $_POST['data'];
        $asesores = explode(',', $data);
        $consulta_asesores = $this->UsuarioDAO->consultarIdUsuarioMultiple($data);
        echo json_encode($consulta_asesores);
    }
    public function envio_factura()
    {
        header('Content-Type:cation/json');
        $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura']);
        $iva = 0;
        $total_etiquetas = $_POST['total_etiquetas'];
        $total_cintas = $_POST['total_cintas'];
        if ($_POST['empresa'] == 3) {
            $_POST['empresa'] = 1;
        }
        if (!empty($factura_portafolio)) {
            foreach (ESTADO_PORTAFOLIO as $key => $value) {
                if ($factura_portafolio[0]->estado_portafolio == $key) {
                    $estado_factu = $value;
                }
            }
            $data = [
                'num_factura' => $_POST['num_factura'],
                'msg' => 'Esta factura ya fue relacionada y se encuentra en estado " ' . $estado_factu . ' "',
                'status' => -1
            ];
        } else {
            if (isset($_POST['iva'])) {
                $iva = 1;
            }
            $data = [
                'num_factura' =>  $_POST['num_factura'],
                'id_cli_prov' =>   $_POST['id_cli_prov'],
                'fecha_factura' =>   $_POST['fecha_factura'],
                'dias_dados' =>   $_POST['dias_dados'],
                'fecha_vencimiento' =>  $_POST['fecha_vencimiento'],
                'iva' => $iva,
                'total_etiquetas' =>   $total_etiquetas,
                'total_cintas' =>   $total_cintas,
                'total_alquiler' =>   $_POST['total_alquiler'],
                'total_tecnologia' =>   $_POST['total_tecnologia'],
                'total_etiq_cint' => $total_etiquetas + $total_cintas,
                'total_soporte' =>  $_POST['total_soporte'],
                'total_m_prima' =>  $_POST['total_m_prima'],
                'total_fletes' =>  $_POST['total_fletes'],
                'asesor' => $_POST['id_usuarios_asesor'],
                'empresa' => $_POST['empresa'],
                'total_factura' => $_POST['total_factura'],
                'estado_portafolio' => 1,

            ];
            $portafolio = $this->PortafolioDAO->insertar($data);
        }
        echo json_encode($data);
    }
    public function vista_gestion_factura()
    {
        parent::cabecera();

        $this->view(
            'Contabilidad/vista_gestion_factura'
        );
    }

    public function valida_factura_anular()
    {
        header('Content-Type:application/json');
        $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura']);
        $data = [];
        if (empty($factura_portafolio)) {
            $data = [
                'num_factura' => $_POST['num_factura'],
                'status' => -1
            ];
        } else {
            $total_etiquetas = $factura_portafolio[0]->total_etiquetas;
            $total_cintas = $factura_portafolio[0]->total_cintas;
            $total_tecnologia = $factura_portafolio[0]->total_tecnologia;
            $total_alquiler = $factura_portafolio[0]->total_alquiler;
            $total_soporte = $factura_portafolio[0]->total_soporte;
            $total_fletes = $factura_portafolio[0]->total_fletes;
            $total_m_prima = $factura_portafolio[0]->total_m_prima;


            $total_etiquetas_iva = $total_etiquetas;
            $total_cintas_iva = $total_cintas;
            $total_tecnologia_iva = $total_tecnologia;
            $total_alquiler_iva = $total_alquiler;
            $total_soporte_iva = $total_soporte;
            $total_fletes_iva = $total_fletes;
            $total_m_prima_iva = $total_m_prima;

            if ($factura_portafolio[0]->iva == 1) {
                $total_etiquetas_iva = ($total_etiquetas * IVA) + $total_etiquetas;
                $total_cintas_iva = ($total_cintas * IVA) + $total_cintas;
                $total_tecnologia_iva = ($total_tecnologia * IVA) + $total_tecnologia;
                $total_alquiler_iva = ($total_alquiler * IVA) + $total_alquiler;
                $total_soporte_iva = ($total_soporte * IVA) + $total_soporte;
                $total_fletes_iva = ($total_fletes * IVA) + $total_fletes;
                $total_m_prima_iva = ($total_m_prima * IVA) + $total_m_prima;
            }
            $data = [
                'id_cli_prov_a' => $factura_portafolio[0]->id_cli_prov,
                'num_factura_a' => $factura_portafolio[0]->num_factura,
                'nit_a' => $factura_portafolio[0]->nit,
                'nombre_empresa_a' => $factura_portafolio[0]->nombre_empresa,
                'fecha_factura_a' => $factura_portafolio[0]->fecha_factura,
                'dias_dados_a' => $factura_portafolio[0]->dias_dados,
                'empresa_a' => $factura_portafolio[0]->empresa,
                'fecha_vencimiento_a' => $factura_portafolio[0]->fecha_vencimiento,
                'id_asesor' => $factura_portafolio[0]->asesor,
                'id_usuarios_asesor' => $factura_portafolio[0]->id_usuarios_asesor,
                'iva_a' => $factura_portafolio[0]->iva,
                'total_etiquetas_a' => round($total_etiquetas, 2),
                'total_etiquetas_iva_a' => round($total_etiquetas_iva, 2),
                'total_cintas_a' => round($total_cintas, 2),
                'total_cintas_iva_a' => round($total_cintas_iva, 2),
                'total_alquiler_a' => round($total_alquiler, 2),
                'total_alquiler_iva_a' => round($total_alquiler_iva, 2),
                'total_tecnologia_a' => round($total_tecnologia, 2),
                'total_tecnologia_iva_a' => round($total_tecnologia_iva, 2),
                'total_soporte_a' => round($total_soporte, 2),
                'total_soporte_iva_a' => round($total_soporte_iva, 2),
                'total_fletes_a' => round($total_fletes, 2),
                'total_fletes_iva_a' => round($total_fletes_iva, 2),
                'total_m_prima_a' => round($total_m_prima, 2),
                'total_m_prima_iva_a' => round($total_m_prima_iva, 2),
                'status' => 1
            ];
        }
        echo json_encode($data);
    }

    public function anula_factura()
    {
        header('Content-Type:cation/json');
        $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura_anula']);
        $data = [];
        if ($factura_portafolio[0]->estado_portafolio == 4) {
            $data = [
                'num_factura' => $_POST['num_factura_anula'],
                'msg' => 'Esta factura ya fue Anulada.',
                'status' => -1
            ];
        } else {
            $datos = [
                'estado_portafolio' => 4, //estado del array globlal "anulada"
                'usuario_anula' => $_SESSION['usuario']->getId_usuario(),
                'fecha_anula' => date('Y-m-d h:i:s'),
                'fecha_pago' => 'null',
            ];
            $condicion = 'num_factura =' . $_POST['num_factura_anula'];
            $data = $this->PortafolioDAO->editar($datos, $condicion);
        }
        echo json_encode($data);
    }
    public function consulta_acobarras_sas()
    {
        header('Content-Type:cation/json');
        $empresa = 1; // pertenece a acobarras sas
        $data_acobarras_sas = $this->PortafolioDAO->ConsultarPortafolioEmpresa($empresa);
        foreach ($data_acobarras_sas as $value) {
            $value->nombre_estado = ESTADO_PORTAFOLIO[$value->estado_portafolio];
            $fechaEnvio = date($value->fecha_vencimiento);
            $fechaActual = date("Y-m-d");
            $value->dias_mora = Validacion::resto_fechas($fechaEnvio, $fechaActual);
            if ($fechaActual < $fechaEnvio) {
                $value->dias_mora = 0;
            }
        }
        $data["data"] = $data_acobarras_sas;
        echo json_encode($data);
    }
    public function consulta_acobarras_col()
    {
        header('Content-Type:cation/json');
        $empresa = 2; //pertenece a acobarras col
        $data_acobarras_col = $this->PortafolioDAO->ConsultarPortafolioEmpresa($empresa);
        foreach ($data_acobarras_col as $value) {
            $value->nombre_estado = ESTADO_PORTAFOLIO[$value->estado_portafolio];
            $fechaEnvio = date($value->fecha_vencimiento);
            $fechaActual = date("Y-m-d");
            $value->dias_mora = Validacion::resto_fechas($fechaEnvio, $fechaActual);
            if ($fechaActual < $fechaEnvio) {
                $value->dias_mora = 0;
            }
        }
        $data["data"] = $data_acobarras_col;
        echo json_encode($data);
    }
    public function valida_fecha_factura()
    {
        header('Content-Type:application/json');
        $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura']);
        $data = [];
        if (empty($factura_portafolio)) {
            $data = [
                'num_factura' => $_POST['num_factura'],
                'status' => -1
            ];
        } else {
            $total_etiquetas = $factura_portafolio[0]->total_etiquetas;
            $total_cintas = $factura_portafolio[0]->total_cintas;
            $total_tecnologia = $factura_portafolio[0]->total_tecnologia;
            $total_alquiler = $factura_portafolio[0]->total_alquiler;
            $total_soporte = $factura_portafolio[0]->total_soporte;
            $total_fletes = $factura_portafolio[0]->total_fletes;
            $total_m_prima = $factura_portafolio[0]->total_m_prima;

            $total_etiquetas_iva = $total_etiquetas;
            $total_cintas_iva = $total_cintas;
            $total_tecnologia_iva = $total_tecnologia;
            $total_alquiler_iva = $total_alquiler;
            $total_soporte_iva = $total_soporte;
            $total_fletes_iva = $total_fletes;
            $total_m_prima_iva = $total_m_prima;

            if ($factura_portafolio[0]->iva == 1) {
                $total_etiquetas_iva = ($total_etiquetas * IVA) + $total_etiquetas;
                $total_cintas_iva = ($total_cintas * IVA) + $total_cintas;
                $total_tecnologia_iva = ($total_tecnologia * IVA) + $total_tecnologia;
                $total_alquiler_iva = ($total_alquiler * IVA) + $total_alquiler;
                $total_soporte_iva = ($total_soporte * IVA) + $total_soporte;
                $total_fletes_iva = ($total_fletes * IVA) + $total_fletes;
                $total_m_prima_iva = ($total_m_prima * IVA) + $total_m_prima;
            }
            $data = [
                'id_cli_prov_f' => $factura_portafolio[0]->id_cli_prov,
                'num_factura_f' => $factura_portafolio[0]->num_factura,
                'nit_f' => $factura_portafolio[0]->nit,
                'nombre_empresa_f' => $factura_portafolio[0]->nombre_empresa,
                'fecha_factura_f' => $factura_portafolio[0]->fecha_factura,
                'dias_dados_f' => $factura_portafolio[0]->dias_dados,
                'empresa_f' => $factura_portafolio[0]->empresa,
                'fecha_vencimiento_f' => $factura_portafolio[0]->fecha_vencimiento,
                'id_asesor' => $factura_portafolio[0]->asesor,
                'id_usuarios_asesor' => $factura_portafolio[0]->id_usuarios_asesor,
                'iva_f' => $factura_portafolio[0]->iva,
                'fecha_pago_f' => $factura_portafolio[0]->fecha_pago,
                'total_etiquetas_f' => round($total_etiquetas, 2),
                'total_etiquetas_iva_f' => round($total_etiquetas_iva, 2),
                'total_cintas_f' => round($total_cintas, 2),
                'total_cintas_iva_f' => round($total_cintas_iva, 2),
                'total_alquiler_f' => round($total_alquiler, 2),
                'total_alquiler_iva_f' => round($total_alquiler_iva, 2),
                'total_tecnologia_f' => round($total_tecnologia, 2),
                'total_tecnologia_iva_f' => round($total_tecnologia_iva, 2),
                'total_soporte_f' => round($total_soporte, 2),
                'total_soporte_iva_f' => round($total_soporte_iva, 2),
                'total_fletes_f' => round($total_fletes, 2),
                'total_fletes_iva_f' => round($total_fletes_iva, 2),
                'total_m_prima_f' => round($total_m_prima, 2),
                'total_m_prima_iva_f' => round($total_m_prima_iva, 2),
                'status' => 1
            ];
        }
        echo json_encode($data);
    }
    public function fecha_pago_factura()
    {
        header('Content-Type:cation/json');
        $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura_fecha_p']);
        $data = [];
        $estado = 3;
        if ($_POST['fecha_pago'] === '0000-00-00') {
            $estado = 2;
        }
        if ($factura_portafolio[0]->estado_portafolio == 3) {
            $data = [
                'num_factura' => $_POST['num_factura_fecha_p'],
                'msg' => 'Esta factura ya tiene fecha de pago.',
                'status' => -1
            ];
        } elseif ($factura_portafolio[0]->estado_portafolio == 4) {
            $data = [
                'num_factura' => $_POST['num_factura_fecha_p'],
                'msg' => 'Esta factura ya fue Anulada.',
                'status' => -1
            ];
        } else {
            $iva = 0;
            $total_etiquetas = $_POST['total_etiquetas'];
            $total_cintas = $_POST['total_cintas'];
            if (isset($_POST['iva'])) {
                $iva = 1;
            }
            $datos = [
                'total_etiquetas' => $total_etiquetas, //fecha de pago que viene por la vista
                'total_cintas' => $total_cintas, //fecha de pago que viene por la vista
                'total_etiq_cint' => $total_etiquetas + $total_cintas, //fecha de pago que viene por la vista
                'total_alquiler' => $_POST['total_alquiler'], //fecha de pago que viene por la vista
                'total_tecnologia' => $_POST['total_tecnologia'], //fecha de pago que viene por la vista
                'total_soporte' => $_POST['total_soporte'], //fecha de pago que viene por la vista
                'total_fletes' => $_POST['total_fletes'], //fecha de pago que viene por la vista
                'total_m_prima' => $_POST['total_m_prima'], //fecha de pago que viene por la vista
                'iva' => $iva, //fecha de pago que viene por la vista
                'fecha_pago' => $_POST['fecha_pago'], //fecha de pago que viene por la vista
                'total_factura' => $_POST['total_factura'], //total factura que viene por la vista
                'estado_portafolio' => $estado, //estado del array globlal "pagada"
                'id_usu_pago' => $_SESSION['usuario']->getId_usuario(),
                'fecha_usu_pago' => date('Y-m-d h:i:s'),
            ];
            $condicion = 'num_factura =' . $_POST['num_factura_fecha_p'];
            $data = $this->PortafolioDAO->editar($datos, $condicion);
        }
        echo json_encode($data);
    }

    public function vista_pedidos_permitidos()
    {
        parent::cabecera();

        $this->view(
            'Contabilidad/vista_pedidos_permitidos'
        );
    }
    public function consulta_pedidos_permitidos()
    {
        header('Content-Type:cation/json');

        $pedidos_permitidos_cont = $this->pedidosDAO->consulta_ped_permitidos();
        foreach ($pedidos_permitidos_cont as  $value) {
            $parametro = 't1.paso_pedido=1 AND t1.id_cli_prov=' . $value->id_cli_prov;
            $value->dato_pedidos = $this->pedidosDAO->consulta_pedidos($parametro);
        }
        $data["data"] = $pedidos_permitidos_cont;
        echo json_encode($data);
    }
    public function vista_documentos_recibidos()
    {
        parent::cabecera();

        $this->view(
            'Contabilidad/vista_documentos_recibidos'
        );
    }
    public function valida_documento_recibido()
    {
        header('Content-Type:application/json');
        $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura']);
        $data = [];
        if (empty($factura_portafolio)) {
            $data = [
                'num_factura' => $_POST['num_factura'],
                'status' => -1
            ];
        } else {
            $total_etiquetas = $factura_portafolio[0]->total_etiquetas;
            $total_cintas = $factura_portafolio[0]->total_cintas;
            $total_tecnologia = $factura_portafolio[0]->total_tecnologia;
            $total_alquiler = $factura_portafolio[0]->total_alquiler;
            $total_soporte = $factura_portafolio[0]->total_soporte;
            $total_fletes = $factura_portafolio[0]->total_fletes;
            $total_m_prima = $factura_portafolio[0]->total_m_prima;


            $total_etiquetas_iva = $total_etiquetas;
            $total_cintas_iva = $total_cintas;
            $total_tecnologia_iva = $total_tecnologia;
            $total_alquiler_iva = $total_alquiler;
            $total_soporte_iva = $total_soporte;
            $total_fletes_iva = $total_fletes;
            $total_m_prima_iva = $total_m_prima;

            if ($factura_portafolio[0]->iva == 1) {
                $total_etiquetas_iva = ($total_etiquetas * IVA) + $total_etiquetas;
                $total_cintas_iva = ($total_cintas * IVA) + $total_cintas;
                $total_tecnologia_iva = ($total_tecnologia * IVA) + $total_tecnologia;
                $total_alquiler_iva = ($total_alquiler * IVA) + $total_alquiler;
                $total_soporte_iva = ($total_soporte * IVA) + $total_soporte;
                $total_fletes_iva = ($total_fletes * IVA) + $total_fletes;
                $total_m_prima_iva = ($total_m_prima * IVA) + $total_m_prima;
            }
            $recibe_doc = 1;
            if (!empty($factura_portafolio[0]->fecha_reci_doc)) {
                $recibe_doc = 2;
            }
            $data = [
                'id_cli_prov_d' => $factura_portafolio[0]->id_cli_prov,
                'num_factura_d' => $factura_portafolio[0]->num_factura,
                'nit_d' => $factura_portafolio[0]->nit,
                'nombre_empresa_d' => $factura_portafolio[0]->nombre_empresa,
                'fecha_factura_d' => $factura_portafolio[0]->fecha_factura,
                'dias_dados_d' => $factura_portafolio[0]->dias_dados,
                'empresa_d' => $factura_portafolio[0]->empresa,
                'fecha_vencimiento_d' => $factura_portafolio[0]->fecha_vencimiento,
                'id_asesor' => $factura_portafolio[0]->asesor,
                'id_usuarios_asesor' => $factura_portafolio[0]->id_usuarios_asesor,
                'iva_d' => $factura_portafolio[0]->iva,
                'total_etiquetas_d' => round($total_etiquetas, 2),
                'total_etiquetas_iva_d' => round($total_etiquetas_iva, 2),
                'total_cintas_d' => round($total_cintas, 2),
                'total_cintas_iva_d' => round($total_cintas_iva, 2),
                'total_alquiler_d' => round($total_alquiler, 2),
                'total_alquiler_iva_d' => round($total_alquiler_iva, 2),
                'total_tecnologia_d' => round($total_tecnologia, 2),
                'total_tecnologia_iva_d' => round($total_tecnologia_iva, 2),
                'total_soporte_d' => round($total_soporte, 2),
                'total_soporte_iva_d' => round($total_soporte_iva, 2),
                'total_fletes_d' => round($total_fletes, 2),
                'total_fletes_iva_d' => round($total_fletes_iva, 2),
                'total_m_prima_d' => round($total_m_prima, 2),
                'total_m_prima_iva_d' => round($total_m_prima_iva, 2),
                'total_factura_d' => $factura_portafolio[0]->total_factura,
                'fecha_reci_doc_d' => $factura_portafolio[0]->fecha_reci_doc,
                'modifi_reci_doc' => $recibe_doc,

                'status' => 1
            ];
        }
        echo json_encode($data);
    }
    public function recibe_documento()
    {
        header('Content-Type:cation/json');
        $factura_portafolio = $this->PortafolioDAO->ConsultarNumFactura_portafolio($_POST['num_factura']);
        $data = [];
        if ($factura_portafolio[0]->estado_portafolio == 4) {
            $data = [
                'num_factura' => $_POST['num_factura'],
                'msg' => 'Esta factura fue Anulada.',
                'status' => -1
            ];
        } else {

            $datos = [
                'estado_portafolio' => 2, //estado del array globlal ""
                'usu_reci_doc' => $_SESSION['usuario']->getId_usuario(),
                'fecha_reci_doc' => $_POST['fecha_recibe'],
            ];
            $condicion = 'num_factura =' . $_POST['num_factura'];
            $data = $this->PortafolioDAO->editar($datos, $condicion);
        }
        echo json_encode($data);
    }
    public function vista_ingreso_trm()
    {
        parent::cabecera();
        $this->view(
            'Contabilidad/vista_ingreso_trm'
        );
    }
    public function ingreso_trm()
    {
        header('Content-Type:cation/json');

        $datos = [
            'fecha_crea' => $_POST['fecha_crea'], //fecha de pago que viene por la vista
            'valor_trm' => $_POST['valor_trm'], //fecha de pago que viene por la vista
            'id_usuario' => $_SESSION['usuario']->getId_usuario(),
        ];
        $res = $this->trmDAO->insertar($datos);
        echo json_encode($res);
    }
}
