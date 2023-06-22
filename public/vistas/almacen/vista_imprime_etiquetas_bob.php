<div id="div">
    <?php


    if (!empty($_GET['datos'])) {
        # code...
        $datos = json_decode($_GET['datos']);

        $op = $datos->num_produccion;
        $maquina = $datos->maquina;
        $metros = $datos->metros_lineales_final;
        $material = $datos->material;
        date_default_timezone_set('America/Bogota');
        $fecha = date('d/m/Y');
        $prueba = "";
        //imprimir etiqueta de marcacion para las bobinas
        if (isset($datos->array_cant_m)) {
            $anchos = $datos->array_cant_m;

            foreach ($anchos as $ancho) {
                $cantidad_impresion = ($ancho->cantidad / 1000);
                $prueba .= '^XA' .
                    '^LH0,20' .
                    '^MMT' .
                    '^PW799' .
                    '^LL0400' .
                    '^LS0' .
                    '^FT366,220^A0N,45,45^FH\^FD' . $op . '^FS' .
                    '^FT369,105^A0N,45,45^FH\^FD' . $maquina . '^FS' .
                    '^FT48,220^A0N,45,45^FH\^FD ORDEN PROD:^FS' .
                    '^FT48,107^A0N,45,45^FH\^FDMAQUINA :^FS' .
                    '^FT562,160^A0N,45,45^FH\^FD' . $metros . '^FS' .
                    '^FT209,160^A0N,45,45^FH\^FD' . $ancho->ancho . '^FS' .
                    '^FT367,160^A0N,45,45^FH\^FD' . $ancho->cantidad . ' DE^FS' .
                    '^FT355,282^A0N,45,45^FH\^FD' . $material . '^FS' .
                    '^FT369,51^A0N,45,45^FH\^FD' . $fecha . '^FS' .
                    '^FT48,160^A0N,45,45^FH\^FDANCHO :^FS' .
                    '^FT48,282^A0N,45,45^FH\^FDMATERIAL:^FS' .
                    '^FT48,57^A0N,45,45^FH\^FDFECHA :^FS' .
                    '^BY2,3,47^FT51,345^BCN,,Y,N' .
                    '^FD> :' . $op . '^FS' .
                    '^LRY^FO1,176^GB796,0,60^FS^LRN' .
                    '^PQ' . ceil($cantidad_impresion) . ',0,1,Y^XZ<br>';
            }
        } else {
            $cantidad_impresion = ($metros / 1000);
            $ancho = $datos->ancho;
            $prueba = '^XA' .
                '^LH0,20' .
                '^MMT' .
                '^PW799' .
                '^LL0400' .
                '^LS0' .
                '^FT366,220^A0N,45,45^FH\^FD' . $op . '^FS' .
                '^FT369,105^A0N,45,45^FH\^FD' . $maquina . '^FS' .
                '^FT48,220^A0N,45,45^FH\^FD ORDEN PROD:^FS' .
                '^FT48,107^A0N,45,45^FH\^FDMAQUINA :^FS' .
                '^FT562,160^A0N,45,45^FH\^FD' . $metros . '^FS' .
                '^FT209,160^A0N,45,45^FH\^FD' . $ancho . '^FS' .
                '^FT367,160^A0N,45,45^FH\^FDMETROS :^FS' .
                '^FT355,282^A0N,45,45^FH\^FD' . $material . '^FS' .
                '^FT369,51^A0N,45,45^FH\^FD' . $fecha . '^FS' .
                '^FT48,160^A0N,45,45^FH\^FDANCHO :^FS' .
                '^FT48,282^A0N,45,45^FH\^FDMATERIAL:^FS' .
                '^FT48,57^A0N,45,45^FH\^FDFECHA :^FS' .
                '^BY2,3,47^FT51,345^BCN,,Y,N' .
                '^FD> :' . $op . '^FS' .
                '^LRY^FO1,176^GB796,0,60^FS^LRN' .
                '^PQ' . ceil($cantidad_impresion) . ',0,1,Y^XZ<br>';
        }
        echo $prueba;
    } else if (isset($_GET['datos_trasavilidad'])) {
        $datosS = json_decode($_GET['datos_trasavilidad'], true);
        $datos_array =  $datosS;

        $data_item = json_decode($datos_array[0]);
        $data_form = json_decode($datos_array[1]);
        $operario = "0" . ($datos_array[2]['id_persona']);

        // 46x18
        if ($data_form[0]->value == 1) {
            $prueba = "^XA" .
                "^MMT" .
                "^PW783" .
                "^LL0144" .
                "^LL0144" .
                "^LS0" .

                "^FT746,121^A0I,20,19^FH\^FDFecha:^FS " .
                "^FT684,120^A0I,23,24^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT746,95^A0I,20,19^FH\^FDO.P.:^FS " .
                "^FT684,94^A0I,23,24^FH\^FD" . $data_item->n_produccion . "^FS " .
                "^FT746,67^A0I,20,19^FH\^FDCant:^FS " .
                "^FT684,66^A0I,23,24^FH\^FD" . $data_form[4]->value . "^FS " .
                "^FT746,44^A0I,20,19^FH\^FDLote:^FS " .
                "^FT684,43^A0I,23,24^FH\^FD" . $data_form[1]->value . "^FS " .
                "^FT693,14^A0I,25,24^FH\^FD" . $data_item->codigo . "^FS " .
                "^FT588,80^A0I,20,19^FH\^FDAux:^FS " .
                "^FT547,79^A0I,23,24^FH\^FD" . $operario . "^FS " .
                "^BY80,80^FT494,57^BXI,5,200,0,0,1,~ " .
                "^FH\^FD" . $data_item->codigo . ";" . $data_form[4]->value . "^FS " .

                "^FT362,121^A0I,20,19^FH\^FDFecha:^FS " .
                "^FT300,120^A0I,23,24^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT362,95^A0I,20,19^FH\^FDO.P.:^FS " .
                "^FT300,94^A0I,23,24^FH\^FD" . $data_item->n_produccion . "^FS " .
                "^FT362,67^A0I,20,19^FH\^FDCant:^FS " .
                "^FT300,66^A0I,23,24^FH\^FD" .  $data_form[4]->value . "^FS " .
                "^FT362,44^A0I,20,19^FH\^FDLote:^FS " .
                "^FT300,43^A0I,23,24^FH\^FD" . $data_form[1]->value . "^FS " .
                "^FT309,14^A0I,25,24^FH\^FD" . $data_item->codigo . "^FS " .
                "^FT204,80^A0I,20,19^FH\^FDAux:^FS " .
                "^FT163,79^A0I,23,24^FH\^FD" . $operario . "^FS " .
                "^BY80,80^FT110,57^BXI,5,200,0,0,1,~ " .
                "^FH\^FD" . $data_item->codigo . ";" . $data_form[4]->value . "^FS " .

                "^PQ" . $data_form[3]->value . ",0,1,Y^XZ";
        } else {
            // 100x50 
            $porciones_cod = explode("-", $data_item->codigo);

            $empresa = $data_item->nombre_empresa;
            if (strlen($empresa) >= 47) {
                $nombre1 = substr($empresa, 0, 47);
                $nombre2 = substr($empresa, 48, strlen($empresa));
            } else {
                $nombre1 = substr($empresa, 0, strlen($empresa));
                $nombre2 = "";
            }
            $prueba = "^XA" .
                "^MMT" .
                "^PW831" .
                "^LL0400" .
                "^LS0" .
                "^FO310,310^GFA,5146,5146,62,M01JFC,L01LFE,L07MFiQ0F,K03NFEhX02P01F,K0IFJ07FF8hW07P03F,J01FFL07FEhW0FP038,</br>" .
                "J07FCL01FFO0399C3C7C33B81F1F8E078I078FC3187E38F3BC70F81F800E1E3F18CC06781F83818C7I078FC71C7E38F1BC70FC1FC,</br>" .
                "J0FFN07FCN03BDCFDFE73F87F3FCE3FE001FBFC71CFE7BFBFE71FC7FC00F7EFF99FE067F7F8FC38C7001FDFE71CFF3BF9FE71FC3FC,</br>" .
                "I01FCO0FEN0399CFDCE73FCFF3DCE3FE001FBCC739E673BBEE73DCF8C00F7EF399FE0E7F798FC38CF001EB8E71CE73BF9FE73CC7CC,</br>" .
                "I07F8O07FQ01C19F73DDE779DE78E003C33EF39E6773BEE778CFJ0EF0C799F60E7F67DEC38CE001C39E73DC77339E6738CF,</br>" .
                "I07EP03F8P01F07F7399CE71DCE0E003E07EE3986703B8E771DEK0F81F99EE0E7B0FDCC31CE001E0FEE3B867039CE770CE,</br>" .
                "I0FCP01FCQ0F9FFE7BBCEF3DDE4E001F1FEE3B8E707B9EEF3DCK07C7FB9CE1CF73FD8C71CE001F9FEE3B8E703B8E771DE,</br>" .
                "003F8Q07EQ079FCE77BBCE799FCEI07BFCEFB9EF0779CEE79EK01EFF39FDBCEF7F81EF78EI079FCCF39E70739EE73DE,</br>" .
                "003FR03FQ079F8E6F3FCFF19FCEI073F9DF3FCE2E71CCFF9FEJ01CFE73B9FCFE7F07FE78EI079F8DF3FC73E39CE7F9FE,</br>" .
                "007ER01F8O03F9F8E7F3FCFF39FDE003F3F1FE3FCE7E71CCFF1FFJ0FCFC73F9FCFC7E3JF9C003F1F0FF3F8E7E79CE7F0FF,</br>" .
                "00FCS0FCO03E0E1E7C1DC7C39C3C003C1C0FE1F0C7C71DC7C027J0F07073E1FCF8383F87F9C003E0C0FE1F047C71CC3E0378,</br>" .
                "01VFCS01CI0380038078U0301CJ07N07P0380018U0300CJ07,03VFES01CI03800718FR01CJ01CI01EN07P0380038Q01CJ01CJ0F,</br>" .
                "03ETF9FS018I0380071FER01CJ038I03EN0EP0380038Q01CJ01CI03E,03C7SF9FS018I0380071F8S08J038007FCN0ET038Q018J038003FC,</br>" .
                "0FC7801BFDIFC00F8F8R018I0380071F8X038007FO0ET038W038007F8,0F87C01BKFC01F0FCR018M02gG018003EO04gS018003E,</br>" .
                "0F03E01F7JFC01E07C,1F01E01FFDIFC01E03CI03,1E01F01BFF7EFC03E03EI0FC,1E00F01BEFFEFC03C01E001jYFE,</br>" .
                "3E00F01BIFE7C07C01F001jYFE,7C00F81KF7C0F801F001jYFE,7800781IFDFFC0FI0F801jYFE,78007C1LFC0FI0F801FE,</br>" .
                "78003C0LFC0FI07800FC,F8003E1LFC1EI07800F8,FI01E1FEJFC3EI078M01FFCiG07FFM01FFE,</br>" .
                "FI01E1JFBFC3EI07CM07IF803FFL0JFEJ07FE03FF8I01FF0FFCK03FF81MF8J03IFCL07IF8003FFL0FFE,</br>" .
                "FI01F0KFCC7CI03CL03JFE03FFK01KFJ07FE03FF8I01FF0FFCK03FF8NF8I01KF8J03KF003FFL0FFC,</br>" .
                "FJ0F8IFBF0C78I03CK01LF83FFK01KFJ07FE01FFCI03FF0FFEK07FF7NF8I0LFEJ0LFC03FF8J03FFC,</br>" .
                "FJ079LFC78I03CK01LFC1FF8J01KFJ07FC01FFCI03FF07FEK07FE7NF8I0MFI03LFE01FF8J03FF8,</br>" .
                "FJ079LFCF8I03CK07LFE1FF8J03FFBFF8I0FFC01FFEI03FF07FEK0RF8003MFC007MF01FF8J03FF,</br>" .
                "FJ07DBEJFDFJ03CK0NF1FFCJ07FF3FFCI0FFC00FFEI03FF07FEJ01RF800NFE01NFC1FF8J07FF,</br>" .
                "FJ03DBEF6FFDEJ03CJ03NF8FFCJ07FE1FFCI0FFC007FEI03FF07FEJ01RFI0NFE03NFC1FF8J07FE,</br>" .
                "FJ03DBEJFDEJ03CJ07NFCFFCJ0FFE1FFCI0FFC007FFI03FE07FFJ03RF003OF07NFE1FFCJ0FFE,</br>" .
                "FJ01FFE7JFEJ03CJ07FFE01IFEOFC0FFEI0FFC007FFI03FE03YF007JF1JF8JFC3JF0OFC,</br>" .
                "FK0FFE7F3FFCJ03CJ0IFI03FFEOF80FFEI0FF8003FFI07FE03RFJ03FE007FFE001IF8IFC003IF0OF8,</br>" .
                "FK0FL03CJ03CI01IFI03RF80FFEI0FF8003FF8007FE03QFEJ03FE01IFCI0IFC3FF8001IF8OF8,</br>" .
                "FK0F8K078J03CI03FFCJ0FFE7NF007FF001FF8003FFC007FE01NFDFFCJ07FE01IFJ07FFC1FCJ07FF8OF,</br>" .
                "FK078K0FK03CI03FF8J07F87MFE007FF001FF8001FFC007FE01NFDFF8J07FE03FFEJ01FFC0F8J07FF8OF,</br>" .
                "FK078K0FK03CI07FFK07F07MFE007FF801FFJ0FFC007FE01NFBFFK07FE03FFCJ01FFE078J03FFC7MFE,</br>" .
                "FK07CJ01FK03CI0FFEK01C07MFC003FF803FFJ0FFE007FC01NFBFFK07FE0IF8K0FFE02K01FFC7MFC,</br>" .
                "FK03CJ01EK07CI0FFCN07MFC001FF803FFJ0FFE007FC00MFE3FFK07FE0IFL0FFEM01FFC3MFC,</br>" .
                "FK01EJ01EK078I0FFCN03MFC001FFC03FFJ07FE00FFC00MFE3FFK07FC0FFEL0FFEN0FFC3MF8,</br>" .
                "F8J01EJ03EK078I0FFCN03FF003FFI01FFC03FFJ07FF00FF800FFC00FFE3FF8J0FFC1FFCL07FFN0FFC3FF003FF8,</br>" .
                "78K0FJ03CK078001FF8N03FF003FFJ0FFC03FFJ03FF80FF800FFC00FFC1FFCJ0FFC1FFCL07FFN0FFC3FF007FE,</br>" .
                "78K0FJ078K078001PFC1FF807FEJ0NF8003MFE0FFC01FFC1SF8L07FFN0FFC3FF007FE,</br>" .
                "78K0FJ078K0F8001PFC1FF807FEI03NF800NFC0FFC01FF81SF8L07FEN0FFC1FF007FE,</br>" .
                "7CK0F8I0F8J01FI01PFC1FF80FFE001OF807NFC07FE03FF00SF8L07FEN0FFC1FF80FFC,</br>" .
                "3EK07CI0FK01FI01PFC1FF81FF800PF81OFC07FE07FF007RFM07FEN0FFC1FF81FFC,</br>" .
                "3EK03CI0FK01EI01PFC1FF81FF800PF03OFC03FE07FE003RFM07FEN0FFC1FF81FF8,</br>" .
                "1EK03C001FK03EI03PFC0FF83FF801PF0PFC03FF0FFE001RFM0FFEN0FFC1FF83FF,</br>" .
                "1FK01E003EK03EI03PFC0FFC3FF007PF0PFC03FF1FFC003RFM0FFCM01FFC1FF87FF,</br>" .
                "0FK01E003CK03CI03PFC0FFC7FE007OFE1PFC03FF1FF8003QFEM0FFCM01FF80FF87FE,</br>" .
                "0F8J01E003CK07CI03PFC0FFCFFE007OFE1PF803FF1FF8007RFL01FFCM01FF80FFC7FE,</br>" .
                "0FCK0F0078K0F8Q01FF80FFCFFC00PFE3PF801FF3FF001SFL01FFCM03FF807FDFFC,</br>" .
                "07CK078078K0FR01FF807FCFF800FFEJ0FFC07FF8I03FFI01FF3FE001FF8I03FF07FFL01FF8M07FF007FDFF8,</br>" .
                "03EK078078J01FR03FF007JF800FFCJ0FF807FFJ03FFI01JFE003FF8I03FF03FF8K07FF8M0IF007JF8,</br>" .
                "03FK07C0FK03EL02K03FF003JF001FF8I01FF807FEJ03FEI01JFC007FFJ03FF03FF8K0IF81K01FFE007JF,</br>" .
                "01F8J03C1EK07EL06K07FF003IFE001FF8I01FF807FEJ03FEI01JF8007FFJ03FF03FF8K0IF03K01FFE007IFE,</br>" .
                "01F8J03C1EK0FCK01EK0FFE003IFE001FF8I01FF807FCJ07FEJ0JF8007FEJ03FE01FFCJ03FFE0F8J03FFC003IFE,</br>" .
                "007CJ01E3EJ01F8K0FF8I01FFE003IFC003FF8I01FF807FCJ07FCJ0JFI07FEJ07FE01FFEJ07FFE1FCJ0IFC003IFC,</br>" .
                "003EK0E3CJ03FL0FF8I03FFC003IFC003FF8I01FF807FEJ07FCJ0IFEI07FFJ07FE01IFJ0IFC7FEI01IF8003IF8,</br>" .
                "003FK0F3CJ07FK03FFCI07FF8003IF8001FFCI01FF807FFJ0FFCJ07FFEI07FF8I07FE01IF8003IF8IF8007IFI03IF8,</br>" .
                "001FCJ0F78I01FCK03IF803IF8001KFC1OF007NFCJ07KF07NFE00JF01JF1IFE07IFEI03KFC,</br>" .
                "I0FEJ07FJ01F8K01IFE0JFI01KFC1OF007NFCJ07KF07NFE007NFE0OFCI01KFC,</br>" .
                "I07FJ07FJ07F8K01NFEI01KFC1OF007NFCJ07KF07NFE003NFE0OF8I01KFC,</br>" .
                "I01FCI07FJ0FEM0NFCI01KF80OF007NFCJ07JFE03NFC003NF807NFJ01KFC,</br>" .
                "I01FEI03FI03FCM07MF8I01KF80OF003NF8J03JFE03NFC001NF007MFEK0KF8,</br>" .
                "J07FC001E001FFN03LFEK0KF807NF001NF8J03JFE01NFCI0MFC001MFCK0KF8,</br>" .
                "J01FF001E003FEN01LFCK0KF803MFEI0NF8J03JFE00NF8I07LF8I0MFL0KF8,</br>" .
                "K0FFE01C03FFCO0LF8K07JF801MFEI07MF8J03JFE007MF8I03KFEJ07KFEL0KF,</br>" .
                "K03IFC9IFEP03JFEL07JF8007LFEI01MF8J03JFE001MF8J07JF8J01KFM0KF,L07MF8Q07FFEiG0IF8L03IF,L03LFES0FEiH01FCN03F8,</br>" .
                "M03KF,O07F,^FS" .
                "^FT244,348^A0I,25,24^FH\^FDFecha:^FS " .
                "^FT178,348^A0I,25,24^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT268,314^A0I,25,24^FH\^FDConsecutivo:^FS " .
                "^FT128,314^A0I,25,24^FH\^FD" . $data_item->num_pedido . "^FS " .
                "^FT779,25^A0I,25,24^FH\^FDFecha Comp:^FS " .
                "^FT779,66^A0I,25,24^FH\^FDCantidad Total:^FS " .
                "^FT779,107^A0I,25,24^FH\^FDEtq. por Rollo:^FS " .
                "^FT779,146^A0I,25,24^FH\^FDCavidades:^FS " .
                "^FT643,25^A0I,25,24^FH\^FD" . str_replace('-', '/', $data_item->fecha_compromiso) . "^FS " .
                "^FT623,66^A0I,25,24^FH\^FD" . $data_form[2]->value . "^FS " .
                "^FT630,107^A0I,25,24^FH\^FD" .   $data_form[4]->value . "^FS " .
                "^FT666,146^A0I,25,24^FH\^FD" . $data_item->cav_presentacion . "^FS " .
                "^FT431,146^A0I,25,24^FH\^FD" . $data_form[1]->value . "^FS " .
                "^FT431,107^A0I,25,24^FH\^FD" . $operario . "^FS " .
                "^FT491,146^A0I,25,24^FH\^FDLote:^FS " .
                "^FT432,70^A0I,25,24^FH\^FD" . $data_item->n_produccion . "^FS " .
                "^FT491,107^A0I,25,24^FH\^FDAux:^FS " .
                "^FT490,70^A0I,25,24^FH\^FDO.P.:^FS " .
                "^FT491,186^A0I,25,24^FH\^FDCore:^FS " .
                "^FT641,186^A0I,25,24^FH\^FD" . $porciones_cod[0] . "^FS " .
                "^FT779,186^A0I,25,24^FH\^FDDimensiones:^FS " .
                "^FT431,186^A0I,25,24^FH\^FD" . $data_item->nombre_core . "^FS " .
                "^FT779,260^A0I,25,24^FH\^FDCliente:^FS " .
                "^FT695,260^A0I,25,24^FH\^FD" . $nombre1 . "^FS " .
                "^FT695,229^A0I,25,24^FH\^FD" . $nombre2 . "^FS " .
                "^BY176,176^FT238,26^BXI,11,200,0,0,1,~ " .
                "^FH\^FD" . $data_item->codigo . "^FS " .
                "^PQ" . $data_form[3]->value . ",0,1,Y^XZ";
        }
        echo $prueba;
    } else {
        $datosS = json_decode($_GET['datos_trasavilidad_impresion'], true);
        $datos_array =  $datosS;

        $data_item[0] = json_decode($datos_array[0]);
        $data_form = json_decode($datos_array[1]);
        $operario = "0" . ($datos_array[2]['id_persona']);

        // 46x18
        if ($data_form[0]->value == 1) {
            $prueba = "^XA" .
                "^MMT" .
                "^PW783" .
                "^LL0144" .
                "^LL0144" .
                "^LS0" .

                "^FT746,121^A0I,20,19^FH\^FDFecha:^FS " .
                "^FT684,120^A0I,23,24^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT746,95^A0I,20,19^FH\^FDO.P.:^FS " .
                "^FT684,94^A0I,23,24^FH\^FD" . $data_item[0]->n_produccion . "^FS " .
                "^FT746,67^A0I,20,19^FH\^FDCant:^FS " .
                "^FT684,66^A0I,23,24^FH\^FD" . $data_form[4]->value . "^FS " .
                "^FT746,44^A0I,20,19^FH\^FDLote:^FS " .
                "^FT684,43^A0I,23,24^FH\^FD" . $data_form[1]->value . "^FS " .
                "^FT693,14^A0I,25,24^FH\^FD" . $data_item[0]->codigo . "^FS " .
                "^FT588,80^A0I,20,19^FH\^FDAux:^FS " .
                "^FT547,79^A0I,23,24^FH\^FD" . $operario . "^FS " .
                "^BY80,80^FT494,57^BXI,5,200,0,0,1,~ " .
                "^FH\^FD" . $data_item[0]->codigo . ';' . $data_form[4]->value . "^FS " .

                "^FT362,121^A0I,20,19^FH\^FDFecha:^FS " .
                "^FT300,120^A0I,23,24^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT362,95^A0I,20,19^FH\^FDO.P.:^FS " .
                "^FT300,94^A0I,23,24^FH\^FD" . $data_item[0]->n_produccion . "^FS " .
                "^FT362,67^A0I,20,19^FH\^FDCant:^FS " .
                "^FT300,66^A0I,23,24^FH\^FD" . $data_form[4]->value . "^FS " .
                "^FT362,44^A0I,20,19^FH\^FDLote:^FS " .
                "^FT300,43^A0I,23,24^FH\^FD" . $data_form[1]->value . "^FS " .
                "^FT309,14^A0I,25,24^FH\^FD" . $data_item[0]->codigo . "^FS " .
                "^FT204,80^A0I,20,19^FH\^FDAux:^FS " .
                "^FT163,79^A0I,23,24^FH\^FD" . $operario . "^FS " .
                "^BY80,80^FT110,57^BXI,5,200,0,0,1,~ " .
                "^FH\^FD" . $data_item[0]->codigo . ';' . $data_form[4]->value  . "^FS " .

                "^PQ" . $data_form[3]->value . ",0,1,Y^XZ";
        } else {

            // 100x50 
            $porciones_cod = explode("-", $data_item[0]->codigo);

            $empresa = $data_item[0]->nombre_empresa;
            if (strlen($empresa) >= 47) {
                $nombre1 = substr($empresa, 0, 47);
                $nombre2 = substr($empresa, 48, strlen($empresa));
            } else {
                $nombre1 = substr($empresa, 0, strlen($empresa));
                $nombre2 = "";
            }
            $prueba = "^XA" .
                "^MMT" .
                "^PW831" .
                "^LL0400" .
                "^LS0" .
                "^FO310,310^GFA,5146,5146,62,M01JFC,L01LFE,L07MFiQ0F,K03NFEhX02P01F,K0IFJ07FF8hW07P03F,J01FFL07FEhW0FP038,</br>" .
                "J07FCL01FFO0399C3C7C33B81F1F8E078I078FC3187E38F3BC70F81F800E1E3F18CC06781F83818C7I078FC71C7E38F1BC70FC1FC,</br>" .
                "J0FFN07FCN03BDCFDFE73F87F3FCE3FE001FBFC71CFE7BFBFE71FC7FC00F7EFF99FE067F7F8FC38C7001FDFE71CFF3BF9FE71FC3FC,</br>" .
                "I01FCO0FEN0399CFDCE73FCFF3DCE3FE001FBCC739E673BBEE73DCF8C00F7EF399FE0E7F798FC38CF001EB8E71CE73BF9FE73CC7CC,</br>" .
                "I07F8O07FQ01C19F73DDE779DE78E003C33EF39E6773BEE778CFJ0EF0C799F60E7F67DEC38CE001C39E73DC77339E6738CF,</br>" .
                "I07EP03F8P01F07F7399CE71DCE0E003E07EE3986703B8E771DEK0F81F99EE0E7B0FDCC31CE001E0FEE3B867039CE770CE,</br>" .
                "I0FCP01FCQ0F9FFE7BBCEF3DDE4E001F1FEE3B8E707B9EEF3DCK07C7FB9CE1CF73FD8C71CE001F9FEE3B8E703B8E771DE,</br>" .
                "003F8Q07EQ079FCE77BBCE799FCEI07BFCEFB9EF0779CEE79EK01EFF39FDBCEF7F81EF78EI079FCCF39E70739EE73DE,</br>" .
                "003FR03FQ079F8E6F3FCFF19FCEI073F9DF3FCE2E71CCFF9FEJ01CFE73B9FCFE7F07FE78EI079F8DF3FC73E39CE7F9FE,</br>" .
                "007ER01F8O03F9F8E7F3FCFF39FDE003F3F1FE3FCE7E71CCFF1FFJ0FCFC73F9FCFC7E3JF9C003F1F0FF3F8E7E79CE7F0FF,</br>" .
                "00FCS0FCO03E0E1E7C1DC7C39C3C003C1C0FE1F0C7C71DC7C027J0F07073E1FCF8383F87F9C003E0C0FE1F047C71CC3E0378,</br>" .
                "01VFCS01CI0380038078U0301CJ07N07P0380018U0300CJ07,03VFES01CI03800718FR01CJ01CI01EN07P0380038Q01CJ01CJ0F,</br>" .
                "03ETF9FS018I0380071FER01CJ038I03EN0EP0380038Q01CJ01CI03E,03C7SF9FS018I0380071F8S08J038007FCN0ET038Q018J038003FC,</br>" .
                "0FC7801BFDIFC00F8F8R018I0380071F8X038007FO0ET038W038007F8,0F87C01BKFC01F0FCR018M02gG018003EO04gS018003E,</br>" .
                "0F03E01F7JFC01E07C,1F01E01FFDIFC01E03CI03,1E01F01BFF7EFC03E03EI0FC,1E00F01BEFFEFC03C01E001jYFE,</br>" .
                "3E00F01BIFE7C07C01F001jYFE,7C00F81KF7C0F801F001jYFE,7800781IFDFFC0FI0F801jYFE,78007C1LFC0FI0F801FE,</br>" .
                "78003C0LFC0FI07800FC,F8003E1LFC1EI07800F8,FI01E1FEJFC3EI078M01FFCiG07FFM01FFE,</br>" .
                "FI01E1JFBFC3EI07CM07IF803FFL0JFEJ07FE03FF8I01FF0FFCK03FF81MF8J03IFCL07IF8003FFL0FFE,</br>" .
                "FI01F0KFCC7CI03CL03JFE03FFK01KFJ07FE03FF8I01FF0FFCK03FF8NF8I01KF8J03KF003FFL0FFC,</br>" .
                "FJ0F8IFBF0C78I03CK01LF83FFK01KFJ07FE01FFCI03FF0FFEK07FF7NF8I0LFEJ0LFC03FF8J03FFC,</br>" .
                "FJ079LFC78I03CK01LFC1FF8J01KFJ07FC01FFCI03FF07FEK07FE7NF8I0MFI03LFE01FF8J03FF8,</br>" .
                "FJ079LFCF8I03CK07LFE1FF8J03FFBFF8I0FFC01FFEI03FF07FEK0RF8003MFC007MF01FF8J03FF,</br>" .
                "FJ07DBEJFDFJ03CK0NF1FFCJ07FF3FFCI0FFC00FFEI03FF07FEJ01RF800NFE01NFC1FF8J07FF,</br>" .
                "FJ03DBEF6FFDEJ03CJ03NF8FFCJ07FE1FFCI0FFC007FEI03FF07FEJ01RFI0NFE03NFC1FF8J07FE,</br>" .
                "FJ03DBEJFDEJ03CJ07NFCFFCJ0FFE1FFCI0FFC007FFI03FE07FFJ03RF003OF07NFE1FFCJ0FFE,</br>" .
                "FJ01FFE7JFEJ03CJ07FFE01IFEOFC0FFEI0FFC007FFI03FE03YF007JF1JF8JFC3JF0OFC,</br>" .
                "FK0FFE7F3FFCJ03CJ0IFI03FFEOF80FFEI0FF8003FFI07FE03RFJ03FE007FFE001IF8IFC003IF0OF8,</br>" .
                "FK0FL03CJ03CI01IFI03RF80FFEI0FF8003FF8007FE03QFEJ03FE01IFCI0IFC3FF8001IF8OF8,</br>" .
                "FK0F8K078J03CI03FFCJ0FFE7NF007FF001FF8003FFC007FE01NFDFFCJ07FE01IFJ07FFC1FCJ07FF8OF,</br>" .
                "FK078K0FK03CI03FF8J07F87MFE007FF001FF8001FFC007FE01NFDFF8J07FE03FFEJ01FFC0F8J07FF8OF,</br>" .
                "FK078K0FK03CI07FFK07F07MFE007FF801FFJ0FFC007FE01NFBFFK07FE03FFCJ01FFE078J03FFC7MFE,</br>" .
                "FK07CJ01FK03CI0FFEK01C07MFC003FF803FFJ0FFE007FC01NFBFFK07FE0IF8K0FFE02K01FFC7MFC,</br>" .
                "FK03CJ01EK07CI0FFCN07MFC001FF803FFJ0FFE007FC00MFE3FFK07FE0IFL0FFEM01FFC3MFC,</br>" .
                "FK01EJ01EK078I0FFCN03MFC001FFC03FFJ07FE00FFC00MFE3FFK07FC0FFEL0FFEN0FFC3MF8,</br>" .
                "F8J01EJ03EK078I0FFCN03FF003FFI01FFC03FFJ07FF00FF800FFC00FFE3FF8J0FFC1FFCL07FFN0FFC3FF003FF8,</br>" .
                "78K0FJ03CK078001FF8N03FF003FFJ0FFC03FFJ03FF80FF800FFC00FFC1FFCJ0FFC1FFCL07FFN0FFC3FF007FE,</br>" .
                "78K0FJ078K078001PFC1FF807FEJ0NF8003MFE0FFC01FFC1SF8L07FFN0FFC3FF007FE,</br>" .
                "78K0FJ078K0F8001PFC1FF807FEI03NF800NFC0FFC01FF81SF8L07FEN0FFC1FF007FE,</br>" .
                "7CK0F8I0F8J01FI01PFC1FF80FFE001OF807NFC07FE03FF00SF8L07FEN0FFC1FF80FFC,</br>" .
                "3EK07CI0FK01FI01PFC1FF81FF800PF81OFC07FE07FF007RFM07FEN0FFC1FF81FFC,</br>" .
                "3EK03CI0FK01EI01PFC1FF81FF800PF03OFC03FE07FE003RFM07FEN0FFC1FF81FF8,</br>" .
                "1EK03C001FK03EI03PFC0FF83FF801PF0PFC03FF0FFE001RFM0FFEN0FFC1FF83FF,</br>" .
                "1FK01E003EK03EI03PFC0FFC3FF007PF0PFC03FF1FFC003RFM0FFCM01FFC1FF87FF,</br>" .
                "0FK01E003CK03CI03PFC0FFC7FE007OFE1PFC03FF1FF8003QFEM0FFCM01FF80FF87FE,</br>" .
                "0F8J01E003CK07CI03PFC0FFCFFE007OFE1PF803FF1FF8007RFL01FFCM01FF80FFC7FE,</br>" .
                "0FCK0F0078K0F8Q01FF80FFCFFC00PFE3PF801FF3FF001SFL01FFCM03FF807FDFFC,</br>" .
                "07CK078078K0FR01FF807FCFF800FFEJ0FFC07FF8I03FFI01FF3FE001FF8I03FF07FFL01FF8M07FF007FDFF8,</br>" .
                "03EK078078J01FR03FF007JF800FFCJ0FF807FFJ03FFI01JFE003FF8I03FF03FF8K07FF8M0IF007JF8,</br>" .
                "03FK07C0FK03EL02K03FF003JF001FF8I01FF807FEJ03FEI01JFC007FFJ03FF03FF8K0IF81K01FFE007JF,</br>" .
                "01F8J03C1EK07EL06K07FF003IFE001FF8I01FF807FEJ03FEI01JF8007FFJ03FF03FF8K0IF03K01FFE007IFE,</br>" .
                "01F8J03C1EK0FCK01EK0FFE003IFE001FF8I01FF807FCJ07FEJ0JF8007FEJ03FE01FFCJ03FFE0F8J03FFC003IFE,</br>" .
                "007CJ01E3EJ01F8K0FF8I01FFE003IFC003FF8I01FF807FCJ07FCJ0JFI07FEJ07FE01FFEJ07FFE1FCJ0IFC003IFC,</br>" .
                "003EK0E3CJ03FL0FF8I03FFC003IFC003FF8I01FF807FEJ07FCJ0IFEI07FFJ07FE01IFJ0IFC7FEI01IF8003IF8,</br>" .
                "003FK0F3CJ07FK03FFCI07FF8003IF8001FFCI01FF807FFJ0FFCJ07FFEI07FF8I07FE01IF8003IF8IF8007IFI03IF8,</br>" .
                "001FCJ0F78I01FCK03IF803IF8001KFC1OF007NFCJ07KF07NFE00JF01JF1IFE07IFEI03KFC,</br>" .
                "I0FEJ07FJ01F8K01IFE0JFI01KFC1OF007NFCJ07KF07NFE007NFE0OFCI01KFC,</br>" .
                "I07FJ07FJ07F8K01NFEI01KFC1OF007NFCJ07KF07NFE003NFE0OF8I01KFC,</br>" .
                "I01FCI07FJ0FEM0NFCI01KF80OF007NFCJ07JFE03NFC003NF807NFJ01KFC,</br>" .
                "I01FEI03FI03FCM07MF8I01KF80OF003NF8J03JFE03NFC001NF007MFEK0KF8,</br>" .
                "J07FC001E001FFN03LFEK0KF807NF001NF8J03JFE01NFCI0MFC001MFCK0KF8,</br>" .
                "J01FF001E003FEN01LFCK0KF803MFEI0NF8J03JFE00NF8I07LF8I0MFL0KF8,</br>" .
                "K0FFE01C03FFCO0LF8K07JF801MFEI07MF8J03JFE007MF8I03KFEJ07KFEL0KF,</br>" .
                "K03IFC9IFEP03JFEL07JF8007LFEI01MF8J03JFE001MF8J07JF8J01KFM0KF,L07MF8Q07FFEiG0IF8L03IF,L03LFES0FEiH01FCN03F8,</br>" .
                "M03KF,O07F,^FS" .
                "^FT244,348^A0I,25,24^FH\^FDFecha:^FS " .
                "^FT178,348^A0I,25,24^FH\^FD" . date('d/m/Y') . "^FS " .
                "^FT268,314^A0I,25,24^FH\^FDConsecutivo:^FS " .
                "^FT128,314^A0I,25,24^FH\^FD" . $data_item[0]->num_pedido . "^FS " .
                "^FT779,25^A0I,25,24^FH\^FDFecha Comp:^FS " .
                "^FT779,66^A0I,25,24^FH\^FDCantidad Total:^FS " .
                "^FT779,107^A0I,25,24^FH\^FDEtq. por Rollo:^FS " .
                "^FT779,146^A0I,25,24^FH\^FDCavidades:^FS " .
                "^FT643,25^A0I,25,24^FH\^FD" . str_replace('-', '/', $data_item[0]->fecha_compromiso) . "^FS " .
                "^FT623,66^A0I,25,24^FH\^FD" . $data_form[2]->value . "^FS " .
                "^FT630,107^A0I,25,24^FH\^FD" .  $data_form[4]->value . "^FS " .
                "^FT666,146^A0I,25,24^FH\^FD" . $data_item[0]->cav_presentacion . "^FS " .
                "^FT431,146^A0I,25,24^FH\^FD" . $data_form[1]->value . "^FS " .
                "^FT431,107^A0I,25,24^FH\^FD" . $operario . "^FS " .
                "^FT491,146^A0I,25,24^FH\^FDLote:^FS " .
                "^FT432,70^A0I,25,24^FH\^FD" . $data_item[0]->n_produccion . "^FS " .
                "^FT491,107^A0I,25,24^FH\^FDAux:^FS " .
                "^FT490,70^A0I,25,24^FH\^FDO.P.:^FS " .
                "^FT491,186^A0I,25,24^FH\^FDCore:^FS " .
                "^FT641,186^A0I,25,24^FH\^FD" . $porciones_cod[0] . "^FS " .
                "^FT779,186^A0I,25,24^FH\^FDDimensiones:^FS " .
                "^FT431,186^A0I,25,24^FH\^FD" . $data_item[0]->nombre_core . "^FS " .
                "^FT779,260^A0I,25,24^FH\^FDCliente:^FS " .
                "^FT695,260^A0I,25,24^FH\^FD" . $nombre1 . "^FS " .
                "^FT695,229^A0I,25,24^FH\^FD" . $nombre2 . "^FS " .
                "^BY176,176^FT238,26^BXI,11,200,0,0,1,~ " .
                "^FH\^FD" . $data_item[0]->codigo . "^FS " .
                "^PQ" . $data_form[3]->value . ",0,1,Y^XZ";
        }
        echo $prueba;
    }
    ?>
</div>
<!-------------------------------------------------------------------------------------------------->
<?php include PUBLICO . '/vistas/plantilla/footer.php'; ?>
<script>
    $("div#div").printArea();
</script>
