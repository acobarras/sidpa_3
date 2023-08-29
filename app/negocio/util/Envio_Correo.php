<?php

namespace MiApp\negocio\util;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// require '../vendor/phpmailer/phpmailer/src/Exception.php';
// require '../vendor/phpmailer/phpmailer/src/PHPMailer.php';
// require '../vendor/phpmailer/phpmailer/src/SMTP.php';

class Envio_Correo
{
    public static function SolicitudesCompras($TipoCompra, $correo, $data)
    {
        // data debe contener el correo de envio,forma_pago,fecha_compromiso,asesor, y toda la data del item del pedido
        $html = '
        <!DOCTYPE html>
            <html>

            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=" utf-8">
            </head>

            <body>
                <div style="width: 900px; justify-content: center;
            align-items: center;">
                    <div style="background-color: #001c85; ">
                        <br><br>
                        <div style="width: 800px;  margin:0px auto;">
                            <img src="' . IMG_CORREO . '" style="width: 300px;" alt="Logo_empresa">
                            <br><br>
                            <div style="background-color: white; padding:30px">
                                <div style="width:639px; font-size:25px;">
                                    <br>
                                    <h4 style="font-weight: 900;color: #737679;">¡Buen día!' . $data->nombre_comprador . '!</h4>
                                    <h6 style="color: #5f6368;font-weight: 200;">Se informa que el pedido No ' . $data->num_pedido . ' item ' . $data->item . ' estan solicitando la compra de:</h6>
                                </div>
                                <br><br>
                                <center>
                                    <table style="background-color:#e8e8e8; width: auto; font-size:20px;" border="1">
                                        <tr style="font-size: 10px;">
                                            <th style="text-align: center; padding: 8px;">Código Solicitado</th>
                                            <th style="text-align: center; padding: 8px;">Cantidad Solicitada</th>
                                            <th style="text-align: center; padding: 8px;">Cantidad Alistada</th>
                                            <th style="text-align: center; padding: 8px;">Valor Unitario</th>
                                            <th style="text-align: center; padding: 8px;">Asesor</th>
                                            <th style="text-align: center; padding: 8px;">Forma de pago</th>
                                            <th style="text-align: center; padding: 8px;">Fecha de Compromiso</th>
                                        </tr>
                                        <tr style="width: 100%; font-size: 17px;">
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data->codigo_producto . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data->cantidad_requerida . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data->cant_bodega . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data->precio_venta . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data->asesor . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data->forma_pago . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data->fecha_compromiso . '</span></td>
                                        </tr>
                                    </table>
                                </center>
                                <br><br>
                                <div style="width:639px; font-size:25px;">
                                    <h6 style="color: #5f6368;font-weight: 200;">Quedamos atentos a sus comentarios.</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Proceso Logistica.</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Tel: ' . TEL_EMPRESA . ' Ext 127</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">' . NOMBRE_EMPRESA . '</h6>
                                </div>
                            </div>
                            <br><br>
                        </div>
                    </div>
            </body>

            </html>';
        $remite = 'Solicitud de Compra ' . $TipoCompra . ' Sidpa';
        $subject = "Solicitud " . $TipoCompra . " " . $data->nombre_empresa;
        self::php_miler($html, $remite, $subject, $correo);
    }
    public static function envio_correo_aprobacion_precio($data, $user, $correo)
    {
        $html = '
        <!DOCTYPE html>
        <html>

            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=" utf-8">
            </head>
            
            <body>
            <div style="width: 900px; justify-content: center;
            align-items: center;">
            <div style="background-color: #001c85; ">
            <br><br>
            <div style="width: 800px;  margin:0px auto;">
            <img src="' . IMG_CORREO . '" style="width: 300px;" alt="Logo_empresa">
            <br><br>
            <div style="background-color: white; padding:30px">
            <div style="width:639px; font-size:25px;">
                                    <br>
                                    <h4 style="font-weight: 900;color: #737679;">Buen día!</h4>
                                    <h6 style="color: #5f6368;font-weight: 200;">Se solicita aprobación el precio del producto con id: ' . $data[0]->id_clien_produc . '</h6>
                                </div>
                                <br><br>
                                <center>
                                <table style="background-color:#e8e8e8; width: auto; font-size:20px;" border="1">
                                        <tr style="font-size: 10px;">
                                        <th style="text-align: center; padding: 8px;">#</th>
                                        <th style="text-align: center; padding: 8px;">Código</th>
                                            <th style="text-align: center; padding: 8px;">Descripción </th>
                                            <th style="text-align: center; padding: 8px;">Moneda</th>
                                            <th style="text-align: center; padding: 8px;">Precio Venta</th>
                                            <th style="text-align: center; padding: 8px;">Cantidad Minima</th>
                                            <th style="text-align: center; padding: 8px;">Moneda Autoriza</th>
                                            <th style="text-align: center; padding: 8px;">Precio Autoriza</th>
                                            <th style="text-align: center; padding: 8px;">Asesor</th>
                                            </tr>
                                        <tr style="width: 100%; font-size: 17px;">
                                        <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->id_clien_produc . '</span></td>
                                        <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->codigo_producto . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->descripcion_productos . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->moneda . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->precio_venta . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->cantidad_minima . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->moneda_autoriza . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data[0]->precio_autorizado . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $user[0]->nombre . ' ' . $user[0]->apellido . '</span></td>
                                            </tr>
                                            </table>
                                            </center>
                                            <br><br>
                                            <div style="width:639px; font-size:25px;">
                                    <h6 style="color: #5f6368;font-weight: 200;">Cualquier duda comunicarse con el asesor .</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Correo: ' . $user[0]->correo . '</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Tel: ' . $user[0]->celular . '</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">' . NOMBRE_EMPRESA . '</h6>
                                </div>
                                </div>
                                <br><br>
                                </div>
                                </div>
                                </body>
                                
                                </html>';
        $remite = 'Verificación de Precio ' . $data[0]->nombre_empresa . ' Sidpa';
        $subject = 'Solicitud Verificación de Precio ' . $data[0]->nombre_empresa;
        return self::php_miler($html, $remite, $subject, $correo);
    }

