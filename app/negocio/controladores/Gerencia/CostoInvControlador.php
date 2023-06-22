<?php

namespace MiApp\negocio\controladores\Gerencia;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ClaseArticuloDAO;
use MiApp\persistencia\dao\TipoArticuloDAO;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;

class CostoInvControlador extends GenericoControlador
{
    private $ClaseArticuloDAO;
    private $TipoArticuloDAO;
    private $productosDAO;
    private $entrada_tecnologiaDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->ClaseArticuloDAO = new ClaseArticuloDAO($cnn);
        $this->TipoArticuloDAO = new TipoArticuloDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
    }
    public function vista_inv_gerencia()
    {
        parent::cabecera();
        $this->view(
            'Gerencia/vista_inv_gerencia',
            [
                "clase_articulo" => $this->ClaseArticuloDAO->consulta_clase_articulo(),
                "tipo_articulo" => $this->TipoArticuloDAO->consultar_tipo_articulo(),
            ]
        );
    }

    public function consultar_inventarios_gerencia()
    {
        header('Content-Type:cation/json');
        $form = Validacion::Decodifica($_POST['form']);
        $id_clase_articulo = $form['id_clase_articulo'];
        $id_tipo_articulo = $form['id_tipo_articulo'];
        $consulta = $id_clase_articulo.' AND t2.id_tipo_articulo = '.$id_tipo_articulo;
        if ($id_tipo_articulo == '-1') {
            $consulta = $id_clase_articulo;
        }
        $resultado = $this->productosDAO->consultar_productos($consulta);
        foreach ($resultado as $valor) {
            if($id_clase_articulo == 3) {
                $valor->precio_usado = $valor->costo;
            } else {
                $valor->precio_usado = $valor->precio1;
            }
            $sumas = $this->entrada_tecnologiaDAO->consultar_cantidad($valor->id_productos);
            $valor->cantidad_inventario = $sumas;
            if($id_clase_articulo == 3) {
                $valor->total = $valor->costo * $sumas;
            } else {
                $valor->total = $valor->precio1 * $sumas;
            }
        }
        $arreglo["data"] = $resultado;
        echo json_encode($arreglo);
    }
}
