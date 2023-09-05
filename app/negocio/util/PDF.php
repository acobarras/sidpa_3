<?php

namespace MiApp\negocio\util;

// require 'vendor/dompdf/dompdf/src/Autoloader.php';
// require PUBLICO . '/librerias/vendor/dompdf/dompdf/src/Autoloader.php';
// require_once './app/vendor/phpqrcode/qrlib.php';



use Dompdf\Dompdf;
use Dompdf\Options;
// use Exception;

// use const CARPETA_PRINCIPAL;
use MiApp\negocio\util\Validacion;

/**
 * Description of pdfGenerar
 *
 * @author perso
 */
class PDF
{

    /**
     * 
     * @param type $persona
     * @param type $pedido
     * @param type $dire_entre
     * @param type $dire_radic
     * @param type $traz
     */
    public static function pdf_pedidos($persona, $pedido, $items, $dire_entre, $dire_radic)
    {

        $dias = '';
        if ($pedido['pertenece'] == 1) {
            $cabeza_pedido = "<img id='imgtitulo' src='" . CARPETA_IMG . PROYECTO . "/img_pdf/cabeza_pedido.jpg'>";
            $pie_pedido = "<img id='imgpie_formato' src='" . CARPETA_IMG . PROYECTO . "/img_pdf/pie_pedido.jpg'>";
        } elseif ($pedido['pertenece'] == 2) {
            $cabeza_pedido = '<img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/cabeza_pedido_col.jpg">';
            $pie_pedido = "";
        } else {
            $cabeza_pedido = "<img id='imgtitulo' src='" . CARPETA_IMG . PROYECTO . "/img_pdf/cuenta_cobro.jpg'>";
            $pie_pedido = "";
        }
        $forma_pago = FORMA_PAGO[$pedido['forma_pago']];
        if ($pedido['forma_pago'] == 4) {
            $numero_dias = $pedido['dias_dados'];
            $dias = DIAS_DADOS[$pedido['dias_dados']];
            $forma_pago = "Credito " . $dias;
        }
        // Validar la direccion
        $dir_entrega = $dire_entre->nombre_ciudad . " " . $dire_entre->direccion;
        $pais = '';
        if ($dire_entre->nombre_pais != 'COLOMBIA') {
            $pais = $dire_entre->nombre_pais;
            $dir_entrega = $pais . " " . $dire_entre->nombre_ciudad . " " . $dire_entre->direccion;
        }
        if ($dire_entre->nombre_departamento != 'BOGOTÁ D.C') {
            $departamento = $dire_entre->nombre_departamento;
            $dir_entrega = $pais . " " . $departamento . " " . $dire_entre->nombre_ciudad . " " . $dire_entre->direccion;
        }
        $dir_radica = $dire_radic->nombre_ciudad . " " . $dire_radic->direccion;
        if ($dire_radic->nombre_pais != 'COLOMBIA') {
            $pais = $dire_radic->nombre_pais;
            $dir_radica = $pais . " " . $dire_radic->nombre_ciudad . " " . $dire_radic->direccion;
        }
        if ($dire_radic->nombre_departamento != 'BOGOTÁ D.C') {
            $departamento = $dire_radic->nombre_departamento;
            $dir_radica = $pais . " " . $departamento . " " . $dire_radic->nombre_ciudad . " " . $dire_radic->direccion;
        }

        $documento = "<img id='imgpie_formato' src='" . CARPETA_IMG . PROYECTO . "/firmas/empleados/" . FIRMA_PEDIDO . ".png'>";

        // Introducimos HTML de prueba
        $html = "
    <html>
        <head>
		    <meta charset='utf-8'>
		    <title> pdf Pedido </title>
            <link rel='stylesheet' href='" . CARPETA_CSS . "/img_pdf/pdfstyle/stylepedidopdf.css'>
	    </head>
	<body>
		<div id='imgencabezadop'>
        $cabeza_pedido
		</div>
		<div id='imgenpiedepaginap'>
		$pie_pedido
		</div>
		<div id='firmacartera'>
		$documento
		</div>";
        $html .= "
        <div class='radicacion'>
            <ul>
                <li class='li1'><b>Fecha: </b><u>" . str_replace('-', '/', $pedido['fecha_crea_p']) . " " . $pedido['hora_crea'] . " </u></li>
                <li class='li2'><b>Fecha Radicación:</b><u>" . str_replace('-', '/', $pedido['fecha_crea_p']) . " " . $pedido['hora_crea'] . " </u></li>
                <li class='li2'><b>N° de radicado: </b><u>" . $pedido['num_pedido'] . "</u></li>
            </ul>
        	</div>
           <div class='posicion'>	
            <div class='fin_pedido1'>
                <table>
                    <tr>
                        <th style='width: 23%;'>Fecha solicitud (Tecnología)</th>
                        <td style='width: 24%;'></td>
                        <th style='width: 27%;'>Fecha compromiso de entrega</th>
                        <td style='width: 24%;'>" . str_replace('-', '/', $pedido['fecha_compromiso']) . "</td>
                    </tr>
                    <tr>
                        <th style='width: 25%;'>Fecha de alistamiento</th>
                        <td style='width: 24%;'></td>
                        <th style='width: 25%;'>Fecha de entrega</th>
                        <td style='width: 24%;'></td>
                    </tr>
                </table>
            </div>	
            <div class='fin_pedido2'>
                <table>
                    <tr>
                        <td style='width: 5%;'></td>
                        <th style='width: 20%;'>Firma autorización cartera</th>
                        <td style='width: 5%;'></td>
                        <th style='width: 20%;'>Despachado por</th>
                        <td style='width: 5%;'></td>
                        <th style='width: 20%;'>Facturado por</th>
                        <td style='width: 5%;'></td>
                    </tr>
                </table>
            </div>	
        </div>
        <div class='titulo_pedido'>
            <table>	
                <tr>
                    <th style='width: 17%;'>Razón social cliente:</th>
                    <td colspan='4' style='text-align: justify;' class='pinta'>" . $pedido['nombre_empresa'] . "</td>
                    <th style='width: 17%;'>Asesor comercial:</th>
                    <td colspan='1' class='pinta'>" . $persona->nombres . " " . $persona->apellidos . "</td>
                </tr>
                <tr>
                    <th>Orden de compra:</th>
                    <td colspan='2' class='pinta'>" . $pedido['orden_compra'] . "</td>
                    <th>Nit:</th>
                    <td style='width: 12%;' class='pinta'>" . $pedido['nit'] . "</td>					
                    <th>Fecha cierre:</th>
                    <td class='pinta'>" . str_replace("-", "/", $pedido['fecha_cierre']) . "</td>
                </tr>
                <tr>
                    <th>Dirección entrega:</th>
                    <td colspan='4' class='pinta'>" . $dir_entrega . "</td>
                    <th>Dir. radicación:</th>
                    <td class='pinta'>" . $dir_radica . "</td>
                </tr>
                <tr>
                    <th>Nombre contacto:</th>
                    <td colspan='2' class='pinta'>" . $dire_entre->contacto . "</td>
                    <th>Cargo:</th>
                    <td class='pinta'>" . $dire_entre->cargo . "</td>					
                    <th>Email:</th>
                    <td class='pinta'>" . $dire_entre->email . "</td>					
                </tr>
                <tr>
                    <th>Celular:</th>
                    <td colspan='2' class='pinta'>" . $dire_entre->celular . "</td>
                    <th style='width: 7%;'>Tel.fijo:</th>
                    <td class='pinta'>" . $dire_entre->telefono . "</td>					
                    <th>Horario recepción:</th>
                    <td class='pinta'>" . $dire_entre->horario . "</td>					
                </tr>
                <tr>
                    <th>Condición pago:</th>
                    <td style='width: 17%;' class='pinta'>" . $forma_pago . "</td>
                    <th style='width: 12%;'>Parcial:</th>
                    ";
        if ($pedido['parcial'] == 1) {
            $radiosi = 'checked';
            $radiono = '';
        } else {
            $radiosi = '';
            $radiono = 'checked';
        }
        $html .= "
                    <td style='width: 9%;' colspan='2' class='pinta'>
                        <label style='margin-left: 20px;' for='parcial_si'>Si</label><input type='radio' style='width: 20%;' id='parcial_si' value='1' $radiosi />
                        <label style='margin-left: 20px;' for='parcial_no'>No</label><input type='radio' style='width: 20%;' id='parcial_no' value='2' $radiono />
                    </td>";
        $visto = array(
            '' => '',
            '0' => '',
            '1' => 'checked',
        );
        $difer_mas = $visto[$pedido['difer_mas']];
        $difer_menos = $visto[$pedido['difer_menos']];
        $difer_ext = $visto[$pedido['difer_ext']];
        $html .= "
                        <th>Diferencia: <input type='text' style='width: 25%; font-weight: none; font-size: 11px; background: rgb(208, 208, 208,0.5);' value='" . $pedido['porcentaje'] . " %'/></th>
                        <td style='width: 31%;' class='pinta'>
                            <label style='width: 0%;' for='difer_mas'>Mas</label><input type='checkbox' style='width: 10%;' $difer_mas id='difer_mas'/>
                            <label style='width: 0%;' for='difer_menos'>Menos</label><input type='checkbox' style='width: 10%;' $difer_menos id='difer_menos' />
                            <label style='width: 0%;' for='difer_ext'>Exacto</label><input type='checkbox' style='width: 11%;' $difer_ext id='difer_ext' />
                        </td>";
        $html .= "
        	    </tr>
            </table>
        </div>
        <div class='cabeza_pedido'>
        	<div style='text-align: center;'>PRODUCTOS</div>
        </div>
        <div class='cuerpo_pedido'>
        	<table>
        		<tr>
        			<th style='width: 3%;'>No. Item</th>
        			<th style='width: 10%;'>Código</th>
        			<th style='width: 5%;'>Cant.</th>
        			<th style='width: 27%;'>Descripción</th>
        			<th style='width: 4%;'>Ficha Tec N°</th>
        			<th style='width: 3%;'>Ruta emb</th>
        			<th style='width: 3%;'>Core</th>
        			<th style='width: 4%;'>Roll. paq X</th>
        			<th style='width: 4%;'>TRM</th>
        			<th style='width: 3%;'>Moneda</th>
        			<th style='width: 4%;'>V.Unitario</th>
        			<th style='width: 10%;'>Valor Total</th>
        		</tr>";
        /* agregar items al pdf */
        $suma = 0;
        $contador = 0;
        for ($i = 0; $i < count($items); $i++) {
            /* validar que no lleguen vacios */
            $cant_x = ($items[$i]->cant_x == '') ? "" : number_format($items[$i]->cant_x, 0, ',', '.');
            $trm = (trim($items[$i]->trm) == '' || $items[$i]->trm == 0.00) ? "N/A" : number_format($items[$i]->trm, 2, ',', '.');
            $v_unidad = (trim($items[$i]->v_unidad) == '') ? "N/A" : number_format($items[$i]->v_unidad, 2, ',', '.');
            $total = (trim($items[$i]->total) == '') ? "" : number_format($items[$i]->total, 2, ',', '.');

            $html .= '<tr>';
            $html .= '<td>' . $items[$i]->item . '</td>';
            $html .= '<td>' . $items[$i]->codigo . '</td>';
            $html .= '<td>' . number_format($items[$i]->Cant_solicitada, 0, ',', '.') . '</td>';
            $html .= '<td style="text-align:justify;padding: 0 3px;">' . $items[$i]->descripcion_productos . '</td>';
            $html .= '<td>' . $items[$i]->ficha_tecnica . '</td>';
            $html .= '<td>' . $items[$i]->nombre_r_embobinado . '</td>';
            $html .= '<td>' . $items[$i]->nombre_core . '</td>';
            $html .= '<td>' . $cant_x . '</td>';
            $html .= '<td>' . $trm . '</td>';
            $html .= '<td>' . $items[$i]->moneda . '</td>';
            $html .= '<td>' . number_format($items[$i]->v_unidad, 2, ',', '.') . '</td>';
            $html .= '<td>' . number_format($items[$i]->total, 2, ',', '.') . '</td>';
            $html .= '</tr>';
            $suma += $items[$i]->total;
            $contador++;
        }
        $iva = ($pedido['iva'] == 1) ? $suma * 0.19 : 0;

        $total_final = $suma + $iva;
        for ($j = $contador; $j < 17; $j++) {
            $html .= "
                    <tr>
                        <td></td>
                        <td></td>
                        <td class='descrip'></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>";
        }
        $html .= "
        	</table>
        </div>
        <br>
        <div class='fin_pedido'>
        	<table>
        		<tr>
        			<th style='width: 60%;'>OBSERVACIONES</th>
        			<th style='width: 10.9%;'>SUB TOTAL</th>
        			<td style='width: 7.4%;'>" . number_format($suma, 2, ',', '.') . "</td>
        		</tr>
        		<tr>
        			<td rowspan='3'>" . $pedido['observaciones'] . "</td>
        			<th>IVA 19%</th>
        			<td>" . number_format($iva, 2, ',', '.') . "</td>
        		</tr>
        		<tr>
        			<th>FLETES</th>
        			<td></td>
        		</tr>
        		<tr>
        			<th>TOTAL</th>
        			<td>" . number_format($total_final, 2, ',', '.') . "</td>
        		</tr>
        	</table>
        </div>	
        </body></html>";

        // Opciones de dompdf
        $options = new Options();
        $options->setIsHtml5ParserEnabled(true);
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO); //ESTA ES LA CARPETA GLOBAL PARA QUE ENTRE A LOS ESTILOS Y A LAS IMAGENES
        // Ejecucion de dompdf con la variable de obciones
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait'); // (Opcional) Configurar papel y orientación
        $dompdf->render(); // Generar el PDF desde contenido HTML
        // $pdf = $dompdf->output(); // Obtener el PDF generado
        $dompdf->stream('', array("Attachment" => true)); // Enviar el PDF generado al navegador
    }

    /**
     * Funcion para generar pdf de numero de produccion agrupada y individual 
     */
    public static function pdf_num_produccion($cabecera, $items)
    {
        // $pie_pedido = "<img id='imgpie_formato' src='" . CARPETA_IMG . "/img_pdf/pie_pedido.jpg'>";

        $html = "<html>
            <head>
		        <title>pdf Pedido</title>
		        <link type='text/css' rel='stylesheet' href='" . CARPETA_CSS . "/img_pdf/pdfstyle/pdfnum_produccion.css'>
            </head>
            <body>
            <div id='imgencabezadop'>
                <img id='imgtitulo' src='" . CARPETA_IMG . PROYECTO . "/img_pdf/cabezote_op.jpg'/>    
            </div>
            <div id='imgenpiedepaginap'>
            </div>
            <div class='cabeza_op'>
                <table align='center' border='1' height='60' width='100%' cellpadding='0' cellspacing='0'>
                    <thead>
                        <tr class='text-center'>
                            <th class='th_medio '>Fecha compromiso</th>
                            <th class='th_medio '>Fecha O.P.</th>
                            <th class='th_medio '>O.P. N°</th>
                            <th rowspan='2' class='th_medio'> <img src='" . $cabecera['qr_produccion'] . "' width='40' height='40' ></th>
                            <th >Salida N°</th>
                            <th>Entrada N°</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr class='text-center'>
                        <td class='ptl-5'>" . $cabecera['fecha_compromiso_global'] . "</td>
                        <td>" . $cabecera['fecha_op'] . "</td>
                        <td>" . $cabecera['num_produccion'] . "</td>
                        <td></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table >
                <div class='mt'></div>
                <table align='center' border='1' height='30' width='100%' cellpadding='0' cellspacing='0' >
                    <tr class='text-center '>
                        <td  style='width: 10mm;' class='ptl-5'>Tamaño Etiqueta:</td>
                        <td  style='width: 16.8mm;'>" . $cabecera['tamano_etiqueta'] . "</td>
                        <td  style='width: 1mm; border: 1px solid white; color: white;'> </td>
                        <td  style='width: 10mm;'>Magnetico:</td>
                        <td  style='width: 19.6mm;'>" . $cabecera['magnetico'] . "</td>
                        <td  style='width: 1mm; border: white 1px solid; color: white;'> </td>
                        <td  style='width: 5mm;'>Avance:</td>
                        <td  style='width: 5mm;'>" . $cabecera['avance'] . "</td>
                    </tr>           
                </table> 
                <div class='mt'></div>
                <table align='center' border='1' height='50' width='100%' cellpadding='0' cellspacing='0'>
                    <tr class='text-center '>
                        <td  rowspan='2' style='width: 19mm;' class=''>Cód Material:</td>
                        <td  rowspan='2' style='width: 19.8mm;'>" . $cabecera['codigo_material'] . "</td>
                        <td  style='width: 2mm; border: 1px solid white; color: white;'></td>
                        <td  style='width: 20.5mm;'>Ancho:</td>
                        <td  style='width: 26.5mm;'>" . $cabecera['ancho_material'] . "</td>
                        <td  style='width: 1mm; border: white 1px solid; color: white;'> </td>
                        <td  rowspan='2' style='width: 5mm;'>Cantidad:</td>
                        <td  rowspan='2' style='width: 4mm;'>" . number_format($cabecera['cant_op'], 0, ',', '.') . "</td>                        
                    </tr>
                    <tr class='text-center '>
                        <td  style='width: 1mm; border: 1px solid white; color: white;'> </td>
                        <td  style='width: 10mm;'>m²:</td>
                        <td  style='width: 15mm;'>" . $cabecera['m2_total'] . "</td>
                        <td  style='width: 1mm; border: 1px solid white; color: white;'> </td>                                                              
                    </tr>
                </table>
                <div class='mt'></div>
                 <table>
                    <tr>
                        <th style='width: 91mm; text-align: left; border: white 1px solid;'>Se firma a continuación en constancia de recepción de los materiales necesarios y la verificación de la información:</th>
                        <th style='width: 35mm; border: white 1px solid; border-bottom: black 1px solid; color: white;'>5</th>
                        <th style='width: 15mm; border: white 1px solid;'>Cargo:</th>
                        <th style='width: 35mm; border: white 1px solid; border-bottom: black 1px solid; color: white;'>5</th>
                    </tr>
                </table>
                <div class='mt'></div>
                <table align='center' border='1' height='50' width='100%' cellpadding='0' cellspacing='0'>
                    <tr>
                        <th  class='obs'>Obs:</th>
                    </tr>
                </table>
                <div class='mt'></div>
                <table  align='center' height='50' width='100%' cellpadding='0' cellspacing='0'>
                    <tr>
                        <th style='width: 28mm;' >Cód. Material:</th>
                        <th style='width: 10mm;' class='linea'></th>
                        <th style='width: 22mm;' >Proveedor:</th>
                        <th style='width: 5mm;' class='linea'></th>
                        <th style='width: 32mm;' >Ancho Recibido:</th>
                        <th style='width: 5mm;' class='linea'></th>
                        <th style='width: 32mm;' >Ancho Utilizado:</th>
                        <th style='width: 5mm;' class='linea'></th>
                    </tr>
                </table>
                
                <div class='mt-5'></div>
                <h4 class='text-center'>INFORMACION TROQUELADO</h4>
                <div class='mt-5'></div>
                <div class=''>
                <table align='center' height='50' border='1' width='100%' cellpadding='0' cellspacing='0' style='margin-bottom:30px;'>                  
                        <tr class='text-center'>
                            <th  style='width: 0mm;' ></th>
                            <th  style='width: 0mm;' >Cód.Etiqueta</th>
                            <th style='width: 63mm;' >Descripción</th>
                            <th style='width: 0mm;' >Ubicación</th>
                            <th style='width: 0mm;' >Cantidad</th>
                            <th style='width: 0mm;' >mL</th>
                            <th style='width: 0mm;' >m²</th>
                            <th style='width: 0mm;' >Operario</th>
                            <th style='width: 0mm; border: 1px solid white; color: white;'> </th>

                        </tr>
                   
                ";
        $contador = 0;
        $contador2 = 0;
        $margen = '';
        $saltopag = '';
        for ($i = 0; $i < count($items); $i++) {
            if ($items[$i]['ficha_tecnica_produc'] == '') {
                $ficha_tecnica = $items[$i]['ficha_tecnica'];
            } else {
                $ficha_tecnica = $items[$i]['ficha_tecnica_produc'];
            }
            $metros_lineales = ($items[$i]['cant_op'] * $items[$i]['avance']) / ($items[$i]['cav_montaje'] * 1000);
            $metros_cuadrados = ($metros_lineales * $items[$i]['ancho_material']) / 1000;
            $html .= "  <tr class='text-center letra-10'>
                            <td style='width: 0mm;'class='mt-10 letra-12' > 
                                <img src='" . $items[$i]['QR_item_codigo'] . "' width='35' height='35' >
                            </td>
                            <td style='width: 5mm;'>" . $items[$i]['codigo'] . "</td>
                            <td style='width: 10mm;' >" . $items[$i]['descripcion_productos'] . "</td>
                            <td style='width: 10mm;' >" . $items[$i]['ubi_troquel'] . "||" . $ficha_tecnica . "</td>
                            <td style='width: 10mm;' >" . number_format($items[$i]['cant_op'], 0, ',', '.') . "</td>
                            <td style='width: 10mm;' ><b>" . round($metros_lineales) . "</b></td>
                            <td style='width: 10mm;' ><b>" . number_format($metros_cuadrados, 2, ',', '.') . "</b></td>
                            <td style='width: 10mm;margin:0 15px;' ></td>
                            <td style='width: 10mm;' class='td-white' ></td>
                        </tr>";
            $contador++;
            if ($contador > 7) {
                $saltopag = " <div class='salto-pag'></div>";
                $margen = "margin-top:36px;";
            }
            if ($contador > 15) {
                $saltopag = " <div class='salto-pag'></div>";
                $margen = "margin-top:36px;";
                break;
            }
        }

        //tabla dos embobinado
        $html .= "
                </table>
                    $saltopag
                     <div style='$margen'>
                       <div style='$margen'>
                       <h4 class='text-center'>INFORMACION EMBOBINADO</h4>
                        <table align='center' height='50' border='1' width='100%' cellpadding='0' cellspacing='0'>
                            <tr class='text-center letra-12' >
                            <th  style='width: 0mm;'></th>
                            <th  style='width: 0mm;'>Pedido ítem</th>
                            <th style='width: 0mm;'>Cantidad</th>
                            <th style='width: 0mm;'>Core</th>
                            <th style='width: 0mm;'>Rollo X/paq</th>
                            <th style='width: 0mm;'>Ml Rollo</th>
                            <th style='width: 0mm;'>Sen/Emb</th>
                            <th style='width: 0mm;'>Tolerancia</th>
                            <th style='width: 20mm;'>Q.emb OP1</th>
                            <th style='width: 20mm;'>Q.emb OP2</th>
                            <th style='width: 20mm;'>Q.emb OP3</th>
                            <th style='width: 15mm;'>Total</th>
                            <th style='width: 0mm;' class='td-white'> </th>
                        </tr>";
        for ($i = 0; $i < count($items); $i++) {
            $html .= "<tr class='text-center' style='font-size:9.5px;'>
                            <td style='width: 0mm;'class='mt-10 letra-10' > 
                              <img src='" . $items[$i]['QR_item_codigo'] . "' width='35' height='35' >
                            </td>
                            <td >" . $items[$i]['num_pedido'] . "-" . $items[$i]['item'] . "</td>
                            <td>" . $items[$i]['cant_op'] . "</td>
                            <td >" . $items[$i]['nombre_core'] . "</td>
                            <td>" . $items[$i]['cant_x'] . "</td>
                            <td>" . $items[$i]['ml_item'] . "</td>
                            <td>" . $items[$i]['nombre_r_embobinado'] . "</td>";
            //---------------------------------------------------------------------------
            if ($items[$i]['difer_ext'] == "1") {
                $ext = 'Ext';
            } else {
                $ext = '';
            }
            if ($items[$i]['difer_mas'] == "1") {
                $mas = '+';
            } else {
                $mas = '';
            }
            if ($items[$i]['difer_menos'] == "1") {
                $menos = '-';
            } else {
                $menos = '';
            }
            //---------------------------------------------------------------------------
            $html .= "<td>" . $items[$i]['porcentaje'] . " " . $ext . $mas . " " . $menos . "</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class='td-white' ></td>
                        </tr>";
            $contador2++;
            if ($contador2 > 15) {
                break;
            }
        }

        $html .= " </table>
                        </div>
                    </div>
                    
                 </div>
                  <hr class='mt'>
            </div>
            </div>";

        // Opciones de dompdf
        $options = new Options();
        // $options->setIsHtml5ParserEnabled(true);
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO);
        // Ejecucion de dompdf con la variable de obciones
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait'); // (Opcional) Configurar papel y orientación
        $dompdf->render(); // Generar el PDF desde contenido HTML
        // $pdf = $dompdf->output(); // Obtener el PDF generado
        $dompdf->stream('', array("Attachment" => true)); // Enviar el PDF generado al navegador
    }


    public static function cuentaCobroPdf($cabecera, $items)
    {
        $dia_letra = date('l');
        $mes_letra = date('F');
        // $f = new \NumberFormatter("es", \NumberFormatter::SPELLOUT);
        $html = '<html>
            <head>
    		    <title>pdf Factura</title>
    		    <link rel="stylesheet" type="text/css" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/style_cc.css" />
            </head>
            <body>
                <div id="imgenpiedepaginap">
                    <div class="primera_parte">
                        <table>
                            <tr>
                                <th colspan="3">Recibido Por</th>
                            </tr>
                            <tr>
                                <td colspan="3" class="trasparente">5</td>
                            </tr>
                        </table>
                    </div>
                </div>	    
                <div class="primera_parte">
                    <div class="titulo_cc">Cota, Cundinamarca; ' . strtolower(DIAS_ESP[$dia_letra]) . ' ' . date('d') . ' de ' . strtolower(MES_ESP[$mes_letra]) . ' de ' . date('Y') . '.<span class="m-l">' . $cabecera['numero_lista_empaque'] . '</span></div>
                </div>
                <div class="primera_parte1">
                    <div>
                        <h2>CUENTA COBRO No ' . $cabecera['numero_doc_relacionado'] . '</h2>
                    </div>
                    <div>
                        <h4>' . strtoupper($cabecera['cliente']) . '</h4>
                    </div>
                    <div>
                        <h4>SON : ' . strtoupper(number_format($cabecera['total_documento'], 2, ',', '.')) . ' PESOS M/CTE</h4>
                    </div>
    		    </div>
                <div class="primera_parte2">
                    <div>
                        <h5>Concepto: </h5>
                    </div>
                </div>
    		    <div class="segunda_parte">
    			    <table>
    				    <tr>
                            <th class="borde-right-1" style="width: 10mm">Item</th>
                            <th style="width: 100mm;">Descripción</th>
                            <th class="borde-right-1" style="width: 20mm">Cantidad</th>
                            <th class="borde-right-1" style="width: 20mm">V. unidad</th>
                            <th class="borde-right-1" style="width: 30mm">V. Total</th>
    				    </tr>
    				    <tr>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1"></td>	
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                        </tr>';
        $cuenta = 0;
        for ($i = 0; $i < count($items); $i++) {
            $cuenta = $cuenta + 1;
            $cantidad_codigo = $items[$i]['cantidad_por_facturar'];
            if ($items[$i]['moneda'] == 'Dolar') {
                $valor_dolar = $items[$i]['v_unidad'] * $items[$i]['trm']; // se saca el valor del producto de dolares a pesos
                $valor_item_total = $cantidad_codigo * $valor_dolar;
            } else {
                $valor_item_total = $cantidad_codigo * $items[$i]['v_unidad'];
            }
            $html .= '			
                        <tr>
                            <td class="borde-right-1 text-center">' . ($i + 1) . '</td>
                            <td class="borde-right-1">' . strtoupper($items[$i]['descripcion_productos']) . '</td>
                            <td class="borde-right-1 text-center">' . number_format($cantidad_codigo, 0, ',', '.') . '</td>
                            <td class="borde-right-1 text-center">$ ' . number_format($items[$i]['v_unidad'], 2, ',', '.') . '</td>
                            <td class="borde-right-1 text-center">$ ' . number_format($valor_item_total, 2, ',', '.') . '</td>
                        </tr>
                        ';
        }
        for ($j = $cuenta; $j < 10; $j++) {
            $html .= '			
                            <tr>
                                <td class="borde-right-1 text-center">&nbsp;</td>
                                <td class="borde-right-1">&nbsp;</td>	
                                <td class="borde-right-1 text-center">&nbsp;</td>
                                <td class="borde-right-1 text-center">&nbsp;</td>
                                <td class="borde-right-1 text-center">&nbsp;</td>
                            </tr>';
        }
        $html .= '<tfoot>
                        <tr>
                            <td class="borde-right-1 text-center">&nbsp;</td>
                            <td colspan="3" class="borde-right-1">Total</td>	
                            <td class="borde-right-1 text-center">' . number_format($cabecera['total_documento'], 2, ',', '.') . '</td>
                        </tr>
                </tfoot>';

        $html .= ' </table>
                </div>
            </body>
        </html>';
        // Opciones de dompdf
        $options = new Options();
        // $options->setIsHtml5ParserEnabled(true);
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO);
        // Ejecucion de dompdf con la variable de obciones
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait'); // (Opcional) Configurar papel y orientación
        $dompdf->render(); // Generar el PDF desde contenido HTML
        // $pdf = $dompdf->output(); // Obtener el PDF generado
        $dompdf->stream('', array("Attachment" => true)); // Enviar el PDF generado al navegador
        // return $html;
    }

    public static function listaEmpaquePdf($cabecera, $items)
    {
        if ($cabecera['tipo_documento'] == 8 || $cabecera['tipo_documento'] == 11 || $cabecera['tipo_documento'] == 6) {
            $cabeza = "/cabeza_remision_acosas";
        } else {
            $cabeza = "/cabeza_remision_acocol";
        }
        $html = '
        <html>
            <head>
                <title>pdf Factura</title>
                <link rel="stylesheet" type="text/css" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/style_factura.css" />
            </head>
            <body>
                <header>
                    <img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_lista_empaque' . $cabeza . '.jpg">
                    <br>
                    <div class="numero">
                        <h5> N°' . $cabecera['numero_lista_empaque'] . '</h5>
                    </div>
                </header>
                <footer>
                    <table>
                        <tr>
                            <th>Realizado Por</th>
                            <th>Despachado Por</th>
                            <th>Recibido Por</th>
                        </tr>
                        <tr>
                            <td>' . strtoupper($cabecera['usuario_facturacion']) . '</td>
                            <td class="trasparente">5</td>
                            <td class="trasparente">5</td>
                        </tr>
                    </table>
                </footer> 
                <div class="contenido">
                    <div class="primera_parte">
                        <table>
                            <tr>
                                <th>Fecha Elaboración:</th>
                                <td colspan="3">' . $cabecera['fecha_elaboracion'] . '</td>
                                <th>N° Documento:</th>
                                <td colspan="1">' . $cabecera['numero_lista_empaque'] . '</td>
                            </tr>
                            <tr>
                                <th>Cliente:</th>
                                <td colspan="5">' . strtoupper($cabecera['cliente']) . '</td>
                            </tr>
                            <tr>
                                <th>Orden de Compra:</th>
                                <td>' . $cabecera['orden_compra'] . '</td>
                                <th>Pais:</th>
                                <td>' . strtoupper($cabecera['pais']) . '</td>
                                <th>Departamento:</th>
                                <td>' . strtoupper($cabecera['departamento']) . '</td>
                            </tr>
                            <tr>
                                <th>Ciudad:</th>
                                <td colspan="1">' . strtoupper($cabecera['ciudad']) . '</td>
                                <th>Dirección:</th>
                                <td colspan="3">' . strtoupper($cabecera['direccion']) . '</td>
                            </tr>
                            <tr>
                                <th>Numero Pedido:</th>
                                <td colspan="3">' . $cabecera['numero_pedido'] . '</td>
                                <th>Doc.Relacionado:</th>
                                <td colspan="1">' . $cabecera['numero_doc_relacionado'] . '</td>
                            </tr>
                        </table>
                    </div>
                    <div class="segunda_parte">
                        <table>
                            <tr>
                            <th class="borde-right-1" style="width: 20mm">Qr</th>
                            <th class="borde-right-1" style="width: 20mm">Codigo</th>
                            <th class="borde-right-1" style="width: 20mm">Cantidad</th>
                            <th style="width: 100mm;">Descripción</th>
                        </tr>
                        <tr>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1"></td>	
                        </tr>';
        $contador = 0;
        $cantidad_inicial = 11;
        for ($i = 0; $i < count($items); $i++) {
            if ($contador == $cantidad_inicial) {
                $cantidad_inicial = 15;
                $contador = 0;
                $html .= '</table>
                <hr>
                <table>';
            }
            $contador = $contador + 1;
            $codigo = $items[$i]['codigo'];
            $caracter = "*";
            $valor = str_replace(".", ",", $items[$i]['v_unidad']);
            $cantidad_codigo = $items[$i]['cantidad_por_facturar'];
            $demo = $codigo . $caracter . $cantidad_codigo . $caracter . $valor;
            $nombre = "item" . $i;
            Validacion::GeneraQR($demo, $nombre);

            $html .= '			
                <tr>
                    <td class="borde-right-1 text-center" style="width: 20mm"><img src="' . CARPETA_IMG . PROYECTO . '/img_qr/QR/item' . $i . '.png" /></td>
                    <td class="borde-right-1 text-center" style="width: 20mm">' . $codigo . '</td>
                    <td class="borde-right-1 text-center" style="width: 20mm">' . $cantidad_codigo . '</td>
                    <td class="borde-right-1" style="width: 100mm">' . strtoupper($items[$i]['descripcion_productos']) . '</td>
                </tr>';
        }
        $html .= '
                    </div>
                </div>
            </body>
        </html>';

        // Opciones de dompdf
        $options = new Options();
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO);
        // Ejecucion de dompdf con la variable de obciones
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait'); // (Opcional) Configurar papel y orientación
        $dompdf->render(); // Generar el PDF desde contenido HTML
        // $pdf = $dompdf->output(); // Obtener el PDF generado
        $dompdf->stream('', array("Attachment" => true)); // Enviar el PDF generado al navegador
        $files = glob(CARPETA_IMG . PROYECTO . '/img_qr/QR/*'); //obtenemos todos los nombres de los ficheros
        foreach ($files as $file) {
            if (is_file($file))
                unlink($file); //elimino el fichero
        }
    }

    public static function memorando_interno_entrega($items, $data, $documento)
    {
        $html = '<html>
            <head>
    		    <title>pdf Memorando Interno Entrega</title>
    		    <link rel="stylesheet" type="text/css" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/pdfmemorando_interno.css" />
            </head>
            <body>
            <div class="container">
                <div id="imgencabezadop">
                    <img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/Cabezote_Memorando_Interno.png" />
                </div>

                <table align="center" border="1" width="100%" cellspacing="0">
                    <tr>
                        <td style="width:300px; font-weight: bold;" align="center">NO. DOCUMENTO ' . $documento . '</td>
                        <td style="width:200px;  font-weight: bold;" align="center">FECHA</td>
                        <td style="width:200px" align="center">' . date('y-m-d h:i:s') . '</td>
                    </tr>
                </table>
                <br>
                <table align="center"border="1" width="100%" cellspacing="0">
                <tr>
                    <td style="width:250px; font-weight: bold;" align="center">NOMBRE</td>
                    <td COLSPAN=6 style="width:400px" align="center">' . $data['nombre'] . '</td>
                </tr>
                <tr>
                    <td style="width:100px; font-weight: bold;" align="center">AREA</td>
                    <td COLSPAN=6 style="width:100px" align="center">' . $data['area'] . '</td>
                </tr>
            </table>
                <br>
                <table align="center"border="1" width="100%" cellspacing="0">
                    <tr>
                        <td style="width:80px;" align="center">CODIGO</td>
                        <td style="width:80px;" align="center">CANTIDAD</td>
                        <td style="width:80px;" align="center">DESCRIPCCIÓN</td>
                        <td style="width:80px;" align="center">CAV</td>
                        <td style="width:80px;" align="center">COR</td>
                        <td style="width:80px;" align="center">ROLLOS X</td>
                        <td COLSPAN=2 style="width:80px;" align="center">UBICACION</td>
                    </tr>';
        $contador = 0;
        for ($i = 0; $i < count($items); $i++) {
            $html .= '<tr>
                <td style="width:80px;" align="center">' . $items[$i]['codigo_producto'] . '</td>
                <td style="width:80px;" align="center">' . $items[$i]['salida'] . '</td>
                <td style="width:80px; font-size:12px;" align="center">' . $items[$i]['descripcion'] . '</td>
                <td style="width:80px;" align="center">' . $items[$i]['cav'] . '</td>
                <td style="width:80px;" align="center">' . $items[$i]['cor'] . '</td>
                <td style="width:80px;" align="center">' . $items[$i]['rollos_x'] . '</td>
                <td COLSPAN=2 style="width:80px;" align="center">' . $items[$i]['ubicacion'] . '</td>
            </tr>';
            $contador++;
        }
        for ($j = $contador; $j < 5; $j++) {
            $html .= "
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td COLSPAN=2>&nbsp;</td>
                    </tr>";
        }
        $html .= '  <tr>
                        <td COLSPAN=6 ROWSPAN=2 align="center">OBSERVACIONES: &nbsp;' . $data['obseveciones'] . '</td>
                        <td align="center"></td>
                        <td align="center"></td>
                    </tr>
                    <tr>
                        <td align="center"></td>
                        <td align="center"></td>
                    </tr>
                    <tr>
                        <td style="height: 15mm; color: #ece8e8;" COLSPAN=3 align="center">&nbsp;</td>
                        <td style="height: 15mm; color: #ece8e8;" COLSPAN=2 align="center">&nbsp;</td>
                        <td style="height: 15mm; color: #ece8e8;" COLSPAN=3 align="center">&nbsp;</td>
                    </tr>
                    <tr>
                        <td COLSPAN=3 align="center" style="border: none;">FIRMA SOLICITANTE</td>
                        <td COLSPAN=2 align="center" style="border: none;">FIRMA AUTORIZACIÓN</td>
                        <td COLSPAN=3 align="center" style="border: none;">FIRMA DESPACHO</td>
                    </tr>
                </table>
                <br>
                </div>
            </body>
        </html>';

        // Opciones de dompdf
        $options = new Options();
        // $options->setIsHtml5ParserEnabled(true);
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO);
        // Ejecucion de dompdf con la variable de obciones
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait'); // (Opcional) Configurar papel y orientación
        $dompdf->render(); // Generar el PDF desde contenido HTML
        // $pdf = $dompdf->output(); // Obtener el PDF generado
        $dompdf->stream('', array("Attachment" => true)); // Enviar el PDF generado al navegador

    }

    public static function respuesta_pdf_pqr($data, $clasificacion, $datos_id_respuesta)
    {
        $html = "<html>
            <head>
                <meta charset='utf-8'>
                <title>pdf Pedido</title>
                <link type='text/css' rel='stylesheet' href='" . CARPETA_CSS . "/img_pdf/pdfstyle/stylepqrpdf.css'>
	        </head>
            <body>
            <header>
                <div id='imgencabezadop'>
                    <img id='imgtitulo' src='" . CARPETA_IMG . PROYECTO . "/img_pdf/cabeza_respu_pqr.jpg'>
                </div>
            </header>
            <footer>
                <p>Cordialmente,</p>
                <div id='caja_imgenfirma'>
                    <img id='imgenfirma' src='" . CARPETA_IMG . PROYECTO . "/firmas/empleados/" . FIRMA_JEFE_SER . "'>
                    <h5>
                        <p>" . JEFE_SER_CLIENTE . "</p>
                        <p>JEFE DE SERVICIO AL CLIENTE</p>
                    </h5>
                </div>
            </footer> 
                
                <div class='margen_documento'>
                    <div id='informacion_general'>
                        <h3>INFORMACIÓN GENERAL</h3>
                        <p>&nbsp;</p>
                        <P>Razón social del cliente: " . $data['datos_direccion'][0]['nombre_empresa'] . "</P>
                        <P>Nit: " . $data['datos_direccion'][0]['nit'] . "<span class='tab_linea'>Clacificación: " . $clasificacion . "</span></p>
                        <P>Contacto: " . $data['datos_direccion'][0]['contacto'] . "<span class='tab_linea'>Radicado: " . $data['num_pqr'] . "</span></P>
                        <p>&nbsp;</p>
                    </div>
                    <h3>INFORMACIÓN DE ENTRADA</h3>
                    <div class='justificado'>" . $data['apertura_pqr'] . "</div>
                    <h3>ANÁLISIS DEL CASO</h3>
                    <div class='justificado'>" . $datos_id_respuesta->analisis_pqr . "</div>
                    <h3>ACCIÓN DE MEJORA</h3>
                    <div class='justificado'>" . $datos_id_respuesta->accion . "</div>
                    <div class='justificado'>Por último, " . NOMBRE_EMPRESA . ", desea presentar excusas por las fallas presentadas y los problemas que estas pudieron generar, así como manifestar su interés en fortalecer y mantener una relación comercial sana y productiva para ambas partes, por esta razón se generan constantemente planes de acción, para fortalecer los puntos débiles de la compañía y mejorar de manera continua nuestro desempeño.</div>
                    <br>
                    <p>Nota: Se realizó la corrección correspondiente y entrega " . $data['cantidad_reclama'] . " unidades al cliente.</p>
                    <br>
                </div>
            </body>
        </html>";
        // Opciones de dompdf
        $options = new Options();
        // $options->setIsHtml5ParserEnabled(true);
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO);
        // Ejecucion de dompdf con la variable de obciones
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait'); // (Opcional) Configurar papel y orientación
        $dompdf->render(); // Generar el PDF desde contenido HTML
        $pdf = $dompdf->output(); // Obtener el PDF generado
        return $pdf;
        // $dompdf->stream('', array("Attachment" => true)); // Enviar el PDF generado al navegador
    }

    public static function crea_cotizacion_visita($fecha, $data_completa, $num_cotizacion, $estado, $trm, $firma)
    {
        if ($estado == 2) {
            $conteo = 1;
        } else {
            $conteo = count($data_completa);
        }
        $html = '
    <html>
        <head>
            <meta charset="utf-8">
            <title>PDF Cotización</title>
            <link type="text/css" rel="stylesheet" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/stylepdfcotiza_visita.css">
        </head>
        <body>
            <header>
                <img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/cabeza_cotiza_visita.jpg">
                <br> 
            </header>
            <div class="img_vertical">
                <img id="vertical" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/vertical_cotiza_visita.png">
            </div>
            <footer>
                <img id="imgpie" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/pie_cotiza_visita.jpg">
            </footer> 
            <div class="titulo">
                <h4 class="fecha">Fecha: ' . $fecha . '</h4>
                <h5 class="numero">CONSECUTIVO ' . date("Y") . ': MA-' . $num_cotizacion . '</h5>
            </div>
            <div class="contenido">
                Señores: <b>' . $data_completa[0]->nombre_empresa . '</b>
                <br>
                Atn: ' . $data_completa[0]->contacto . ' - ' . $data_completa[0]->cargo . '
                <br>
                Ciudad: ' . $data_completa[0]->nombre_ciudad . '
                <br>
                <p>Respetados Señores,</p>
                <br>
                Cordialmente estamos dando a conocer nuestra propuesta comercial, teniendo en cuenta sus
                necesidades de identificación con tecnología de código de barras para el control y manejo de sus
                productos e impresión de etiquetas adhesivas y no adhesivas en diferentes formas, tamaños y colores.
                <p></p>
                De antemano agradecemos su interés por tener en cuenta a ' . NOMBRE_EMPRESA . ', como la mejor
                solución a sus necesidades y en donde encontrará múltiples ventajas en servicio, calidad y precio.
                <p></p>
                TECNOLOGÍA
                    <p></p>
                    ';
        $total = 0;
        $total_pesos = 0;
        $total_dolares = 0;
        $total_mano = 0;
        $dolares_pesos = 0;
        $lleva_conteo = 0;
        for ($i = 0; $i < $conteo; $i++) {
            $conteo_items = count($data_completa[$i]->repuestos);
            if ($estado == 2 || $data_completa[$i]->item == 0) {
                $equipo = 'Cotizacion Visita';
            } else {
                $equipo = $data_completa[$i]->equipo . ' - ' . 'N/S: ' . $data_completa[$i]->serial_equipo . ' (Caso: ' . $data_completa[$i]->num_consecutivo . ' Item: ' . $data_completa[$i]->item . ')';
            }
            $html .= '<table align="center" border="1" width="100%" cellspacing="0">
        // <thead>
        //   <tr>
        //     <th  colspan="4">' . $equipo . '</th>
        //   </tr>
        //   <tr class = "sub_tabla">
        //     <th width="60%">Artículos</th>
        //     <th width="10%">Moneda</th>
        //     <th width="10%">Cantidad</th>
        //     <th width="20%">V/r Unidad</th>
        //   </tr>
        // </thead>
        // <tbody>';
            for ($a = 0; $a < $conteo_items; $a++) {
                $productos = $data_completa[$i]->repuestos[$a];
                if ($data_completa[$i]->repuestos[$a]->moneda == 1) {
                    $moneda = 'Pesos';
                    $simbolo = '$';
                    $total_pesos = $total_pesos + $data_completa[$i]->repuestos[$a]->valor * $data_completa[$i]->repuestos[$a]->cantidad;
                } else {
                    $moneda = 'Dolar';
                    $simbolo = 'U$Dol ';
                    $total_dolares = $total_dolares + $data_completa[$i]->repuestos[$a]->valor;
                }
                if ($productos->id_tipo_articulo == 14 || $productos->id_tipo_articulo == 12) {
                    $total_mano = $total_mano + $data_completa[$i]->repuestos[$a]->valor;
                }
                $descripcion_productos = $productos->codigo_producto . ' ' . $productos->descripcion_productos;
                if (strlen($descripcion_productos) >= 50) {
                    $nombre1 = substr($descripcion_productos, 0, 50);
                    $nombre2 = substr($descripcion_productos, 50, 80);
                } else {
                    $nombre1 = substr($descripcion_productos, 0, 50);
                    $nombre2 = "";
                }
                $html .= '
                    <tr>
                        <td width="60%">' . $nombre1 . '<br>' . $nombre2 . '</td>
                        <td width="10%" align="center">' . $moneda . '</td>
                        <td width="10%" align="center">' . $data_completa[$i]->repuestos[$a]->cantidad . '</td>
                        <td width="20%" align="center">' . $simbolo . ' ' . number_format($data_completa[$i]->repuestos[$a]->valor, 2, ',', '.') . '</td>
                    </tr>';
            }
            $html .= '</tbody>
                    </table>';
        }
        $total_en_pesos = $total_pesos - $total_mano;
        $dolares_pesos = $total_dolares * $trm;
        $total = $total_en_pesos + $dolares_pesos + $total_mano;
        $html .= '<br>
                <table align="center" border="1" width="100%" cellspacing="0">
                    <tbody>
                        <tr>
                            <th style="width:20%;" align="center">Valor TRM</th>
                            <th style="width:20%;" align="center">Repuestos en Dolares</th>
                            <th style="width:20%;" align="center">Repuestos en Pesos</th>
                            <th style="width:20%;" align="center">Total Mano de Obra</th>
                            <th style="width:20%;" align="center">Total Cotizacion</th>
                        </tr>
                        <tr>
                            <td style="width:20%;" align="center">$' . number_format($trm, 2, ',', '.') . '</td>
                            <td style="width:20%;" align="center">U$Dol ' . $total_dolares . '</td>
                            <td style="width:20%;" align="center">$' . number_format($total_en_pesos, 2, ',', '.') . '</td>
                            <td style="width:20%;" align="center">$' . number_format($total_mano, 2, ',', '.') . '</td>
                            <td style="width:20%;" align="center">$' . number_format($total, 2, ',', '.') . '</td>
                        </tr>
                    </tbody>
                </table>
                <br>
                    <b>NOTA:</b>
                    <br>
                    <div>
                    <ul>
                        <li>Se puede hacer la entrega con el 10% de más o de menos etiquetas</li>
                        <li>Para el caso de los valores cotizados en dólares se tendrá en cuenta para la facturación la TRM de la fecha de la orden de compra.</li>
                        <li>Favor generar dos órdenes de compra una para repuestos y otra para mano de obra; o dos líneas de descripción dentro de la misma orden de compra discriminando repuestos y mano de obra.</li>
                        <li>Para el caso de reparaciones de board efectuadas en laboratorio y/o visitas sobre los equipos, estamos sujetos a las pruebas de funcionamiento durante la reparación y el periodo de garantía, de ser necesario el cambio de la parte se generará una nueva cotización.</li>
                    </ul>
                    </div>
                    <br>
                    <b>CONDICIONES COMERCIALES</b>
                    <br>
                    PRECIOS NETOS MÁS 19% DE IVA
                    <p></p>
                    <b>COMPROMISO DE ENTREGA:</b>
                    <div>
                        ETIQUETAS: De Cinco (5) días hábiles, fecha orden de compra y/o aprobación de artes.
                        <br>
                        TECNOLOGIA: De quince (15) a veinte (20) días hábiles, posteriores a recibir la orden de compra,
                        dependiendo del inventario de stock y/o importación.
                    </div>
                    <p></p>
                    <b>FORMA DE PAGO: </b>' . FORMA_PAGO[$data_completa[0]->forma_pago] . ' 
                    <p></p>
                    <b>VALIDEZ DE LA OFERTA:</b> 30 días.
                    <p></p>
                    <b>GARANTIA:</b> 3 meses.
                    <p></p>
                    Cordialmente,
                    <br><br><br>
                    <b>' . JEFE_SOPORTE . '</b>
                    <br>
                    Jefe de Soporte Técnico
                    <br>
                    Tel. ' . TEL_EMPRESA . '
                    <br>
                    em@il:' . CORREO_SOPORTE_TEC . '
                </div>';

        $options = new Options();
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream();
        $pdf = $dompdf->output(); // Obtener el PDF generado
        return $pdf;
    }

    public static function acta_entrega_soporte($numero_acta, $data, $estado, $trm, $firma)
    {
        $conteo = count($data);
        $fecha = date('Y-m-d');
        if ($estado == 2 || $data[0]->estado_cotiza == 7) {
            $observacion = 'Los equipos mostrados a continuación se devuelven sin reparar y presentan daños en los siguientes repuestos:';
        } else {
            $observacion = '';
        }
        $html = '
        <html>
            <head>
                <meta charset="utf-8">
                <title>Acta Entrega</title>
                <link type="text/css" rel="stylesheet" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/stylepdfcotiza_visita.css">
            </head>
            <body>
                <header>
                    <img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/cabeza_acta_entrega.jpg">
                    <br> 
                </header>
                <div class="img_vertical">
                    <img id="vertical" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/vertical_cotiza_visita.png">
                </div>
                <footer>
                    <img id="imgpie" src="' . CARPETA_IMG . PROYECTO . '/img_pdf/pie_cotiza_visita.jpg">
                </footer> 
                <div class="titulo">
                    <h4 class="fecha">Fecha: ' . $fecha . '</h4>
                    <h5 class="numero">ENT: ' . $numero_acta . '</h5>
                </div>
                <div class="contenido">
                    Señores: <b>' . $data[0]->nombre_empresa . '</b>
                    <br>
                    Atn: ' . $data[0]->contacto . ' - ' . $data[0]->cargo . '
                    <br>
                    Ciudad:' . $data[0]->nombre_ciudad . '
                    <br>
                    <p>Estimados Señores,</p>
                    <br>
                    Nos permitimos presentar a continuación la relación de entrega del siguiente equipo enviado por ustedes para revisión.
                    <p>' . $observacion . '</p>';
        $total = 0;
        $total_pesos = 0;
        $total_dolares = 0;
        $total_mano = 0;
        $dolares_pesos = 0;
        for ($i = 0; $i < $conteo; $i++) {
            $conteo_items = count($data[$i]->repuestos);
            if ($firma != '' && $firma != 2) {
                $equipo = $data[$i]->equipo . ' N/S: ' . $data[$i]->serial_equipo . ' Caso: ' . $data[$i]->num_consecutivo . ' Item: ' . $data[$i]->item;
            } else {
                $equipo = $data[$i]->equipo . ' N/S: ' . $data[$i]->serial_equipo . ' Caso: ' . $data[$i]->num_consecutivo . ' Item: ' . $data[$i]->item . ' <br> Accesorios ' . $data[$i]->accesorios;
            }
            $html .= '<table align="center" border="1" width="100%" cellspacing="0">
                        <thead>
                          <tr>
                            <th colspan="4">' . $equipo . '</th>
                          </tr>
                          <tr class = "sub_tabla">
                            <th width="60%">Artículos</th>
                            <th width="10%">Moneda</th>
                            <th width="10%">Cantidad</th>
                            <th width="20%">V/r Unidad</th>
                          </tr>
                        </thead>
                        <tbody>';
            for ($a = 0; $a < $conteo_items; $a++) {
                $repuestos = $data[$i]->repuestos[$a];
                if ($repuestos->moneda == 1) {
                    $moneda = 'Pesos';
                    $simbolo = '$';
                } else {
                    $moneda = 'Dolar';
                    $simbolo = 'U$Dol ';
                }
                if ($repuestos->moneda == 1) {
                    $total_pesos = $total_pesos + $repuestos->valor * $repuestos->cantidad;
                } else {
                    $total_dolares = $total_dolares + $repuestos->valor;
                }
                if ($repuestos->id_tipo_articulo == 14 || $repuestos->id_tipo_articulo == 12) {
                    $total_mano = $total_mano + $repuestos->valor;
                }
                $descripcion_productos = $repuestos->codigo_producto . ' ' . $repuestos->descripcion_productos;
                if (strlen($descripcion_productos) >= 50) {
                    $nombre1 = substr($descripcion_productos, 0, 50);
                    $nombre2 = substr($descripcion_productos, 50, 80);
                } else {
                    $nombre1 = substr($descripcion_productos, 0, 50);
                    $nombre2 = "";
                }
                if ($estado == 2 || $data[0]->estado_cotiza == 7) {
                    $valor = 0;
                } else {
                    $valor = $repuestos->valor;
                }
                $html .= '
                <tr>
                <td width="60%">' . $nombre1 . '<br>' . $nombre2 . '</td>
                <td width="10%" align="center">' . $moneda . '</td>
                <td width="10%" align="center">' . $repuestos->cantidad . '</td>
                <td width="20%" align="center">' . $simbolo . ' ' . $valor . '</td>
                </tr>';
            }
            $html .= '</tbody>
                </table>';
        }
        if ($estado == 2 || $data[0]->estado_cotiza == 7) {
            $total_en_pesos = 0;
            $total = 0;
            $total_dolares = 0;
            $total_mano = 0;
        } else {
            $total_en_pesos = $total_pesos - $total_mano;
            $dolares_pesos = $total_dolares * $trm;
            $total = $total_en_pesos + $dolares_pesos + $total_mano;
        }
        $html .= '<br>
                <br>
                    <table align="center" border="1" width="100%" cellspacing="0">
                       <tbody>
                        <thead>
                            <tr>
                                <th style="width:20%;" align="center">Valor TRM</th>
                                <th style="width:20%;" align="center">Repuestos en Dolares</th>
                                <th style="width:20%;" align="center">Repuestos en Pesos</th>
                                <th style="width:20%;" align="center">Total Mano de Obra</th>
                                <th style="width:20%;" align="center">Total Orden</th>
                            </tr>
                        <thead>
                            <tr>
                                <td style="width:20%;" align="center">$' . number_format($trm, 2, ',', '.') . '</td>
                                <td style="width:20%;" align="center">U$Dol ' . $total_dolares . '</td>
                                <td style="width:20%;" align="center">$' . number_format($total_en_pesos, 2, ',', '.') . '</td>
                                <td style="width:20%;" align="center">$' . number_format($total_mano, 2, ',', '.') . '</td>
                                <td style="width:20%;" align="center">$' . number_format($total, 2, ',', '.') . '</td>
                            </tr>
                        </tbody>
                    </table>
                    <br>
                    <b>NOTA:</b>
                        <br>
                        <div>
                            <ul>
                                <li>Los valores anteriores no incluyen el impuesto del IVA</li>
                                <li>Esta revisión tiene garantía de 3 meses para los equipos facturados.</li>
                            </ul>
                        </div>
                        <br>
                    <br>';
        if ($firma != '' && $firma != 2) {
            $html .= '
                    <table class="sin_bordes" width="100%">
                                <tr>
                                    <th width="50%"></th>
                                    <th width="50%">FIRMA CLIENTE</th>
                                </tr>
                                <tr>
                                    <td class="nombre_persona">
                                        <br>
                                            Cordialmente,
                                        <br>
                                        <br>
                                        <br>
                                        ' . JEFE_SOPORTE . '
                                        <br>
                                        Jefe de Soporte Técnico
                                        <br>
                                        Tel. ' . TEL_EMPRESA . '
                                        <br>
                                        em@il:' . CORREO_SOPORTE_TEC . '
                            <td class="firma"> <img src="data:image/png;base64,' . $firma . '" width="150px" height="100px"/>
                        </td>
                    </tr>
                </table>
            </div>
        </body>
        </html>';
        } else {
            $html .= '<table class="sin_bordes" width="100%">
            <tr>
                <th width="50%"></th>
            </tr>
            <tr>
                <td class="nombre_persona">
                    <br>
                        Cordialmente,
                    <br>
                    <br>
                    <br>
                   ' . JEFE_SOPORTE . '
                    <br>
                    Jefe de Soporte Técnico
                    <br>
                    Tel. ' . TEL_EMPRESA . '
                    <br>
                    em@il: ' . CORREO_SOPORTE_TEC . '
                </td>
            </tr>
            </table>
        </div>
    </body>
    </html>';
        }
        $options = new Options();
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->set('chroot', PUBLICO);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream();
        $pdf = $dompdf->output(); // Obtener el PDF generado
        return $pdf;
    }

    public static function crea_remision_equipos($datos, $estado, $nombre_usuario, $sede, $nota, $apellido_usuario, $firma, $recibido)
    {
        //Cuando los equipos vienen por laboratorio es estado 1 cuando es visita es estado 2
        if ($estado == 1) {
            $cabeza = "/cabezote_remision_equipos";
            $nombre_campo = 'Accesorios';
            $foter = '';
        } else {
            $cabeza = "/informe_servicio_tecnico";
            $nombre_campo = 'Observaciones';
            $foter = '<table width="100%">
                        <tr>
                            <th width="30%">Realizado Por</th>
                            <th width="40%">Recibido Por</th>
                            <th class="firma" width="50%" class="recibido">Firma</th>
                        </tr>
                        <tr>
                            <td class="nombre_persona">' . $nombre_usuario . '&nbsp;' . $apellido_usuario . '</td>
                            <td class="nombre_persona"> ' . $recibido . '</td>
                            <td class="firma"> <img src="data:image/png;base64,' . $firma . '" width="100px" height="50px"/></td>
                        </tr>
                    </table>';
        }
        $html = '
        <html>
            <head>
                <title>Remision Equipos' . $datos[0]['num_consecutivo'] . ' </title>
                <link rel="stylesheet" type="text/css" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/style_equipo_soporte.css" />
            </head>
            <body>
                <header>
                    <img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_pdf' . $cabeza . '.jpg">
                    <br>
                    <div class="numero">
                        <h5> N°' . $datos[0]['num_consecutivo'] . '</h5>
                    </div>
                </header>
                <footer>
                ' . $foter . '
                </footer> 
                <div class="contenido">
                    <div class="primera_parte">
                        <table>
                            <tr>
                                <th>Fecha Ingreso:</th>
                                <td colspan="2">' . $datos[0]['fecha_ingreso'] . '</td>
                                <th>N° Consecutivo:</th>
                                <td colspan="3">' . $datos[0]['num_consecutivo'] . '</td>
                            </tr>
                            <tr>
                                <th>Cliente:</th>
                                <td colspan="5">' . $datos[0]['nombre_empresa'] . '</td>
                            </tr>
                            <tr>
                                <th>Pais:</th>
                                <td >' . $datos[0]['pais'] .  '</td>
                                <th>Departamento:</th>
                                <td>'  . $datos[0]['departamento'] . '</td>
                                <th>Ciudad:</th>
                                <td>' . $datos[0]['ciudad'] .  '</td>
                            </tr>
                            <tr>
                                <th>Dirección:</th>
                                <td colspan="5">' . $datos[0]['direccion'] . '</td>
                            </tr>
                            <tr>
                                <th>Nota:</th>
                                <td colspan="5">' . $nota . '</td>
                            </tr>
                        </table>
                    </div>
                    <div class="segunda_parte">
                        <table>
                            <tr>
                            <th class="borde-right-1" style="width: 5mm">Item</th>
                            <th class="borde-right-1" style="width: 25mm">Modelo</th>
                            <th class="borde-right-1" style="width: 25mm">Serial</th>
                            <th class="borde-right-1" style="width: 40mm">' . $nombre_campo . '</th>
                            <th style="width: 40mm;">Procedimiento</th>
                        </tr>
                        <tr>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1 text-center"></td>
                            <td class="borde-right-1 borde-top-1"></td>	
                        </tr>';
        $contador = 0;
        $cantidad_inicial = 23;
        for ($i = 0; $i < count($datos); $i++) {
            if ($contador == $cantidad_inicial) {
                $cantidad_inicial = 23;
                $contador = 0;
                $html .= '</table>
                <hr>
                <table>';
            }
            $contador = $contador + 1;
            $item = $i + 1;
            $html .= '			
                <tr>
                    <td class="borde-right-1 text-center" style="width: 5mm">' . $item . '</td>
                    <td class="borde-right-1 text-center" style="width: 20mm">' . $datos[$i]['equipo'] . '</td>
                    <td class="borde-right-1 text-center" style="width: 20mm">' . $datos[$i]['serial_equipo'] . '</td>
                    <td class="borde-right-1 text-center" style="width: 20mm">' . $datos[$i]['accesorios'] . '</td>
                    <td class="borde-right-1" style="width: 100mm">' . $datos[$i]['procedimiento'] . '</td>
                </tr>';
        }
        $html .= '
                    </div>
                </div>
            </body>
        </html>';

        $options = new Options();
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);
        $options->set('chroot', PUBLICO);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream();
        $pdf = $dompdf->output(); // Obtener el PDF generado
        return $pdf;
    }

    public static function certificado_producto($datos,$fecha,$num_certificado,$vencimiento)
    {
        $cabeza = "/certificado_analisis";
        $html = '
        <html>
            <head>
                <title>Certificado Productos</title>
                <link rel="stylesheet" type="text/css" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/style_certificados.css" />
            </head>
            <body>
                <header>
                    <img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_pdf' . $cabeza . '.jpg">
                </header>
                <div class="titulo" style="font-size: small;">
                    <p style="line-height:0"><b>Fecha emisión: </b>'.$fecha.'</p>
                    <p style="line-height:0"><b>Orden de compra N°: </b>'.$datos[0]['orden_compra'].'</p>
                    <p style="line-height:0"><b>Certificado de Análisis N°: </b>'.$num_certificado.'</p>
                    <p style="line-height:0"><b>Nombre Cliente: </b>'.$datos[0]['nombre_empresa'].'</p>
                </div>
                <div class="contenido">
                    <div class="primera_parte">
                        <table style="font-size: x-small;">
                        <thead>
                            <tr>
                                <th style="width: 20mm">CÓD. PRODUCTO</th>
                                <th style="width: 138mm">REFERENCIA</th>
                                <th style="width: 10mm">CANTIDAD</th>
                                <th style="width: 15mm">LOTE</th>
                            </tr>';
                            foreach ($datos as $value) {
                            $html .= '<tr>
                                <td>'.$value['codigo'].'</td>
                                <td>'.$value['descripcion_productos'].'</td>
                                <td style="text-align: center;">'.$value['Cant_solicitada'].'</td>
                                <td style="text-align: center;">'.$value['n_produccion'].'</td>	
                            </tr>';
                            }
                        $html .= '</thead>
                        </table>
                        <table style="font-size: x-small;">
                        <tbody>
                            <tr>
                                <th rowspan="2" style="width: 130mm">CERTIFICADO DE ANÁLISIS</th>
                                <th colspan="2" style="width: 70mm">REFERENCIA</th>
                            </tr>
                            <tr>
                                <th>SI</th>
                                <th>NO</th>
                            </tr>
                            <tr>
                                <td>Falta de registro</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Color diferente al especificado</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Textos embotados por falla en Inspección</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Textos Incompletos por falla de Impresión</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Impresión diferente</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Ausencia de impresión</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Variaciones de forma y/o tamaño</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Variaciones de tono</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Sangrado del adhesivo</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Troquel descentrado</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Terminaciones o acabados erróneos</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Diferencia en las cavidades requeridas</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Gap lateral fuera de parámetros</td>
                                <td></td>
                                <td style="text-align: center;">X</td>
                            </tr>
                            <tr>
                                <td>Vence</td>
                                <td colspan="2" style="text-align: center;">'.$vencimiento.'</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <ol start="1" style="font-size: small;">
                        <li>No retire los rollos del empaque original hasta el momento en que los va a imprimir, esto en razón a que los cambios de humedad relativa pueden ocasionar perdida o incremento de tensión, deterioro en el core interno y hasta pérdida de la alineación.</li>
                        <li>Almacenar en un lugar seco y fresco, mantener el material alejado de fuentes de calor e ignición o la luz directa del sol.</li>
                        <li>Almacenar y procesar el material bajo condiciones estables de humedad y temperatura. Las condiciones ideales son 23 ± 2 ºC y humedad relativa entre 50 y 55 %.</li>
                        <li>Cuando use parcialmente un rollo, regrese el sobrante a su empaque original.</li>
                        <li>Apilar en columnas independientes, los rollos de diferente diámetro exterior.</li>
                        <li>Apoyar los rollos sobre superficies planas, en pilas no mayores a 40 cm de altura.</li>
                        <li>No apoyar los rollos de costado (el eje del core debe quedar perpendicular respecto a la superficie), así se evitará su deformación. </li>
                    </ol>
                </div>
            </body>
        </html>';

        $options = new Options();
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);
        $options->set('chroot', PUBLICO);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream();
        $pdf = $dompdf->output(); // Obtener el PDF generado
        return $pdf;
    }
    
    public static function certificado_cintas($datos,$fecha,$num_certificado,$vencimiento)
    {
        $cabeza = "/certificado_analisis";
        $html = '
        <html>
            <head>
                <title>Certificado Productos</title>
                <link rel="stylesheet" type="text/css" href="' . CARPETA_CSS . '/img_pdf/pdfstyle/style_certificados.css" />
            </head>
            <body>
                <header>
                    <img id="imgtitulo" src="' . CARPETA_IMG . PROYECTO . '/img_pdf' . $cabeza . '.jpg">
                </header>
                <div class="titulo" style="font-size: small;">
                    <p style="line-height:0"><b>Fecha emisión: </b>'.$fecha.'</p>
                    <p style="line-height:0"><b>Orden de compra N°: </b>'.$datos[0]['orden_compra'].'</p>
                    <p style="line-height:0"><b>Certificado de Análisis N°: </b>'.$num_certificado.'</p>
                    <p style="line-height:0"><b>Nombre Cliente: </b>'.$datos[0]['nombre_empresa'].'</p>
                </div>
                <div class="contenido">
                    <div class="primera_parte">
                        <table style="font-size: x-small;">
                        <thead>
                            <tr>
                                <th style="width: 20mm">CÓD. PRODUCTO</th>
                                <th style="width: 138mm">REFERENCIA</th>
                                <th style="width: 10mm">CANTIDAD</th>
                                <th style="width: 15mm">LOTE</th>
                            </tr>';
                            foreach ($datos as $value) {
                            $html .= '<tr>
                                <td>'.$value['codigo'].'</td>
                                <td>'.$value['descripcion_productos'].'</td>
                                <td style="text-align: center;">'.$value['Cant_solicitada'].'</td>
                                <td style="text-align: center;">'.$value['n_produccion'].'</td>	
                            </tr>';
                            }
                        $html .= '</thead>
                        </table>
                        <table style="font-size: x-small;">
                        <tbody>
                            <tr>
                                <th style="width: 126mm">CERTIFICADO DE ANÁLISIS</th>
                                <th style="width: 70mm">REFERENCIA</th>
                            </tr>
                            <tr>
                                <td>Base de la película</td>
                                <td style="text-align: center;">Poliéster</td>
                            </tr>
                            <tr>
                                <td>Calibre Base de la película</td>
                                <td style="text-align: center;">4.5 micras (µm)</td>
                            </tr>
                            <tr>
                                <td>Calibre Total</td>
                                <td style="text-align: center;">6.5 micras (µm) ± 10%</td>
                            </tr>
                            <tr>
                                <td>Capacidades de escaneo</td>
                                <td style="text-align: center;">IR Y LUZ VISIBLE</td>
                            </tr>
                            <tr>
                                <td>Vence</td>
                                <td style="text-align: center;">'.$vencimiento.'</td>
                            </tr>
                        </tbody>
                        </table>
                    </div>
                    <ol start="1" style="font-size: small;">
                        <li>No retire los rollos del empaque original hasta el momento de uso, esto en razón a que los cambios de humedad relativa pueden ocasionar perdida o incremento de tensión, deterioro en el core interno y hasta pérdida de la alineación.</li>
                        <li>Almacenar en un lugar seco y fresco, mantener el material alejado de fuentes de calor e ignición o la luz directa del sol.</li>
                        <li>Almacenar y procesar el material bajo condiciones estables de humedad y temperatura. Las condiciones ideales son 22 ± 2 ºC y humedad relativa entre 50 y 55 %.</li>
                        <li>Cuando use parcialmente un rollo, regrese el sobrante a su empaque original.</li>
                        <li>Apilar en columnas independientes, los rollos de diferente diámetro exterior.</li>
                        <li>Apoyar los rollos sobre superficies planas, en pilas no mayores a 30 cm de altura.</li>
                        <li>No apoyar los rollos de costado (el eje del core debe quedar perpendicular respecto a la superficie), así se evitará su deformación. </li>
                    </ol>
                </div>
            </body>
        </html>';

        $options = new Options();
        $options->set('enable_html5_parser', true);
        $options->set('isHtml5ParserEnabled', true);
        $options->setIsRemoteEnabled(true);
        $options->set('chroot', PUBLICO);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('letter', 'portrait');
        $dompdf->render();
        $dompdf->stream();
        $pdf = $dompdf->output(); // Obtener el PDF generado
        return $pdf;
    }
}
