<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;



class ConsultaInventarioControlador extends GenericoControlador
{
    private $productosDAO;    
    private $entrada_tecnologiaDAO;



    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->productosDAO = new productosDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);


    }

    /*  
     * Función para cargar la vista (vista_cotizador_etiquetas)
     */
    public function vista_consultar_inventario_comercial()
    {
        parent::cabecera();
        $this->view(
            'Comercial/vista_consultar_inventario_comercial'
        );
    }
    /*    
     * Función para cargar la vista consultar_inventario_comercial
     */

    public function consulta_inventario_comercial()
    {
        header('Content-Type: application/json');

        $resultado = $this->productosDAO->consultar_productos_inventario_comercial();
        foreach ($resultado as $valor) {
            $valor->cantidad = $this->entrada_tecnologiaDAO->consultar_cantidad($valor->id_productos);
        }
        $data['data'] = $resultado;
        echo json_encode($data);
    }
}
