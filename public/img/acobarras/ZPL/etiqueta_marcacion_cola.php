<div>
    <?php

    use MiApp\negocio\util\Validacion;

    $resolucion = 200;
    if (isset($_POST['resolucion'])) {
        $resolucion = $_POST['resolucion'];
    }

    $descripcion = $_POST['formulario']['descripcion'];
    if (strlen($descripcion) >= 33) {
        $descripcion1 = substr($descripcion, 0, 34);
        $descripcion2 = substr($descripcion, 34, strlen($descripcion));
    } else {
        $descripcion1 = substr($descripcion, 0, strlen($descripcion));
        $descripcion2 = "";
    }

    $data = $_POST['formulario'];

    $tipo = $_POST['tipo'];
    if ($tipo == 1) {
        $dato1 = 'O.P';
        $dato2 = $_POST['op'];
        $cantidad = 1;
    } else {
        $dato1 = 'Fecha';
        $dato2 = date("d-m-Y");
        $cantidad = $data['cantidad'];
    }

    $datos_operatio = $_POST['datos_persona'];
    $nombre = explode(' ', $datos_operatio->nombres);
    $apellido = explode(' ', $datos_operatio->apellidos);
    $operario =  $nombre[0] . ' ' . $apellido[0];
    $operario = Validacion::quitarTildes($operario);


    switch ($resolucion) {
        case '300':
            $zpl = '^XA
            ^FO50,50^GFA,11322,11322,102,,:oL03KF8,:oK03MF,oJ0PF8,oI07FFCJ01IF,:oH01FFC003E
            I0FFC,:oH03FCI03EJ07F,:gP07FF8U0IFkP01FFCU01FEJ07FK0FC,gO0LFR03KFEkM07KFT01FK07
            FEJ0FE,L07MFES0NFP01MFEN01RFEK0NFEO01RFM01RFL0NFEQ07MFES0FEK0FFEJ01F8,:L07MFER0
            7NFEN01OFCM01SF8J0OFO01RFEL01SF8J0NFEP01OF8Q01F8J01IFK0FC,L07MFEQ01PFM01QFM01SF
            CJ0OFO01SFL01SFEI01NFEP07OFEQ03EK01C1FK03E,L07NFQ03PF8L07QFCL03SFEJ0OFO01SF8K03
            SFEI01NFEO01QFQ0FEK03C0F8J01FC,L0OFP07RF8K0RFEL03TF8I0OFO07SFCK03TF8001OFO03QFE
            O01F8K0380F8K07F,L0OFP0SFEJ07SFL03TFCI0OFO07TFEJ03TFC001OFN03SFO01FK01F8018K03F
            8,::L0OFO0TFEJ0TF8K03UFI0OF8N07TFEJ03TFC001OFN07SFCN03EK01F001EK01F8,L0OF8M01TF
            EI03TF8K03UFI0OF8N07TFEJ03UF007OFN0TFCN0FCK03EI0EK01FE,L0OF8M03UFI07TFCK03UFI0O
            F8N07TFEJ03UF007OFM01UFN0FL07EI0F8K03F,L0OF8M03LFI0LF800LF8I0LFK07UF800OF8N07TF
            EJ07UF001OFM03KF8I03KF8L03EL07CI078L0FC,:P0KF8M0LFJ01KF801KFCK07JFK07IFM07JF8K0
            1KFEN0JF8L0KFJ07IFM07JFL07KFM03IFEL0KF8L0FEL0FCI07CL07C,O01KFCL07KF8L0JF807KFL0
            1JFCJ07IFM07JF8K01KFEN0IFEM01JFJ07FFEN0JFCK07KFL01JF8M0JFCL0FCL0EJ07EL07E,O01KF
            CL0LFM0IFE01KFEL01JFEJ07IFM01JF8K01KFEN0IFEN0JFJ07FFEN07IFCK0LFL03JFN0JF8L0FCK0
            3EJ01EL01E,O03KFCK01KF8M07FFE07KF8M0JFEJ07IFN0JF8K07KFEN0IFEN0JFJ07FFEN03IFCK0L
            FEK03IFEN07FFCM0FL03CJ01FL01E,O0LFCK01KF8M03FF007JFEN07IFEJ07IFN0JF8K07LFN0IFEN
            07IFI01IFEN03IFCJ03LFEK0JF8N03FEM03FL038K07M0E,:::O0LFEK03KFO0F800KFCN01JF8I07I
            FN0JFL0MFM03IFEN07IFI01IFCN03IFCJ03LFEK0JFO01FN03CL0F8K07M0F,N01LFEK07JFEO02001
            JF8P07IF8I07FFEN0JFL0MFM03IFEN0JFI01IFCN03IFK07MFJ01JFO01EN03CL0FL03M03,N01LFEK
            07JFCR01JF8P07IF8I07FFEN0JFK03MFM03IF8N0IFEI01IFCN03IFK0NFJ01IFEX01FL03FL01FL03
            ,N03LFEJ03JFES07JF8P03IF8I07FFEM01JFK0NF8L03IF8N0IFEI01IFCN07IFK0NFJ01IFCX01FL0
            3CL01F8K03,:N07LFEJ03JF8S07JFQ03JFI07FFEM01IFCK0IFE0IF8L03IF8M01IFEI01IFCN07IFJ
            03IFC3IFJ01IFCX01FL0FCM0F8K01C,N07FFE1FFEJ07JFT0JFEQ03JF001IFEM07IFCJ01IFE0IF8L
            07IF8M07IFEI01IFCN0IFCJ03IFC3IF8I01IF8X03FK01FCM0FEK01C,N0IFE1FFEJ07JFT0JF8Q01J
            F001IFEL01JF8J01IFC0IF8L07IF8L01JFEI01IFCL01JFCJ03IF83IF8I07IF8X03FK01FFE00107F
            FEK01C,N0IFC1FFEJ0JFES01JF8Q01JF001IFEK03JFEK03IF80IFCL07IF8L07JFEI01IFCL07JFCJ
            07IF807FF8I07IF8X03FK07FCFD01EJFK01C,:L01JF81IFCI0JFT01JFR01JF3VFCK07IF807FFCJ0
            WFC007WF8J07IF007FF8I07WF8J03FK07CCE380F84FFK01C,L03JF81IFCI0JFT01JFR01gGF8K07F
            FE007FFCJ0WFC007VFEJ01JF007FF8I07WFCJ03FK0FCFE1IF9E07K01E,:L03IFE00IFCI0JFT01JF
            R01gGFK01IFE007FFCJ0WF801WFEJ03JF007FFCI0XFCJ03FK0F0CE07FE7E07CJ01E,L03IFE00IFC
            001IFET01IFCR01gFEK03IFC007IFJ0WF001WFEJ03IFC007FFCI0XFCJ07FJ01E0FFDKF01CJ01E,L
            07IF800JF801IFET01IFCR01gFL03IFI07IFI01WF001WFCJ0JFC007FFCI0XFCJ07FJ03E0IF83IF0
            1EJ01E,:L07IF800JF801IFET01IFCR01gGFK0JFI07IFI01VFE001WF8J0JFI07FFCI0XF8J07FJ03
            80C0183FE700EJ01E,L0JFI0JF801IFET01IF8R03gGF8J0IFEI07IFI01VFC001VFEJ01JFI07FFCI
            0XF8J07FJ0780C607IFC00FJ01E,:L0IFEI07IF801IFET01IF8R03gGFCJ0IFEI07IFC001UFEI01V
            FK07IF8I07FFEI0XF8J07FJ0700FFDJFE00FJ01E,K03IFEI07IF801IFCT07IF8R03gGFEI01IFEI0
            7IFC001UFJ07UFCK07IFJ03FFEI0XF8J07FI01F003FDKF003CI01E,:K07IFCI07JF01IFCT07IF8R
            03gHF8001IF8I07IFC007TFK07TF8L07IFJ03FFEI0XF8J03FI01E003FDFF1FF003EI01E,K07IFCI
            07JF01IFCT07IF8R07IF839IFE1QF8007IF8I01IFC001FDIFE7E7JFCK01C0JF3FBJF8M07FFCJ03I
            FW07IF8J03FI01C00IF7JF001EI01E,K0JFJ01JF01IFCT07IF8R07FFEI0IFCM01JFC007IFJ01IFC
            K0IFCI0JFCN07FFEI0JF8M0IFCJ03IFW07IFK03FI07C00IFBJF001EI01C,:K0IFEJ01JF01IFET07
            IF8Q01IFEI0IFCN07IFC00JFJ01IFEK0IF8I0JFCN07FFEI0JFEL01IF8K0IFW07IFK03FI0780039F
            83IFI0EI01C,J01IFEJ01JF01IFET07IFCQ01IFCI0IFCN07IFC01IFCJ01IFEK0IF8I07IFCN0IFEI
            07IFEL01IF8K0IFW0JFK03FI0F80038383F7CI0F8001C,:J07SF01IFET07IFCQ07IFC001IFCN01I
            FC01RFEK0IF8I07IFEN0IFEI07IFEL03RFEV0IFCK03FI0EI0FC383F9CI038001C,J07SF01IFET01
            IFCQ0JFI01IF8N01IFC03RFEJ01IF8I01IFEN0IFCI07IFEL07RFEV0IFCK01F001EI0FE3BCIFI01C
            001C,I01TF01JFT01JFP01JFI01IF8N01IFC07SFJ01IFJ01JFN0IFCJ07IF8K07RFEV0IFL01F001
            EI0IFC1IFI01E003,I07TF81JFT01JFP01IFCI01IF8N01IFC07SFJ01IFK0JFN0IFCJ07IF8K0SFEI
            01EP07IFL01F0038I0CFEC3F87I01E003,:I07TF81JFEP08001JF8O03IFCI01IF8N01IFC0TFJ01I
            FK0JF8K01JFCJ03IF8K0SFEI0FEP0JFM03C0F8I0C20FF1FCI01FC03,I0UF80JFEO0FC001JF8O07I
            F8I07IF8N07IFC0TFJ01IFK0JF8K01JFCJ03JFJ03TF001FFO01IFEM03C0FJ0FE1BF178J07C0F,I0
            UF80KFN01FF001JFEN01JF8I07IF8N07IFC7UFI03IFK07IF8K01JFCJ01JFJ0UF00IF8N03IFN03C1
            F9PFDLF0E,001UFC0KF8M03FFE01KFN0KF8I07IF8N07IF8VFI03IFK07IFEK01JFK01JF8I0UF0JF8
            N0JFO0F3YF9E,:::001UFC07JFEM0IFE01KF8L01KFJ07IF8M01JF8VFI03FFEK01IFEK01JFK01JF8
            003UF0KF8L01IFEO0gHFE,001UFC07KFEK07JF00KFEL03JFEJ07IF8L03KFBVFI03FFEK01JFK01JF
            L0JF8003UF8KFCL03IFEO0FEY07E,00VFC03LFJ01KF807KFCJ0LFCJ07IF8L07JFE3VF8003FFEK01
            JFK03JFL0JFC007UF81JFEL0JFEO0FEY07C,00JF8M01IFC03LFC007LF007LF801MFK07UFC7IFN01
            JF8007FFEL0JF8J03JFL0JFC01IFEN03IF81KF8I01KF8O03EY0F,01JFN01IFE007TFE001UFEK07U
            FC7IFN01JF8007FFEL0JF8J03JFL03IFE01IFEN01IF80UFQ0FX03F,:03JFN01IFE003TFEI0UFCK0
            7UF9JFN01JF8007FFEL03IF8J03IFCL03IFE01IF8N01IFC07SFEQ0FCV01FE,03IFCN01IFE001TFC
            I07TF8K07UF3IFEO0JF8007FF8L03IFEJ03IFCL01JF03IF8N01IFC01SF8Q03EV01F8,0JF8N01IFE
            I0TFJ01SFEL07TFE3IFEO0JF8007FF8L03IFEJ03IFCL01JF03IF8N01IFC01SFR01FV03F8,0JF8O0
            JFI07RF8K07RFM07TF07IF8O0JF8007FF8L01JFJ03IFCM0JF07IF8N01IFC00SFR01F8U07F,::0JF
            P0JFJ0RFL01QFEL01TFC0JF8O0JF803IF8L01JFJ03IFN0JF07IFP0JF003QF8S0FET01FC,3JFP0JF
            J03PFN0QFCL01TF00JFP0JF803IF8M0JFCI03IFN0NFEP0JFI03OFET03ET03E,3IFEP0JFJ01OFCN0
            3OFEM01SFE01JFP07IFC03IF8M0JFCI03IFN07MFEP0JFI03OFU01F8S0FC,7IFEP07IFEJ01NFP07M
            FEN01RFE001IFEP07IFC03IFN07IFEI03IFN07MFCP0JFJ0NFCV07ER01F8,gK01KFER07KFkM07KFX
            01FR0FE,:gM07FCV07FkP01FF8Y01FEP01FC,oH03FCO0FF,oH01FFCL01FFC,:oI07FFEJ01IF,:oJ
            03OF8,:oK03MF,oL03JFE,,::::^FS
            ^FT16,198^A0N,42,43^FH\^FDCodigo:^FS
            ^FT16,281^A0N,42,43^FH\^FDDescripcion:^FS
            ^FT16,389^A0N,42,43^FH\^FDAncho:^FS
            ^FT894,389^A0N,42,43^FH\^FDPeso:^FS
            ^FT16,482^A0N,42,43^FH\^FD' . $dato1 . ':^FS
            ^FT379,482^A0N,42,43^FH\^FDOperario:^FS
            ^FT379,389^A0N,42,43^FH\^FDMetros Lineales: ^FS
            ^FT915,238^BQN,2,7
            ^FH\^FDLA,' . $data['codigo'] . ';' . $data['ancho'] . ';' .  $data['ml'] . '^FS
            ^FO5,226^GB1169,350,3^FS
            ^FO5,334^GB1165,0,3^FS
            ^FO5,422^GB1165,0,3^FS
            ^FO364,337^GB0,168,3^FS
            ^FO875,336^GB0,86,3^FS
            ^FT249,283^AAN,36,20^FH\^FD' . $descripcion1 . '^FS
            ^FT16,321^AAN,36,20^FH\^FD' . $descripcion2 . '^FS
            ^FT180,194^AAN,36,20^FH\^FD' . $data['codigo'] . '^FS
            ^FT149,389^AAN,36,20^FH\^FD' . $data['ancho'] . '^FS
            ^FT676,389^AAN,36,20^FH\^FD' . $data['ml'] . '^FS
            ^FT994,389^AAN,36,20^FH\^FD' . $data['peso'] . '^FS
            ^FT131,478^AAN,36,15^FH\^FD' . $dato2 . '^FS
            ^FT560,478^AAN,36,20^FH\^FD' . $operario . '^FS
            ^FO5,501^GB1165,0,3^FS
            ^FT18,557^A0N,42,40^FH\^FDLote:^FS
            ^FT118,557^AAN,36,20^FH\^FD' . $data['lote'] . '^FS
            ^PQ' . $cantidad . ',0,1,Y^XZ';
            echo $zpl;
            break;

        default: // 200 por defecto
            $zpl = '~DG000.GRF,06912,072,
            ,:::::::::::::lL03FFC,lK03FIFC0,lJ01FKF8,lJ07F8181FE,lI01FC01803F80,lI03E003C007C0,gG03FFE0N0IF80iP03FFE0O0F8003C001F0,
            L07FIFE0M01FIFE0L0KF80J03FLFC0I0KF80K0NFK03FLF80H01FJF80L03FIFC0M01E0H07E0H078,L07FIFE0M0LF80J03FJFE0J03FMF8001FJFC0K0N
            FE0I03FMFI01FJF80L0LFN03C0H0HFI03C,L0KFE0L03FKFC0J0MFK07FMFC001FJFC0K0OFJ07FMFC003FJF80K03FKFC0L0780H0E70H01E,L0KFE0L0N
            FJ03FLFC0I07FMFE001FJFC0K0OFC0H07FMFE003FJFC0K0MFE0L0F0H01C380H07,L0LFL01FMF80H07FLFE0I07FNFH03FJFC0J01FNFE0H07FNFH03FJ
            FC0J01FMFL01C0H01C380H0380,L0LFL07FMFC001FNFJ07FNF803FJFC0J01FNFE0H07FNF803FJFC0J07FMF80J03C0H0381C0H01C0,K01FKFL0OFE00
            3FNF80H07FNFC03FJFE0J01FOFI07FNF803FJFC0J0OFC0J0780H0381C0H01E0,K01FKFK01FOFH07FNFC0H0PFC03FJFE0J01FOFI07FNFC07FJFC0I01
            FNFE0J0F0I0700E0I0F0,L0LF80I03FIFC7FIF80FJF9FIFE0H0PFC03FJFE0J01FOF800FOFC03FJFE0I03FHFC03FIFK0E0I0E00F0I070,N03FHF80I0
            7FHFC007FHF81FIFH01FHFE0H0PFC0I0IFE0J01FOF800FOFC0I0IFE0I07FHFI07FHFJ01C0I0E0070I038,N03FHF80I0JFI01FHF83FHF80H07FHFI0H
            FC0I03FFE0I0JFK03FF80I07FF800FFC0I03FFC0I0IFE0I0IFC0H01FHFJ01C0H01C00380H038,N07FHF80H01FHFC0I07FE07FHFJ01FHF800FFC0I01
            FFE0I0JFK03FF0J03FF800FFC0J0HFE0H01FHFE0I0IFK0IFJ0380H01C00380H01C,N07FHFC0H03FHFK03FC0FHFC0J0IF801FFC0J0HFE0H01FIFK03F
            F0J01FF801FFC0J0HFE0H01FIFI01FFE0J07FC0I0380H038001C0H01C,N0JFC0H07FFE0J01F01FHF80J07FF801FF80J0HFC0H01FIFK03FF0J01FF80
            1FF80J0HFE0H03FIFI01FFC0J03F0J070I070H01C0I0E,M01FIFC0H07FFC0J01E01FHFL07FFC01FF80J0HFC0H03FIF80I03FF0J01FF801FF80J0HFC
            0H03FIFI03FF80J01E0J070I070I0E0I0E,M01FIFC0H0IF80K0C03FFE0K03FFC01FF80J0HFC0H07FIF80I03FF0J01FF801FF80J0HFC0H07FIF8007F
            F0L080J060I0E0I070I07,M03FIFE0H0IFO03FFC0K03FFC01FF80J0HFC0H07FIF80I07FE0J03FF801FF80J0HFC0H0KF8007FF0R0E0I0E0I070I07,M
            03FIFE001FFE0N07FF80K01FFC03FF80I01FF80H0HFDFF80I07FE0J03FF001FF80I01FFC0H0HFDFF8007FE0R0E0H01C0I0380H07,M07FE7FE001FFC
            0N0IFM01FFE03FF80I03FF8001FFCFFC0I07FE0J07FF003FF80I03FFC001FF9FF800FFE0R0C0H01C0I0380H03,M0HFE7FE003FF80N0HFE0M0HFE03F
            F0J07FF0H01FF8FFC0I07FE0J0IFH03FF0J07FF8001FF9FFC00FFC0Q01C0H03C0I03C0H0380,M0HFC7FF003FF80N0HFE0M0HFE03FF80H01FFE0H03F
            F8FFC0I0IFJ07FFE003FF80H01FHF8003FF0FFC00FFC0Q01C0H07DC82FFE0H0380,L01FF83FF007FF0N01FFC0M0TFC0H03FF0FFC003FPFE0FQFI07F
            E0FFC01FQFI01C0H07DD839BE0H0380,L01FF83FF007FF0N01FFC0M0TF80H07FE0FFE003FPFC1FQFI07FE0FFC01FQF8001C0H0E7DFHFA70H0380,L0
            3FF03FF007FF0N01FFC0M0TFJ07FE07FE003FPF81FPFE0H0HFC0FFC01FQF800180H0E7C7EFA70H0180,L07FE03FF00FFE0N03FF80M0SFE0I0HFC07F
            E003FPF81FPFC0H0HFC0FFE01FQF80018001C7FIFE3800180,L07FE03FF80FFE0N03FF80M0TFI01FF807FE003FOFE01FPF8001FF807FE01FQFI0180
            01C40DFFC3800180,L0HFC01FF80FFE0N03FF80M0TF8001FF807FF007FOFC01FPFI03FF807FE01FQFI018003840FHF81C00180,K01FFC01FF80FFE0
            N03FF80L01FSFC003FF003FF007FOF801FOFC0H03FF007FE03FQFI01800704CFHF81C00180,K01FF801FF80FFE0N03FF80L01FSFE007FF003FF007F
            NFE003FOFJ07FE007FF03FQFI01800707FIFC0E00180,K03FF801FFC0FFE0N03FF0M01FTFH07FE003FF007FNFI03FNFC0I0HFE003FF01FQFI01800E
            07EFHFE0700180,K03FF0H0HFC0FFE0N03FF0M03FFC1FFE0I03FHFH0HFE003FF0H03FFC07FFC0I01FFE03FFE0J0HFC003FF0P07FF0H01C00E07FIFE
            0700380,K07FE0H0HFC0FFE0N03FF80L03FF80FFC0J07FF80FFC001FF8003FF803FFC0J0HFC00FFE0I01FFC003FF0P07FE0H01C01C07FIFE0380380
            ,K0HFE0H0HFC0FFE0N03FF80L07FF80FFC0J03FF81FF8001FF8003FF801FFC0J0HFC00FHFJ01FF8003FF0P07FE0H01C03C07BCFF80380380,K0HFE0
            H0HFE0FFE0N03FF80L07FF00FFC0J01FF83FFC003FF8003FF001FFC0J0HFC007FF0I03FF8003FF80O0HFE0H01C038079CFB001C0380,J01FMFE0FFE
            0N03FF80L0IFH0HFC0J01FF83FMF8003FF0H0HFE0J0HFC007FF0I07FMF80O0HFC0I0C0700798FFA00E03,J01FMFE0FHFO03FFC0K01FFE01FF80J01F
            F87FMFC003FF0H0HFE0I01FF8003FF80H07FMF80N01FFC0I0E07007FF7FE00E07,J03FMFE0FHFO03FFC0K03FFE01FF80J01FF87FMFC003FF0H07FF0
            I01FF8003FF80H0OF800C0K01FF80I0E0E005FDFCE00707,J07FMFE0FHFM0803FFE0K07FFC01FF80J01FF8FNFC007FF0H07FF0I01FF8003FFC001FN
            FC03E0K03FF80I060E00467DFA00706,J07FNF07FF80J01C01FFE0K0IF801FF80J03FF9FNFC007FF0H07FF80H01FF8001FFC001FNFC0FF0K07FF0J0
            71C007EFHF20038E,J0PF07FFC0J03F01FHFK01FHF801FF80J03FF9FNFE007FE0H03FF80H01FF8001FFC003FNFC1FF0K0IFK073FQF8E,I01FOF07FF
            E0J0HF81FHF80I03FHFH01FF80J07FF3FNFE007FE0H03FFC0H01FF80H0HFE003FNFC7FF80I01FFE0J03FRFDC,I01FOF83FHFJ01FFC0FHFC0I0IFE00
            3FF0K0IF7FNFE007FE0H03FFC0H03FF0I0HFE007FNFE7FFC0I03FFC0J03FSFC,I03FOF81FHFC0H07FFE07FHFI01FHFC003FF0J03FHF7FNFE007FE0H
            01FFC0H03FF0I07FF00FOFE7FHFJ0IFC0J01C0Q038,I03FF80I03FF81FIFH03FHFE07FHFC00FIF8003FOFEFFE0J07FF00FFE0H01FFE0H03FF0I07FF
            00FFE0J0HFE3FHFC003FHF80J01C0Q038,I07FF0J01FF80FOFC03FOFI03FOFEFFC0J07FF00FFC0I0HFE0H03FF0I07FF81FFC0J07FF1FIF83FIFM0E0
            Q070,I0HFE0J01FFC07FNF801FNFE0H03FOFDFFC0J07FF00FFC0I0IFI03FF0I03FF81FFC0J07FF1FNFE0L070Q0E0,I0HFE0J01FFC07FNFH01FNFC0H
            07FOFBFF80J03FF00FFC0I07FF0H07FF0I03FFC3FF80J07FF0FNFC0L0780O01E0,H01FFC0J01FFC03FMFE0H0OF80H07FOF3FF80J03FF80FFC0I07FF
            0H07FE0I03FFC7FF0K07FF07FMF80L03C0O03C0,H01FFC0J01FFC01FMF80H07FLFE0I07FNFE7FF0K03FF80FFC0I07FF8007FE0I01FFE7FF0K03FF03
            FLFE0M01E0O0780,H03FF80K0HFC007FLFJ01FLFC0I07FNF87FF0K03FF81FF80I03FF8007FE0I01FFEFFE0K03FF80FLFC0N0F0O0F,H07FF0L0HFE00
            3FKFC0J0MFK07FNF0FFE0K03FF81FF80I03FFC007FE0J0KFE0K03FF807FKFP0780M01E,H07FF0L0HFE0H0LFL03FJF80J0OF81FFC0K01FFC1FF80I01
            FFC00FFE0J0KFC0K03FF801FJFC0O03C0M03C,H07FE0L07FE0H03FIF80L0JFE0K07FLFC00FF80L0HF81FF0J01FFC007FC0J07FIF80K01FF8003FHFE
            0P01F0M0F8,Y03FF80N0HFE0iQ07FE0R0F80K01F0,lI03E0K07C0,lJ0FC0I03F,lJ03FC003FC,lK0LF0,lK01FIF80,lL01FF8,,::::::::::::::^XA
            ^MMT
            ^PW799
            ^LL0400
            ^LS0
            ^FT32,96^XG000.GRF,1,1^FS
            ^FT11,134^A0N,28,28^FH\^FDCodigo:^FS
            ^FT11,190^A0N,28,28^FH\^FDDescripcion:^FS
            ^FT11,261^A0N,28,28^FH\^FDAncho:^FS
            ^FT605,259^A0N,28,28^FH\^FDPeso:^FS
            ^FT11,326^A0N,28,28^FH\^FD' . $dato1 . ':^FS
            ^FT257,326^A0N,28,28^FH\^FDOperario:^FS
            ^FT257,259^A0N,28,28^FH\^FDMetros Lineales: ^FS
            ^FT619,165^BQN,2,5
            ^FH\^FDLA,' . $data['codigo'] . ';' . $data['ancho'] . ';' .  $data['ml'] . '^FS
            ^FO3,153^GB791,237,2^FS
            ^FO3,226^GB788,0,2^FS
            ^FO3,285^GB788,0,2^FS
            ^FO246,228^GB0,113,2^FS
            ^FO592,227^GB0,58,2^FS
            ^FT168,187^AAN,27,15^FH\^FD' . $descripcion1 . '^FS
            ^FT11,220^AAN,27,15^FH\^FD' . $descripcion2 . '^FS
            ^FT122,137^AAN,27,15^FH\^FD' . $data['codigo'] . '^FS
            ^FT101,262^AAN,27,15^FH\^FD' . $data['ancho'] . '^FS
            ^FT457,262^AAN,27,15^FH\^FD' . $data['ml'] . '^FS
            ^FT673,262^AAN,27,15^FH\^FD' . $data['peso'] . '^FS
            ^FT90,322^AAN,27,10^FH\^FD' . $dato2 . '^FS
            ^FT379,322^AAN,27,15^FH\^FD' . $operario . '^FS
            ^FO3,339^GB788,0,2^FS
            ^FT12,379^A0N,28,28^FH\^FDLote:^FS
            ^FT80,378^AAN,27,15^FH\^FD' . $data['lote'] . '^FS
            ^PQ' . $cantidad . ',0,1,Y^XZ
            ^XA^ID000.GRF^FS^XZ';
            echo $zpl;
            break;
    }

    ?>
</div>