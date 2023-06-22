<div>
    <?php

    use MiApp\negocio\util\Validacion;

    $data_item = json_decode($_POST['datos']);
    $formulario = Validacion::Decodifica($_POST['formulario']);
    $check = $data_item->logo_etiqueta;
    $operario = "0" . ($formulario['id_persona']);

    $fecha_actual = date("d-m-Y");
    //sumo 1 año
    $fecha_año = date("d-m-Y", strtotime($fecha_actual . "+ 1 year"));

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
            "^FT91,42^ACN,18,10^FH\^FD" . $fecha_a単o . "^FS" .
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
            "^FT475,42^ACN,18,10^FH\^FD" . $fecha_a単o . "^FS" . "<br>" .
            "^FT414,107^A0N,18,19^FH\^FDCant:^FS" . "<br>" .
            "^FT475,107^ACN,18,10^FH\^FD" . $formulario['cant_x'] . "^FS" . "<br>".
            "^FT414,85^A0N,18,19^FH\^FDAux:^FS" . "<br>" .
            "^FT475,85^ACN,18,10^FH\^FD" . $operario . "^FS" ."<br>" .
            "^FT414,65^A0N,18,19^FH\^FDLote:^FS" ."<br>" .
            "^FT475,65^ACN,18,10^FH\^FD" . $formulario['lote'] . "^FS" ."<br>" .
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
            $prueba = $this->etiqueta_sin_logo($data_item, $porciones_cod, $formulario, $nombre1, $nombre2, $operario);
        } else {
            $prueba = $this->etiqueta_con_logo($data_item, $porciones_cod, $formulario, $nombre1, $nombre2, $operario);
        }
    }
    echo $prueba;
    ?>
</div>