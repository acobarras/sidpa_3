<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\AdhesivoDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\PrecioMateriaPrimaDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\negocio\util\Validacion;


class CotizadorControlador extends GenericoControlador
{
    private $AdhesivoDAO;
    private $productosDAO;
    private $TipoMaterialDAO;
    private $PrecioMateriaPrimaDAO;
    private $TintasDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->TipoMaterialDAO = new TipoMaterialDAO($cnn);
        $this->PrecioMateriaPrimaDAO = new PrecioMateriaPrimaDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
    }

    /*  
     * Función para cargar la vista (vista_cotizador_etiquetas)
     */
    // "mat" => $this->productosDAO->consultar_productos_mat(),
    public function vista_cotizador_etiquetas()
    {
        parent::cabecera();
        $this->view(
            'Comercial/vista_cotizador_etiquetas',
            [
                "adh" => $this->AdhesivoDAO->consultar_adhesivo(),
                "mat" => $this->TipoMaterialDAO->consultar_tipo_material(),
                "precio" => $this->PrecioMateriaPrimaDAO->consultar_precio_materia_prima()
            ]
        );
    }


    /**
     * Función para CALCULAR cotizador de etiquetas.
     */
    public function calcular_cotizacion_etiquetas()
    {
        header("Content-type: application/json; charset=utf-8");
        $hora = date('H:i:s');
        $id_usuario = $_SESSION['usuario']->getId_usuario();
        $id_persona = $_SESSION['usuario']->getId_persona();
        $datos_etiq = parent::calculo_cotizador_etiquetas($_POST);
        echo json_encode($datos_etiq);
        return;
    }
}
