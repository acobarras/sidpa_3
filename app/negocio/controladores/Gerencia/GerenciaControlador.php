<?php

namespace MiApp\negocio\controladores\Gerencia;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\IntentoPedidoDAO;

class GerenciaControlador extends GenericoControlador
{
    private $IntentoPedidoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->IntentoPedidoDAO = new IntentoPedidoDAO($cnn);
    }
    public function vista_intento_pedidos()
    {
        parent::cabecera();

        $this->view(
            'Gerencia/vista_intento_pedidos'
        );
    }
    public function carga_tb_intento_ped()
    {
        header('Content-Type:cation/json');
        if ($_POST['busqueda'] == 1 || $_POST['busqueda'] == 2) {
            $data["data"] =  $this->IntentoPedidoDAO->consul_intentos($_POST['busqueda']);
        }elseif ($_POST['busqueda'] == 3) {
            $parametro = 't1.id_cli_prov=' . $_POST['id'];
            $data["data"] =  $this->IntentoPedidoDAO->consul_intentos_especifico($parametro);
        } else {
            $parametro = 't1.asesor=' . $_POST['id'];
            $data["data"] =  $this->IntentoPedidoDAO->consul_intentos_especifico($parametro);
        }
        echo json_encode($data);
    }
}
