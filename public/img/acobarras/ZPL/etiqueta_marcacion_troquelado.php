<div>
    <?php

    use MiApp\negocio\util\Validacion;

    $nombre = $_POST['datos_persona'][0]->nombres;
    $apellido = $_POST['datos_persona'][0]->apellidos;
    $nombre = Validacion::quitarTildes($nombre);
    $apellido = Validacion::quitarTildes($apellido);
    


    switch ($_POST['resolucion']) {
        case '300':
            $zpl = '
            ^XA
            ^MMT
            ^PW1205
            ^LL0390
            ^FO24,13^GB585,368,3^FS
            ^FT37,77^A0N,38,38^FH\^FD' . $nombre . '^FS
            ^FT37,128^A0N,38,38^FH\^FD' . $apellido . '^FS
            ^FT39,278^A0N,100,98^FH\^FDOP: ^FS
            ^FT182,278^A0N,100,98^FH\^FD' . $_POST['data']['op'] . '^FS
            ^FT39,356^AAN,36,20^FH\^FDITEM:^FS
            ^FT185,356^AAN,36,20^FH\^FD' . $_POST['data']['item'] . '^FS
            ^FO673,13^GB585,368,3^FS
            ^FT686,77^A0N,38,38^FH\^FD' . $nombre . '^FS
            ^FT686,128^A0N,38,38^FH\^FD' . $apellido . '^FS
            ^FT688,278^A0N,100,98^FH\^FDOP: ^FS
            ^FT831,278^A0N,100,98^FH\^FD' . $_POST['data']['op'] . '^FS
            ^FT688,356^AAN,36,20^FH\^FDITEM:^FS
            ^FT834,356^AAN,36,20^FH\^FD' . $_POST['data']['item'] . '^FS
            ^PQ' . $_POST['data']['cantidad'] . ',0,1,Y^XZ';
            break;

        default: // 200 
            $zpl = '
            ^XA
            ^MMT
            ^PW831
            ^LL0264
            ^FO16,9^GB396,249,2^FS
            ^FT25,52^A0N,25,26^FH\^FD' . $nombre . '^FS
            ^FT25,86^A0N,25,26^FH\^FD' . $apellido . '^FS
            ^FT26,187^A0N,68,67^FH\^FDOP: ^FS
            ^FT123,187^A0N,68,67^FH\^FD' . $_POST['data']['op'] . '^FS
            ^FT26,243^AAN,27,15^FH\^FDITEM:^FS
            ^FT125,243^AAN,27,15^FH\^FD' . $_POST['data']['item'] . '^FS
            ^FO456,9^GB396,249,2^FS
            ^FT465,52^A0N,25,26^FH\^FD' . $nombre . '^FS
            ^FT465,86^A0N,25,26^FH\^FD' . $apellido . '^FS
            ^FT466,187^A0N,68,67^FH\^FDOP: ^FS
            ^FT563,187^A0N,68,67^FH\^FD' . $_POST['data']['op'] . '^FS
            ^FT466,243^AAN,27,15^FH\^FDITEM:^FS
            ^FT565,243^AAN,27,15^FH\^FD' . $_POST['data']['item'] . '^FS
            ^PQ' . $_POST['data']['cantidad'] . ',0,1,Y^XZ';
            break;
    }
    echo $zpl;
    ?>
</div>