<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\clientes_proveedorDAO;
use MiApp\persistencia\dao\TipoDocumentoDAO;
use MiApp\persistencia\dao\UsuarioDAO;
use MiApp\persistencia\dao\EmpresasDAO;

class ClienteProveedorControlador extends GenericoControlador
{

    private $clientes_proveedorDAO;
    private $TipoDocumentoDAO;
    private $UsuarioDAO;
    private $EmpresasDAO;

    public function __construct(&$cnn)
    {

        parent::__construct($cnn);
        parent::validarSesion();

        $this->clientes_proveedorDAO = new clientes_proveedorDAO($cnn);
        $this->TipoDocumentoDAO = new TipoDocumentoDAO($cnn);
        $this->UsuarioDAO = new UsuarioDAO($cnn);
        $this->EmpresasDAO = new EmpresasDAO($cnn);
    }

    public function vista_creacion_clientes()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_creacion_clientes',
            [
                "documento" => $this->TipoDocumentoDAO->consultar_tipo_documento(),
                "usuarios" => $this->UsuarioDAO->ConsultarUsuario(),
                "pertenece" => $this->EmpresasDAO->consultar_empresas(),
                "pertenece_modifi" => $this->EmpresasDAO->consultar_empresas(),
            ]
        );
    }

    public function consultar_clientes()
    {
        header('Content-Type: application/json');
        $resultado = $this->clientes_proveedorDAO->consultar_clientes($_SESSION['usuario']->getId_roll());
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }

    public function modificar_estados_cliente()
    {
        header('Content-Type: application/json');
        $editar_estado = ['estado_cli_prov' => $_POST['estado_cli_prov']];
        $condicion = 'id_cli_prov =' . $_POST['id_cli_prov'];
        $resultado = $this->clientes_proveedorDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
        return;
    }

    public function modificar_cliente()
    {
        header('Content-Type: application/json');
        $id_cli_prov = $_POST['id'];
        $formulario = Validacion::Decodifica($_POST['form']);
        $id_usuarios_asesor = '';
        if (isset($_POST['id_usuarios_asesor'])) {
            foreach ($_POST['id_usuarios_asesor'] as $value) {
                if ($id_usuarios_asesor == '') {
                    $id_usuarios_asesor .= $value;
                } else {
                    $id_usuarios_asesor .= ',' . $value;
                }
            }
        }
        $formulario['id_usuarios_asesor'] = $id_usuarios_asesor;
        $condicion = 'id_cli_prov =' . $id_cli_prov;
        $resultado = $this->clientes_proveedorDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
    }

    public function insertar_cliente()
    {
        header('Content-Type: application/json');
        $formulario = Validacion::Decodifica($_POST['form']);
        $id_usuarios_asesor = '';
        if (isset($_POST['id_usuarios_asesor'])) {
            foreach ($_POST['id_usuarios_asesor'] as $value) {
                if ($id_usuarios_asesor == '') {
                    $id_usuarios_asesor .= $value;
                } else {
                    $id_usuarios_asesor .= ',' . $value;
                }
            }
        }
        $formulario['id_usuarios_asesor'] = $id_usuarios_asesor;
        $formulario['fecha_crea'] = date('Y-m-d');
        $formulario['id_usuario'] = $_SESSION['usuario']->getid_usuario();
        // print_r($formulario);
        // Validar si el tipo articulo ya fue creado
        $respuesta = $this->clientes_proveedorDAO->consultar_nit_clientes($formulario['nit']);
        if (empty($respuesta)) {
            $respu = $this->clientes_proveedorDAO->insertar($formulario);
        } else {
            $respu = ['estado' => false];
        }
        echo json_encode($respu);
    }
    public function modificar_paso_pedido()
    {
        header('Content-Type: application/json');
        $editar_estado = ['paso_pedido' => $_POST['paso_pedido']];
        $condicion = 'id_cli_prov =' . $_POST['id_cli_prov'];
        $resultado = $this->clientes_proveedorDAO->editar($editar_estado, $condicion);
        echo json_encode($resultado);
        return;
    }
}
