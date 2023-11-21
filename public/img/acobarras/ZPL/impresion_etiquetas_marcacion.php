<div>
    <?php

    use MiApp\negocio\util\Validacion;

    $data_item = json_decode($_POST['datos']);
    $formulario = Validacion::Decodifica($_POST['formulario']);
    // resolucion de la impresora
    $resolucion = 200;
    if (isset($_POST['resolucion'])) {
        $resolucion = $_POST['resolucion'];
    }
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

    //parametros de orden de compra y de nombres
    $porciones_cod = explode("-", $data_item->codigo);
    $empresa = $data_item->nombre_empresa;
    $orden_compra = $data_item->orden_compra;
    if (strlen($empresa) >= 50) {
        $nombre1 = substr($empresa, 0, 53);
        $nombre2 = substr($empresa, 54, strlen($empresa));
    } else {
        $nombre1 = substr($empresa, 0, strlen($empresa));
        $nombre2 = "";
    }
    if (strlen($orden_compra) >= 27) {
        $orden1 = substr($orden_compra, 0, 30);
        $orden2 = substr($orden_compra, 30, strlen($orden_compra));
    } else {
        $orden1 = substr($orden_compra, 0, strlen($orden_compra));
        $orden2 = "";
    }

    switch ($resolucion) {
        case '300':
            switch ($formulario['tamano']) { //  Switch de tamaños - relacion con el id_tamano_impresora
                case '1': //46x18
                    $prueba =
                        '^XA
                        ^MMT
                        ^PW1269
                        ^LL0213
                        ^LS10
                        ^LT15
                        ^FT126,200^AAN,27,15^FH\^FD' . $data_item->codigo . '^FS
                        ^FT116,161^AAN,27,15^FH\^FD' . $formulario['cant_x'] . '^FS
                        ^FT28,164^A0N,25,24^FH\^FDCant:^FS
                        ^FT117,97^AAN,27,15^FH\^FD' . $formulario['lote'] . '^FS
                        ^FT117,65^AAN,27,15^FH\^FD' . $fecha_ano . '^FS
                        ^FT29,100^A0N,25,24^FH\^FDLote:^FS
                        ^FO372,13^GB0,159,4^FS
                        ^FT117,34^AAN,27,15^FH\^FD' . date('d/m/Y') . '^FS
                        ^FT117,128^AAN,27,15^FH\^FD' . $operario . '^FS
                        ^FT29,132^A0N,25,24^FH\^FDAux:^FS
                        ^FT29,68^A0N,25,24^FH\^FDVence:^FS
                        ^FT29,38^A0N,25,24^FH\^FDFecha:^FS
                        ^FO101,13^GB0,159,4^FS
                        ^FO30,172^GB536,0,4^FS
                        ^FO30,137^GB346,0,1^FS
                        ^FO30,104^GB346,0,1^FS
                        ^FO30,73^GB346,0,1^FS
                        ^FO30,44^GB346,0,1^FS
                        ^BY144,144^FT395,164^BXN,8,200,0,0,1,~
                        ^FH\^FD' . $data_item->codigo . ';' . $formulario['cant_x'] . '^FS
                        ^FT704,200^AAN,27,15^FH\^FD' . $data_item->codigo . '^FS
                        ^FT694,161^AAN,27,15^FH\^FD' . $formulario['cant_x'] . '^FS
                        ^FT606,164^A0N,25,24^FH\^FDCant:^FS
                        ^FT695,97^AAN,27,15^FH\^FD' . $formulario['lote'] . '^FS
                        ^FT695,65^AAN,27,15^FH\^FD' . $fecha_ano . '^FS
                        ^FT607,100^A0N,25,24^FH\^FDLote:^FS
                        ^FO950,13^GB0,159,4^FS
                        ^FT695,34^AAN,27,15^FH\^FD' . date('d/m/Y') . '^FS
                        ^FT695,128^AAN,27,15^FH\^FD' . $operario . '^FS
                        ^FT607,132^A0N,25,24^FH\^FDAux:^FS
                        ^FT607,68^A0N,25,24^FH\^FDVence:^FS
                        ^FT607,38^A0N,25,24^FH\^FDFecha:^FS
                        ^FO679,13^GB0,159,4^FS
                        ^FO608,172^GB536,0,4^FS
                        ^FO608,137^GB346,0,1^FS
                        ^FO608,104^GB346,0,1^FS
                        ^FO608,73^GB346,0,1^FS
                        ^FO608,44^GB346,0,1^FS
                        ^BY144,144^FT973,164^BXN,8,200,0,0,1,~
                        ^FH\^FD' . $data_item->codigo . ';' . $formulario['cant_x'] . '^FS
                        ^PQ' . $formulario['cantidad'] . ',0,1,Y^XZ';
                    break;
                case '2': //100x50
                    if ($check != 1) {
                        $imagen = '';
                    } else {
                        $imagen =
                            '^FO128,0^GFA,13440,13440,00084,:Z64:eJztms+O3DYSxilwMfTBGF0nQMPMI8xxDob1Kv0Ic/RhESpIgDxSjksjh1wW2FegkReQsRcetM1U' . '<br>'
                            . 'fUVKlLona2SYS6CC+890Uz+RxaqvSLaVOuywww477LDDDjtsb2lqjhxSGhsju5RSbMy0UenUmOlGGr5viuxmetJtB2+C+odS/2zKtKO6o8E3ZX5Qqq' . '<br>'
                            . 'fe+pbMCKYODZEd5ZAVdDPTHi5tOkmGeN/Q411DJvlSPXp5bWXv6fHspb+t7CM9zmeaK9+O+ZyZKtz4Ml34WX+tbJkkEH72jxmeTWfxMyl5erGJZas' . '<br>'
                            . 'nSZyL2FILfOgSNyntHTfIQwbz44aJL+l6rgB0XRAm4dzKDPgqxcLs5D5KU4NufFIyWTum9Iyvn9BbxlTMKMyZxxNlWBiXAfNBbQI0M/MA86DBnGrmL' . '<br>'
                            . 'MxUmFb6jrDUYN5fMY2MlpteMjPWzEtmjpk5yIBA0uotE66YoHjMQ6qZPjMTPEh/G2mf/aFOoN1nzy4xgSAaxINp7S5d+jKzy/7AzNyxKNdMLUx0cMr' . '<br>'
                            . 'XbZlGbiTfBbPMJa57wvjv1C6R8B1d1KdJ0/uBumtT8WvYM6mLF4l4x8HEkf6WPErMUDOTQkNKIUM+6qn5QK5yF4X3yuANMUdqEqQPPRw01ky1Z2qex' . '<br>'
                            . 'DT1SJRJDRyjNVMn33G3CtNmLkAP4svnHRPZ6wLfusvMvoy9x5wI0+Wx25mvUYXZ7ZidxDp9OowDXBvBNDVTJa9rJjtHz3linuRlz7R8gVWOL3fEzEM' . '<br>'
                            . 'e2CU7pub2bpLqBhgpiM8hsAbTqNDBk5IUmflSMN0NpgEzyLjhyHNxwY45i2unF5mjvlRM6IdamWclKZXNQNWiuIHvZzMTssHMyN+MJjN75FRhKunv8' . '<br>'
                            . '5bZ/4+YsnrWIsvE9NK99K94i4lYlQ7xk4dLN8xIJCkEGk0piMCMRKL1Kq9Z+RswOce+7JhdZlZiZyPH3pbJ/cXyt1+Y8C+PeZjIt2VdDOYo81MzJ+e' . '<br>'
                            . 'zfxDFhUkxqi+FaSpmqJhFkB7y+2xDYOZ4xaSuGzA5voeEvOLUcCHHlVJFkouMLuaCCzJoXMbPXJAtcfuZJ8qxvLBejQoh4llEx5XJrJ3QO78yZ2GmL' . '<br>'
                            . 'M19pE9QQ9MsyozQhDBLi8K63zITmOqayZ6kT6ClKQrzwlG1Y97lx8qkNi6YG0yWkMksTLMwaWQL81SNW1dMmiXEnihYzRwm+sIuNSPJmkG5cMXUGya' . '<br>'
                            . '1waVgxu3YXViYuWscX3vmKcdTxby8xOSw0YU5mlyOqOkQ+prJsclxvxZO7sltpmeBu5Qa2ue+k8+HacNk3eT8XJk8s/EmMygkjJUxG0GzDNC4aibnu' . '<br>'
                            . 'nqROUSZKS51hqs9MzsufSOXNlRNK5VFSdYx8/mKSW32zIhw5WqPTXSSlBgilMZOhalrZtgwpz1zECZeNGZfmBMUcbhiBv7jXDOHl5j4FEyRLWrI8k8' . '<br>'
                            . 'PcTtHDJj+LJ1dmVQB7byZIzeh9GQmCcfKnMCUW0P8TjzlZ/Lm44ZJsTFv8igzSZQ4KpmpKiYvIXdMpT7x6ntlIkRyvgszSar2s5V44X/ZkXFdJIo6n' . '<br>'
                            . 'CTcjX/YMy81syvMaHPkZ2a4zWSUHU9VbnaZiXtAk6l4chkm5gAm1wAe9BCkRIH5mL2fUR9YSBam3jC5u/QeTLtj8oTPhRlzlJxE5j6wKO+YKOza97L' . '<br>'
                            . 'fGnthuoU5LExJURzT8I3eQo7NO2YummwyM/ABDHKQlmNgDjGtTF7jevYN1OMiy50AJtHsT/7uitlhDUPS4FHScImbREluMe0lL4TugXU66IopqtWlK' . '<br>'
                            . 'PlDzVjX0I0vhSkBAV3JK32b8kLoHi6NXaDZX5h5wYs91axRGyKYOv0XarRjco+p7NNXmNc7luSHj2rS47pmyAsXxykz8xLYkhuk878lSaokQYalvqy' . '<br>'
                            . 'gE/YRCBKW5Pfv1Jkif1nbcBtKapdolknM0mfWYmH+CnXaMkeH9nR/2QKBac2bR8rQhYlFdBoHEfNcM6QE/edyixmYWba1zHxUv/707weSkbcLM4Bpp' . '<br>'
                            . 'Vg4yXBhSkRBUDh3sSXxsllE9nGMala5X37wnlR0WX8mYeZDAO5u2RZfrDBNzfwkzFGGhWQ/q1++/8QvK9PLIliKok3r9n22koCy5ZJlLLl7lKVLkiM' . '<br>'
                            . 'BYgb1mRP7eWF20sZ3AjNye3g3QnrtlvlbZg5JzqVparwwH5e9jCzoWNAA03J7MANPX2aWbRwGk30FGe98l5lPFTM7YJD7OlwEpuyBtkwHEmp0PpMPF' . '<br>'
                            . 'Jhfxm859Ms+DlUCqSz3HZbDjoiNAqmokS1XJzeTSsCrWy9M8qhTj2/Im/UeVozPGxVOXb/KyhnqM0XTG35zunkQ9qfsGTsPxfLUjPlUVrP37Q4Bn3r' . '<br>'
                            . 'qpaGJ6qszllcYb7ZPPcWA/flHZdswuSrdizaQmf9/wVeY4Q3uwlykLr3K5vrI910bZqwXNNVZ5SvHvkbQxz9o+fWGQ5ZQ/rpOzT9t09Wb11sZ8ua47' . '<br>'
                            . 'pVWpqbljz0lhFr+NtGPW3YL49962NqEp1iZm9iQmWFd05+Mpdq0/Y1PClkuZ43MBH7+riXyL/ltl8o7RWloy7RRLeeMrUynsltsaE5+9mlqXfP/enD' . '<br>'
                            . 'YYYcddthhhx122N/SfgfXXIFK:EDB9';
                    }
                    $prueba =
                        '^XA
                    ^MMT
                    ^PW1217
                    ^LL0600
                    ^LS0
                    ^FO896,256^GFA,01408,01408,00004,:Z64:eJxjYEAHCaOYbPxgFI/igcGMB0YxuXgAAQCDqeRQ:9DFE
                    ^FO0,384^GFA,03712,03712,00116,:Z64:eJztzTENADAMA7BK489xTDYOeXLUJuAZAACWOC/TOEO3daYaJ9R8wX9Lbw==:C1DA
                    ^FO832,0^GFA,01280,01280,00008,:Z64:eJxjYIAD9lF6lKYizdwwSo/S1KNpAgCPhCfl:967B
                    ' . $imagen . '
                    ^FO26,148^GB1191,0,4^FS
                    ^FT897,83^AAN,36,20^FH\^FD' . $data_item->num_pedido . '-' . $data_item->item . '^FS
                    ^FT52,197^A0N,42,40^FH\^FD' . $nombre1 . '^FS
                    ^FT45,309^A0N,42,40^FH\^FDTamano:^FS
                    ^FT189,311^AAN,27,15^FH\^FD' . $porciones_cod[0] . '^FS
                    ^FT45,383^A0N,42,40^FH\^FDCavidades:^FS
                    ^FT237,385^AAN,27,15^FH\^FD' . $data_item->cav_cliente  . '^FS
                    ^FT45,467^A0N,42,40^FH\^FDCore:^FS
                    ^FT359,533^AAN,27,15^FH\^FD' . $orden1 . '^FS
                    ^FT45,531^A0N,42,40^FH\^FDOrden de compra:^FS
                    ^FT148,468^AAN,27,15^FH\^FD' . $data_item->nombre_core . '^FS
                    ^FT478,309^A0N,42,40^FH\^FDCant. por rollo:^FS
                    ^FT738,311^AAN,27,15^FH\^FD' . $formulario['cant_x'] . '^FS
                    ^FT478,383^A0N,42,40^FH\^FDCant. total:^FS
                    ^FT674,385^AAN,27,15^FH\^FD' . $formulario['caja'] . '^FS
                    ^FT478,467^A0N,42,40^FH\^FDLote:^FS
                    ^FT576,468^AAN,27,15^FH\^FD' . $formulario['lote'] . '^FS
                    ^FT970,537^AAN,27,15^FH\^FD' . $ano_compro . 'D' . $dia_compro . 'M' . $mes_compro . '^FS
                    ^FT938,537^AAN,27,15^FH\^FDC^FS
                    ^FT265,467^A0N,42,40^FH\^FDAux:^FS
                    ^FT346,468^AAN,27,15^FH\^FD' . $operario . '^FS
                    ^FO27,327^GB885,0,3^FS
                    ^FO245,408^GB0,86,3^FS
                    ^FO464,260^GB0,234,3^FS
                    ^FT52,250^A0N,42,40^FH\^FD' . $nombre2 . '^FS
                    ^BY182,182^FT938,484^BXN,13,200,0,0,1,~
                    ^FH\^FD' . $data_item->codigo . '^FS
                    ^FO31,491^GB1175,0,3^FS
                    ^FO31,577^GB1191,0,3^FS
                    ^FO25,258^GB1181,0,3^FS
                    ^FT45,572^AAN,27,15^FH\^FD' . $orden2 . '^FS
                    ^PQ' . $formulario['cantidad'] . ',0,1,Y^XZ
                    ';
                    break;
                case '3': //55x33
                    $prueba =
                        '^XA
                    ^MMT
                    ^PW1205
                    ^LL0390
                    ^LS0
                    ^BY168,168^FT330,257^BXN,12,200,0,0,1,~
                    ^FH\^FD' . $data_item->codigo . ';' . $formulario['cant_x'] . '^FS
                    ^FT37,78^A0N,42,40^FH\^FDFecha:^FS
                    ^FT37,130^A0N,42,40^FH\^FD' . date('d/m/Y') . '^FS
                    ^FT37,187^A0N,42,40^FH\^FDO.P.:^FS
                    ^FT132,187^A0N,42,40^FH\^FD' . $data_item->n_produccion . '^FS
                    ^FT37,246^A0N,42,40^FH\^FDLote:^FS
                    ^FT132,246^A0N,42,40^FH\^FD' . $formulario['lote'] . '^FS
                    ^FT37,309^A0N,42,40^FH\^FDCant:^FS
                    ^FT132,309^A0N,42,40^FH\^FD' .  $formulario['cant_x'] . '^FS
                    ^FT341,309^A0N,42,40^FH\^FDAux:^FS
                    ^FT460,309^A0N,42,40^FH\^FD' . $operario . '^FS
                    ^FT124,364^A0N,42,40^FH\^FD' . $data_item->codigo . '^FS
                    ^BY168,168^FT979,257^BXN,12,200,0,0,1,~
                    ^FH\^FD' . $data_item->codigo . ';' . $formulario['cant_x'] . '^FS
                    ^FT686,78^A0N,42,40^FH\^FDFecha:^FS
                    ^FT686,130^A0N,42,40^FH\^FD' . date('d/m/Y') . '^FS
                    ^FT686,187^A0N,42,40^FH\^FDO.P.:^FS
                    ^FT781,187^A0N,42,40^FH\^FD' . $data_item->n_produccion . '^FS
                    ^FT686,246^A0N,42,40^FH\^FDLote:^FS
                    ^FT781,246^A0N,42,40^FH\^FD' . $formulario['lote'] . '^FS
                    ^FT686,309^A0N,42,40^FH\^FDCant:^FS
                    ^FT781,309^A0N,42,40^FH\^FD' .  $formulario['cant_x'] . '^FS
                    ^FT990,309^A0N,42,40^FH\^FDAux:^FS
                    ^FT1109,309^A0N,42,40^FH\^FD' . $operario . '^FS
                    ^FT773,364^A0N,42,40^FH\^FD' . $data_item->codigo . '^FS
                    ^PQ' . $formulario['cantidad'] . ',0,1,Y^XZ
                    ';
                    break;

                default: // zpl no creado 
                    $prueba =
                        '^XA
                        ^MMT
                        ^PW1181
                        ^LL0236
                        ^LS0
                        ^FT474,44^A0N,27,26^FH\^FDZPL NO CREADO^FS
                        ^PQ1,0,1,Y^XZ';
                    break;
            }
            echo $prueba;
            break;

        default: // 200 por defecto
            switch ($formulario['tamano']) {
                case '1': //46x18
                    $prueba =
                        "<br>" .
                        "^XA" ."<br>" .
                        "^MMT" ."<br>" .
                        "^PW783" ."<br>" .
                        "^LT6" ."<br>" .
                        "^LL0144" ."<br>" .
                        "^LS10" ."<br>" .
                        "^BY96,96^FT254,106^BXN,6,200,0,0,1,~" ."<br>" .
                        "^FH\^FD" . $data_item->codigo . ";" . $formulario['cant_x'] . "^FS" ."<br>" .
                        "^FT30,21^A0N,18,19^FH\^FDFecha:^FS" . "<br>" .
                        "^FT91,21^ACN,18,10^FH\^FD" . date('d/m/Y') . "^FS" ."<br>" .
                        "^FT30,42^A0N,18,19^FH\^FDVence:^FS" ."<br>" .
                        "^FT91,42^ACN,18,10^FH\^FD" . $fecha_ano . "^FS" ."<br>" .
                        "^FT30,107^A0N,18,19^FH\^FDCant:^FS" ."<br>" .
                        "^FT91,107^ACN,18,10^FH\^FD" . $formulario['cant_x'] . "^FS" . "<br>" .
                        "^FT30,85^A0N,18,19^FH\^FDAux:^FS" ."<br>" .
                        "^FT91,85^ACN,18,10^FH\^FD" . $operario . "^FS" ."<br>" .
                        "^FT30,65^A0N,18,19^FH\^FDLote:^FS" ."<br>" .
                        "^FT91,65^ACN,18,10^FH\^FD" . $formulario['lote'] . "^FS" ."<br>" .
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
                    break;
                case '2': //100x50
                    if ($check != 1) {
                        $imagen = '';
                    } else {
                        $imagen = 'kI07F0,kG01FIFC0,kG07E003F0,k03E0I03E,k0F0K0F80,jY03C0H08001E0,jY070H01C0H070,jX01E0H01C0H038,jX0380H0360I0E,
                        jX070I0H2J07,jX0E0I0630I0380,jW01C0I0410I01C0,R07FHFL01FHF80J0IF80I07FIFE0H07FHFK07FIFC0I0KFC0H0IFE0K07FFC0K0380I0C10J0
                        E0,R0JFL07FHFE0I07FHFE0I0LFC007FHF80I07FJF80H0LF800FIFK01FIFL070J08180I070,R0JFK01FJFJ0KF80H0LFE00FIF80I0LFE0H0LFC00FIF
                        K07FIF80J060I018080I030,R0JF80I07FJF8003FJFC0H0MFH0JF80I0MFH01FKFE01FIFJ01FJFE0J0C0I0180C0I018,R0JF80I0LFE00FKFE0H0MF80
                        FIF80I0MF801FLF01FIFJ03FKFJ0180I010040I01C,R0JF80H01FKFE01FLFI0MF80FIFC0I0MF801FLF81FIF80H07FKFJ0180I030060J0C,S01FF80H
                        03FF807FF03FFC03FF800FLFC001FFC0I0MFC01FLF8003FF80H0HF801FF80H030J020020J06,S01FFC0H0HFC001FF07FE0H0HFC01FC0H03FC0H0HFC
                        0H01FC0H03FC01F80H07F8001FF8001FE0H07FC0H030J060030J06,S01FFC0H0HF80H07E0FF80H07FC01FC0H01FC001FFC0H01FC0H01FC01F80H03F
                        8003FF8003FC0H03F80H060J040010J03,S03FFC001FE0I0781FF0I03FE01FC0H01FC001FFC0H01FC0H01FC03F80H03F8003FFC007F80H01E0I060J
                        0C00180I03,S03FFC003FC0I0301FE0I01FE01F80H01FC003FFE0H01FC0H01FC03F80H03F8007FFC007F0J080I0C0J080H080I0180,S07FFE007F80
                        K03FC0I01FE01F80H01F8007FFE0H01F80H01FC03F80H03F800FHFC00FE0O0C0I0180H0C0I0180,S0FEFE007F80K07F80J0FE01F80H03F8007EFE0H
                        01F80H01F803F0I03F800FDFC00FE0N0180I010I0C0I0180,S0FCFE00FF0L07F0K0HF03F80H03F0H0FE7E0H03F80H03F803F0I07F001FCFC01FC0N0
                        180I030I040J0C0,R01F87E00FE0L07F0K0HF03F80H0HFI0FC7F0H03F80H07F807F0I0HFH01F8FE01FC0N0180I030I060J0C0,R01F87E00FE0L0FE0
                        K07F07F8003FE001F87F0H03F8003FF00FF8003FE003F0FE01FC0N0180I060I020J0C0,R03F07F01FC0L0FE0K07F7FLFC003F83F007FMF07FLFE007
                        F07E01FMFC0010J060I030J0C0,R07F07F01FC0L0FE0K07FNFI03F03F007FLFE0FMFC007E07E01FMFC0030J040I010J040,R07E03F01FC0K01FC0K0
                        7FNFI07F03F807FLFC0FMF800FE07F03FMFC0030J0C0I0180I060,R0FE03F03F80K01FC0K0PF800FE03F807FLFH0MFE0H0FC07F03FMFC0030J080J0
                        80I060,R0FC03F83F80K01FC0K0PFE00FC01F807FKFC00FLF8001FC03F03FMFC0030I0180J0C0I060,Q01F803F83F80K01FC0K0FE7FLFE01FC01F80
                        7FKFI0LFE0H03F803F01FMF80030I010K040I060,Q03F803F83F80K01FC0J01FE0FF0I0HF01F801FC00FF01FC0H01FE03FC0H03F003F80L03F80030
                        I030K060I060,Q03F001F83F80K01FC0J01FC0FE0I07F03F801FC007E01FE0I0FC01FC0H07F003F80L03F80030I02080I060I060,Q07F003FC3F80K
                        01FC0J03FC0FE0I03F83F801FC007E00FE0I0FC01FC0H07F003F80L03F0H030I0618020030I060,Q0LFC3FC0K01FE0J03F80FE0I03F87FJFC00FE00
                        FF0H01FC00FE0H0LF80L07F0H030I040I03830I060,Q0LFC1FC0K01FE0J07F80FC0I03F8FKFE00FE007F0H01FC00FE001FKFC0L07F0H030I0C0I0F0
                        10I060,P01FKFC1FE0L0FE0J0HFH0FC0I03F8FKFE00FC007F8001FC00FF001FKFC060J0FE0H030I0C0H01E0180H060,P01FKFE1FE0I0180FF0I01FF
                        01FC0I03F1FKFE00FC003F8001F8007F003FKFC1F0I01FE0H030H0180H0HCH080H040,P03FKFE0FF0I07C0FF80H03FE01FC0I07F3FKFE00FC003F80
                        01F8007F007FKFC3F80H03FC0H010H018003E380C0H0C0,P07FKFE0FF80H0FE07FC0H0HFC01FC0I0HF3FKFE01FC003FC003F8003F807FKFE7FC0H07
                        F80H0180010H0701C040H0C0,P07F0I0FE07FE003FF07FF003FF801FC0H03FE7F0I07F01FC001FC003F8003F80FE0I0FE3FE001FF0I018003006301
                        C060H0C0,P0FE0I07F07FFC1FFE03FFC0FHFH01FLFC7F0I07F01F8001FE003F8003FC0FE0I0FE3FF807FE0I0180020071C44020H0C0,P0FC0I07F03
                        FKFC01FKFE001FLFCFE0I07F01F80H0FE003F8001FC1FC0I0FE1FKFC0I0180060118E460300180,O01FC0I07F01FKF800FKF8003FLF9FC0I07F01F8
                        0H0FE003F0H01FE3F80I07E0FKF80J0C00401187100100180,O03F80I07F00FJFE0H07FJFI03FLF1FC0I03F83F80H0HFH03F0I0FE3F80I07F07FJFL
                        0C00C01880I0180180,O03F80I03F803FIF80H03FIFC0H03FKFC3F80I03F83F80H07F007F0I0HF7F0J07F01FIFC0K0600801880I01803,O07F0J03F
                        800FHFE0J07FHFJ03FJFE03F80I03F83F80H07F807F0I0JFK07F007FHFM0601801C810I0C03,gG01FE0L0HFhS0HFN030180060080H0C06,jV03030J
                        040I0406,jV01830J0C10H060C,jR0F0H01C7807FBFF300E18,jQ01F80H0C7FPF18,N07FjIF80H060Q030,N03FjIF80H030Q060,jR0F0I0380P0E0,
                        jW01C0O01C0,jX0E0O0380,jX070O07,jX0380N0C,jY0E0M038,jY0780L0F0,jY01E0K03C0,k0F80J0F,k01F0I07C,kG07F80FF0,kH07FHF,kI01C0,,';
                    }
                    $prueba = '~DG000.GRF,01024,004,
                    ,:::::::::::::::H060,:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
                    ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::,:::::::::::::::::
                    ::::::::~DG001.GRF,02560,080,
                    ,:::::::::::::::::K0mOFE0,:,:::::::::::~DG002.GRF,00512,004,
                    03,:::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::,::::::::::::::::::::::
                    :::::~DG003.GRF,05760,060,
                    ,:::::::::::::::::' . $imagen . ':::::::^XA
                    ^MMT
                    ^PW823
                    ^LL0406
                    ^LS0
                    ^FT608,416^XG000.GRF,1,1^FS
                    ^FT0,288^XG001.GRF,1,1^FS
                    ^FT576,128^XG002.GRF,1,1^FS
                    ^FT64,96^XG003.GRF,1,1^FS
                    ^FO17,100^GB806,0,3^FS
                    ^FT607,58^AAN,27,15^FH\^FD' . $data_item->num_pedido . '-' . $data_item->item . '^FS
                    ^FT35,133^A0N,28,28^FH\^FD' . $nombre1 . '^FS
                    ^FT30,209^A0N,28,28^FH\^FDTamano:^FS
                    ^FT128,210^AAN,18,10^FH\^FD' . $porciones_cod[0] . '^FS
                    ^FT30,259^A0N,28,28^FH\^FDCavidades:^FS
                    ^FT160,260^AAN,18,10^FH\^FD' . $data_item->cav_cliente  . '^FS
                    ^FT30,315^A0N,28,28^FH\^FDCore:^FS
                    ^FT243,360^AAN,18,10^FH\^FD' . $orden1 . '^FS
                    ^FT30,359^A0N,28,28^FH\^FDOrden de compra:^FS
                    ^FT100,317^AAN,18,10^FH\^FD' . $data_item->nombre_core . '^FS
                    ^FT323,209^A0N,28,28^FH\^FDCant. por rollo:^FS
                    ^FT499,210^AAN,18,10^FH\^FD' . $formulario['cant_x'] . '^FS
                    ^FT323,259^A0N,28,28^FH\^FDCant. total:^FS
                    ^FT456,260^AAN,18,10^FH\^FD' . $formulario['caja'] . '^FS
                    ^FT323,315^A0N,28,28^FH\^FDLote:^FS
                    ^FT390,317^AAN,18,10^FH\^FD' . $formulario['lote'] . '^FS
                    ^FT656,363^AAN,18,10^FH\^FD' . $ano_compro . 'D' . $dia_compro . 'M' . $mes_compro . '^FS
                    ^FT634,363^AAN,18,10^FH\^FDC^FS
                    ^FT179,315^A0N,28,28^FH\^FDAux:^FS
                    ^FT234,317^AAN,18,10^FH\^FD' . $operario . '^FS
                    ^FO18,221^GB599,0,2^FS
                    ^FO166,276^GB0,58,2^FS
                    ^FO314,176^GB0,159,2^FS
                    ^FT35,169^A0N,28,28^FH\^FD' . $nombre2 . '^FS
                    ^BY126,126^FT635,330^BXN,9,200,0,0,1,~
                    ^FH\^FD' . $data_item->codigo . '^FS
                    ^FO20,333^GB795,0,2^FS
                    ^FO20,390^GB806,0,2^FS
                    ^FO16,175^GB799,0,2^FS
                    ^FT30,387^AAN,18,10^FH\^FD' . $orden2 . '^FS
                    ^PQ' . $formulario['cantidad'] . ',0,1,Y^XZ
                    ^XA^ID000.GRF^FS^XZ
                    ^XA^ID001.GRF^FS^XZ
                    ^XA^ID002.GRF^FS^XZ
                    ^XA^ID003.GRF^FS^XZ
                    ';
                    break;
                case '3': //55x33
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
                    break;

                default: // zpl no creado 
                    $prueba =
                        '^XA
                        ^MMT
                        ^PW799
                        ^LL0160
                        ^LS0
                        ^FT320,30^A0N,18,16^FH\^FDZPL NO CREADO^FS
                        ^PQ1,0,1,Y^XZ';
                    break;
            }
            echo $prueba;
            break;
    }

    ?>
</div>