    public static function correo_confimacion_pedido_client($data, $user, $cliente, $items)
    {
        if (empty($data[0]->orden_compra)) {
            $oc = 'No aplica.';
        } else {
            $oc = $data[0]->orden_compra;
        }
        $html = '
         <!DOCTYPE html>
        <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=" utf-8">
            </head>
            
            <body>
            <div style="width: 900px; justify-content: center;
            align-items: center;">
            <div style="background-color: #001c85; ">
            <br><br>
            <div style="width: 800px;  margin:0px auto;">
            <img src="' . IMG_CORREO . '" style="width: 300px;" alt="Logo_empresa">
            <br><br>
            <div style="background-color: white; padding:30px">
            <div style="width:639px; font-size:25px;">
                                    <br>
                                    <h4 style="font-weight: 900;color: #737679;">¡Buen día!</h4>
                                    <h6 style="color: #5f6368;font-weight: 200;">Informamos que el pedido N° ' . $data[0]->num_pedido . ' correspondiente a ' . $data[0]->nombre_empresa . ', fue creado correctamente en nuestro sistema,</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">bajo la orden de compra número ' . $oc . '</h6>
                                </div>
                                <br><br>
                                <center>                
                                <table style="width: 80%; border-spacing: 0;">
                                       
                                <tr style="background: #232f5d; color: white;">
                                    <th style="border: 0.5px solid; padding: 9px;">Cantidad</th>
                                    <th style="border: 0.5px solid; padding: 9px;">Descripción</th>
                                    <th style="border: 0.5px solid; padding: 9px;">Moneda</th>
                                    <th style="border: 0.5px solid; padding: 9px;">Precio</th>
                                    <th style="border: 0.5px solid; padding: 9px;">Total</th>
                                    <th style="border: 0.5px solid; padding: 9px;">IVA </th>
                                </tr>';
        /* agregar el iva */
        if ($data[0]->iva == 1) {
            $por_iva = "19%";
        } else {
            $por_iva = "0%";
        }
        /* agregar items */
        foreach ($items as $itms) {
            $html .= '
                                <tr style="">
                                <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . number_format($itms->Cant_solicitada, 0, ',', '.') . '</td>
                                <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . $itms->descripcion_productos . '</td>
                                <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . $itms->moneda . '</td>
                                <td style="padding: 7px;border-bottom: 1px solid #ddd;">$' . number_format($itms->v_unidad, 2, ',', '.') . '</td>
                                <td style="padding: 7px;border-bottom: 1px solid #ddd;">$' . number_format($itms->total, 2, ',', '.') . '</td>
                                <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . $por_iva . '</td>
                            </tr>';
        }
        $html .= '</table>
                                </center>
                                <br><br>
                                <div style="width:639px; font-size:25px;">
                                    <h6 style="color: #5f6368;font-weight: 200;">Cualquier duda comunicarse con el asesor .</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Correo: ' . $user[0]->correo . '</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Tel: ' . $user[0]->celular . '</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">' . NOMBRE_EMPRESA . '</h6>
                                </div>
                            </div>
                                <br><br>
                        </div>
                    </div>
                </body>
            </html>';
        $remite = 'Confirmación de pedido para ' . $data[0]->nombre_empresa . ' Sidpa';
        $subject = 'Se ha creado un nuevo pedido para ' . $data[0]->nombre_empresa;
        $cliente = 'mateorozotorres0420028@gmail.com';
        return self::php_miler($html, $remite, $subject, $cliente);
    }
    public static function correo_confimacion_pedido_asesor($data, $user, $asesor, $items)
    {
        if (empty($data[0]->orden_compra)) {
            $oc = '';
        } else {
            $oc = 'Para consultar el pdf correspondiente a la orden de compra ' . $data[0]->orden_compra . ' ingrese a la plataforma.';
        }
        $html = '
        <!DOCTYPE html>
        <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=" utf-8">
            </head>
            
            <body>
            <div style="width: 900px; justify-content: center;
            align-items: center;">
            <div style="background-color: #001c85; ">
            <br><br>
            <div style="width: 800px;  margin:0px auto;">
            <img src="' . IMG_CORREO . '" style="width: 300px;" alt="Logo_empresa">
            <br><br>
            <div style="background-color: white; padding:30px">
            <div style="width:639px; font-size:25px;">
                                    <br>
                                    <h4 style="font-weight: 900;color: #737679;">¡Buen día!</h4>
                                    <h6 style="color: #5f6368;font-weight: 500;">Se informa que el pedido N° ' . $data[0]->num_pedido . ' correspondiente a ' . $data[0]->nombre_empresa . ', presenta una novedad con los ítems. Le solicitamos verificar y comunicarse con la empresa.</h6>
                                    <h6 style="color: #5f6368;font-weight: 500;">' . $oc . '</h6>
                                </div>
                                <br><br>
                                <center>
                                  <table style="width: 80%; border-spacing: 0;">
                                       
					<tr style="background: #232f5d; color: white;">
						<th style="border: 0.5px solid; padding: 9px;">Cantidad</th>
						<th style="border: 0.5px solid; padding: 9px;">Descripción</th>
						<th style="border: 0.5px solid; padding: 9px;">Moneda</th>
						<th style="border: 0.5px solid; padding: 9px;">Precio</th>
						<th style="border: 0.5px solid; padding: 9px;">Total</th>
                        <th style="border: 0.5px solid; padding: 9px;">IVA </th>

					</tr>';
        /* agregar el iva */
        if ($data[0]->iva == 1) {
            $por_iva = "19%";
        } else {
            $por_iva = "0%";
        }
        /* agregar items */
        foreach ($items as $itms) {
            $html .= '
                    <tr style="">
                    <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . number_format($itms->Cant_solicitada, 0, ',', '.') . '</td>
                    <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . $itms->descripcion_productos . '</td>
                    <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . $itms->moneda . '</td>
                    <td style="padding: 7px;border-bottom: 1px solid #ddd;">$' . number_format($itms->v_unidad, 2, ',', '.') . '</td>
                    <td style="padding: 7px;border-bottom: 1px solid #ddd;">$' . number_format($itms->total, 2, ',', '.') . '</td>
                    <td style="padding: 7px;border-bottom: 1px solid #ddd;">' . $por_iva . '</td>

                </tr>';
        }
        $html .= '</table>
                                </center>
                                <br><br>
                                <div style="width:639px; font-size:25px;">
                                    <h6 style="color: #5f6368;font-weight: 200;">Cualquier duda comunicarse con el asesor .</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Correo: ' . $user[0]->correo . '</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Tel: ' . $user[0]->celular . '</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">' . NOMBRE_EMPRESA . '</h6>
                                </div>
                            </div>
                                <br><br>
                        </div>
                    </div>
                </body>
            </html>';
        $remite = 'Confirmación de pedido para ' . $data[0]->nombre_empresa . ' Sidpa';
        $subject = 'Se ha creado un nuevo pedido para ' . $data[0]->nombre_empresa;
        return self::php_miler($html, $remite, $subject, $asesor);
    }

