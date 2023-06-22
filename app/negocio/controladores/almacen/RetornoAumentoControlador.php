<?php

namespace MiApp\negocio\controladores\almacen;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\ItemProducirDAO;
use MiApp\persistencia\dao\MaquinaEmbobinadoDAO;
use MiApp\persistencia\dao\MetrosLinealesDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\persistencia\dao\entrada_tecnologiaDAO;
use MiApp\persistencia\dao\ubicacionesDAO;

class RetornoAumentoControlador extends GenericoControlador
{
    private $ItemProducirDAO;
    private $MaquinaEmbobinadoDAO;
    private $MetrosLinealesDAO;
    private $productosDAO;
    private $entrada_tecnologiaDAO;
    private $ubicacionesDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->ItemProducirDAO = new ItemProducirDAO($cnn);
        $this->MaquinaEmbobinadoDAO = new MaquinaEmbobinadoDAO($cnn);
        $this->MetrosLinealesDAO = new MetrosLinealesDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
        $this->entrada_tecnologiaDAO = new entrada_tecnologiaDAO($cnn);
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
    }

    public function vista_retorno_aumeto_material()
    {
        parent::validarSesion();
        parent::cabecera();
        $this->view(
            'almacen/vista_retorno_aumeto_material',
            [
                "ubicacion" => $this->entrada_tecnologiaDAO->consultar_ubicacion_bobina_ancho()

            ]
        );
    }
    public function consultar_ordenes_producciones()
    {
        header('Content-Type: application/json');

        $op = $this->ItemProducirDAO->consultar_item_producir_num($_POST['num_produccion']);
        foreach ($op as $valores) {
            $ml_emb = $this->MaquinaEmbobinadoDAO->suma_ml_alistado($valores->num_produccion);
            $valores->ml_disponibles_op = $valores->mL_descontado - $ml_emb[0]->ml_emb;
            $valores->materiales = $this->MetrosLinealesDAO->consultar_metros_lineales_especificos($valores->id_item_producir);
            $valores->roll_usuario = $_SESSION['usuario']->getId_roll();
        }
        $resultado['data'] = $op;
        echo json_encode($resultado);
    }
    public function retorno_materiales_op()
    {
        header('Content-Type: application/json');
        $entrada_tecnologia = $_POST['entrada_tecnologia'];
        $codigo = $entrada_tecnologia['codigo_producto'];
        $id_producto = $this->productosDAO->consultar_productos_especifico($codigo);
        $entrada_tecnologia['id_productos'] = $id_producto[0]->id_productos;
        $entrada_tecnologia['ubicacion'] = $entrada_tecnologia['ancho'];
        $entrada_tecnologia['estado_inv'] = 1;
        $entrada_tecnologia['id_usuario'] = $_SESSION['usuario']->getId_usuario();
        $entrada_tecnologia['fecha_crea'] = date('Y-m-d H:i:s');
        $this->entrada_tecnologiaDAO->insertar($entrada_tecnologia);

        $metros_lineales_op = $_POST['metros_lineales_op'];
        $metros_lineales_op['id_persona'] = $_SESSION['usuario']->getId_persona();
        $metros_lineales_op['fecha_crea'] = date('Y-m-d H:i:s');

        $this->MetrosLinealesDAO->insertar($metros_lineales_op);

        $item_producir = $_POST['item_producir'];
        $condicion = "id_item_producir =" . $item_producir['id_item_producir'];
        $respuesta = $this->ItemProducirDAO->editar($item_producir, $condicion);

        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'status' => '1',
                'msg' => 'Exito al retornar material.',
            ];
        } else {
            $respu = [
                'status' => '-2',
                'msg' => 'Error al procesar.',

            ];
        }
        echo json_encode($respu);
        return;
    }

    public function aumenta_material_op()
    {
        header('Content-Type: application/json');
        $datos = $_POST['array_cantidad'];
        $num_produccion = $datos[0]['num_produccion'];
        $total_ml = 0;
        $m2_total = 0;
        foreach ($datos as $valor) {
            $total_ml = $total_ml + $valor['ml'];
            $m2_total = $m2_total + $valor['m2'];
            $entrada_tecno['documento'] = $valor['num_produccion'];
            $entrada_tecno['ubicacion'] = '';
            $entrada_tecno['codigo_producto'] = $valor['codigo'];
            $entrada_tecno['id_productos'] = $valor['id_productos'];
            $entrada_tecno['ancho'] = $valor['ancho'];
            $entrada_tecno['metros'] = $valor['ml'];
            $entrada_tecno['salida'] = $valor['m2'];
            $entrada_tecno['id_usuario'] = $_SESSION['usuario']->getId_usuario();
            $entrada_tecno['fecha_crea'] = date('Y-m-d H:i:s');
            
            $this->entrada_tecnologiaDAO->insertar($entrada_tecno);
            
            
            $metros_lineales['id_item_producir'] = $valor['id_item_producir'];
            $metros_lineales['ancho'] =  $valor['ancho'];
            $metros_lineales['codigo_material'] =  $valor['codigo'];
            $metros_lineales['metros_lineales'] =  $valor['ml'];
            $metros_lineales['estado_ml'] =  1;
            $metros_lineales['id_persona'] =  $_SESSION['usuario']->getId_persona();
            $metros_lineales['fecha_crea'] =  date('Y-m-d H:i:s');
            
            $this->MetrosLinealesDAO->insertar($metros_lineales);
        }
        $item_producir = $this->ItemProducirDAO->consultar_item_producir_num($num_produccion);
        $nuevo_mL_descontado = $item_producir[0]->mL_descontado + $total_ml;
        $nuevo_m2_total = $item_producir[0]->m2_inicial + $m2_total;
        $item_producir_edita = ['mL_descontado' => $nuevo_mL_descontado, 'm2_inicial' => $nuevo_m2_total];
        $condicio_item_producir_edita = 'id_item_producir ='.$item_producir[0]->id_item_producir;
        $respuesta = $this->ItemProducirDAO->editar($item_producir_edita,$condicio_item_producir_edita); 
        $respu = [];
        if (!empty($respuesta)) {
            $respu = [
                'status' => 1,
                'msg' => 'Exito al alistar.',
            ];
        } else {
            $respu = [
                'status' => -2,
                'msg' => 'Error al procesar.',

            ];
        }
        echo json_encode($respu);
        return;
    }
}
