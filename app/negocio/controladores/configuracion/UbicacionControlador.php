<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\CiudadDAO;
use MiApp\persistencia\dao\DepartamentoDAO;
use MiApp\persistencia\dao\PaisDAO;
use MiApp\persistencia\dao\ClaseArticuloDAO;
use MiApp\persistencia\dao\ubicacionesDAO;


class UbicacionControlador extends GenericoControlador
{

    private $PaisDAO;
    private $DepartamentoDAO;
    private $CiudadDAO;
    private $ClaseArticuloDAO;
    private $ubicacionesDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->PaisDAO = new PaisDAO($cnn);
        $this->DepartamentoDAO = new DepartamentoDAO($cnn);
        $this->CiudadDAO = new CiudadDAO($cnn);
        $this->ClaseArticuloDAO = new ClaseArticuloDAO($cnn);
        $this->ubicacionesDAO = new ubicacionesDAO($cnn);
    }



    public function vista_pais()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_crear_pais_dep_mpio',
            [
                "pais" => $this->PaisDAO->consultar_pais(),
                "departamento" => $this->DepartamentoDAO->consultar_departamento(),
            ]
        );
    }
    public function consultar_pais()
    {
        header('Content-Type: application/json');
        $resultado = $this->PaisDAO->consultar_pais();
        $arreglo['data'] = $resultado;
        echo json_encode($arreglo);
    }

    public function insertar_pais()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $datos['fecha_crea'] = date('Y-m-d');
        $respu = $this->PaisDAO->insertar($datos);
        echo json_encode($respu);
    }

    public function modificar_pais()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $pais_editar = array(
            'codigo' => $datos['codigo_pais'],
            'nombre' => $datos['nombre_pais']
        );
        $condicion = 'id_pais =' . $datos['id_pais'];
        $resultado = $this->PaisDAO->editar($pais_editar, $condicion);
        echo json_encode($resultado);
    }

    public function insertar_departamento()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $inser_departamento = array(
            'id_pais' => $datos['id_pais_form2'],
            'nombre' => $datos['nombre_departamento'],
            'fecha_crea' => date('Y-m-d')
        );
        $respu = $this->DepartamentoDAO->insertar($inser_departamento);
        echo json_encode($respu);
    }

    public function consultar_departamentos()
    {
        header('Content-Type: application/json');
        $resultado = $this->DepartamentoDAO->consultar_departamento();
        $arreglo['data'] = $resultado;
        echo json_encode($arreglo);
    }

    public function modificar_departamento()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $departamento_editar = array(
            'id_pais' => $datos['id_pais_modifi'],
            'nombre' => $datos['nombre_departamento_modifi']
        );
        $condicion = 'id_departamento =' . $datos['id_departamento_modifi'];
        $resultado = $this->DepartamentoDAO->editar($departamento_editar, $condicion);
        echo json_encode($resultado);
    }

    public function insertar_ciudad()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $inser_ciudad = array(
            'id_departamento' => $datos['id_departamento2'],
            'nombre' => $datos['nombre_ciudad'],
            'fecha_crea' => date('Y-m-d')
        );
        $respu = $this->CiudadDAO->insertar($inser_ciudad);
        echo json_encode($respu);
    }

    public function consultar_ciudad()
    {
        header('Content-Type: application/json');
        $resultado = $this->CiudadDAO->consultar_ciudad();
        $arreglo['data'] = $resultado;
        echo json_encode($arreglo);
    }

    public function modificar_ciudad()
    {
        header('Content-Type: application/json');
        $datos = $_POST;
        $ciudad_editar = array(
            'nombre' => $datos['nombre_ciudad_modifi'],
            'id_departamento' => $datos['id_depart_modifi']
        );
        $condicion = 'id_ciudad =' . $datos['id_ciudad_modifi'];
        $resultado = $this->CiudadDAO->editar($ciudad_editar, $condicion);
        echo json_encode($resultado);
    }

    public function vista_crea_ubicaciones()
    {
        parent::cabecera();
        $this->view(
            'configuracion/vista_crea_ubicaciones',
            [
                "clase_articulo" => $this->ClaseArticuloDAO->consultar_clase_articulo()
            ]
        );
    }

    public function consultar_ubicaciones()
    {
        header('Content-Type: application/json');
        $tabla_ubicaciones = $this->ubicacionesDAO->tabla_ubicaciones();
        $clase_articulo = $this->ClaseArticuloDAO->consultar_clase_articulo();
        foreach ($tabla_ubicaciones as $value_tab) {
            foreach ($clase_articulo as $value) {
                if ($value_tab->tipo_producto == $value->id_clase_articulo) {
                    $value_tab->nombre_articulo = $value->nombre_clase_articulo;
                }
            }
        }
        $respu['data'] = $tabla_ubicaciones;
        echo json_encode($respu);
    }

    public function insertar_ubicaciones()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $_POST;
        $datos['estado'] = 1;
        $datos['fecha_crea'] = date('Y-m-d H:i:s');
        // Validar si el tipo articulo ya fue creado
        $respuesta = $this->ubicacionesDAO->valida_ubicacion($datos['nombre_ubicacion']);
        if (empty($respuesta)) {
            $respu = $this->ubicacionesDAO->insertar($datos);
        } else {
            $respu = ['estado' => false];
        }
        echo json_encode($respu);
    }

    public function modificar_ubicacion()
    {
        header("Content-type: application/json; charset=utf-8");
        if ($_POST['solo_estado'] == 1) {
            $datos_editar = ['estado' => $_POST['estado']];
            $condicion = 'id =' . $_POST['id'];
        } else {
            $datos_editar = Validacion::Decodifica($_POST['form']);
            $condicion = 'id =' . $_POST['id'];
        }
        $resultado = $this->ubicacionesDAO->editar($datos_editar, $condicion);
        echo json_encode($resultado);        
    }
}