    public static function correo_confirmacion_fecha_compromiso($data, $fecha, $cliente, $asesor)
    {

        if (empty($data[0]->orden_compra)) {
            $oc = '';
        } else {
            $oc = $data[0]->orden_compra;
        }
        $html = '
        <!DOCTYPE html>
        <html>
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=" utf-8">
            </head>
            
            <body>
            <div style="width: 900px; justify-content: center;
            align-items: center;">
            <div style="background-color: #001c85; ">
            <br><br>
            <div style="width: 800px;  margin:0px auto;">
            <img src="' . IMG_CORREO . '" style="width: 300px;" alt="Logo_empresa">
            <br><br>
            <div style="background-color: white; padding:30px">
            <div style="width:639px; font-size:25px;">
                                    <br>
                                    <h4 style="font-weight: 900;color: #737679;">Buen día !</h4>
                                    <h6 style="color: #5f6368;font-weight: 200;">Se informa que el pedido N° ' . $data[0]->num_pedido . ' correspondiente a ' . $data[0]->nombre_empresa . ',con orden de compra ' . $oc . 'ha sido programado para entrega el día ' . date('d/m/Y', strtotime($fecha)) . '.<br><br>Importante: Por favor tenga en cuenta que sí la fecha previamente indicada corresponde un sábado, domingo o festivo, la entrega será pospuesta al día hábil siguiente.</h6>
                                </div>
                                <br><br>
                                <center>
                                </center>
                                <br><br>
                                <div style="width:639px; font-size:25px;">
                                    <h6 style="color: #5f6368;font-weight: 200;">Ante cualquier duda, porfavor comuníquese con su asesor comercial.</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">' . NOMBRE_EMPRESA . '</h6>
                                </div>
                            </div>
                                <br><br>
                        </div>
                    </div>
                </body>
            </html>';
        $html .= "</b><br>
            <br>
            <br>
            Quemados atentos a sus comentarios.
            <br>
            <br>
            <br>
            ******ESTE CORREO ES AUTOMÁTICO, FAVOR NO RESPONDER ******
          </div>";
        $remite = 'Confirmación fecha entrega pedido' . $data[0]->num_pedido . ' Sidpa';
        $subject = 'Información fecha compromiso pedido N° ' . $data[0]->num_pedido;
        $correo = self::php_miler($html, $remite, $subject, $cliente, $asesor);
        return $correo;
    }

