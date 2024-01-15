<?php

namespace MiApp\negocio\controladores\produccion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\PedidosDAO;
use MiApp\persistencia\dao\PedidosItemDAO;
use MiApp\persistencia\dao\impresorasDAO;
use MiApp\persistencia\dao\impresora_tamanoDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\ProgramacionOperarioDAO;


class ImpresionEtiquetasControlador extends GenericoControlador
{

    private $PedidosDAO;
    private $PedidosItemDAO;
    private $impresorasDAO;
    private $impresora_tamanoDAO;
    private $PersonaDAO;
    private $UsuarioDAO;
    private $ProgramacionOperarioDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->PedidosDAO = new PedidosDAO($cnn);
        $this->PedidosItemDAO = new PedidosItemDAO($cnn);
        $this->impresorasDAO = new impresorasDAO($cnn);
        $this->impresora_tamanoDAO = new impresora_tamanoDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->ProgramacionOperarioDAO = new ProgramacionOperarioDAO($cnn);
    }

    public function vista_impresion_etiquetas()
    {
        parent::cabecera();
        $this->view(
            'produccion/vista_impresion_etiquetas',
            [
                'tamano_impresion' => $this->impresora_tamanoDAO->consulta_tamano_impresion(),
            ]
        );
    }

    public function consultar_items_pedido_impresion()
    {
        header('Content-Type: application/json');
        $parametro = 't1.num_pedido =' . $_POST['num_pedido'];
        $id_pedido = $this->PedidosDAO->consulta_pedidos($parametro);
        if ($id_pedido != NULL) {
            $items = $this->PedidosItemDAO->ConsultaIdPedido($id_pedido[0]->id_pedido);
            foreach ($items as $value) {
                $caracter = "-";
                $posicion_coincidencia = strpos($value->codigo, $caracter); //posicion empezando desde el (-)
                $cav_presentacion = substr($value->codigo, ($posicion_coincidencia + 5), 1); //obtener la cav presentacion
                $value->cav_cliente = $cav_presentacion;
                $value->nombre_empresa = $id_pedido[0]->nombre_empresa;
                $value->num_pedido = $id_pedido[0]->num_pedido;
                $value->fecha_compromiso  = $id_pedido[0]->fecha_compromiso;
                $value->nombre_empresa = $id_pedido[0]->nombre_empresa;
                $value->id_persona = $id_pedido[0]->id_persona;
                $value->orden_compra = $id_pedido[0]->orden_compra;
            }
        } else {
            $items = -1;
        }

        echo json_encode($items);
        return;
    }

    public function impresoras_marcacion()
    {

        header('Content-Type: application/json');
        $id_usuario = $_GET["id_usuario"];
        $id_estacion = $_GET['id_estacion_impre'];
        $id_tamano = $_GET['id_tamano'];
        $persona = $_SESSION['usuario']->getId_persona();
        $fecha = date('Y-m-d');
        $maquina = $this->ProgramacionOperarioDAO->ConsultaPersonaFecha($persona, $fecha);
        $impresora = '';

        // Esto es para poduccion cuando ya tienes una impresora por maquina 
        if (!empty($maquina)) {
            $id_maquina = $maquina[0]->id_maquina;
            $impresora = $this->impresorasDAO->consulta_impresoras_maquina($id_maquina, $id_tamano);
        }

        // Esto seria cuando es por subarea
        if (empty($impresora)) {
            $impresora = $this->impresorasDAO->impresoras_subarea($id_estacion, $id_tamano);;
            if (empty($impresora)) { // no encontramos impresoras
                $error = -1;
                echo json_encode($error);
                return;
            }
        }

        // consulta nombre de operario
        $id_persona = $this->UsuarioDAO->consultarIdPersona($id_usuario);
        $datos_persona = $this->PersonaDAO->consultar_personas_id($id_persona[0]->id_persona);
        $data = [
            'impresora' => $impresora,
            'persona' => $datos_persona
        ];
        echo json_encode($data);
        return;
    }

    public function impresion_etiquetas_marcacion()
    {
        $this->zpl('impresion_etiquetas_marcacion');
    }
}
