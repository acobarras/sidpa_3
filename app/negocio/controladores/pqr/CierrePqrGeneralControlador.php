<?php

namespace MiApp\negocio\controladores\pqr;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\CodigoRespuestaPqrDAO;
use MiApp\persistencia\dao\GestionPqrDAO;
use MiApp\persistencia\dao\SeguimientoPqrDAO;

use MiApp\negocio\util\Validacion;
use MiApp\negocio\util\PDF;
use MiApp\negocio\util\Envio_Correo;

class CierrePqrGeneralControlador extends GenericoControlador
{
    private $CodigoRespuestaPqrDAO;
    private $GestionPqrDAO;
    private $SeguimientoPqrDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->CodigoRespuestaPqrDAO = new CodigoRespuestaPqrDAO($cnn);
        $this->GestionPqrDAO = new GestionPqrDAO($cnn);
        $this->SeguimientoPqrDAO = new SeguimientoPqrDAO($cnn);
    }

    public function vista_cierre_pqr_general()
    {
        parent::cabecera();
        $this->view(
            'pqr/vista_cierre_pqr_general',
            [
                'motivo_pqr' => $this->CodigoRespuestaPqrDAO->consultar_codigos_pqr()
            ]
        );
    }

    public function codigo_motivo_pqr()
    {
        header('Content-Type: application/json'); //convierte a json
        $data = $_POST['data'];
        $id_respuesta_pqr = $_POST['codigo_motivo'];
        $datos_id_respuesta = $this->CodigoRespuestaPqrDAO->consultar_codigos_pqr_id($id_respuesta_pqr);
        $datos_id_respuesta = $datos_id_respuesta[0];
        $clasificacion = $_POST['clasificacion'];
        $responsable = $_POST['responsable'];
        $costo = $_POST['costo'];
        $observacion = $_POST['observacion'];

        $num_pqr = $data['num_pqr'];
        // Consultar si el motivo se repite mas de una vez para el mismo cliente
        $repite = $this->GestionPqrDAO->repite_motivo_cliente($data['id_cli_prov'], $id_respuesta_pqr);
        if ($repite[0]->cantidad_misma_respuesta < 1 && $datos_id_respuesta->tipo_pqr != 1) {
            // Generar pdf de contestacion y envio de correo
            $ruta_archivo = CARPETA_IMG . PROYECTO . '/PDF/pdf_pqr/' . $data['num_pqr'] . '.pdf';
            $respu = PDF::respuesta_pdf_pqr($data, $clasificacion, $datos_id_respuesta);
            $respu = file_put_contents($ruta_archivo, $respu);
            $correo = $data['datos_direccion'][0]['email'];
            $correo2 = CORREO_SERV_CLIENTE;
            $correo_envio = Envio_Correo::correos_cierre_pqr($num_pqr, $ruta_archivo, $correo, $correo2);
            if ($correo_envio['state'] == 1) {
                $res = [
                    'status' => 1,
                    'msg' => 'Datos Enviados por correo'
                ];
                unlink($ruta_archivo);
            }
            $estado_pqr = 11;
            $id_actividad_area = 74;
        } else {
            $res = [
                'status' => 1,
                'msg' => 'No se envio correo'
            ];
            $estado_pqr = 10;
            $id_actividad_area = 69;
        }
        // editar el codigo del motivo a la pqr
        $edita_pqr = [
            'id_respuesta_pqr' => $id_respuesta_pqr,
            'clasificacion' => $clasificacion,
            'responsable' => $responsable,
            'costo' => $costo,
            'observacion' => $observacion,
            'estado' => $estado_pqr,
        ];
        $condicion = 'id_pqr =' . $data['id_pqr'];
        $this->GestionPqrDAO->editar($edita_pqr, $condicion);
        // Realizar el seguimiento a la pqr
        $inserta_seguimiento = [
            'id_pqr' => $data['id_pqr'],
            'id_actividad_area' => $id_actividad_area,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('Y-m-d H:i:s')
        ];
        $this->SeguimientoPqrDAO->insertar($inserta_seguimiento);
        echo json_encode($res);
    }

    public function motivo_cierre_pqr()
    {
        header('Content-Type: application/json'); //convierte a json
        $data = $_POST['data'];
        $id_respuesta_pqr = $_POST['codigo_motivo_cierre'];
        $analisis_pqr_cierre = $_POST['analisis_pqr_cierre'];
        $accion_cierre = $_POST['accion_cierre'];
        $responsable_cierre = $_POST['responsable_cierre'];
        $costo_cierre = $_POST['costo_cierre'];
        $observacion_cierre = $_POST['observacion_cierre'];
        $datos_id_respuesta = $this->CodigoRespuestaPqrDAO->consultar_codigos_pqr_id($id_respuesta_pqr);
        foreach ($datos_id_respuesta as $value) {
            $value->analisis_pqr = $analisis_pqr_cierre;
            $value->accion = $accion_cierre;
        }
        $datos_id_respuesta = $datos_id_respuesta[0];
        $clasificacion = $_POST['clasificacion_cierre'];
        $num_pqr = $data['num_pqr'];
        // Generar pdf de contestacion y envio de correo
        $ruta_archivo = CARPETA_IMG . PROYECTO . '/PDF/pdf_pqr/' . $data['num_pqr'] . '.pdf';
        $respu = PDF::respuesta_pdf_pqr($data, $clasificacion, $datos_id_respuesta);
        $respu = file_put_contents($ruta_archivo, $respu);
        if ($_POST['envio_correo'] == 1) {
            // CORREO CLIENTE
            $correo = $data['datos_direccion'][0]['email'];
            $correo2 = CORREO_SERV_CLIENTE;
            $correo2 = "";
            $correo_envio = Envio_Correo::correos_cierre_pqr($num_pqr, $ruta_archivo, $correo, $correo2);
            if ($correo_envio['state'] == 1) {
                unlink($ruta_archivo);
            }
        } else {
            // CORREO DE ATENCION AL CLIENTE
            $correo = CORREO_SERV_CLIENTE;
            $correo2 = "";
            $correo_envio = Envio_Correo::correos_cierre_pqr($num_pqr, $ruta_archivo, $correo, $correo2);
            if ($correo_envio['state'] == 1) {
                unlink($ruta_archivo);
            }
        }
        $estado_pqr = 11;
        $id_actividad_area = 74;
        // editar el codigo del motivo a la pqr
        $edita_pqr = [
            'id_respuesta_pqr' => $id_respuesta_pqr,
            'clasificacion' => $clasificacion,
            'analisis_pqr' => $analisis_pqr_cierre,
            'responsable' => $responsable_cierre,
            'costo' => $costo_cierre,
            'observacion' => $observacion_cierre,
            'estado' => $estado_pqr,
        ];
        $condicion = 'id_pqr =' . $data['id_pqr'];
        $this->GestionPqrDAO->editar($edita_pqr, $condicion);
        // Realizar el seguimiento a la pqr
        $inserta_seguimiento = [
            'id_pqr' => $data['id_pqr'],
            'id_actividad_area' => $id_actividad_area,
            'id_usuario' => $_SESSION['usuario']->getid_usuario(),
            'fecha_crea' => date('Y-m-d H:i:s')
        ];
        $this->SeguimientoPqrDAO->insertar($inserta_seguimiento);
        $res = [
            'status' => 1,
            'data' => '',
        ];
        echo json_encode($res);
        return;
    }
}