    public static function enviar_alistamiento_retenido($motivos, $orden, $cantidad)
    {
        $num_produccion = $orden['num_produccion'];
        $motivos1 = $motivos[0]['descripcion'];
        $correo = CORREO_PRODUCCION_GRAF;

        $body = "<div>
                    Buen día , <br>
                    Se informa del área de Producción/Alistamiento.<br> La orden de 
                    producción N°($num_produccion) no puede continuar el proceso de producción, debido a 
                    los siguientes motivos : <br><br><b>
                    ";
        foreach ($motivos as $motivo) {
            if ($motivo['id'] == 1) {
                $body .= '• ' . $motivo['descripcion'] . '<br>';
            }
            if ($motivo['id'] == 2) {
                $body .= '• ' . $motivo['descripcion'] . '<br>';
                if (isset($cantidad)) {
                    $body .= '► ' . $cantidad . '<br>';
                }
            }
            if ($motivo['id'] == 4) {
                $body .= '► ' . $motivo['descripcion'] . '<br>';
            }

            if ($motivo['id'] == 3) {
                $body .= '• ' . $motivo['descripcion'] . '<br>';
            }
            if ($motivo['id'] == 5) {
                $body .= '► ' . $motivo['descripcion'] . '<br>';
            }
            if ($motivo['id'] == 6) {
                $body .= '► ' . $motivo['descripcion'] . '<br>';
            }
        }
        $body .= "</b><br>
                  <br>
                  <br>
                  Quemados atentos a sus comentarios
                  <br>
                  <br>
                  <br>
                  ******ESTE CORREO ES AUTOMÁTICO, FAVOR NO RESPONDER ******
                </div>";
        //----------------------------------------------------------------------
        $subject = utf8_decode("PRODUCCION (Pre-prensa) " . $num_produccion);
        $remite = 'Retenido Pre-Prensa';

        return self::php_miler($body, $remite, $subject, $correo);
    }

