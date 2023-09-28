<?php

namespace MiApp\negocio\controladores\Comercial;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\AdhesivoDAO;
use MiApp\persistencia\dao\TipoMaterialDAO;
use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\PrecioMateriaPrimaDAO;
use MiApp\persistencia\dao\FormaMaterialDAO;
use MiApp\persistencia\dao\SolicitudesDisenoDAO;
use MiApp\negocio\util\Envio_Correo;


class SolicitudesDisenoControlador extends GenericoControlador
{
    private $AdhesivoDAO;
    private $TipoMaterialDAO;
    private $TintasDAO;
    private $PrecioMateriaPrimaDAO;
    private $FormaMaterialDAO;
    private $SolicitudesDisenoDAO;



    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();
        $this->AdhesivoDAO = new AdhesivoDAO($cnn);
        $this->TipoMaterialDAO = new TipoMaterialDAO($cnn);
        $this->TintasDAO = new TintasDAO($cnn);
        $this->PrecioMateriaPrimaDAO = new PrecioMateriaPrimaDAO($cnn);
        $this->FormaMaterialDAO = new FormaMaterialDAO($cnn);
        $this->SolicitudesDisenoDAO = new SolicitudesDisenoDAO($cnn);
    }

    public function vista_solicitud_diseno()
    {
        parent::cabecera();
        $this->view(
            'Comercial/vista_solicitud_diseno',
            [
                "adh" => $this->AdhesivoDAO->consultar_adhesivo(),
                "mat" => $this->TipoMaterialDAO->consultar_tipo_material(),
                "tintas" => $this->TintasDAO->consultar_tintas(),
                'forma_material' => $this->FormaMaterialDAO->consultar_forma_material(),
                "precio" => $this->PrecioMateriaPrimaDAO->consultar_precio_materia_prima(),
            ]
        );
    }

    // envio correo de solicitud creación cliente
    public function envio_correo_creacioncliente()
    {
        header('Content-Type: application/json');
        $nit = $_POST['nit'];
        $asesor = $_POST['asesor_cod'];
        $razon_social = $_POST['nombre_cliente_cod'];
        $correo = CORREO_CREACION_CLIENTE; // falta definir este correo CON UNA CONSTANTE
        //Mover archivo carpeta temporal
        $nombre_nuevo = '';
        $ext = $_FILES['rut']['type'];
        $explode = explode("/", $ext);
        $extension = array_pop($explode);
        $ruta = $_FILES['rut']['tmp_name'];
        $nombre_nuevo = 'Rut_' . $nit . '.' . $extension;
        $destino = CARPETA_IMG . PROYECTO . '/PDF/rut_temp/' . $nombre_nuevo;
        move_uploaded_file($ruta, $destino);
        // enviar correo y borrar
        $correo_envio = Envio_Correo::correo_solicitud_creacioncliente($nit, $razon_social, $asesor, $correo, $destino);
        unlink($destino);
        echo json_encode($correo_envio);
    }

    // envio de solicitud de diseño
    public function envio_solicitud_diseno()
    {
        header('Content-Type: application/json');
        if ($_POST["tipo_codigo"] == 2) { // actualizacion 
            $codigo_antiguo = $_POST["codigo_antiguo"];
        } else {
            $codigo_antiguo = null;
        }
        $datos = [ // esto cambia cuando agregemos la otra solicitud
            'id_cli_prov' => $_POST['id_cli_prov'],
            'tipo_solicitud' => 1, // solicitud codigo
            'id_usuario_asesor' => $_POST['id_asesor_cod'],
            'ancho' => $_POST['ancho'],
            'alto' => $_POST['alto'],
            'tipo_producto' => $_POST['tipo_product'],
            'id_forma' => $_POST['forma_material'],
            'codigo_tipo_material' => $_POST['tipo_material'],
            'codigo_adh' => $_POST['id_adh'],
            'cavidades' => $_POST['cavidad'],
            'cantidad_tintas' => $_POST['cant_tintas'],
            'grafe' => $_POST['gaf_cort'],
            'terminados' => $_POST['terminados'],
            'estado' => 1, //estado 1 solicitud creada
            'tipo_codigo' => $_POST["tipo_codigo"],
            'codigo_antiguo' => $codigo_antiguo,
            'precio' => $_POST["precio"],
            'cantidad' => $_POST["cantidad_etiquetas"],
            'observaciones' => $_POST['observaciones_cod'],
        ];
        $respuesta = $this->SolicitudesDisenoDAO->insertar($datos);
        // correo de notificacion 
        $solicitud = $this->SolicitudesDisenoDAO->consulta_data_solicitud($respuesta['id']); 
        $correo_solicitud = Envio_Correo::confirmacion_solicitud_codigo($solicitud[0]->asesor, $respuesta['id'], $solicitud[0]->nit, $solicitud[0]->nombre_empresa, $solicitud[0]->correo);
        echo json_encode($respuesta);
    }
}
