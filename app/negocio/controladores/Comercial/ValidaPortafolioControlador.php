<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PortafolioDAO;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\IntentoPedidoDAO;
class ValidaPortafolioControlador extends GenericoControlador
{
    private $PortafolioDAO;
    private $clientes_proveedorDAO;
    private $IntentoPedidoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->PortafolioDAO = new PortafolioDAO($cnn);
        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->IntentoPedidoDAO = new IntentoPedidoDAO($cnn);
    }

    public function valida_facturas()
    {
        header('Content-Type:cation/json');
        $cliente_prov = $this->clientes_proveedorDAO->consultar_clientes_proveedor($_POST['id_cli_prov']);
        $factura_portafolio = $this->PortafolioDAO->ConsultarPortafolioIdCliProv($_POST['id_cli_prov']);
        $forma_pago = $cliente_prov[0]->forma_pago;
        $dias_max_mora = $cliente_prov[0]->dias_max_mora;

        if ($forma_pago != 1) { // valida que la forma de pago sea siempre diferente de "contado".

            $data = [];
            $facturas_vencidas = [];
            $paso_pedido = $cliente_prov[0]->paso_pedido;
            $cupo_cliente = $cliente_prov[0]->cupo_cliente;

            if ($paso_pedido == 0) { // valida si tiene permiso de subir pedido por contabilidad

                if (!empty($factura_portafolio)) { // valida si el cliente tiene facturas

                    $monto_total = 0;
                    $dia_actual = date('Y-m-d');

                    $data = [
                        'id_cli_prov' => $_POST['id_cli_prov'],
                        'status' => 1
                    ];

                    foreach ($factura_portafolio as $value) {
                        $monto_total = $monto_total + $value->total_factura;
                        if ($dia_actual > $value->fecha_vencimiento) { // ESTAMOS EN MORA
                            $dias_mora = Validacion::resto_fechas($dia_actual, $value->fecha_vencimiento);
                            if ($dias_mora > $dias_max_mora) { //NO ALCANZARON LOS DIAS MAXIMOS DE MORA
                                $value->dias_mora = $dias_mora;
                                $facturas_vencidas[] = $value;
                                $data['facturas_vencidas'] = $facturas_vencidas;
                                $data['msg'] = ' ► Facturas vencidas.';
                                $data['status'] = -1;
                            }
                        }
                    }
                    if ($monto_total > $cupo_cliente) { // valida que el cliente no halla supedado el cupo otorgado.
                        $data['msg'] = ' ► Este cliente ha superado el cupo de su crédito para mayor información consulte el modulo de cartera.';
                        $data['status'] = -1;
                    }
                } else { // Este cliente esta al dia
                    $data = [
                        'id_cli_prov' => $_POST['id_cli_prov'],
                        'msg' => '► Este cliente esta al dia.',
                        'status' => 1
                    ];
                }
            } else {
                $data = [
                    'id_cli_prov' => $_POST['id_cli_prov'],
                    'msg' => '► Este cliente tiene paso de un pedido.',
                    'status' => 1
                ];
            }
        } else {
            $data = [
                'id_cli_prov' => $_POST['id_cli_prov'],
                'msg' => '► Este cliente es contado.',
                'status' => 1
            ];
        }
        if ($data['status'] == -1) {
            $intento_pedido = [
                'id_cli_prov' => $data['id_cli_prov'],
                'asesor' => $_SESSION['usuario']->getId_usuario() ,
                'observacion' =>  $data['msg'],
                'fecha_crea' => date('y-m-d h:i:s'),
            ];
            $this->IntentoPedidoDAO->insertar($intento_pedido);
        }
        echo json_encode($data);
    }
}
