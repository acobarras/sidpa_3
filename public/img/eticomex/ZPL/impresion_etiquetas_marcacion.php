<div>
    <?php

    use MiApp\negocio\util\Validacion;

    $data_item = json_decode($_POST['datos']);
    $formulario = Validacion::Decodifica($_POST['formulario']);
    $check = $data_item->logo_etiqueta;
    $operario = "0" . ($formulario['id_persona']);

    $fecha_actual = date("d-m-Y");
    //sumo 1 año
    $fecha_ano = date("d-m-Y", strtotime($fecha_actual . "+ 1 year"));

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
            $imagen = "
            ,::::::::::::::::::lL080,lJ015DD,lI02FJF8,lI01557550,lI0FE8CAFE,lH01D004005,
            gI020P020iR020P03F800E003F0,gG0I5O015540iP01550O01400140H050,
            L0KFE0M0BFIFE0K03FIFE80J0MFE80H0KFE0K0MFE80I0BFLF80H03FJFN0KF80M0F8003F8001E,
            L0KDC0M05DIDC0K01DIDC0K0NDJ05DIDC0K05DLD40I01DLD40H01DJDN05DIDO050H01D8001C,
            L0KFE0L03FKF80I02FKFA0I02FMFE0H0KFE0K0NFE0I03FMF8003FJFM03FJFE0M0E0H03B80H0E,
            L0K540L0M540I0N5K0O5I0K540K0O5J015M540015J5L015L5M010I05140H0140,
            L0KFE0K03FMF80H0NF80H02FNF800FJFE0K0OFC0H0BFMFE003FJF80J03FLFE0K0B80H0E0E0H03E0,
            K01DKDL05DMD8001DMDC0H01DNDC00DJDC0K0PDI0PDH01DJD80J05DMDL0C0I0C040I0C0,
            K01FKFK03FNF8007FMFE0H03FNFE00FJFE0K0PF800FOF803FJF80I03FNF80I03E0H01A030I0B0,
            K015K5K015N54015O5I015N54005K5L0P5I0P5405K5K0P5K0140H010010I010,
            K01FKF80I03FNFE03FOF8003FNFE80FJFE0K0PF800FOFC07FJF80I0JFEAFIF80I03C0H0380380H0B8,
            N01DHDK05DHD4005DDC05DHDC005DHD4001DNDC0I05DHDL0PD800DODC0H01DHDC0I05DDC001DHDC0I050I0100180H01C,
            N03FHF80I0IFE0H03FFE0FHFE0H03FHFE003FF0J0HFE80H03FHFL0HF80I03FF800FFC0I03FFE0H01FHFC0I0IF80H03FFE0I060I0600180I0C,
            N0H5750I015750I01574057540I05754005H5K0H540I075H5K01550J01550015740I015740H0155740H015540I015540I040I040H040I04,
            N0JF80H0IFE0J0HF81FHF80I03FFE007FE0J03FE80H0JF80I01FF80I03FF801FF80J0HFE0H03FHFE0H0BFF80J0FE80I0E0I0E0H0F80H0E80M015
            DHD80H05DD80J01C05DDC0J01DHDH05DC0J01DC0I0JD80I01DD0J01DD801DD80J0HDC0H05DIDI05DD0K05C0J0C0H01C0H050I04,
            M01FIF80H0IF80J03A03FFE0J01FFE007FE0J03FE0I0JF80I03FF80I03FF801FF80J0HFE0H03FHFE0H0BFF0K03A0J080H0280H0780
            H0280M015I5I015H5L01005H5M0I5H0H540J01540H015I5K01550J0155001550K0H540H0K5I0H540K010J010I010I010I01,
            M03FIFC001FFE0N0IF80K0IF80FFE0J07FE0H03FIFA0I03FF80I03FF803FF80J0HFC0H0KF800FFE0K020J0380H0780H0380H0380
            M05DD5DC001DDC0N0HDC0L05DD005DC0J05DC0H01DC5DC0I05DD0J01DD001DD80I01DD40H0HD1DD001DDC0Q050I050J0C0H01C0M0H
            FC7FE003FF80M01FFE0L03FF80FFA0J0HF80H0BFEFFE0I07FE0J03FF803FF80I03FF8003FFBFF803FF80Q0780H0E0J0E0H0380M0H5
            0554001550N015540L015500550J01550I0H545540I0H540I015H5H01550J0I5I0I5155001550R050H0155011540H0140M0HF87FE
            003FF0N03FFC0L03FF80FFA0I0BFF80H0HFCFFE0I0HFE0J0IFH03FF80I0IF8003FE3FF803FF80Q0F80H0EA8AHAE0H01C0M0HD05DC
            005DC0N01DDC0L01DD15DHDJ5IDI01DDC5DC0H0H5HDK5HDC055DD5J5IDI05DC1DD801DD80Q050H01D040H050H01C0L01FF83FE007
            FE0N03FF80L03FFBFOFE0H03FF87FE002FPFC0FPFE0H0HFA3FF803FPFE0H0E0H03BEEAHAB8001E0L01550154005540N01550M015R
            540H015401550015P5015P540015501550015P540H050H0H101H1010H0140L0BFE03FE80FFC0N03FE80L03FQFE80H0HFE03FF803F
            PF81FPF8001FF80FFC03FPFE0H0E0H0E3FEEFE9A0H0E0L0HDC01DD00DDC0N05DC0M05DD5DOD40H05DC01DD001DODC01DPDI01DD0
            0DDC01DPDC0H050H041045H50C00140L0HFC03FF82FF80N07FE0M03FRFE0H0HF803FF803FOFA03FOFA0H03FE00FFE03FPFE0H0E00
            1E322EBA0F0H0E0K017540155005540N0H540M0J575H575H57540H0H54015700175H575H5754015H575H575540H017400754035H5
            75H575H540H0700141017100700140K03FF803FF80FF80N0HFE0M07FSF800FF802FF803FNFE803FOF80H0HFE00FFE03FPFC0H0E00
            3C2EFEBE8F800E0K01DD001DDC0DDC0N05DC0M05DD5DPDH01DD0H0HD805DNDI01DND40H015DC00DDC05DPDC0H050018005C4D0500
            140K03FE001FF82FF80N0HFE0M0HFE2BFFAJAIF807FF002FF802BFFEABFFE0H02AFFEAAFFE0I03FF800BFE00202020202FF80H0F0
            0383FAEBA81801E0K0H5400155405540N0H540M0H5401550J0155405540H0H5I01554015540J0H54005540I01550H01540P0H5J05
            004015H5150040140K0HFC0H0HFC2FFC0N0HFE80L0HFE03FE0K0HFC0FFE0H0HFC001FF800FFE0J0HF800FFE80H03FF800BFE0O02F
            F80H0F80E00EEBA880E01C0K05DC0H0HDC0DDC0N05DC0L01DDC01DD0K05DC0DDC0H0HDC001DD400DDC0J0HDC005DC0I05DD0H01DC
            0P0HD80H0500400CC140H0401C0K0HFIABFFE2FFC0N07FE0L03FFE03FE0K07FC0FFEAHAHFC003FF800FFE0J0HF8007FE0I07FFAHA
            BFE0O03FF80H0780C03EE2AA8060380J015M5405540N0H540L0155401540K0H5415M54001550H0I5K0H5I0I5J0O5P01550I01010
            0154150H0501,J03FMFE0FFE0L0807FF80K0IF803FE0K0HFC3FMFE003FF800FHF80I0HF8003FF80H0OF80080K0IFJ0183803FAAE
            E8038380J05DMDC05DC0K01005DDC0J015DD005DC0K05DC5DMDC001DD0H05DD0I01DD0H01DDC001DNDH0580K05DC0I0181001014
            4C001C5,J0OFE07FF0K03C03FFE0J03FFE007FE0K0HFC7FMFE003FE0H03FF80H01FF8003FFC003FNF83FE0K0HFA0J08B202AKA03
            E680J0O5405H5L054015540J01554005540K0H545N54001550H01550I01550H015540015N501540J015540I014500154510H0145,
            J0OFE0FHFC0J0FE03FFE0J0IFE00FFE0K0HF8FNFE803FE0H03FF80H01FF8001FFE00BFNF8FFE80I03FF80J0EFQFHE80I01DOD05DD
            C0I01DD01DHDJ01DHDC005DC0J05DD9DNDC001DC0H01DDC0H01DD0H01DDC005DND8DHDK05DD0K05DRD4,I03FOF03FHF80H0BFF81F
            HFA0H0BFHF800FFE0I02FHFBFNFE803FE0H02FFE0H03FF0I0HFE00FOFAFHFE0H03FFE0K060R0C,I01550J01550155750075H5H0H5
            750015H5700175H575H57551750J0175007540I05740H01550I0755015540J0H5075H5I057540K010Q010,I0HFE0J03FF80FOF80F
            OFA0H0PFEFHFK03FF807FE0I0HFE8007FE0I0BFF83FF80J0HFE3FIFAFIFC0K0380P038,I05DC0J01DD01DODH05DNDC0015DOD5DD0
            J01DD805DC0I05DC0H05DD0I01DD01DDC0J0HDC1DIDH5IDC0K0140P010,I0HFE0J01FF80FNFE003FNF8001FOFEFFE0J03FF807FE0
            I07FE8007FE0I03FF83FF80J0HFE3FNF80K03E0P0B0,I0H540J0155005N540015M540H015O545540J0155005540I0I5I0H540I015
            505540K0H5405N5M0140O0140,H02FF80K0HF800FLFE80H0NFC0H03FNFE9FF80J01FFC0FFC0I03FF800FFE0I03FF8FFE0K0HFE0BF
            LF80M0F80N03E0,H01DD80K0HDC005DKDC0I05DKDC0I01DNDC1DD0K01DDC05DC0I01DDC005DC0I01DDCDDC0K05DD01DLDO01C0N05
            40,H07FF80K0HF8003FKF80I03FKF80I03FNF8BFF0L0HFE0FFC0I03FFE00FFE0J0KF80K07FF00FKFA0O0E0N0E,H0I5M0H540H0K54
            0K015I540J015M5H0H540L0H5405540I01554005540J0K5M0I5H015I540P050M014,H0HFE80K0HFC0H0KFE0K03FIFE0J03FMFA0FF
            E0L0HFE0FF80I01FFE00FFE0J0KF80K07FF803FIFE80O0F80L03E,Y05DD40M05DDC0iO01DDC0Q01C0L058,gS020iR020R03F80J03
            E0,lH0150J05,lI0FE88AFE80,lJ0KD0,lI02FJF8,lJ01575,,::::::::";
            $prueba =
                '^XA~
            TA000~
            JSN
            ^LT0
            ^MNW
            ^MTT
            ^PON^PMN^LH0,0^JMA^JUS^LRN^CI0^XZ
            ~DG000.GRF,06528,068,' .
                $imagen .
                '^XA
            ^MMT
            ^PW831
            ^LL0400
            ^LS0
            ^FT160,96^XG000.GRF,1,1^FS' .
                '^BY144,144^FT633,355^BXN,9,200,0,0,1,~^FH\^FD' . $data_item->codigo . '^FS
            ^FT37,232^ACN,36,20^FH\^FD' . $data_item->num_pedido . '^FS
            ^FT371,357^A0N,24,24^FH\^FDFecha:^FS
            ^FT451,358^ACN,18,10^FH\^FD' . date('d/m/Y') . '^FS
            ^FT267,270^A0N,24,26^FH\^FDCantidad total:^FS
            ^FT439,271^ACN,18,10^FH\^FD' . $formulario['caja'] . '^FS
            ^FT267,228^A0N,24,26^FH\^FDCantidad por rollo:^FS
            ^FT478,229^ACN,18,10^FH\^FD' .  $formulario['cant_x'] . '^FS
            ^FT37,312^A0N,24,26^FH\^FDCavidades:^FS
            ^FT163,313^ACN,18,10^FH\^FD' . $data_item->cav_cliente . '^FS
            ^FT37,270^A0N,24,26^FH\^FDTama\A4o:^FS
            ^FT140,271^ACN,18,10^FH\^FD' . $porciones_cod[0] . '^FS
            ^FT197,357^A0N,24,24^FH\^FDAux:^FS
            ^FT245,358^ACN,18,10^FH\^FD' . $operario . '^FS
            ^FT267,312^A0N,24,24^FH\^FDLote:^FS
            ^FT331,313^ACN,18,10^FH\^FD' . $formulario['lote'] . '^FS
            ^FT37,357^A0N,24,24^FH\^FDCore:^FS
            ^FT99,358^ACN,18,10^FH\^FD' . $data_item->nombre_core . '^FS
            ^FT37,185^A0N,25,24^FH\^FD' . $nombre2 . '^FS
            ^FT37,154^A0N,25,24^FH\^FD' . $nombre1 . '^FS
            ^FO26,370^GB575,0,1^FS
            ^FO26,324^GB575,0,1^FS
            ^FO26,282^GB576,0,1^FS
            ^FO26,123^GB776,0,2^FS
            ^FO27,239^GB575,0,1^FS
            ^FO26,195^GB776,0,1^FS
            ^FO344,326^GB0,45,1^FS
            ^FO155,325^GB0,46,1^FS
            ^FO240,196^GB0,129,2^FS
            ^FO600,196^GB0,175,1^FS
            ^PQ' . $formulario['cantidad'] . ',0,1,Y^XZ' . '<br>';
        }
    }
    echo $prueba;
    ?>
</div>