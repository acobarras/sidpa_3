<?php

namespace MiApp\negocio\controladores\diseno;

use MiApp\negocio\generico\GenericoControlador;
use MiApp\persistencia\dao\SolicitudesDisenoDAO;
// use MiApp\persistencia\dao\TintasDAO;
use MiApp\persistencia\dao\productosDAO;
use MiApp\negocio\util\Envio_Correo;

final class SolicitudesPendientesControlador extends GenericoControlador
{
    private $SolicitudesDisenoDAO;
    private $productosDAO;

    public function __construct(&$cnn)
    {
        parent::__construct($cnn);
        parent::validarSesion();

        $this->SolicitudesDisenoDAO = new SolicitudesDisenoDAO($cnn);
        $this->productosDAO = new productosDAO($cnn);
    }


    public function vista_solicitudes_diseno()
    {
        parent::cabecera();
        $this->view(
            'diseno/vista_solicitudes_diseno'
        );
    }

    public function consulta_solicitudes_codigo()
    {
        header('Content-Type: application/json');
        $condicion = 'WHERE t1.tipo_solicitud = 1 AND t1.estado = 1';
        $datos =  $this->SolicitudesDisenoDAO->consulta_solicitudes($condicion);
        foreach ($datos as $value) {
            $value->grafe_id = $value->grafe;
            $value->grafe = GRAF_CORTE[$value->grafe];
            $value->terminados1 =  explode(",", $value->terminados);
            $value->terminados = '';
            foreach ($value->terminados1 as $terminados ) {
                $value->terminados .= '- ' . implode(TERMINADOS_DISENO[$terminados]).'<br>';
            }
        }
        $data['data'] = $datos;
        echo json_encode($data);
        return;
    }

    public function cirre_solicitud_cod()
    {
        header('Content-Type: application/json');
            $datos = [
                'estado' => 2, // codigo creado -- cierre de caso
                'codigo_creado' => $_POST["codigo"]
            ]; // este estado puede variar cuando uniquemos las solicitudes 
            $condicion = 'id_solicitud =' . $_POST['id_solicitud'];
            $cambio_estado = $this->SolicitudesDisenoDAO->editar($datos, $condicion);
            if ($cambio_estado) {
                $solicitud = $this->SolicitudesDisenoDAO->consulta_data_solicitud($_POST['id_solicitud']);//esta consulta funciona cuando vamos a enviar el correo por ahora no               
                $correo_envio = Envio_Correo::correo_respuesta_creacodigo($solicitud[0]->asesor,$_POST['id_solicitud'], $solicitud[0]->nit, $solicitud[0]->nombre_empresa, $solicitud[0]->codigo_creado, $solicitud[0]->correo);
                $respuesta = [
                    'status' => 1,
                    'msg' => 'El código <b>'.$_POST['codigo'].'</b> de la solicitud ' . $_POST['id_solicitud'] . ' fue creado exitosamente y se envio un correo al asesor.',
                ];
            } else {
                $respuesta = [
                    'status' => -1,
                    'msg' => '¡Ups ocurrio un problema intenta de nuevo!',
                ];
            }
        echo json_encode($respuesta);
        return;
    }

    public function consulta_estado_solicitud() {
        header('Content-Type: application/json');
        $solicitud = $this->SolicitudesDisenoDAO->consulta_data_solicitud($_POST["data"]['id_solicitud']);
        $codigo = $this->productosDAO->cons_prod_codigo($_POST['codigo']);
        if ($solicitud[0]->estado == 2) {
            $respuesta = [
                'status' => -1,
                'msg' => 'Esta solicitud ya fue cerrada'
            ];
        } else if (empty($codigo)) {// no existe el codigo
            $respuesta = [
                'status' => -2,
                'msg' => 'Este código no ha sido creado, ¿Desea crearlo?'
            ];
        }else{// el producto ya esta creado 
            $respuesta = [
                'status' => 1,
                'msg' => 'El código ya está creado, cerraremos el caso y notificaremos por correo al asesor'
            ];
        }
        echo json_encode($respuesta);
        return;        
    }
}