    public static function correos_apertura_pqr($num_pqr, $redaccion, $correo)
    {

        $body = "<div>
                    Buen día , <br><br>
                    El proceso de Servicio al Cliente de " . NOMBRE_EMPRESA . ", le informa que se ha radicado en nuestro sistema una petición, queja o reclamo, referente a uno de los bienes o servicios que previamente le hemos suministrado. <br><br>

                    Tenga presente que cualquier consulta referente a este caso la podrá realizar con el número de radicado <span style='color: red;'>$num_pqr</span>. <br><br>
                    
                    A continuación se expone la información de entrada que hemos recibido, para generar el análisis del caso y plantear la acciones correctivas y de mejora a las que haya lugar:<br> 
                    $redaccion
                    <br><br>
                    ";
        $body .= "<br>
                  Cordialmente,
                  <br>
                  <br>
                  Equipo de servicio al cliente.
                  <br>
                  " . CORREO_SERV_CLIENTE . "
                  <br>
                  Tel.: " . TEL_EMPRESA . "
                  <br>
                  <br>
                  ******ESTE CORREO ES AUTOMÁTICO, FAVOR NO RESPONDER ******
                </div>";
        //----------------------------------------------------------------------
        $subject = "Notificación de radicado pqr " . $num_pqr;
        $remite = '' . NOMBRE_EMPRESA . ' servicio al cliente';
        return self::php_miler($body, $remite, $subject, $correo);
    }

    public static function correos_cierre_pqr($num_pqr, $archivo, $correo, $correo2)
    {

        $body = "<div>
                    Buen día, teniendo en cuenta las reclamaciones previamente presentadas a continuación adjuntamos respuesta formal con el analisis del caso y los planes de acción que se han ejecutado y/o están en ejecución con el fin de prevenir la reiteración de las fallas.
                    <br><br>
                    Cabe aclarar que los materiales retornados a nosotros como producto no conforme (si aplica) fueron destruidos y producidos nuevamente para entregar como reposición en su momento.
                    <br><br>
                    Quedamos atentos a cualquier comentario.
                    <br><br>
                    ";
        $body .= "<br>
                  Cordialmente,
                  <br><br>
                  Equipo de servicio al cliente.
                  <br>
                 " . CORREO_SERV_CLIENTE . "
                  <br>
                  Tel.: " . TEL_EMPRESA . "
                  <br><br>
                  ******ESTE CORREO ES AUTOMÁTICO, FAVOR NO RESPONDER ******
                </div>";
        //----------------------------------------------------------------------
        $subject = "Notificación de cierre pqr " . $num_pqr;
        $remite = '' . NOMBRE_EMPRESA . ' servicio al cliente';
        return self::php_miler($body, $remite, $subject, $correo, $correo2, $archivo);
    }

    public static function Solicitud_compras_soporte($correo, $data)
    {
        $html = '
        <!DOCTYPE html>
            <html>

            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=" utf-8">
            </head>

            <body>
                <div style="width: 900px; justify-content: center;
            align-items: center;">
                    <div style="background-color: #001c85; ">
                        <br><br>
                        <div style="width: 800px;  margin:0px auto;">
                            <img src="' . IMG_CORREO . '" style="width: 300px;" alt="Logo_empresa">
                            <br><br>
                            <div style="background-color: white; padding:30px">
                                <div style="width:639px; font-size:25px;">
                                    <br>
                                    <h4 style="font-weight: 900;color: #737679;">¡Buen día!</h4>
                                    <h6 style="color: #5f6368;font-weight: 200;">Se informa que el diagnostico No ' . $data['num_consecutivo'] . ' item ' . $data['item'] . 'del cliente ' . $data['nombre_empresa'] . ' estan solicitando la compra de:</h6>
                                </div>
                                <br><br>
                                <center>
                                    <table style="width: 100%;" border="1">
                                        <tr style="background-color:#0d1b50;color:white;font-size: 15px;">
                                            <th style="text-align: center; padding: 8px;">Código</th>
                                            <th style="text-align: center; padding: 8px;">Cantidad</th>
                                            <th style="text-align: center; padding: 8px;">Solicitado Por</th>
                                        </tr>
                                        <tr style="width: 100%; font-size: 17px;color:black">
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data['codigo_producto'] . '-' . $data['descripcion_productos'] . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">' . $data['cantidad'] . '</span></td>
                                            <td style="text-align: center; padding: 8px;"><span style="width: 100%; font-size: small;">Soporte Tecnico</span></td>
                                        </tr>
                                    </table>
                                </center>
                                <br><br>
                                <div style="width:639px; font-size:25px;">
                                    <h6 style="color: #5f6368;font-weight: 200;">Quedamos atentos a sus comentarios.</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Proceso Soporte Tecnico.</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">Tel: ' . TEL_EMPRESA . ' Ext 121</h6>
                                    <h6 style="color: #5f6368;font-weight: 200;">' . NOMBRE_EMPRESA . '</h6>
                                </div>
                            </div>
                            <br><br>
                        </div>
                    </div>
            </body>
            </html>';
        $remite = 'Solicitud de Compra Soporte tecnico Sidpa';
        $subject = "Solicitud Soporte Tecnico";
        return self::php_miler($html, $remite, $subject, $correo);
    }

