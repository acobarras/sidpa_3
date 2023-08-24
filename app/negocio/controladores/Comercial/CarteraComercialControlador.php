<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\PortafolioDAO;

class CarteraComercialControlador extends GenericoControlador 
{
    private $PortafolioDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->PortafolioDAO = new PortafolioDAO($cnn);
    }

    /** Función para cargar la vista (vista_cartera_comercial)*/
    public function vista_cartera_comercial()
    {
        parent::cabecera();
        $this->view(
            'Comercial/vista_cartera_comercial'
        );
    }
  
     /* Función para consultar tablas*/
    public function consulta_cartera_vencida()
    {
        header('Content-Type: application/json');
        $id_persona = $_SESSION['usuario']->getId_persona();
        $vencida = $this->PortafolioDAO->consulta_cartera($id_persona,'<');
        $otra_cartera = $this->PortafolioDAO->consulta_cartera($id_persona,'>=');
        $data['vencida'] = $vencida;
        $data['cartera'] = $otra_cartera;
        echo json_encode($data);
    }

    /* Función para consultar tablas*/
    public function consulta_detallada_facturas()
    {
        header('Content-Type: application/json');
        $tipo = $_POST['tipo'];
        if ($tipo == "vencida") {
            $condicion = '<';
        }else{
            $condicion = '>=';
        }
        $id_cliente = $_POST['id_cli_prov'];
        $id_persona = $_SESSION['usuario']->getId_persona();
        $facturas = $this->PortafolioDAO->detalle_facturasVencidas($id_cliente, $id_persona,$condicion);
        $data['data'] = $facturas;
        echo json_encode($data);
    }
}
