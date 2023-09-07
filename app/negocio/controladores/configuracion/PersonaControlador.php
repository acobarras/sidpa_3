<?php

namespace MiApp\negocio\controladores\configuracion;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\negocio\util\Validacion;
use MiApp\persistencia\dao\CiudadDAO;
use MiApp\persistencia\dao\PaisDAO;
use MiApp\persistencia\dao\DepartamentoDAO;
use MiApp\persistencia\dao\TipoDocumentoDAO;
use MiApp\persistencia\dao\PersonaDAO;
use MiApp\persistencia\dao\AreaTrabajoDAO;


class PersonaControlador extends GenericoControlador
{

    private $CiudadDAO;
    private $PaisDAO;
    private $DepartamentoDAO;
    private $TipoDocumentoDAO;
    private $PersonaDAO;
    private $AreaTrabajoDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->CiudadDAO = new CiudadDAO($cnn);
        $this->PaisDAO = new PaisDAO($cnn);
        $this->DepartamentoDAO = new DepartamentoDAO($cnn);
        $this->TipoDocumentoDAO = new TipoDocumentoDAO($cnn);
        $this->PersonaDAO = new PersonaDAO($cnn);
        $this->AreaTrabajoDAO = new AreaTrabajoDAO($cnn);
    }

    public function vista_persona()
    {
        parent::cabecera();
        $resultado = $this->PersonaDAO->consultar_personas();
        foreach ($resultado as $value) {
            $value->nombre_estado = ESTADO_SIMPLE[$value->estado];
        }
        $arreglo = $resultado;
        $this->view(
            'configuracion/vista_crear_persona',
            [
                "pais" => $this->PaisDAO->consultar_pais(),
                "departamento" => $this->DepartamentoDAO->consultar_departamento(),
                "ciudad" => $this->CiudadDAO->consultar_ciudad(),
                "tipo_documento" => $this->TipoDocumentoDAO->consultar_tipo_documento(),
                "persona" => $this->PersonaDAO->consultar_personas(),
                "jefeImediato" => $this->PersonaDAO->jefe_imediato(),
                "area" => $this->AreaTrabajoDAO->consultar_areas(),
                "lista" => $arreglo,
            ]
        );
    }

    public function consultar_personas()
    {
        header("Content-type: application/json; charset=utf-8");
        $resultado = $this->PersonaDAO->consultar_personas();
        foreach ($resultado as $value) {
            $value->nombre_estado = ESTADO_SIMPLE[$value->estado];
            $extencion = 'jpg';
            $nombre_fichero = CARPETA_IMG . PROYECTO . "/fotos_persona/" . $value->num_documento . '.' . $extencion;
            $existencia = '';
            if (file_exists($nombre_fichero)) {
                $existencia = "SI";
            } else {
                $existencia = "NO";
            }
            $value->existe_imagen = $existencia;
        }
        $arreglo = $resultado;
        echo json_encode($arreglo);
    }

    public function insertar_personas()
    {
        header('Content-Type:application/json');
        $datos = $_POST;
        $foto = $_FILES['foto_persona'];
        $datos['estado'] = 1;
        $datos['fecha_crea'] = date('Y-m-d');
        $datos['id_usuario_creador'] = $_SESSION['usuario']->getid_usuario();
        if ($foto['name'] != '') {
            $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
            $foto['name'] = $datos['num_documento'] . '.' . $extension;
            $nuevo_nombre = $foto['name'];
            $ubicacion = 'fotos_persona';
            $img = PersonaControlador::inserta_img($foto, $ubicacion, $nuevo_nombre);
            $respu = $this->PersonaDAO->insertar($datos);
        } else {
            $respu = $this->PersonaDAO->insertar($datos);
        }
        echo json_encode($respu);
    }

    public static function inserta_img($nombre, $ubi, $nuevo_nombre)
    {
        if (isset($nombre)) {
            $ruta = $nombre['tmp_name'];
            $des = CARPETA_IMG . PROYECTO . "/fotos_persona/" . $nuevo_nombre;
            move_uploaded_file($ruta, $des);
        }
        return $nuevo_nombre;
    }

    public function modificar_persona()
    {
        header("Content-type: application/json; charset=utf-8");
        $datos = $_POST;
        $union = null;
        if (isset($datos['comite'])) {
            foreach ($datos['comite'] as $value) {
                if ($union == '') {
                    $union = $value;
                } else {
                    $union .= ',' . $value;
                }
            }
        }
        $datos['comite'] = $union;
        $id_persona = $datos['id_persona'];
        if ($_FILES['foto_persona']['tmp_name'] != '') {
            $foto = $_FILES['foto_persona'];
            $extension = pathinfo($foto['name'], PATHINFO_EXTENSION);
            $foto['name'] = $datos['num_documento'] . '.' . $extension;
            $nuevo_nombre = $foto['name'];
            $ubicacion = 'fotos_persona';
            $img = PersonaControlador::modificar_img($foto, $nuevo_nombre);
            if ($img = 1) {
                $img_nueva = PersonaControlador::inserta_img($foto, $ubicacion, $nuevo_nombre);
            } else {
                $img_nueva = PersonaControlador::inserta_img($foto, $ubicacion, $nuevo_nombre);
            }
        }
        $condicion = 'id_persona =' . $id_persona;
        $formulario = $datos;
        $resultado = $this->PersonaDAO->editar($formulario, $condicion);
        echo json_encode($resultado);
        return;
    }

    public static function modificar_img($img, $img_ante)
    {
        $nombre_fichero = CARPETA_IMG . PROYECTO . '/fotos_persona/' . $img_ante;
        if (file_exists($nombre_fichero)) {
            unlink(CARPETA_IMG . PROYECTO . "/fotos_persona/" . $img_ante); //elimina acá le damos la direccion exacta del archivo 
            $respuesta = 1;
        } else {
            $respuesta = 2;
        }
        return $respuesta;
    }
}