    public static function correo_solicitud_logistica($num_pqr, $codigo_produc, $empresa, $direccion, $correo)
    {
        $body = "<div>
        Buen día,
        <br><br>
        Reciba un cordial saludo. Por medio de la presente, se solicita realizar la recolección de los siguientes productos:" . $codigo_produc . "
        Estos artículos corresponden a la <b>" . $num_pqr . "</b>, del cliente <b>" . $empresa . "</b> cuya ubicación es <b>" . $direccion . "</b>
        <br><br>
        Agradecemos de antemano su diligencia en este asunto.
        <br><br>
        Gracias por su atención.
        <br><br>
        ";
        $body .= "<br>
        Cordialmente,
        <br><br>
        Equipo de servicio al cliente.
        <br>
     " . CORREO_SERV_CLIENTE . "
      <br>
      Tel.: " . TEL_EMPRESA . "
      <br><br>
      ******ESTE CORREO ES AUTOMÁTICO, FAVOR NO RESPONDER ******
    </div>";
        //----------------------------------------------------------------------
        $subject = "Notificación para recolección de material PQR " . $num_pqr;
        $remite = '' . NOMBRE_EMPRESA . ' servicio al cliente';
        return self::php_miler($body, $remite, $subject, $correo, $correo2 = '');
    }
    public static function pruebas()
    {
        $correo = 'mateorozotorres0420028@gmail.com';
        $correo2 = 'desarrollo@acobarras.com';
        $body = "<img src='" . IMG_CORREO . "' style='width: 300px;' alt='Logo_empresa'>";
        $body .= "PRUEBAS<i style='width:30%;color:black' class='fas fa-helicopter'></i>";
        //----------------------------------------------------------------------
        $subject = "PRUEBAS";
        $remite = '' . NOMBRE_EMPRESA . ' servicio al cliente';
        return self::php_miler($body, $remite, $subject, $correo, $correo2);
    }

    public static function php_miler($html, $remite, $subject, $correo, $correo2 = "", $adjunto = "")
    {
        if (MODO_PRUEBA) {
            $correo = 'desarrollo@acobarras.com';
            $correo2 = '';
        }
        set_time_limit(2400);
        $mail = new PHPMailer(true);
        //Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP(); // Set mailer to use SMTP
        $mail->Host = HOST_CORREOS;
        $mail->SMTPAuth = true;
        $mail->Username = CORREO_ENTREGA;
        $mail->Password = CLAVE_CORREOS;
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;
        $remite = $remite;
        $mail->setFrom(CORREO_ENTREGA, $remite);
        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Timeout = 300;
        $mail->addAddress($correo);
        if ($correo2 != '') {
            $mail->addAddress($correo2);
        }
        if ($adjunto != '') {
            $mail->addAttachment($adjunto);
        }
        $mail->Subject =  utf8_decode($subject);
        $mail->Body = $html;
        if ($mail->send()) {
            $respuesta = [
                "state" => 1,
                "msg" => "exito"
            ];
        } else {
            $respuesta = [
                "state" => -1,
                "msg" => $mail->ErrorInfo
            ];
        }
        return $respuesta;
    }
}
