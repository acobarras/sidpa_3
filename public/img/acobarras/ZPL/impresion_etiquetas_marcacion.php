<div>
    <?php

    use MiApp\negocio\util\Validacion;

    $data_item = json_decode($_POST['datos']);
    $formulario = Validacion::Decodifica($_POST['formulario']);
    $check = $data_item->logo_etiqueta;
    $operario = "0" . ($formulario['id_persona']);
    // fecha actual
    $fecha_actual = date("d-m-Y");
    //sumo 1 año
    $fecha_ano = date("d-m-Y", strtotime($fecha_actual . "+ 1 year"));
    // extraer año,mes, dia de la fecha de compromiso
    $fechacompro = strtotime($data_item->fecha_compro_item);
    $ano_compro = date("y", $fechacompro);
    $dia_compro = date("d", $fechacompro);
    $mes_compro = date("m", $fechacompro);

    if ($formulario['caja'] == '') { // se ejecutan las etiquetas 46x18 y 55x33
        if ($formulario['tamano'] == 1) { // La etiqueta es de 46x18
            $prueba =
                "<br>" .
                "^XA~TA000~JSN^LT0^MNW^MTT^PON^PMN^LH0,2^JMA^JUS^LRN^CI0^XZ" .
                "^XA" .
                "^MMT" .
                "^PW783" .
                "^LL0144" .
                "^LS0" .
                "^BY96,96^FT254,106^BXN,6,200,0,0,1,~" .
                "^FH\^FD" . $data_item->codigo . ";" . $formulario['cant_x'] . "^FS" .
                "^FT30,21^A0N,18,19^FH\^FDFecha:^FS" . "<br>" .
                "^FT91,21^ACN,18,10^FH\^FD" . date('d/m/Y') . "^FS" .
                "^FT30,42^A0N,18,19^FH\^FDVence:^FS" .
                "^FT91,42^ACN,18,10^FH\^FD" . $fecha_ano . "^FS" .
                "^FT30,107^A0N,18,19^FH\^FDCant:^FS" .
                "^FT91,107^ACN,18,10^FH\^FD" . $formulario['cant_x'] . "^FS" . "<br>" .
                "^FT30,85^A0N,18,19^FH\^FDAux:^FS" .
                "^FT91,85^ACN,18,10^FH\^FD" . $operario . "^FS" .
                "^FT30,65^A0N,18,19^FH\^FDLote:^FS" .
                "^FT91,65^ACN,18,10^FH\^FD" . $formulario['lote'] . "^FS" .
                "^FT80,135^ACN,18,10^FH\^FD" . $data_item->codigo . "^FS" . "<br>" .
                "^FO18,88^GB94,0,1^FS" . "<br>" .
                "^FO16,114^GB367,0,2^FS" . "<br>" .
                "^FO17,67^GB94,0,1^FS" . "<br>" .
                "^FO108,67^GB125,0,1^FS" . "<br>" .
                "^FO109,88^GB124,0,1^FS" . "<br>" .
                "^FO108,46^GB126,0,1^FS" . "<br>" .
                "^FO108,24^GB124,0,1^FS" . "<br>" .
                "^FO17,46^GB94,0,1^FS" . "<br>" .
                "^FO17,24^GB93,0,1^FS" . "<br>" .
                "^FO84,0^GB0,115,3^FS" . "<br>" .
                "^FO231,0^GB0,115,3^FS" . "<br>" .
                "<br>" .
                "^BY96,96^FT638,106^BXN,6,200,0,0,1,~" . "<br>" .
                "^FH\^FD" . $data_item->codigo . ";" . $formulario['cant_x'] . "^FS" . "<br>" .
                "^FT414,21^A0N,18,19^FH\^FDFecha:^FS" . "<br>" .
                "^FT475,21^ACN,18,10^FH\^FD" . date('d/m/Y') . "^FS" . "<br>" .
                "^FT414,42^A0N,18,19^FH\^FDVence:^FS" . "<br>" .
                "^FT475,42^ACN,18,10^FH\^FD" . $fecha_ano . "^FS" . "<br>" .
                "^FT414,107^A0N,18,19^FH\^FDCant:^FS" . "<br>" .
                "^FT475,107^ACN,18,10^FH\^FD" . $formulario['cant_x'] . "^FS" . "<br>" .
                "^FT414,85^A0N,18,19^FH\^FDAux:^FS" . "<br>" .
                "^FT475,85^ACN,18,10^FH\^FD" . $operario . "^FS" . "<br>" .
                "^FT414,65^A0N,18,19^FH\^FDLote:^FS" . "<br>" .
                "^FT475,65^ACN,18,10^FH\^FD" . $formulario['lote'] . "^FS" . "<br>" .
                "^FT464,135^ACN,18,10^FH\^FD" . $data_item->codigo . "^FS" . "<br>" .
                "^FO402,88^GB94,0,1^FS" . "<br>" .
                "^FO400,114^GB367,0,2^FS" . "<br>" .
                "^FO401,67^GB94,0,1^FS" . "<br>" .
                "^FO492,67^GB125,0,1^FS" . "<br>" .
                "^FO493,88^GB124,0,1^FS" . "<br>" .
                "^FO492,46^GB126,0,1^FS" . "<br>" .
                "^FO492,24^GB124,0,1^FS" . "<br>" .
                "^FO401,46^GB94,0,1^FS" . "<br>" .
                "^FO401,24^GB93,0,1^FS" . "<br>" .
                "^FO468,0^GB0,115,3^FS" . "<br>" .
                "^FO615,0^GB0,115,3^FS" . "<br>" .
                "^PQ" . $formulario['cantidad'] . ",0,1,Y^XZ" .
                "<br>";
        } else { // la etiqueta es de 55x33
            $prueba = "^XA" .
                "^LH0,0 " .
                "^MMT " .
                "^PW831 " .
                "^LL0264 " .
                "^LS0 " .
                "^BY144,144^FT216,162^BXN,7,200,0,0,1,~ " .
                "^FH\^FD" . $data_item->codigo . ";" . $formulario['cant_x'] . "^FS " .
                "^FT17,206^A0N,31,31^FH\^FDCant:^FS " .
                "^FT99,206^A0N,31,31^FH\^FD" .  $formulario['cant_x'] . "^FS " .
                "^FT217,206^A0N,31,31^FH\^FDAux:^FS " .
                "^FT289,207^A0N,31,31^FH\^FD" . $operario . "^FS " .
                "^FT17,164^A0N,31,31^FH\^FDLote:^FS " .
                "^FT91,164^A0N,31,31^FH\^FD" . $formulario['lote'] . "^FS " .
                "^FT17,122^A0N,31,31^FH\^FDO.P.:^FS " .
                "^FT91,122^A0N,31,31^FH\^FD" . $data_item->n_produccion . "^FS " .
                "^FT21,38^A0N,31,31^FH\^FDFecha:^FS " .
                "^FT21,80^A0N,31,31^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT81,249^A0N,28,28^FH\^FD" . $data_item->codigo . "^FS " .
                "^BY144,144^FT632,162^BXN,7,200,0,0,1,~ " .
                "^FH\^FD" . $data_item->codigo . ";" . $formulario['cant_x'] . "^FS " .
                "^FT433,206^A0N,31,31^FH\^FDCant:^FS " .
                "^FT515,206^A0N,31,31^FH\^FD" .  $formulario['cant_x'] . "^FS " .
                "^FT633,206^A0N,31,31^FH\^FDAux:^FS " .
                "^FT705,207^A0N,31,31^FH\^FD" . $operario . "^FS " .
                "^FT433,164^A0N,31,31^FH\^FDLote:^FS " .
                "^FT507,164^A0N,31,31^FH\^FD" . $formulario['lote'] . "^FS " .
                "^FT433,122^A0N,31,31^FH\^FDO.P.:^FS " .
                "^FT507,122^A0N,31,31^FH\^FD" . $data_item->n_produccion . "^FS " .
                "^FT437,38^A0N,31,31^FH\^FDFecha:^FS " .
                "^FT437,80^A0N,31,31^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT497,249^A0N,28,28^FH\^FD" . $data_item->codigo . "^FS " .
                "^PQ" . $formulario['cantidad'] . ",0,1,Y^XZ ";
        }
    } else { // se ejecuta la etiqueta de 100x50
        $porciones_cod = explode("-", $data_item->codigo);
        $empresa = $data_item->nombre_empresa;
        if (strlen($empresa) >= 57) {
            $nombre1 = substr($empresa, 0, 60);
            $nombre2 = substr($empresa, 61, strlen($empresa));
        } else {
            $nombre2 = substr($empresa, 0, strlen($empresa));
            $nombre1 = "";
        }
        if ($check != 1) {
            $prueba = "
            ^XA
            ^MMT
            ^PW831
            ^LL0400
            ^LS0
            ^BY144,144^FT633,310^BXN,9,200,0,0,1,~
            ^FH\^FD" . $data_item->codigo . "^FS
    
            ^FT37,186^ACN,36,20^FH\^FD" . $data_item->num_pedido . "^FS
            ^FT371,311^A0N,24,24^FH\^FDFecha:^FS
            ^FT451,313^ACN,18,10^FH\^FD" . date('d/m/Y') . "^FS
            ^FT267,224^A0N,24,26^FH\^FDCantidad total:^FS
            ^FT439,225^ACN,18,10^FH\^FD" . $formulario['caja'] . "^FS
            ^FT267,182^A0N,24,26^FH\^FDCantidad por rollo:^FS
            ^FT478,183^ACN,18,10^FH\^FD" .  $formulario['cant_x'] . "^FS
            ^FT37,267^A0N,24,26^FH\^FDCavidades:^FS
            ^FT163,268^ACN,18,10^FH\^FD" . $data_item->cav_cliente . "^FS
            ^FT267,267^A0N,24,24^FH\^FDLote:^FS
            ^FT331,268^ACN,18,10^FH\^FD" . $formulario['lote'] . "^FS
            ^FT197,311^A0N,24,24^FH\^FDAux:^FS
            ^FT245,313^ACN,18,10^FH\^FD" . $operario . "^FS
            ^FT37,311^A0N,24,24^FH\^FDCore:^FS
            ^FT99,313^ACN,18,10^FH\^FD" . $data_item->nombre_core . "^FS
            ^FT37,140^A0N,25,24^FH\^FD" . $nombre2 . "^FS
            ^FT37,108^A0N,25,24^FH\^FD" . $nombre1 . "^FS
            ^FT37,224^A0N,24,26^FH\^FDTama\A4o:^FS
            ^FT140,225^ACN,18,10^FH\^FD" . $porciones_cod[0] . "^FS
            ^FO26,325^GB575,0,1^FS
            ^FO26,279^GB575,0,1^FS
            ^FO26,237^GB576,0,1^FS
            ^FO27,193^GB575,0,2^FS
            ^FO26,149^GB776,0,1^FS
            ^FO344,280^GB0,45,1^FS
            ^FO155,280^GB0,45,1^FS
            ^FO240,150^GB0,129,2^FS
            ^FO600,150^GB0,175,1^FS
            ^PQ" . $formulario['cantidad'] . ",0,1,Y^XZ";
        } else {

            $imagen = '
            ^FO17,20^GFA,06912,06912,00072,:Z64:
            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:2219
            ';
            $prueba = '
            ^XA
            ^MMT
            ^PW831
            ^LL0400
            ^LS0
            ' . $imagen . '
            ^BY112,112^FT643,317^BXN,7,200,0,0,1,~
            ^FH\^FD' . $data_item->codigo . '^FS
            ^FT618,91^ACN,36,20^FH\^FD' . $data_item->num_pedido . '-' . $data_item->item . '^FS
            ^FT655,358^ACN,18,10^FH\^FD' . $ano_compro . 'D' . $dia_compro . 'M' . $mes_compro . '^FS
            ^FT614,357^A0N,24,24^FH\^FDC^FS
            ^FT225,270^A0N,24,26^FH\^FDCantidad total:^FS
            ^FT396,272^ACN,18,10^FH\^FD' . $formulario['caja'] . '^FS
            ^FT225,228^A0N,24,26^FH\^FDCantidad por rollo:^FS
            ^FT435,229^ACN,18,10^FH\^FD' . $formulario['cant_x'] . '^FS
            ^FT36,358^A0N,24,26^FH\^FDOrden de Compra:^FS
            ^FT238,358^ACN,18,10^FH\^FD' . $data_item->orden_compra . '^FS
            ^FT36,269^A0N,24,26^FH\^FDCavidades:^FS
            ^FT162,270^ACN,18,10^FH\^FD' . $data_item->cav_cliente . '^FS
            ^FT450,315^A0N,24,24^FH\^FDAux:^FS
            ^FT498,316^ACN,18,10^FH\^FD' . $operario . '^FS
            ^FT225,313^A0N,24,24^FH\^FDLote:^FS
            ^FT288,314^ACN,18,10^FH\^FD' . $formulario['lote'] . '^FS
            ^FT36,227^A0N,24,26^FH\^FDTama\A4o:^FS
            ^FT139,228^ACN,18,10^FH\^FD' . $porciones_cod[0] . '^FS
            ^FT36,314^A0N,24,24^FH\^FDCore:^FS
            ^FT98,315^ACN,18,10^FH\^FD' . $data_item->core . '^FS
            ^FT37,185^A0N,25,24^FH\^FD' . $nombre1 . '^FS
            ^FT37,154^A0N,25,24^FH\^FD' . $nombre2 . '^FS
            ^FO26,370^GB770,0,1^FS
            ^FO26,324^GB772,0,1^FS
            ^FO26,282^GB576,0,1^FS
            ^FO26,123^GB776,0,2^FS
            ^FO27,239^GB575,0,1^FS
            ^FO26,195^GB776,0,1^FS
            ^FO437,283^GB0,42,1^FS
            ^FO210,196^GB0,129,2^FS
            ^FO600,13^GB0,110,1^FS
            ^FO600,196^GB0,175,1^FS
            ^PQ' . $formulario['cantidad'] . ',0,1,Y^XZ';
        }
    }
    echo $prueba;
    ?>
</div>