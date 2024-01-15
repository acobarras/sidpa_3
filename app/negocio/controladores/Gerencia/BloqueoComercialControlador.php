<?php

namespace MiApp\negocio\controladores\Gerencia;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\UsuarioDAO;

class BloqueoComercialControlador extends GenericoControlador
{
    private $UsuarioDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->UsuarioDAO = new UsuarioDAO($cnn);
    }

    public function vista_bloqueo_comercial()
    {
        parent::cabecera();

        $this->view(
            'Gerencia/vista_bloqueo_comercial',
        );
    }
    public function consultar_asesores()
    {
        header('Content-Type:cation/json');
        $asesores = $this->UsuarioDAO->consultar_roll(4);
        $res['data'] = $asesores;
        echo json_encode($res);
    }
    public function bloquear_asesor()
    {
        header('Content-Type:cation/json');
        $editar_estado = ['bloqueo_pedido' => $_POST['bloqueo_pedido']];
        if ($_POST['id_usuario'] == 0) {
            $condicion = 'id_roll =' . 4;
        } else {
            $condicion = 'id_usuario =' . $_POST['id_usuario'];
        }
        $resultado = $this->UsuarioDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
    }
}
