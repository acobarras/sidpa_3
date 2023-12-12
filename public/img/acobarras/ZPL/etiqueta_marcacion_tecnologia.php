<div>
    <?php
        switch ($_POST['resolucion']) {
            case '300':
                $zpl = '^XA
                ^MMT
                ^PW1228
                ^LL0390
                ^LS0
                ^FT21,68^A0N,42,40^FH\^FDN. Parte:^FS
                ^FT17,215^A0N,42,40^FH\^FDDescripcion:^FS
                ^FT15,370^A0N,42,40^FH\^FDCant:^FS
                ^FT471,182^BQN,2,6
                ^FH\^FDLA,'.$_POST['formulario']['nparte'].';'.$_POST['formulario']['cantidad'].'^FS
                ^FT21,105^AAN,36,20^FH\^FD'.$_POST['formulario']['nparte2'][0].'^FS
                ^FT232,207^AAN,36,20^FH\^FD'.$_POST['formulario']['descripcion1'].'^FS
                ^FT17,252^AAN,36,20^FH\^FD'.(isset($_POST['formulario']['descripcion2'][0]) ? $_POST['formulario']['descripcion2'][0] : '').'^FS
                ^FT17,291^AAN,36,20^FH\^FD'.(isset($_POST['formulario']['descripcion2'][1]) ? $_POST['formulario']['descripcion2'][1] : '').'^FS
                ^FT108,370^AAN,36,20^FH\^FD'.$_POST['formulario']['cantidad'].'^FS
                ^FO0,168^GB613,0,3^FS
                ^FO0,316^GB612,0,3^FS
                ^FT21,145^AAN,36,20^FH\^FD'.(isset($_POST['formulario']['nparte2'][1]) ? $_POST['formulario']['nparte2'][1] : '').'^FS
                ^FO463,0^GB0,168,3^FS
                ^FT635,68^A0N,42,40^FH\^FDN. Parte:^FS
                ^FT631,215^A0N,42,40^FH\^FDDescripcion:^FS
                ^FT629,370^A0N,42,40^FH\^FDCant:^FS
                ^FT1085,182^BQN,2,6
                ^FH\^FDLA,'.$_POST['formulario']['nparte'].';'.$_POST['formulario']['cantidad'].'^FS
                ^FT635,105^AAN,36,20^FH\^FD'.$_POST['formulario']['nparte2'][0].'^FS
                ^FT846,207^AAN,36,20^FH\^FD'.$_POST['formulario']['descripcion1'].'^FS
                ^FT631,252^AAN,36,20^FH\^FD'.(isset($_POST['formulario']['descripcion2'][0]) ? $_POST['formulario']['descripcion2'][0] : '').'^FS
                ^FT631,291^AAN,36,20^FH\^FD'.(isset($_POST['formulario']['descripcion2'][1]) ? $_POST['formulario']['descripcion2'][1] : '').'^FS
                ^FT722,370^AAN,36,20^FH\^FD'.$_POST['formulario']['cantidad'].'^FS
                ^FO614,168^GB613,0,3^FS
                ^FO614,316^GB612,0,3^FS
                ^FT635,145^AAN,36,20^FH\^FD'.(isset($_POST['formulario']['nparte2'][1]) ? $_POST['formulario']['nparte2'][1] : '').'^FS
                ^FO1077,0^GB0,168,3^FS
                ^PQ'.$_POST['formulario']['cantidad_eti'].',0,1,Y^XZ';
                echo $zpl;
                break;
                
                default://200
                $zpl = '^XA
                ^MMT
                ^PW831
                ^LL0264
                ^LS0
                ^FT14,46^A0N,28,28^FH\^FDN. Parte:^FS
                ^FT12,145^A0N,28,28^FH\^FDDescripcion:^FS
                ^FT10,250^A0N,28,28^FH\^FDCant:^FS
                ^FT319,122^BQN,2,4
                ^FH\^FDLA'.$_POST['formulario']['nparte'].';'.$_POST['formulario']['cantidad'].'^FS
                ^FT14,73^AFN,27,15^FH\^FD'.$_POST['formulario']['nparte2'][0].'^FS
                ^FT157,142^AFN,27,15^FH\^FD'.$_POST['formulario']['descripcion1'].'^FS
                ^FT12,172^AFN,27,15^FH\^FD'.(isset($_POST['formulario']['descripcion2'][0]) ? $_POST['formulario']['descripcion2'][0] : '').'^FS
                ^FT12,199^AFN,27,15^FH\^FD'.(isset($_POST['formulario']['descripcion2'][1]) ? $_POST['formulario']['descripcion2'][1] : '').'^FS
                ^FT73,248^AFN,27,15^FH\^FD'.$_POST['formulario']['cantidad'].'^FS
                ^FO0,114^GB415,0,2^FS
                ^FO0,214^GB414,0,2^FS
                ^FT14,100^AFN,27,15^FH\^FD'.(isset($_POST['formulario']['nparte2'][1]) ? $_POST['formulario']['nparte2'][1] : '').'^FS
                ^FO313,0^GB0,114,2^FS
                ^FT430,46^A0N,28,28^FH\^FDN. Parte:^FS
                ^FT428,145^A0N,28,28^FH\^FDDescripcion:^FS
                ^FT426,250^A0N,28,28^FH\^FDCant:^FS
                ^FT735,112^BQN,2,4
                ^FH\^FDLA'.$_POST['formulario']['nparte'].';'.$_POST['formulario']['cantidad'].'^FS
                ^FT430,73^AFN,27,15^FH\^FD'.$_POST['formulario']['nparte2'][0].'^FS
                ^FT573,142^AFN,27,15^FH\^FD'.$_POST['formulario']['descripcion1'].'^FS
                ^FT428,172^AFN,27,15^FH\^FD'.(isset($_POST['formulario']['descripcion2'][0]) ? $_POST['formulario']['descripcion2'][0] : '').'^FS
                ^FT428,199^AFN,27,15^FH\^FD'.(isset($_POST['formulario']['descripcion2'][1]) ? $_POST['formulario']['descripcion2'][1] : '').'^FS
                ^FT489,248^AFN,27,15^FH\^FD'.$_POST['formulario']['cantidad'].'^FS
                ^FO416,114^GB415,0,2^FS
                ^FO416,214^GB414,0,2^FS
                ^FT430,100^AFN,27,15^FH\^FD'.(isset($_POST['formulario']['nparte2'][1]) ? $_POST['formulario']['nparte2'][1] : '').'^FS
                ^FO729,0^GB0,114,2^FS
                ^PQ'.$_POST['formulario']['cantidad_eti'].',0,1,Y^XZ
                ';
                echo $zpl;
                break;
        }
    ?>
</div>