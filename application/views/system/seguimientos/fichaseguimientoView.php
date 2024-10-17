<?php
    error_reporting(E_ALL & ~E_WARNING );
    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfReader;
    global $PDFId_SeguimientoGlo;
    $PDFId_SeguimientoGlo=$data['datos_seguimiento']['principal']->Id_Seguimiento;
    class PDF extends Fpdi{
        public $PDFId_Seguimiento;
        function OnePage($data,$conteo){
            global $PDFId_Seguimiento;
            //print_r($PDFId_Seguimiento);
            $PDFId_Seguimiento = $data['principal']->Id_Seguimiento ;

            $width = 85;
            $height = 95;
            $y=$this->GetY();
            $width = 85;
            $width=$width+10;
            $this->Ln(3);
            $this->SetFont('Avenir','',11);
            $this->Cell(165, 4); 
            $this->SetTextColor(0, 0, 0);
            $this->Cell(55, 4, utf8_decode('INFORMA:'), 0,1);

            $this->Ln(1);
            $this->Cell(130, 4);
            $this->SetTextColor(128, 128, 128);
            $this->Cell(56, 4, utf8_decode($data['principal']->Elemento_Captura), 0, 1,'R');
          
            $this->Ln(1);
            
            $this->Cell(120, 4); 
            $this->SetTextColor(0, 0, 0);
            $this->Cell(55, 4, utf8_decode('FECHA Y HORA DE ELABORACIÓN:'), 0, 1);

           
            $fechalimpia=$this->formatearFecha($data['principal']->FechaHora_Creacion); 
            $this->Ln(1);
            $this->Cell(130, 4);
            $this->SetTextColor(128, 128, 128);
            $this->Cell(56, 4, utf8_decode($fechalimpia), 0, 1,'R');
           

            $this->Ln(10);
            $this->MultiCell(190, 7, utf8_decode('POR MEDIO DE ESTE DOCUMENTO, SE INFORMA QUE COMO RESULTADO DE LAS LABORES DE INVESTIGACIÓN Y ANÁLISIS REALIZADAS POR LOS DIVERSOS DEPARTAMENTOS DE LA DIRECCIÓN DE INTELIGENCIA Y POLÍTICA CRIMINAL, SE HA INTEGRADO UNA FICHA DELICTIVA QUE INCLUYE INFORMACIÓN DETALLADA ACERCA DE PERSONAS, VEHÍCULOS Y ACTIVIDADES DELICTIVAS RELACIONADAS CON BANDAS CRIMINALES. EL PROPÓSITO DE ESTA FICHA ES CUMPLIR CON NUESTROS OBJETIVOS DE PREVENIR Y PROTEGER LOS DERECHOS DE NUESTROS CIUDADANOS.'), 0, 'J');

            $this->SetY($y+85);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(190, 5, utf8_decode('INFORMACIÓN GENERAL'), 0, 1, 'C');
            $this->Cell(190, 5, utf8_decode('FICHA DELICTIVA'), 0, 1, 'C');
            
            $this->Cell(190, 1, utf8_decode('_______________________________________________________________________________________________'));
            $this->Ln(5);

            $this->SetFillColor(156,156,156); //242
            $this->Cell(65,7, utf8_decode('NOMBRE DE GRUPO DELICTIVO: '),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            $this->Cell(125,7, utf8_decode($data['principal']->Nombre_grupo_delictivo),1,0,'L');
            $this->Ln(7);
   
            $this->SetFillColor(156,156,156); //242
            $this->Cell(65,7, utf8_decode('PELIGROSIDAD:'),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            $this->Cell(30,7, utf8_decode($data['principal']->Peligrosidad),1,0,'C',true);
            $this->SetFillColor(156,156,156); //242
            $this->Cell(55,7, utf8_decode('TOTAL DE INTEGRANTES:'),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            $this->Cell(40,7, utf8_decode($conteo),1,0,'C',true);
            $this->Ln(7);

            $this->SetFillColor(156,156,156); //242
            $this->Cell(65,7, utf8_decode('DELITOS PRINCIPALES:'),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            if($data['delitos']!=[]){
                $delitos=$data['delitos'];
                $i=0;
                foreach ($delitos as $delito) {
                    if($i==0){

                        $Caddelitos=$delito->Delito;
                    }else{
                        $Caddelitos.=', '.$delito->Delito;
                    }
                    $i++;
                }
            }else{
                $Caddelitos='';
            }
            $this->SetFont('Avenir','',7);
            $this->Cell(125,7, utf8_decode($Caddelitos),1,0,'L',true);
            $this->Ln(10);

            $this->SetFont('Avenir','',11);
            $this->SetFillColor(156,156,156); //242
            $this->Cell(190,7, utf8_decode('MODUS OPERANDI: '),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            $this->Ln(7);
            $this->MultiCell(190,7, utf8_decode($data['principal']->Modus_operandi),1,'J');

            $this->Ln(10);
            $this->SetFillColor(156,156,156); //242
            $this->Cell(190,7, utf8_decode('OBSERVACIONES: '),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            $this->Ln(7);
            $this->MultiCell(190,7, utf8_decode($data['principal']->Observaciones),1,'J');
        }
        function InfoHijoRed($data){
            //print_r($data);
            
            global $PDFId_Seguimiento;
            //print_r($PDFId_Seguimiento);
            $PDFId_Seguimiento = $data['principal']->Id_Seguimiento ;
            
            $this->SetTextColor(255, 255, 255);                   
            $this->SetFillColor(41,41,95); 
            //$this->Cell(190, 5, utf8_decode('RED DE VINCULO RELACIONADA '.$data['principal']->Nombre_grupo_delictivo.', FOLIO RED:'.$data['principal']->Id_Seguimiento ), 0, 0, 'C',true);
            $this->ln(6);
            $width = 85;
            $height = 95;
            $y=$this->GetY();
            $width = 85;
            $width=$width+10;
            $this->Ln(3);
            $this->SetFont('Avenir','',11);
            $this->Cell(165, 4); 
            $this->SetTextColor(0, 0, 0);
            $this->Cell(55, 4, utf8_decode('INFORMA:'), 0,1);

            $this->Ln(1);
            $this->Cell(130, 4);
            $this->SetTextColor(128, 128, 128);
            $this->Cell(56, 4, utf8_decode($data['principal']->Elemento_Captura), 0, 1,'R');
          
            $this->Ln(1);
            //Nombre_grupo_delictivo
            $this->Cell(120, 4); 
            $this->SetTextColor(0, 0, 0);
            $this->Cell(55, 4, utf8_decode('FECHA Y HORA DE ELABORACIÓN:'), 0, 1);

           
            $fechalimpia=$this->formatearFecha($data['principal']->FechaHora_Creacion); 
            $this->Ln(1);
            $this->Cell(130, 4);
            $this->SetTextColor(128, 128, 128);
            $this->Cell(56, 4, utf8_decode($fechalimpia), 0, 1,'R');

            $this->Ln(3);
            $this->SetTextColor(0, 0, 0);
            $this->Cell(190, 5, utf8_decode('INFORMACIÓN GENERAL'), 0, 1, 'C');
            
            $this->Cell(190, 1, utf8_decode('_______________________________________________________________________________________________'));
            $this->Ln(5);

            $this->SetFillColor(156,156,156); //242
            $this->Cell(65,7, utf8_decode('DELITOS PRINCIPALES:'),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            if($data['delitos']!=[]){
                $delitos=$data['delitos'];
                $i=0;
                foreach ($delitos as $delito) {
                    if($i==0){

                        $Caddelitos=$delito->Delito;
                    }else{
                        $Caddelitos.=', '.$delito->Delito;
                    }
                    $i++;
                }
            }else{
                $Caddelitos='';
            }
            $this->SetFont('Avenir','',7);
            $this->Cell(125,7, utf8_decode($Caddelitos),1,0,'L',true);
            $this->Ln(10);

            $this->SetFont('Avenir','',11);
            $this->SetFillColor(156,156,156); //242
            $this->Cell(190,7, utf8_decode('MODUS OPERANDI: '),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            $this->Ln(7);
            $this->MultiCell(190,7, utf8_decode($data['principal']->Modus_operandi),1,'J');

            $this->Ln(5);
            $this->SetFillColor(156,156,156); //242
            $this->Cell(190,7, utf8_decode('OBSERVACIONES: '),1,0,'L',true);
            $this->SetFillColor(255,255,255); //242
            $this->Ln(7);
            $this->MultiCell(190,7, utf8_decode($data['principal']->Observaciones),1,'J');
        }
        function PagePersona($data,$numero_persona){
            $this->SetTextColor(255, 255, 255);                   
            $this->SetFillColor(41,41,95); 
            $this->Cell(190, 5, utf8_decode('PERSONA IDENTIFICADA '.$numero_persona), 0, 0, 'C',true);
            $this->ln(6);
            $this->Cell(155, 4, '');
            $this->SetFont('helvetica','B',6);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(30, 4, utf8_decode('CAPTURO ('.$data['datos_persona']->Capturo.')'));
            $this->SetFont('Avenir','',11);
            $this->ln(4);
            $width = 70;
            $height = 95;
            $y=$this->GetY();
            if($data['datos_persona']->Foto!=null&&$data['datos_persona']->Foto!='SD'&&$data['datos_persona']->Foto!=''){
                $filename = base_url."public/files/Seguimientos/" . $data['datos_persona']->Id_Seguimiento . "/Personas/".$data['datos_persona']->Foto;
                $type = exif_imagetype($filename);
                $extension = '';
                switch($type){
                    case 1:
                        $extension = 'gif';
                    break;
                    case 2:
                        $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                        $image = imagecreatefromjpeg($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".jpeg";
                        imagejpeg($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                    case 3:
                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                        $image = imagecreatefrompng($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".png";
                        imagepng($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                } 
            }else{
                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                $imagennueva=base_url."public/media/images/frentesilueta.png";
                $this->Image($imagennueva,10, $y, $width, $height, $extension);
            }
            $width=$width+5;
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(41, 4, utf8_decode('NOMBRE COMPLETO:'));

            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_persona']->Nombre.' '.$data['datos_persona']->Ap_Paterno.' '.$data['datos_persona']->Ap_Materno), 0, 'J');
            
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(15, 4, utf8_decode('EDAD: '));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(15, 4, utf8_decode($data['datos_persona']->Edad));
            $this->SetTextColor(51, 51, 51);
            $this->Cell(15, 4, utf8_decode('CURP: '));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(50, 4, utf8_decode($data['datos_persona']->Curp));

            $this->Ln(10);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(50, 4, utf8_decode('FECHA DE NACIMIENTO: '));

            $this->SetTextColor(128, 128, 128);
            $arrayFecha=explode('-',$data['datos_persona']->Fecha_Nacimiento);
            $arrayFecha=array_reverse($arrayFecha);
            $Fecha=implode("-", $arrayFecha);
            $this->MultiCell(90, 4, utf8_decode($Fecha), 0, 'J');
            
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(20, 4, utf8_decode('GENERO: '));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(25, 4, utf8_decode($data['datos_persona']->Genero));
            $this->SetTextColor(51, 51, 51);
            $this->Cell(25, 4, utf8_decode('TELEFONO: '));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(30, 4, utf8_decode($data['datos_persona']->Telefono));


            $this->Ln(10);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(15, 4, utf8_decode('ALIAS: '));
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_persona']->Alias), 0, 'J');

            $this->Ln(10);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(15, 4, utf8_decode('REMISIONES ASOCIADAS A LA PERSONA: '));
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_persona']->Remisiones), 0, 'J');
            $this->Ln(10);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(45, 4, utf8_decode('ROL DE LA PERSONA: '));
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_persona']->Rol), 0, 'J');

         
            $this->SetY($y+97);

            if($data['antecedentes']!=[]){
                $i=1;
                $antecedentes=$data['antecedentes'];
                foreach ($antecedentes as $antecedente){
                    
                    if($i!=1 ||$this->GetY()>=265){
                        $aux=$this->revisaYAntecedente($this->GetY());
                        $this->SetY($aux);
                    }
                    if($antecedente->Fecha_Antecedente!='SD'){
                        $fechalimpia=$this->fechaMes($antecedente->Fecha_Antecedente);
                    }else{
                        $fechalimpia='';
                    }
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156); 
                    $this->Cell(190, 4, utf8_decode('ANTECEDENTE '.$i.' '.$fechalimpia),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$antecedente->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->Ln(3);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(190, 4, utf8_decode($antecedente->Descripcion_Antecedente), 0, 'J');
                    $i++;
                    $this->ln(2);
                }
            }
            $aux=$this->revisaYDomicilio($this->GetY());
            $this->SetY($aux);
            if($data['domicilios']!=[]){
                $i=1;
                $domicilios=$data['domicilios'];
                foreach ($domicilios as $domicilio){
                    if($i!=1 ||$this->GetY()>=245){
                        $aux=$this->revisaYDomicilio($this->GetY());
                        $this->SetY($aux);
                    }
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode('DOMICILIO '.$i.' ('.$domicilio->Estatus.')'),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$domicilio->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->ln(3);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('UBICACION DEL DOMICILIO: '));
                    $this->Ln(5);
                    $this->SetTextColor(128, 128, 128);
                    $cadena = str_replace('CALLE ','',$domicilio->Calle);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
                    $domicilioCompleto='CALLE: '.$cadena;
                    $cadena = str_replace('CALLE ','',$domicilio->Calle2);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
                    $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE 2: '.$cadena: '';
                    $domicilioCompleto.=', COLONIA: '.$domicilio->Colonia;
                    $domicilioCompleto .= ($domicilio->NumExt!='SD') ? ', NUM.EXT: '.$domicilio->NumExt: '';
                    $domicilioCompleto .= ($domicilio->NumInt!='SD') ? ', NUM.INT: '.$domicilio->NumInt: '';
                    $domicilioCompleto .= ($domicilio->CP!='SD') ? ', CP: '.$domicilio->CP: '';
                    $domicilioCompleto .= ($domicilio->CoordY!='SD') ? ', COORDY: '.$domicilio->CoordY: '';
                    $domicilioCompleto .= ($domicilio->CoordX!='SD') ? ', COORDX: '.$domicilio->CoordX: '';
                    $this->MultiCell(190, 4, utf8_decode($domicilioCompleto), 0, 'J');
                    $this->ln(2);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('OBSERVACION DEL DOMICILIO: '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(160, 4, utf8_decode($domicilio->Observaciones_Ubicacion), 0, 'J');
                    $this->Ln(2);
                    $i++;
                }
            }
            if($data['redes_sociales']!=[]){
                $aux=$this->revisaYRed($this->GetY());
                $this->SetY($aux);
                $i=1;
                $redes_sociales=$data['redes_sociales'];
                foreach ($redes_sociales as $red_social){
                    if($i!=1||$this->GetY()>=220){
                        $aux=$this->revisaYRed($this->GetY());
                        $this->SetY($aux);
                    }
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode('DATO RED SOCIAL '.$i),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$red_social->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->ln(4);
                    $i++;
                    $width = 65;
                    $height = 60;
                    $y=$this->GetY();
                    if($red_social->Foto_Nombre!=null&&$red_social->Foto_Nombre!='SD'&&$red_social->Foto_Nombre!=''){

                        $filenameForencia = base_url."public/files/Seguimientos/" . $GLOBALS['PDFId_Seguimiento']  . "/Redes_Sociales/".$red_social->Foto_Nombre;
                        $type = exif_imagetype($filenameForencia);
                        $extension = '';

                        switch($type){
                            case 1:
                                $extension = 'gif';
                            break;
                            case 2:
                                $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                $image = imagecreatefromjpeg($filenameForencia);
                                imageinterlace($image, false);
                                $nombre="red_social".rand().".jpeg";
                                imagejpeg($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,130, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                            case 3:
                                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                $image = imagecreatefrompng($filenameForencia);
                                imageinterlace($image, false);
                                $nombre="red_social".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,130, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                        } 
                    }

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(125, 4, utf8_decode('NOMBRE DE USUARIO (PERFIL): '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(120, 4, utf8_decode($red_social->Usuario), 0, 'J');
                    $this->Ln(4);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(125, 4, utf8_decode('TIPO DE ENLACE: '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(120, 4, utf8_decode($red_social->Tipo_Enlace), 0, 'J');
                    $this->Ln(4);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(125, 4, utf8_decode('ENLACE: '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(120, 4, utf8_decode($red_social->Enlace), 0, 'J');
                    $this->Ln(4);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(125, 4, utf8_decode('OBSERVACION DE ENLACE: '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(120, 4, utf8_decode($red_social->Observacion_Enlace), 0, 'J');
                    if($red_social->Foto_Nombre!=null&&$red_social->Foto_Nombre!='SD'&&$red_social->Foto_Nombre!=''){//solo si existe fot realiza esta funcion
                        $y1=$this->GetY();
                        $operacion=$y-$y1;
                        $diferencia=abs($operacion);
                        if($diferencia<65){ 
                            $y2=$y+65-$y1;
                            $this->Ln($y2+5);
                        }
                    }
                }
            }
            if($data['forencias']!=[]&&$_SESSION['userdataSIC']->Visualizacion==1){
                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);
                $i=1;
                $forencias=$data['forencias'];
                foreach ($forencias as $forencia){
                    if($i!=1||$this->GetY()>=230){
                        $aux=$this->revisaY($this->GetY());
                        $this->SetY($aux);
                    }
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode('DATO '.$i),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$forencia->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->ln(4);
                    $i++;
                    $width = 60;
                    $height = 65;
                    $y=$this->GetY();
                    if($forencia->Foto_Nombre!=null&&$forencia->Foto_Nombre!='SD'&&$forencia->Foto_Nombre!=''){

                        $filenameForencia = base_url."public/files/Seguimientos/" . $GLOBALS['PDFId_Seguimiento']  . "/Forencias/".$forencia->Foto_Nombre;
                        $type = exif_imagetype($filenameForencia);
                        $extension = '';

                        switch($type){
                            case 1:
                                $extension = 'gif';
                            break;
                            case 2:
                                $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                $image = imagecreatefromjpeg($filenameForencia);
                                imageinterlace($image, false);
                                $nombre="forencia".rand().".jpeg";
                                imagejpeg($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,130, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                            case 3:
                                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                $image = imagecreatefrompng($filenameForencia);
                                imageinterlace($image, false);
                                $nombre="forencia".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,130, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                        } 
                    }


                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('DESCRIPCION DEL DATO: '));
                    $this->Ln(5);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(115, 4, utf8_decode($forencia->Descripcion_Forencia), 0, 'J');
                    if($forencia->Foto_Nombre!=null&&$forencia->Foto_Nombre!='SD'&&$forencia->Foto_Nombre!=''){//solo si existe fot realiza esta funcion
                        $y1=$this->GetY();
                        $operacion=$y-$y1;
                        $diferencia=abs($operacion);
                        if($diferencia<65){ 
                            $y2=$y+65-$y1;
                            $this->Ln($y2+5);
                        }
                    }
                }
                $this->ln(4);
            }

        }
        function PageVehiculo($data,$numero_vehiculo){
            $this->SetTextColor(255, 255, 255);                   
            $this->SetFillColor(41,41,95); 
            $this->Cell(190, 5, utf8_decode('VEHICULO IDENTIFICADO '.$numero_vehiculo), 0, 1, 'C',true);
            $this->ln(4);
            $this->Cell(155, 4, '');
            $this->SetFont('helvetica','B',6);
            $this->Cell(30, 4, utf8_decode('CAPTURO ('.$data['datos_Vehiculo']->Capturo.')'));
            $this->SetFont('Avenir','',11);
            $this->ln(4);
            $width = 70;
            $height = 95;
            $y=$this->GetY();
            if($data['datos_Vehiculo']->Foto!=null&&$data['datos_Vehiculo']->Foto!='SD'&&$data['datos_Vehiculo']->Foto!=''){
                $filename = base_url."public/files/Seguimientos/" . $data['datos_Vehiculo']->Id_Seguimiento . "/Vehiculos/".$data['datos_Vehiculo']->Foto;
                $type = exif_imagetype($filename);
                $extension = '';
                switch($type){
                    case 1:
                        $extension = 'gif';
                    break;
                    case 2:
                        $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                        $image = imagecreatefromjpeg($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".jpeg";
                        imagejpeg($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                    case 3:
                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                        $image = imagecreatefrompng($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".png";
                        imagepng($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                } 
            }else{
                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                $imagennueva=base_url."public/media/images/vehiculoSilueta.png";
                $this->Image($imagennueva,10, $y, $width, $height, $extension);
            }
            $width=$width+10;
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(60, 4, utf8_decode('PLACAS: '));
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_Vehiculo']->Placas), 0, 'J');

            $this->Ln(2);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(60, 4, utf8_decode('NIVS: '));
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_Vehiculo']->Nivs), 0, 'J');

            $this->Ln(2);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(15, 4, utf8_decode('MARCA:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(35, 4, utf8_decode($data['datos_Vehiculo']->Marca));
            $this->Ln(7);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(25, 4, utf8_decode('SUBMARCA:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(50, 4, utf8_decode($data['datos_Vehiculo']->Submarca));

            $this->Ln(7);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(20, 4, utf8_decode('MODELO:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(30, 4, utf8_decode($data['datos_Vehiculo']->Modelo));
            $this->Ln(7);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(15, 4, utf8_decode('COLOR:'));
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_Vehiculo']->Color));

            $this->Ln(2);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(60, 4, utf8_decode('NOMBRE DEL PROPIETARIO:'));
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_Vehiculo']->Nombre_Propietario), 0, 'J');

            $this->Ln(7);
            $this->Cell($width, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(60, 4, utf8_decode('INFORMACION DE LAS PLACA:'));
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(90, 4, utf8_decode($data['datos_Vehiculo']->InfoPlaca), 0, 'J');

            $this->SetY($y+105);

            if($data['antecedentes']!=[]){
                $i=1;
                $antecedentes=$data['antecedentes'];
                foreach ($antecedentes as $antecedente){
                    if($i!=1 ||$this->GetY()>=265){
                        $aux=$this->revisaYAntecedente($this->GetY());
                        $this->SetY($aux);
                    }
                    if($antecedente->Fecha_Antecedente!='SD'){
                        $fechalimpia=$this->fechaMes($antecedente->Fecha_Antecedente);
                    }else{
                        $fechalimpia='';
                    }
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode('ANTECEDENTE '.$i.' '.$fechalimpia),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$antecedente->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->Ln(2);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(190, 4, utf8_decode($antecedente->Descripcion_Antecedente), 0, 'J');
                    $i++;
                }
               $this->ln(4);
            }
            $aux=$this->revisaYDomicilio($this->GetY());
            $this->SetY($aux);
            if($data['domicilios']!=[]){
                $i=1;
                $domicilios=$data['domicilios'];
                foreach ($domicilios as $domicilio){
                    if($i!=1 ||$this->GetY()>=245){
                        $aux=$this->revisaYDomicilio($this->GetY());
                        $this->SetY($aux);
                    }
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode('DOMICILIO '.$i.' ('.$domicilio->Estatus.')'),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$domicilio->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->ln(4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('UBICACION DEL DOMICILIO: '));
                    $this->Ln(5);
                    $this->SetTextColor(128, 128, 128);
                    $cadena = str_replace('CALLE ','',$domicilio->Calle);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
                    $domicilioCompleto='CALLE: '.$cadena;
                    $cadena = str_replace('CALLE ','',$domicilio->Calle2);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
                    $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE 2: '.$cadena: '';
                    $domicilioCompleto.=', COLONIA: '.$domicilio->Colonia;
                    $domicilioCompleto .= ($domicilio->NumExt!='SD') ? ', NUM.EXT: '.$domicilio->NumExt: '';
                    $domicilioCompleto .= ($domicilio->NumInt!='SD') ? ', NUM.INT: '.$domicilio->NumInt: '';
                    $domicilioCompleto .= ($domicilio->CP!='SD') ? ', CP: '.$domicilio->CP: '';
                    $domicilioCompleto .= ($domicilio->CoordY!='SD') ? ', COORDY: '.$domicilio->CoordY: '';
                    $domicilioCompleto .= ($domicilio->CoordX!='SD') ? ', COORDX: '.$domicilio->CoordX: '';
                    $this->MultiCell(190, 5, utf8_decode($domicilioCompleto), 0, 'J');
                    $this->ln(4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('OBSERVACION DEL DOMICILIO: '));
                    $this->Ln(5);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(160, 4, utf8_decode($domicilio->Observaciones_Ubicacion), 0, 'J');
                    $this->ln(4);
                    $i++;
                }
                $this->ln(4);
            }
        }
        function PageEvento($data,$numero_Evento){
            $this->SetFont('Avenir','',11);
            //$this->AddPage();
            $this->OneEvento($data, $numero_Evento);
            /*if($data['evento']->Path_Pdf!='SD' && $data['evento']->Path_Pdf!=null){
                $archivoExistente = '../public/files/GestorCasos/'.$data['evento']->Folio_infra.'/'.$data['evento']->Path_Pdf;
                
                $pageCount = $this->setSourceFile($archivoExistente);

                for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                    $pageId = $this->importPage($pageNumber, PdfReader\PageBoundaries::MEDIA_BOX);
                    $this->addPage();
                    $this->useTemplate($pageId, 0, 0, 210, 230, true);
                }
                // Añadir la página del primer archivo al nuevo PDF
                //$pageId = $this->importPage(1,PdfReader\PageBoundaries::MEDIA_BOX);
                //$this->addPage();
                //$this->useTemplate($pageId,0,0,210,230,true);
            }*/
        }
        function OneEvento($data, $i){
            $Folio_infra         = $data['principales']->Folio_infra;


            $this->SetTextColor(255, 255, 255);                   
            $this->SetFillColor(41,41,95); 
            $this->Cell(190, 5, utf8_decode('EVENTOS RELACIONADOS A LA RED DE VÍNCULO'), 0, 1, 'C',true);            
            $this->ln(3);
            $this->SetTextColor(51, 51, 51);
            $this->SetFillColor(156,156,156); 
            $this->Cell(190, 5, utf8_decode('DATOS PRINCIPALES (EVENTO '.$i.')'), 0, 1, 'C',true);
            $this->ln(2);

            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(33,  4, utf8_decode("FOLIO AURA:"));

            $this->SetTextColor(0, 0, 255);
            $this->Cell(20,  4, $Folio_infra);

            $this->ln(7);
            $this->Cell(5, 4);//VIÑETA PARA JUSTIFICAR
            $this->SetTextColor(51, 51, 51);
            $this->Cell(60, 4, utf8_decode('FECHA Y HORA DE RECEPCION:'));
            $this->SetTextColor(128, 128, 128);
            $fechalimpia=$this->formatearFecha($data['principales']->FechaHora_Recepcion);
            $this->Cell(11, 4, utf8_decode($fechalimpia));

            $this->ln(7);
            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(20, 4, utf8_decode('FUENTE:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(60, 4, utf8_decode($data['principales']->Fuente));

            $this->SetTextColor(51, 51, 51);
            $this->Cell(25, 4, utf8_decode('FOLIO 911:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(25, 4, utf8_decode($data['principales']->Folio_911));
        
            $this->ln(7);
            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(10, 4, utf8_decode('CDI:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(25, 4, utf8_decode($data['evento']->Cdi)); 

            $this->ln(7);
            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(20, 4, utf8_decode('DELITOS:'));
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(160, 4, utf8_decode($data['principales']->delitos_concat.' , '.$data['principales']->CSviolencia.' , '.$data['principales']->Tipo_Violencia), 0, 'J');
           
            $this->ln(3);
            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(190, 5, utf8_decode('UBICACION DEL EVENTO:'));
            $this->ln(5);
            $this->Cell(5, 4);
            $ubicacion="";
            $ubicacion = ($data['principales']->Calle!='SD'&&$data['principales']->Calle!="") ? 'CALLE: '.$data['principales']->Calle: '';
            $ubicacion .= ($data['principales']->Calle2!='SD'&&$data['principales']->Calle2!="") ? ', CALLE2: '.$data['principales']->Calle2: '';
            $ubicacion .=', COLONIA: '.$data['principales']->Colonia;
            $ubicacion .= ($data['principales']->NoExt!='SD'&&$data['principales']->NoExt!=""&&$data['principales']->NoExt!="null"&&$data['principales']->NoExt!="undefined") ? ', NOEXT: '.$data['principales']->NoExt: '';
            $this->SetTextColor(128, 128, 128);
            $this->MultiCell(180, 4, utf8_decode($ubicacion));
            $this->ln(3);
            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(35, 4, utf8_decode('COORDENADA Y:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(55, 4, utf8_decode($data['principales']->CoordY));
            $this->SetTextColor(51, 51, 51);
            $this->Cell(35, 4, utf8_decode('COORDENADA X:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(55, 4, utf8_decode($data['principales']->CoordX));

            $this->ln(7);
            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(14, 4, utf8_decode('ZONA:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(80, 4, utf8_decode($data['principales']->Zona));
            $this->SetTextColor(51, 51, 51);
            $this->Cell(18, 4, utf8_decode('VECTOR:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(15, 4, utf8_decode($data['principales']->Vector));
            
            $this->ln(7);
            $this->Cell(5, 4);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(105, 4, utf8_decode('ELEMENTO ASIGNADO AL SEGUIMIENTO DEL EVENTO:'));
            $this->SetTextColor(128, 128, 128);
            $this->Cell(20, 4, utf8_decode($data['principales']->ClaveSeguimiento));
            $this->ln(7);


            if($data['evento']->Unidad_Primer_R != NULL && $data['evento']->Unidad_Primer_R != ''){  
                $this->Cell(5, 4);
                $this->SetTextColor(51, 51, 51);
                $this->Cell(65, 4, utf8_decode('UNIDAD PRIMER RESPONDIENTE:'));
                $this->SetTextColor(128, 128, 128);
                $this->Cell(180, 4, utf8_decode($data['evento']->Unidad_Primer_R));
                $this->ln(7);
                $this->Cell(5, 4);
                $this->SetTextColor(51, 51, 51);
                $this->Cell(190, 5, utf8_decode('INFORMACION DEL PRIMER RESPONDIENTE:'));
                $this->ln(5);
                $this->Cell(5, 4);
                $this->SetTextColor(128, 128, 128);
                $this->MultiCell(180, 4, utf8_decode($data['evento']->Informacion_Primer_R));
                $this->ln(3);
            }
            if($data['evento']->Acciones!=NULL && $data['evento']->Acciones!='' ){
                $this->Cell(5, 4);
                $this->SetTextColor(51, 51, 51);
                $this->Cell(22, 4, utf8_decode('ACCIONES REALIZADAS POR IO:'));
                $this->ln(5);
                $this->Cell(5, 4);
                $this->SetTextColor(128, 128, 128);
                $this->MultiCell(169, 4, utf8_decode($data['evento']->Acciones));
                $this->ln(3);
            }
            if($data['evento']->Ubo_Detencion!=0 && $data['detencion']!=[]){
                $this->SetTextColor(51, 51, 51);
                $this->SetFillColor(156,156,156); 
                $this->Cell(190, 4, utf8_decode('DETENCION DEL EVENTO'),0,0,'C',true);
                $this->ln(7);
                $this->Cell(5, 4);
                $this->SetTextColor(51, 51, 51);
                $this->Cell(55, 4, utf8_decode('UBICACION DE LA DETENCION:'));
                $this->ln(5);

                $detencion =  $data['detencion'];

                $cadena = str_replace('CALLE ','',$detencion->Calle);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
                $ubicacionDet='CALLE: '.$cadena;

                $cadena = str_replace('CALLE ','',$detencion->Calle2);
                $ubicacionDet .= ($detencion->Calle2!='SD'&& $detencion->Calle2!='') ? ', CALLE 2: '.$cadena: '';
                $ubicacionDet .=', COLONIA: '.$detencion->Colonia;
                $ubicacionDet .= ($detencion->NumExt!='SD' && $detencion->NumExt!='') ? ', NUM.EXT: '.$detencion->NumExt: '';
                $ubicacionDet .= ($detencion->NumInt!='SD' && $detencion->NumInt!='') ? ', NUM.INT: '.$detencion->NumInt: '';
                $ubicacionDet .= ($detencion->CP!='SD') ? ', CP: '.$detencion->CP: '';
                $ubicacionDet .= ($detencion->CoordY!='SD') ? ', COORDY: '.$detencion->CoordY: '';
                $ubicacionDet .= ($detencion->CoordX!='SD') ? ', COORDX: '.$detencion->CoordX: '';
                if($detencion->Foraneo == 1){
                    $ubicacionDet .=', ESTADO: '.$detencion->Estado;
                    $ubicacionDet .=', MUNICIPIO: '.$detencion->Municipio;
                }

                $this->Cell(5, 4);
                $this->SetTextColor(128, 128, 128);
                $this->MultiCell(180, 4, utf8_decode($ubicacionDet));
                $this->ln(3);

                if($detencion->Observaciones_Detencion!=''){
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(55, 4, utf8_decode('OBSERVACIONES DE LA DETENCION:'));
                    $this->ln(5);
                    $this->Cell(5, 4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(180, 4, utf8_decode($detencion->Observaciones_Detencion));
                    $this->ln(3);
                }


                $this->Cell(5, 4);
                $this->SetTextColor(51, 51, 51);
                $this->Cell(55, 4, utf8_decode('NOMBRES DE LOS DETENIDOS:'));
                $this->ln(5);
                $this->Cell(5, 4);
                $this->SetTextColor(255, 0, 0);
                $this->MultiCell(180, 4, utf8_decode($detencion->Nombres_Detenidos));
                $this->ln(3);

                $this->Cell(5, 4);
                $this->SetTextColor(51, 51, 51);
                $this->Cell(55, 4, utf8_decode('NOMBRES DE LOS ELEMENTOS QUE REALIZARON LA DETENCION ('.$detencion->Compañia.'):'));
                $this->ln(5);
                $this->Cell(5, 4);
                $this->SetTextColor(128, 128, 128);
                $this->MultiCell(180, 4, utf8_decode($detencion->Elementos_Realizan_D));
                $this->ln(3);

                $this->Cell(5, 4);
                $this->SetTextColor(51, 51, 51);
                $detencionIO = ($detencion->Detencion_Por_Info_Io == 1)? "SI":"NO";
                $this->Cell(55, 4, utf8_decode('LA DETENCION FUE POR LA INFOMACION PROPORCIONADA POR IO: '.$detencionIO));
                $this->ln(7);
            }

            /*$this->Cell(190, 2, '', 0, 1);//FUNCION PARA PINTAR RECTANGULO
            $y2 = $this->GetY();
            $this->SetY($y1);
            $this->Cell(190, $y2-$y1, '', 1, 1);*/
            $this->Ln(2);

            $this->SetTextColor(51, 51, 51);
            $this->SetFillColor(156,156,156); 
            $this->Cell(190, 4, utf8_decode('HECHOS REPORTADOS'),0,0,'C',true);
            $hechos= $data['hechos'];
            $this->Ln(5);
            $this->SetTextColor(128, 128, 128);
            $k=1;
            foreach($hechos as $hecho){
                $auxhecho = str_replace("\n", " ", $hecho->Descripcion);
                $this->Cell(5, 4);
                $this->MultiCell(180, 4, utf8_decode($k.'.- '.$auxhecho.' FECHA Y HORA: '.$hecho->Fecha_Hora_Hecho), 0, 'J');
                $this->Ln(1);
                $this->Cell(195, 1, utf8_decode('__________________________________________________________________________________________________'));
                $this->Ln(5);
                $k++;
            }
            if($data['entrevistas']!=[]){
                $aux = $this->revisaYEvento($this->GetY());
                $this->SetY($aux);
                $this->SetTextColor(51, 51, 51);
                $this->Cell(190, 4, utf8_decode('ENTREVISTAS'), 0, 1, 'C',true);
                $this->Ln(2);
                $entrevistas= $data['entrevistas'];

                foreach($entrevistas as $entrevista){
                    $aux = $this->revisaYEvento($this->GetY());
                    $this->SetY($aux);
                   

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(31, 4, utf8_decode('ENTREVISTADO:'));
                    $this->SetTextColor(128, 128, 128);
                    $edad = ($entrevista->edad_entrevistado!=null && $entrevista->edad_entrevistado!='SD' && trim($entrevista->edad_entrevistado)!='')?'('.$entrevista->edad_entrevistado.' AÑOS)':'';
                    $this->Cell(95, 4, utf8_decode($entrevista->entrevistado.' '.$edad));

                    $this->Ln(6);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(33, 4, utf8_decode('ENTREVISTADOR:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(75, 4, utf8_decode($entrevista->entrevistador));
                    $this->SetTextColor(51, 51, 51);
                    
                    $this->Cell(38, 4, utf8_decode('NUM. TELEFÓNICO:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(70, 4, utf8_decode($entrevista->telefono_entrevistado));
                    $this->Ln(6);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(60, 4, utf8_decode('PROCEDENCIA DE ENTREVISTA:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(30, 4, utf8_decode($entrevista->procedencia));
                    $this->Ln(6);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(20, 4, utf8_decode('ENTREVISTA:'));
                    $this->Ln(5);
                    $this->SetTextColor(128, 128, 128);
                    $auxentrevista = str_replace("\n", " ", $entrevista->entrevista);
                    $this->MultiCell(190, 4, utf8_decode($auxentrevista), 0, 'J');
                    $this->Ln(1);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(195, 1, utf8_decode('__________________________________________________________________________________________________'));
                    $this->Ln(4);
                }
            }
            if($data['vehiculos']!=null && $data['vehiculos']!=[]){
                $aux = $this->revisaYVehI($this->GetY());
                $this->SetY($aux);
                $vehiculos = $data['vehiculos'];
                $this->SetTextColor(51, 51, 51);
                $this->SetFillColor(156,156,156);
                $this->Cell(190, 4, utf8_decode('VEHICULOS RELACIONADOS AL EVENTO'),0,0,'C',true);
                $this->ln(6);
                $i=1;
                foreach($vehiculos as $vehiculo){
                    if($i!=1){
                        $aux = $this->revisaYVehI($this->GetY());
                        $this->SetY($aux);
                    }

                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(40, 4, utf8_decode('VEHICULO '.$i),0,0,'C',true);
                    $this->Cell(5, 4);  
                    $this->Cell(14, 4, utf8_decode('PLACA:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(60, 4, utf8_decode($vehiculo->Placas_Vehiculo));
                    if($vehiculo->Capturo!="SD"){
                        $this->SetFont('Avenir','',6);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(30, 1, utf8_decode('CAPTURO '.$vehiculo->Capturo.' Y LA ULTIMA ACTUALIZACION '.$vehiculo->Ultima_Actualizacion));
                        $this->SetFont('Avenir','',11);
                    }
                
                    $this->Ln(7);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(16, 4, utf8_decode('MARCA:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(45, 4, utf8_decode($vehiculo->Marca));
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(23, 4, utf8_decode('SUBMARCA:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(40, 4, utf8_decode($vehiculo->Submarca));
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(18, 4, utf8_decode('ESTATUS:'));
                    $this->SetTextColor(128, 128, 128);             
                    if($vehiculo->Estado_Veh=='CORROBORADO'){
                        $this->SetTextColor(255,0,0);
                        $this->Cell(40, 4, utf8_decode($vehiculo->Estado_Veh));
                    }else{
                        $this->SetTextColor(0,0,200);
                        $this->Cell(40, 4, utf8_decode($vehiculo->Estado_Veh));
                    }

                    $this->Ln(7);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(37, 4, utf8_decode('TIPO DE VEHICULO:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(60, 4, utf8_decode($vehiculo->Tipo_Vehiculo));
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(49, 4, utf8_decode('RELACION EN EL EVENTO:'));
                    if($vehiculo->Tipo_veh_invo!='PARTE AFECTADA'){
                        $this->SetTextColor(255,0,0);
                        $this->Cell(40, 4, utf8_decode($vehiculo->Tipo_veh_invo));
                    }else{
                        $this->SetTextColor(0,0,200);
                        $this->Cell(40, 4, utf8_decode($vehiculo->Tipo_veh_invo));
                    }
                    
                    $this->Ln(7);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(15, 4, utf8_decode('COLOR:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(45, 4, utf8_decode($vehiculo->Color));
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(19, 4, utf8_decode('MODELO:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(55, 4, utf8_decode($vehiculo->Modelo));

                
                    $this->Ln(7);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(28, 4, utf8_decode('DESCRIPCION:'));
                    $this->Ln(5);
                    $this->Cell(5, 4);
                    $this->SetTextColor(128, 128, 128);
                    $auxVehiculo = str_replace(array("\n", "\t"), array(' ', ' '), $vehiculo->Descripcion_gral);
                    $this->MultiCell(185, 5, utf8_decode($auxVehiculo), 0, 'J');
                    $this->Ln(2);
                    if($vehiculo->Path_Imagen!=null && $vehiculo->Path_Imagen!=''){
                        $nombreFotoV1=explode("?",$vehiculo->Path_Imagen);
                        $filename = base_url."public/files/GestorCasos/" . $Folio_infra . "/Evento/";
                        $image1=$filename.$nombreFotoV1[0];
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(30, 4, utf8_decode('FOTO:'));
                        $this->Ln(5);
                        $type = exif_imagetype($image1);
                        $extension = '';
                        $width = $height = $x = $y = 0;
                        $widthImg = getimagesize($image1)[0];
                        $heightImg = getimagesize($image1)[1];

                        $width = 100;
                        $height = 50;
                        $y=$this->GetY();
                        switch($type){
                            case 1:
                                $extension = 'gif';
                            break;
                            case 2:
                                $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                $image = imagecreatefromjpeg($image1);
                                imageinterlace($image, false);
                                $nombre="Vehtemporal".rand().".jpeg";
                                imagejpeg($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,60, $y, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                            case 3:
                                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                $image = imagecreatefrompng($image1);
                                imageinterlace($image, false);
                                $nombre="Vehtemporal".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,60, $y, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                        }
                        $this->Ln(50);
                        
                    }else{
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(30, 4, utf8_decode('FOTO: SD'));
                        $this->Ln(2);
                    }
                    
                    $this->Cell(195, 1, utf8_decode('__________________________________________________________________________________________________'));
                    $this->Ln(4);
                    $i++;
                }
                
            }
            if($data['personas']!=null && $data['personas']!=[]){
                $aux = $this->revisaYInvolucrado($this->GetY());
                $this->SetY($aux);
                $personas = $data['personas'];
                $this->SetTextColor(51, 51, 51);
                $this->SetFillColor(156,156,156); 
                $this->Cell(190, 4, utf8_decode('INVOLUCRADOS'),0,0,'C',true);
                $this->ln(7);
                $i=1;
                foreach ($personas as $persona){
                    $aux = $this->revisaYInvolucrado($this->GetY());
                    $this->SetY($aux);

                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(60, 4, utf8_decode('INVOLUCRADO '.$i),0,0,'C',true);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(13, 4, utf8_decode('SEXO:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(50, 4, utf8_decode($persona->Sexo));

                    if($persona->Capturo!="SD"){// aqui hay que hacerlo negritas y a final de la cinta
                        $this->SetFont('Avenir','',6);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(30, 1, utf8_decode('CAPTURO '.$persona->Capturo.' Y LA ULTIMA ACTUALIZACION '.$persona->Ultima_Actualizacion));
                        $this->SetFont('Avenir','',11);
                    }


                
                    $this->Ln(7);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(35, 4, utf8_decode('RANGO DE EDAD:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(30, 4, utf8_decode($persona->Rango_Edad));

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(28, 4, utf8_decode('COMPLEXION:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(30, 4, utf8_decode($persona->Complexion));
                
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(20, 4, utf8_decode('ESTATUS:'));
                    if($persona->Estado_Res=='NO CORROBORADO'){
                        $this->SetTextColor(0,0,200);
                        $this->Cell(55, 4, utf8_decode($persona->Estado_Res));
                    }else if($persona->Estado_Res=='CORROBORADO'){
                        $this->SetTextColor(255,0,0);
                        $this->Cell(55, 4, utf8_decode($persona->Estado_Res));
                    }else{
                        $this->SetTextColor(0,200,0);
                        $this->Cell(55, 4, utf8_decode($persona->Estado_Res));
                    }

        
                    $this->Ln(7);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(30, 4, utf8_decode('TIPO DE ARMA:'));
                    $this->SetTextColor(128, 128, 128);
                    $this->Cell(40, 4, utf8_decode($persona->Tipo_arma));

                    $this->Ln(7);
                    $this->Cell(5, 4);
                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(30, 4, utf8_decode('DESCRIPCION:'));

                    $this->Ln(5);
                    $this->Cell(5, 4);
                    $this->SetTextColor(128, 128, 128);
                    $auxDescripcion=str_replace("\n", " ", $persona->Descripcion_Responsable);
                    $this->MultiCell(185, 5, utf8_decode($auxDescripcion), 0, 'J');
                    $this->Ln(3);
                if($persona->Path_Imagen!=null&&$persona->Path_Imagen!=''){
                        $nombreFotoV1=explode("?",$persona->Path_Imagen);
                        $filename = base_url."public/files/GestorCasos/" . $Folio_infra . "/Evento/";
                        $image1=$filename.$nombreFotoV1[0];
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(30, 4, utf8_decode('FOTO:'));
                        $this->Ln(5);
                        $type = exif_imagetype($image1);
                        $extension = '';
                        $width = $height = $x = $y = 0;
                        $widthImg = getimagesize($image1)[0];
                        $heightImg = getimagesize($image1)[1];
        
                        $width = 40;
                        $height = 55;
                        $y=$this->GetY();
                        switch($type){
                            case 1:
                                $extension = 'gif';
                            break;
                            case 2:
                                $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                $image = imagecreatefromjpeg($image1);
                                imageinterlace($image, false);
                                $nombre="Pertemporal".rand().".jpeg";
                                imagejpeg($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,85,$y, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                            case 3:
                                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                $image = imagecreatefrompng($image1);
                                imageinterlace($image, false);
                                $nombre="Pertemporal".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,85,$y, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                                
                            break;
                        }
                        $this->Ln(55);
                    }else{
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(30, 4, utf8_decode('FOTO: SD'));
                        $this->Ln(2);
        
                    }
                    $this->Cell(195, 1, utf8_decode('__________________________________________________________________________________________________'));
                    $this->Ln(7);
                    $i++;
                }
            }

            if($data['fotos']!=null && $data['fotos']!=[]){
                //print_r($data['usuarios']);
                $fotos = $data['fotos'];
                $usuarios = $data['usuarios'];
                $fotosIO = [];
                $j=0;
                
                foreach($usuarios as $usuario){
                    $auxCount =count($fotos);
                    for($i=0; $i< $auxCount; $i++){
                        if(trim($fotos[$i]->Capturo) == trim($usuario->User_Name)){
                            $fotosIO[$j] = $fotos[$i];
                            unset($fotos[$i]);
                            $j++;
                        }
                    } 
                    $fotos = array_values($fotos);   
                }

                if($fotosIO!=[]){
                    $aux = $this->revisaYInvolucrado($this->GetY());
                    $this->SetY($aux);
                    
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156); 
                    $this->Cell(190, 4, utf8_decode('IMAGENES BARRIDO DE CAMARAS INTELIGENCIA OPERATIVA'),0,0,'C',true);
                    $this->Ln(7);
                    foreach($fotosIO as $foto){
                        $aux = $this->revisaYInvolucrado($this->GetY());
                        $this->SetY($aux);

                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(70, 4, utf8_decode('UBICACION: '.$foto->id_ubicacion));
                        $this->Cell(60, 4, utf8_decode('CAMARA: '.$foto->id_camara));
                        if($foto->Capturo!="SD"){
                            $this->SetFont('Avenir','',6);
                            $this->SetTextColor(51, 51, 51);
                            $this->Cell(30, 1, utf8_decode('CAPTURO '.$foto->Capturo.' Y LA ULTIMA ACTUALIZACION '.$foto->Ultima_Actualizacion));
                            $this->SetFont('Avenir','',11);
                        }
                        $this->Ln(5);
                        $ubicacion="";
                        $cadena = str_replace('CALLE ','',$foto->CalleF);
                        $ubicacion='CALLE: '.$cadena;

                        $cadena = str_replace('CALLE ','',$foto->Calle2F);
                        $ubicacion .= ($foto->Calle2F!='SD'&& $foto->Calle2F!='') ? ', CALLE 2: '.$cadena: '';
                        
                        $ubicacion .=', COLONIA: '.$foto->ColoniaF;
                        $ubicacion .= ($foto->no_ExtF!='SD' && $foto->no_ExtF!="" && $foto->no_ExtF!="null" && $foto->no_ExtF!="undefined") ? ', NOEXT: '.$foto->no_ExtF: '';

                        $this->Cell(5, 4);
                        $this->SetTextColor(128, 128, 128);
                        $this->MultiCell(160, 4, utf8_decode($ubicacion), 0, 'J');
                        
                        $this->Ln(2);
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(33, 4, utf8_decode('COORDENADA Y:'));
                        $this->SetTextColor(128, 128, 128);
                        $this->Cell(60, 4, utf8_decode($foto->cordYF));
                    
            
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(33, 4, utf8_decode('COORDENADA X:'));
                        $this->SetTextColor(128, 128, 128);
                        $this->Cell(60, 4, utf8_decode($foto->cordXF));
            
            
            
                        $this->Ln(7);
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(50, 4, utf8_decode('FECHA Y HORA DE FOTO:'));
                        $fechalimpia=$this->formatearFecha($foto->fecha_captura_foto.' '.$foto->hora_captura_foto);
                        $this->SetTextColor(128, 128, 128);
                        $this->Cell(20, 4, utf8_decode($fechalimpia));
            
                        $this->Ln(7);
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(40, 4, utf8_decode('DESCRIPCION DE FOTO:'));
                        $this->Ln(5);
                        $this->Cell(5, 4);
                        $this->SetTextColor(128, 128, 128);
                        $auxfoto=str_replace("\n"," ",$foto->Descripcion);
                        $this->MultiCell(180, 4, utf8_decode($auxfoto), 0, 'J');
                        $this->Ln(2);
                        if($foto->Path_Imagen!=null&&$foto->Path_Imagen!=''){
                            $nombreFotoV1=explode("?",$foto->Path_Imagen);
                            $filename = base_url."public/files/GestorCasos/" . $Folio_infra . "/Seguimiento/";
                            $image1=$filename.$nombreFotoV1[0];
                            $this->Cell(5, 4);
                            $this->SetTextColor(51, 51, 51);
                            $this->Cell(30, 4, utf8_decode('FOTO:'));
                            $this->Ln(5);
                            $type = exif_imagetype($image1);
                            $extension = '';
                            $width = $height = $x = $y = 0;
                            $widthImg = getimagesize($image1)[0];
                            $heightImg = getimagesize($image1)[1];
            
                            $width = 100;
                            $height = 50;
                            $y=$this->GetY();
                            
                            switch($type){
                                case 1:
                                    $extension = 'gif';
                                break;
                                case 2:
                                    $extension = 'jpeg';
                                    $image = imagecreatefromjpeg($image1);
                                    imageinterlace($image, false);
                                    $nombre="temporal".rand().".jpeg";//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                    imagejpeg($image,$nombre);
                                    $imagennueva=base_url."public/".$nombre;
                                    $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                    imagedestroy($image);
                                    unlink($nombre);
                                break;
                                case 3:
                                    $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                    $image = imagecreatefrompng($image1);
                                    imageinterlace($image, false);
                                    $nombre="temporal".rand().".png";
                                    imagepng($image,$nombre);
                                    $imagennueva=base_url."public/".$nombre;
                                    $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                    imagedestroy($image);
                                    unlink($nombre);
                                    
                                break;
                            }    
                        }
                        $this->Ln(50);
                        $this->Cell(195, 1, utf8_decode('__________________________________________________________________________________________________'));
                        $this->Ln(7);
                    }
                }
                //$fotos = array_values($fotos); 
                if($fotos!=[]){
                    $aux = $this->revisaYInvolucrado($this->GetY());
                    $this->SetY($aux);
                    
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156); 
                    $this->Cell(190, 4, utf8_decode('IMAGENES ANALISIS DE VIDEOS'),0,0,'C',true);
                    $this->Ln(7);
                    foreach($fotos as $foto){
                        $aux = $this->revisaYInvolucrado($this->GetY());
                        $this->SetY($aux);

                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(70, 4, utf8_decode('UBICACION: '.$foto->id_ubicacion));
                        $this->Cell(60, 4, utf8_decode('CAMARA: '.$foto->id_camara));
                        if($foto->Capturo!="SD"){
                            $this->SetFont('Avenir','',6);
                            $this->SetTextColor(51, 51, 51);
                            $this->Cell(30, 1, utf8_decode('CAPTURO '.$foto->Capturo.' Y LA ULTIMA ACTUALIZACION '.$foto->Ultima_Actualizacion));
                            $this->SetFont('Avenir','',11);
                        }
                        $this->Ln(5);
                        $ubicacion="";
                        $cadena = str_replace('CALLE ','',$foto->CalleF);
                        $ubicacion='CALLE: '.$cadena;

                        $cadena = str_replace('CALLE ','',$foto->Calle2F);
                        $ubicacion .= ($foto->Calle2F!='SD'&& $foto->Calle2F!='') ? ', CALLE 2: '.$cadena: '';
                        
                        //$ubicacion = ($foto->CalleF!='SD'&&$foto->CalleF!="") ? 'CALLE: '.$foto->CalleF: '';
                        //$ubicacion .= ($foto->Calle2F!='SD'&&$foto->Calle2F!="") ? ', CALLE 2: '.$foto->Calle2F: '';
                        $ubicacion .=', COLONIA: '.$foto->ColoniaF;
                        $ubicacion .= ($foto->no_ExtF!='SD' && $foto->no_ExtF!="" && $foto->no_ExtF!="null" && $foto->no_ExtF!="undefined") ? ', NOEXT: '.$foto->no_ExtF: '';

                        $this->Cell(5, 4);
                        $this->SetTextColor(128, 128, 128);
                        $this->MultiCell(160, 4, utf8_decode($ubicacion), 0, 'J');
                        
                        $this->Ln(2);
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(33, 4, utf8_decode('COORDENADA Y:'));
                        $this->SetTextColor(128, 128, 128);
                        $this->Cell(60, 4, utf8_decode($foto->cordYF));
                    
            
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(33, 4, utf8_decode('COORDENADA X:'));
                        $this->SetTextColor(128, 128, 128);
                        $this->Cell(60, 4, utf8_decode($foto->cordXF));
            
            
            
                        $this->Ln(7);
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(50, 4, utf8_decode('FECHA Y HORA DE FOTO:'));
                        $fechalimpia=$this->formatearFecha($foto->fecha_captura_foto.' '.$foto->hora_captura_foto);
                        $this->SetTextColor(128, 128, 128);
                        $this->Cell(20, 4, utf8_decode($fechalimpia));
            
                        $this->Ln(7);
                        $this->Cell(5, 4);
                        $this->SetTextColor(51, 51, 51);
                        $this->Cell(40, 4, utf8_decode('DESCRIPCION DE FOTO:'));
                        $this->Ln(5);
                        $this->Cell(5, 4);
                        $this->SetTextColor(128, 128, 128);
                        $auxfoto=str_replace("\n"," ",$foto->Descripcion);
                        $this->MultiCell(180, 4, utf8_decode($auxfoto), 0, 'J');
                        $this->Ln(2);
                        if($foto->Path_Imagen!=null&&$foto->Path_Imagen!=''){
                            $nombreFotoV1=explode("?",$foto->Path_Imagen);
                            $filename = base_url."public/files/GestorCasos/" . $Folio_infra . "/Seguimiento/";
                            $image1=$filename.$nombreFotoV1[0];
                            $this->Cell(5, 4);
                            $this->SetTextColor(51, 51, 51);
                            $this->Cell(30, 4, utf8_decode('FOTO:'));
                            $this->Ln(5);
                            $type = exif_imagetype($image1);
                            $extension = '';
                            $width = $height = $x = $y = 0;
                            $widthImg = getimagesize($image1)[0];
                            $heightImg = getimagesize($image1)[1];
            
                            $width = 100;
                            $height = 50;
                            $y=$this->GetY();
                            
                            switch($type){
                                case 1:
                                    $extension = 'gif';
                                break;
                                case 2:
                                    $extension = 'jpeg';
                                    $image = imagecreatefromjpeg($image1);
                                    imageinterlace($image, false);
                                    $nombre="temporal".rand().".jpeg";//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                    imagejpeg($image,$nombre);
                                    $imagennueva=base_url."public/".$nombre;
                                    $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                    imagedestroy($image);
                                    unlink($nombre);
                                break;
                                case 3:
                                    $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                    $image = imagecreatefrompng($image1);
                                    imageinterlace($image, false);
                                    $nombre="temporal".rand().".png";
                                    imagepng($image,$nombre);
                                    $imagennueva=base_url."public/".$nombre;
                                    $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                    imagedestroy($image);
                                    unlink($nombre);
                                    
                                break;
                            }    
                        }
                        $this->Ln(50);
                        $this->Cell(195, 1, utf8_decode('__________________________________________________________________________________________________'));
                        $this->Ln(7);
                    }
                }
            }
            if($data['tareas']!=null && $data['tareas']!=[]){
                $aux = $this->revisaYEvento($this->GetY());
                $this->SetY($aux);
                
                foreach ($data['tareas'] as $element) {
                    if (!empty($element['Principales'])) {
                        $actualizaciones = $element['Principales'];
                        foreach ($actualizaciones as $dato) {
                            //print_r($dato);
                            $this->SetTextColor(51, 51, 51);
                            $this->SetFillColor(156,156,156); 
                            $this->Cell(190, 4, utf8_decode('REPORTE ZEN ('.$element['Tipo'].')'),0,0,'C',true);
                            $this->Ln(7);
                            switch($element['Tipo']){
                                case 'BARRIDO':
                                        $this->Cell(5, 4);
                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(33, 4, utf8_decode('COORDENADA Y:'));
                                        $this->SetTextColor(128, 128, 128);
                                        $this->Cell(80, 4, utf8_decode($dato->coordenada_y));
                                    
                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(33, 4, utf8_decode('COORDENADA X:'));
                                        $this->SetTextColor(128, 128, 128);
                                        $this->Cell(60, 4, utf8_decode($dato->coordenada_x));
                                        $this->Ln(7);

                                        $this->Cell(5, 4);
                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(51, 4, utf8_decode('DESCRIPCION BARRIDO :'));
                                        $this->Ln(5);
                                        
                                        $this->Cell(5, 4);
                                        $this->SetTextColor(128, 128, 128);
                                        $this->MultiCell(180, 4, utf8_decode(strtoupper($dato->descripcion)));
                                        $this->Ln(3);

                                        $this->Cell(5, 4);
                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(45, 4, utf8_decode('NUMERO DE CAMARAS:'));
                                        $this->SetTextColor(128, 128, 128);
                                        $this->Cell(60, 4, utf8_decode($dato->camaras));
                                        $this->Ln(7);
                                        if($dato->img){
                                            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                            
                                            if (strpos($url, '172.18.110.25') == true) {
                                                $publicUrl = 'http://172.18.110.90:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                            } else{
                                                $publicUrl = 'http://187.216.250.252:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                            }
    
                                           if( $publicUrl !=''){
                                                $image_data = file_get_contents($publicUrl);
                                                if ($image_data === false) {
                                                    $this->Cell(5, 4);
                                                    $this->SetTextColor(51, 51, 51);
                                                    $this->Cell(45, 4, utf8_decode('NO HAY FOTO:'.$publicUrl));
                                                    $this->Ln(7);
                                                    
                                                }else{
    
    
                                                    $aux = $this->revisaYZen($this->GetY());
                                                    $this->SetY($aux);
                                                    $temp_image = 'temp_image.png';
                                                    file_put_contents($temp_image, $image_data);
                                                    $type = exif_imagetype($temp_image);
                                                    $width = 100;
                                                    $height = 50;
                                                    $y=$this->GetY();
                                                    
                                                    switch($type){
                                                        case 1:
                                                            $extension = 'gif';
                                                        break;
                                                        case 2:
                                                            $extension = 'jpeg';
                                                            $image = imagecreatefromjpeg($temp_image);
                                                            imageinterlace($image, false);
                                                            $nombre="temporal".rand().".jpeg";//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                                            imagejpeg($image,$nombre);
                                                            $imagennueva=base_url."public/".$nombre;
                                                            $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                            imagedestroy($image);
                                                            unlink($nombre);
                                                            unlink($temp_image);
                                                        break;
                                                        case 3:
                                                            $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                                            $image = imagecreatefrompng($temp_image);
                                                            imageinterlace($image, false);
                                                            $nombre="temporal".rand().".png";
                                                            imagepng($image,$nombre);
                                                            $imagennueva=base_url."public/".$nombre;
                                                            $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                            imagedestroy($image);
                                                            unlink($nombre);
                                                            unlink($temp_image);
                                                            
                                                        break;
                                                    } 
                                                    $this->Ln(53); 
                                                }
                                           }   
                                        }
                                break;
                                case 'BUSQUEDA':
                                    $this->Cell(5, 4);
                                    $this->SetTextColor(51, 51, 51);
                                    $this->Cell(51, 4, utf8_decode('DESCRIPCION BUSQUEDA :'));
                                    $this->Ln(5);
                                    
                                    $this->Cell(5, 4);
                                    $this->SetTextColor(128, 128, 128);
                                    $this->MultiCell(180, 4, utf8_decode(strtoupper($dato->descripcion)));
                                    $this->Ln(3);

                                    if($dato->img){
                                        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        
                                        if (strpos($url, '172.18.110.25') == true) {
                                            $publicUrl = 'http://172.18.110.90:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                        } else{
                                            $publicUrl = 'http://187.216.250.252:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                        }

                                       if( $publicUrl !=''){
                                            $image_data = file_get_contents($publicUrl);
                                            if ($image_data === false) {
                                                $this->Cell(5, 4);
                                                $this->SetTextColor(51, 51, 51);
                                                $this->Cell(45, 4, utf8_decode('NO HAY FOTO:'.$publicUrl));
                                                $this->Ln(7);
                                                
                                            }else{


                                                $aux = $this->revisaYZen($this->GetY());
                                                $this->SetY($aux);
                                                $temp_image = 'temp_image.png';
                                                file_put_contents($temp_image, $image_data);
                                                $type = exif_imagetype($temp_image);
                                                $width = 100;
                                                $height = 50;
                                                $y=$this->GetY();
                                                
                                                switch($type){
                                                    case 1:
                                                        $extension = 'gif';
                                                    break;
                                                    case 2:
                                                        $extension = 'jpeg';
                                                        $image = imagecreatefromjpeg($temp_image);
                                                        imageinterlace($image, false);
                                                        $nombre="temporal".rand().".jpeg";//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                                        imagejpeg($image,$nombre);
                                                        $imagennueva=base_url."public/".$nombre;
                                                        $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                        imagedestroy($image);
                                                        unlink($nombre);
                                                        unlink($temp_image);
                                                    break;
                                                    case 3:
                                                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                                        $image = imagecreatefrompng($temp_image);
                                                        imageinterlace($image, false);
                                                        $nombre="temporal".rand().".png";
                                                        imagepng($image,$nombre);
                                                        $imagennueva=base_url."public/".$nombre;
                                                        $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                        imagedestroy($image);
                                                        unlink($nombre);
                                                        unlink($temp_image);
                                                        
                                                    break;
                                                } 
                                                $this->Ln(53); 
                                            }
                                       }   
                                    }
                                    
                                break;
                                case 'ENTREVISTA':
                                        $this->Cell(5, 4);
                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(20, 4, utf8_decode('NOMBRE :'));
                                        $this->SetTextColor(128, 128, 128);
                                        $this->Cell(90, 4, utf8_decode(strtoupper($dato->nombre_entrevistado)));

                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(24, 4, utf8_decode('TELEFONO :'));
                                        $this->SetTextColor(128, 128, 128);
                                        $this->Cell(60, 4, utf8_decode($dato->telefono_entrevistado));
                                        $this->Ln(7);

                                        $this->Cell(5, 4);
                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(51, 4, utf8_decode('RELACION EN EL EVENTO :'));
                                        $this->SetTextColor(128, 128, 128);
                                        $this->Cell(90, 4, utf8_decode(strtoupper($dato->tipo_entrevistado)));
                                        $this->Ln(7);

                                        $this->Cell(5, 4);
                                        $this->SetTextColor(51, 51, 51);
                                        $this->Cell(51, 4, utf8_decode('ENTREVISTA :'));
                                        $this->Ln(5);
                                        
                                        $this->Cell(5, 4);
                                        $this->SetTextColor(128, 128, 128);
                                        $this->MultiCell(185, 4, utf8_decode(strtoupper($dato->entrevista)));
                                        $this->Ln(3);

                                break;
                                case 'OTRA':
                                    $this->Cell(5, 4);
                                    $this->SetTextColor(51, 51, 51);
                                    $this->Cell(51, 4, utf8_decode('DESCRIPCION OTRA TAREA :'));
                                    $this->Ln(5);
                                    
                                    $this->Cell(5, 4);
                                    $this->SetTextColor(128, 128, 128);
                                    $this->MultiCell(180, 4, utf8_decode(strtoupper($dato->descripcion)));
                                    $this->Ln(3);

                                    if($dato->img){
                                        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        
                                        if (strpos($url, '172.18.110.25') == true) {
                                            $publicUrl = 'http://172.18.110.90:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                        } else{
                                            $publicUrl = 'http://187.216.250.252:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                        }

                                       if( $publicUrl !=''){
                                            $image_data = file_get_contents($publicUrl);
                                            if ($image_data === false) {
                                                $this->Cell(5, 4);
                                                $this->SetTextColor(51, 51, 51);
                                                $this->Cell(45, 4, utf8_decode('NO HAY FOTO:'.$publicUrl));
                                                $this->Ln(7);
                                                
                                            }else{


                                                $aux = $this->revisaYZen($this->GetY());
                                                $this->SetY($aux);
                                                $temp_image = 'temp_image.png';
                                                file_put_contents($temp_image, $image_data);
                                                $type = exif_imagetype($temp_image);
                                                $width = 100;
                                                $height = 50;
                                                $y=$this->GetY();
                                                
                                                switch($type){
                                                    case 1:
                                                        $extension = 'gif';
                                                    break;
                                                    case 2:
                                                        $extension = 'jpeg';
                                                        $image = imagecreatefromjpeg($temp_image);
                                                        imageinterlace($image, false);
                                                        $nombre="temporal".rand().".jpeg";//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                                        imagejpeg($image,$nombre);
                                                        $imagennueva=base_url."public/".$nombre;
                                                        $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                        imagedestroy($image);
                                                        unlink($nombre);
                                                        unlink($temp_image);
                                                    break;
                                                    case 3:
                                                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                                        $image = imagecreatefrompng($temp_image);
                                                        imageinterlace($image, false);
                                                        $nombre="temporal".rand().".png";
                                                        imagepng($image,$nombre);
                                                        $imagennueva=base_url."public/".$nombre;
                                                        $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                        imagedestroy($image);
                                                        unlink($nombre);
                                                        unlink($temp_image);
                                                        
                                                    break;
                                                } 
                                                $this->Ln(53); 
                                            }
                                       }   
                                    }
                                break;
                                case 'VIGILANCIA':
                                    $this->Cell(5, 4);
                                    $this->SetTextColor(51, 51, 51);
                                    $this->Cell(51, 4, utf8_decode('DESCRIPCION VIGILANCIA :'));
                                    $this->Ln(5);
                                    
                                    $this->Cell(5, 4);
                                    $this->SetTextColor(128, 128, 128);
                                    $this->MultiCell(180, 4, utf8_decode(strtoupper($dato->descripcion)));
                                    $this->Ln(3);

                                    if($dato->img){
                                        $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
                                        
                                        if (strpos($url, '172.18.110.25') == true) {
                                            $publicUrl = 'http://172.18.110.90:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                        } else{
                                            $publicUrl = 'http://187.216.250.252:9090/api/images/'.strtolower($element['Tipo'])."/".$dato->img;
                                        }

                                       if( $publicUrl !=''){
                                            $image_data = file_get_contents($publicUrl);
                                            if ($image_data === false) {
                                                $this->Cell(5, 4);
                                                $this->SetTextColor(51, 51, 51);
                                                $this->Cell(45, 4, utf8_decode('NO HAY FOTO:'.$publicUrl));
                                                $this->Ln(7);
                                                
                                            }else{


                                                $aux = $this->revisaYZen($this->GetY());
                                                $this->SetY($aux);
                                                $temp_image = 'temp_image.png';
                                                file_put_contents($temp_image, $image_data);
                                                $type = exif_imagetype($temp_image);
                                                $width = 100;
                                                $height = 50;
                                                $y=$this->GetY();
                                                
                                                switch($type){
                                                    case 1:
                                                        $extension = 'gif';
                                                    break;
                                                    case 2:
                                                        $extension = 'jpeg';
                                                        $image = imagecreatefromjpeg($temp_image);
                                                        imageinterlace($image, false);
                                                        $nombre="temporal".rand().".jpeg";//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                                        imagejpeg($image,$nombre);
                                                        $imagennueva=base_url."public/".$nombre;
                                                        $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                        imagedestroy($image);
                                                        unlink($nombre);
                                                        unlink($temp_image);
                                                    break;
                                                    case 3:
                                                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                                        $image = imagecreatefrompng($temp_image);
                                                        imageinterlace($image, false);
                                                        $nombre="temporal".rand().".png";
                                                        imagepng($image,$nombre);
                                                        $imagennueva=base_url."public/".$nombre;
                                                        $this->Image($imagennueva,60,$y, $width, $height, $extension);
                                                        imagedestroy($image);
                                                        unlink($nombre);
                                                        unlink($temp_image);
                                                        
                                                    break;
                                                } 
                                                $this->Ln(53); 
                                            }
                                       }   
                                    }
                                break;
                            }
                        }
                    }
                }
            }
        }
        function formatearFecha($fecha = "1997-01-04 13:30:00"){//funcion para dar formato la fecha y hora 
            setlocale(LC_TIME, 'es_CO.UTF-8');
            $DIA_INGLES=strftime("%A", strtotime($fecha));
            switch ($DIA_INGLES) {
                case 'Monday':
                    $day_of_the_week = 'Lunes';
                    break;
                case 'Tuesday':
                    $day_of_the_week = 'Martes';
                    break;
                case 'Wednesday':
                    $day_of_the_week = 'Miercoles';
                    break;
                case 'Thursday':
                    $day_of_the_week = 'Jueves';
                    break;
                case 'Friday':
                    $day_of_the_week = 'Viernes';
                    break;
                case 'Saturday':
                    $day_of_the_week = 'Sabado';
                    break;
                case 'Sunday':
                    $day_of_the_week = 'Domingo';
                    break;
            }
            $MESNUM=strftime("%m", strtotime($fecha));
            $mes="";
            switch ($MESNUM) {
                case '1':
                    $mes="Enero";
                    break;
                case '2':
                    $mes="Febrero";
                    break;
                case '3':
                    $mes="Marzo";
                    break;
                case '4':
                    $mes="Abril";
                    break;
                case '5':
                    $mes="Mayo";
                    break;
                case '6':
                    $mes="Junio";
                    break;
                case '7':
                    $mes="Julio";
                    break;
                case '8':
                    $mes="Agosto";
                    break;
                case '9':
                    $mes="Septiembre";
                    break;
                case '10':
                    $mes="Octubre";
                    break;			
                case '11':
                    $mes="Noviembre";
                    break;
                case '12':
                    $mes="Diciembre";
                    break;
            }
            $results =$day_of_the_week.strftime(", %d  de ", strtotime($fecha)).$mes. strftime(" del %G", strtotime($fecha))." a las ".date('G:i ', strtotime($fecha));;
            return strtoupper($results);
        }

        function revisaY($y){
            if($y<275&&$y>230){
                $this->AddPage();
                return 35;
            }else{
                return $y;
            }
        }
        function revisaYRed($y){
            if($y<275&&$y>220){
                $this->AddPage();
                return 35;
            }else{
                return $y;
            }
        }
        function revisaYAntecedente($y){
            if($y<275&&$y>265){
                $this->AddPage();
                return 35;
            }else{
                return $y;
            }
        }
        function revisaYDomicilio($y){
            if($y<275&&$y>245){
                $this->AddPage();
                return 35;
            }else{
                return $y;
            }
        }
        function revisaYZen($y){
            if($y>230){///si se encuentra en el rango
                $this->AddPage();
                return 32;
            }else{
                return $y;
            }
        }
        function revisaYInvolucrado($y){
            if($y>205){///si se encuentra en el rango
                $this->AddPage();
                return 32;
            }else{
                return $y;
            }
        }
        function revisaYVehI($y){
            if($y>215){///si se encuentra en el rango
                $this->AddPage();
                return 32;
            }else{
                return $y;
            }
        }
        function revisaYEvento($y){
            if($y<275&&$y>250){
                $this->AddPage();
                return 35;
            }else{
                return $y;
            }
        }
        function fechaMes($cad){
            $MESNUM=0;
            $mesaux[0]='';
            $dia='';
            $año='';
            if(strpos($cad, '-')){
                $mesaux=explode('-',$cad);
                $MESNUM=$mesaux[1];
                $año=$mesaux[0];
            }else if(strpos($cad, '/')){
                $mesaux=explode('/',$cad);
                $cadMes= ltrim($mesaux[1],'0');
                $MESNUM=(int)$cadMes;
                $dia=$mesaux[0].' ';
                $año=$mesaux[2];
            }
            $mes="";
            switch ($MESNUM) {
                case '1':
                    $mes="Enero";
                    break;
                case '2':
                    $mes="Febrero";
                    break;
                case '3':
                    $mes="Marzo";
                    break;
                case '4':
                    $mes="Abril";
                    break;
                case '5':
                    $mes="Mayo";
                    break;
                case '6':
                    $mes="Junio";
                    break;
                case '7':
                    $mes="Julio";
                    break;
                case '8':
                    $mes="Agosto";
                    break;
                case '9':
                    $mes="Septiembre";
                    break;
                case '10':
                    $mes="Octubre";
                    break;			
                case '11':
                    $mes="Noviembre";
                    break;
                case '12':
                    $mes="Diciembre";
                    break;
                default :
                    $mes=$MESNUM;
                    break;
            }
            return strtoupper ('('.$dia.$mes.' del '.$año.')');
        }
        function revisaYUbicacion($y){
            if($y<275&&$y>190){
                $this->AddPage();
                return 32;
            }else{
                return $y;
            }
        }
        function PagePersonaEntrevistada($data,$i){
            $this->SetFont('helvetica','',11);
            $this->SetTextColor(255, 255, 255);
            $this->SetFillColor(41,41,95); 
            $this->Cell(190, 5, utf8_decode('PERSONA ENTREVISTADA RELACIONADA '.$i), 0, 0, 'C',true);
            $this->ln(10);
            $this->Cell(155, 4, '');
            $this->SetFont('helvetica','B',6);
            $this->Cell(30, 4, utf8_decode('CAPTURO ('.$data['Principales']->Capturo.')'));
            $this->SetFont('Avenir','',11);
            $this->ln(4);
            $width = 73;
            $height = 115;
            $y=$this->GetY()+21;
            if($data['Principales']->Foto!=null&&$data['Principales']->Foto!='SD'&&$data['Principales']->Foto!=''){
                $filename = base_url."public/files/Entrevistas/" . $data['Principales']->Id_Persona_Entrevista . "/".$data['Principales']->Foto;
                $type = exif_imagetype($filename);
                $extension = '';
                switch($type){
                    case 1:
                        $extension = 'gif';
                    break;
                    case 2:
                        $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                        $image = imagecreatefromjpeg($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".jpeg";
                        imagejpeg($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                    case 3:
                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                        $image = imagecreatefrompng($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".png";
                        imagepng($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                } 
            }else{
                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                $imagennueva=base_url."public/media/images/frentesilueta.png";
                $this->Image($imagennueva,10, $y, $width, $height, $extension);
            }
            $width=$width+2;
            $this->Cell(30, 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('FECHA:'), 0,0);
            $this->SetFont('helvetica','',11);
            $this->SetFillColor(236,236,236);
            $SoloFecha=explode(' ',$data['Principales']->FechaHora_Creacion);
            $arrayFecha=explode('-',$SoloFecha[0]);
            $arrayFecha=array_reverse($arrayFecha);
            $FechaHora_Creacion=implode("/", $arrayFecha); 
            $this->Cell(45,7, utf8_decode($FechaHora_Creacion),0,0,'C',true);
            $this->Cell(20, 7);
            $this->SetFont('helvetica','B',11);
            $this->Cell(30, 7, utf8_decode('NO.REMISIÓN:'), 0,0);
            $arrayAux = explode(',', $data['Principales']->Remisiones);
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236); 
            $this->Cell(45,7, utf8_decode($arrayAux[0]),0,0,'C',true);
    
            $this->Ln(9);
            $this->Cell(30, 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica','B',11);
            $this->Cell(35, 7, utf8_decode('REMITIDO POR:'), 0,0);
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236); 
            $this->Cell(125,7, utf8_decode($data['Principales']->Detenido_por),0,0,'J',true);
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell($width, 7);
            $this->Cell(20, 7, utf8_decode('NOMBRE:'));
            $this->SetFont('helvetica','',11);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($data['Principales']->Nombre.' '.$data['Principales']->Ap_Paterno.' '.$data['Principales']->Ap_Materno),0,0,'J',true);
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell($width, 7);
            $this->Cell(20, 7, utf8_decode('CALLE 1:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($data['Principales']->Calle_Domicilio),0,0,'J',true);
    
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('CALLE 2:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($data['Principales']->Calle2_Domicilio),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('NO.ETX:'));
            $this->SetFont('helvetica','',9);
            $this->Cell(30,7, utf8_decode($data['Principales']->No_Exterior_Domicilio),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7,'');
            $this->Cell(20, 7, utf8_decode('NO.INT:'));
            $this->SetFont('helvetica','',9);
            $this->Cell(30,7, utf8_decode($data['Principales']->No_Interior_Domicilio),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(22, 7, utf8_decode('COLONIA:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(93,7, utf8_decode($data['Principales']->Colonia_Domicilio),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7, utf8_decode('EDAD:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(10,7, utf8_decode($data['Principales']->Edad),0,0,'C',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7, utf8_decode('CURP:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(75,7, utf8_decode($data['Principales']->CURP),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(50, 7, utf8_decode('FECHA DE NACIMIENTO: '));
            $arrayFecha=explode('-',$data['Principales']->Fecha_Nacimiento);
            $arrayFecha=array_reverse($arrayFecha);
            $Fecha=implode("/", $arrayFecha);
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(65,7, utf8_decode($Fecha),0,0,'J',true);
            
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7, utf8_decode('ALIAS:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(100,7, utf8_decode($data['Principales']->Alias),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(30, 7, utf8_decode('ASOCIADO A:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->MultiCell(85,7, utf8_decode($data['Principales']->Asociado_A),0,1,'C',true);
            //$this->Cell(85,7, utf8_decode($data['Principales']->Asociado_A),0,0,'J',true);
    
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('BANDA:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($data['Principales']->Banda),0,0,'J',true);
            
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(25, 7, utf8_decode('TELEFONO: '));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(90,7, utf8_decode($data['Principales']->Telefono),0,0,'J',true);
    
            $this->Ln(29);
            $this->SetFont('helvetica','B',11);
            $this->Cell(25, 7, utf8_decode('LUGAR DE DETENCIÓN:'));
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('CALLE 1:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(75,7, utf8_decode($data['Principales']->Calle_Detencion),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('CALLE 2:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(75,7, utf8_decode($data['Principales']->Calle2_Detencion),0,0,'J',true);
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('NO.EXT:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(20,7, utf8_decode($data['Principales']->No_Exterior_Detencion),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('NO.EXT:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(20,7, utf8_decode($data['Principales']->No_Interior_Detencion),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(25, 7, utf8_decode('COLONIA:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(85,7, utf8_decode($data['Principales']->Colonia_Detencion),0,0,'J',true);
            $this->Ln(19);
           /* $this->SetFont('helvetica','B',11);
            $this->Cell(60, 7, utf8_decode('ELEMENTO QUE CAPTURO:'));
            $this->Cell(50, 7, utf8_decode($data['Principales']->Capturo));*/
        }
        function DatosRelevantesPersona($data){
            if($data['Entrevistas']!=[]){
                $entrevistas= $data['Entrevistas'];
                $i=1;
                foreach($entrevistas as $entrevista){
                    if($i!=1||$this->GetY()>=230){
                        $aux=$this->revisaY($this->GetY());
                        $this->SetY($aux);
                    }
                    $this->SetFont('helvetica','',11);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFillColor(156,156,156); 
                    $this->Cell(190, 4, utf8_decode('ENTREVISTA '.$i),0,0,'C',true);
                    $this->Ln(4);
                    $this->SetTextColor(0, 0, 0);
                    $this->Cell(165, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$entrevista->Capturo.')'));
                    $this->Ln(4);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(40, 7, utf8_decode('ENTREVISTADOR:'));
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->Cell(60,7, utf8_decode($entrevista->Indicativo_Entrevistador),0,0,'C',true);
                    $this->Ln(7);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(20, 8, utf8_decode('ENTREVISTA DETENIDO:'));
                    $this->Ln(7);
                    $this->SetFont('helvetica','',9);
                    $this->MultiCell(190,7, utf8_decode('-'.$entrevista->Entrevista),0,1,'C',true);
                    $this->Ln(7);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(40, 8, utf8_decode('ALIAS REFERIDOS:'));
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->Cell(150,7, utf8_decode($entrevista->Alias_Referidos),0,0,'C',true);
                    //$this->Cell(40, 8, utf8_decode($this->GetY()));
                    $this->Ln(8);
                    $i++;
                    $width = 75;
                    $height = 80;
                    $aux=$this->revisaY($this->GetY());
                    $this->SetY($aux);
                    
                    $y=$this->GetY();
                    if($entrevista->Foto!=null&&$entrevista->Foto!='SD'&&$entrevista->Foto!=''){
                        $filenameentrevista = base_url."public/files/Entrevistas/" . $data['Principales']->Id_Persona_Entrevista  . "/FotosEntrevistas/".$entrevista->Foto;
                        $type = exif_imagetype($filenameentrevista);
                        $extension = '';
    
                        switch($type){
                            case 1:
                                $extension = 'gif';
                            break;
                            case 2:
                                $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                $image = imagecreatefromjpeg($filenameentrevista);
                                imageinterlace($image, false);
                                $nombre="entrevista".rand().".jpeg";
                                imagejpeg($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,65, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                            case 3:
                                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                $image = imagecreatefrompng($filenameentrevista);
                                imageinterlace($image, false);
                                $nombre="entrevista".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,65, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                        } 
                        $this->Ln(82);
                    }
                }
            }
            if($data['Ubicaciones']!=[]){
                $i=1;
                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);
                $domicilios=$data['Ubicaciones'];
                foreach ($domicilios as $domicilio){
                    if($i!=1 ||$this->GetY()>=185){
                        $aux=$this->revisaYUbicacion($this->GetY());
                        $this->SetY($aux);
                    }
                    $this->SetFont('helvetica','',11);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode('UBICACIÓN '.$i),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$domicilio->Capturo.')'));
                    $this->SetFont('helvetica','B',11);
                    $this->ln(2);
                    $this->Cell(130, 7, utf8_decode('DIRECCIÓN DE LA UBICACIÓN:'));
                    $this->Ln(7);
                    $domicilioCompleto='CALLE: '.$domicilio->Calle;
                    $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE2: '.$domicilio->Calle2: '';
                    $domicilioCompleto.=', COLONIA: '.$domicilio->Colonia;
                    $domicilioCompleto .= ($domicilio->NumExt!='SD') ? ', NUM.EXT: '.$domicilio->NumExt: '';
                    $domicilioCompleto .= ($domicilio->NumInt!='SD') ? ', NUM.INT: '.$domicilio->NumInt: '';
                    $domicilioCompleto .= ($domicilio->CP!='SD') ? ', CP: '.$domicilio->CP: '';
                    $domicilioCompleto .= ($domicilio->CoordY!='SD') ? ', COORDY: '.$domicilio->CoordY: '';
                    $domicilioCompleto .= ($domicilio->CoordX!='SD') ? ', COORDX: '.$domicilio->CoordX: '';
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(190,7, utf8_decode($domicilioCompleto),0,1,'C',true);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(130, 7, utf8_decode('OBSERVACIÓN DE LA UBICACIÓN: '));
                    $this->Ln(7);
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(190,7, utf8_decode($domicilio->Observaciones_Ubicacion),0,1,'C',true);
                    if($domicilio->Link_Ubicacion!='SD'&& trim($domicilio->Link_Ubicacion)!=''){
                        $this->SetFont('helvetica','B',11);
                        $this->Cell(130, 7, utf8_decode('LINK DE LA UBICACIÓN: '));
                        $this->Ln(7);
                        $this->SetFont('helvetica','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(190,7, utf8_decode($domicilio->Link_Ubicacion),0,1,'C',true);
                    }
                    $i++;
                    $width = 65;
                    $height = 70;
                    $aux=$this->revisaY($this->GetY());
                    $this->SetY($aux);
                    $y=$this->GetY();
                    if($domicilio->Foto!=null&&$domicilio->Foto!='SD'&&$domicilio->Foto!=''){
    
                        $filenameUbicacion = base_url."public/files/Entrevistas/" . $data['Principales']->Id_Persona_Entrevista   . "/UbicacionesRelevantes/".$domicilio->Foto;
                        $type = exif_imagetype($filenameUbicacion);
                        $extension = '';
    
                        switch($type){
                            case 1:
                                $extension = 'gif';
                            break;
                            case 2:
                                $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                $image = imagecreatefromjpeg($filenameUbicacion);
                                imageinterlace($image, false);
                                $nombre="domicilio".rand().".jpeg";
                                imagejpeg($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,65, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                            case 3:
                                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                $image = imagecreatefrompng($filenameUbicacion);
                                imageinterlace($image, false);
                                $nombre="domicilio".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,65, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                        } 
                        $this->Ln(72);
                    }
                }
            }
            if($data['Forensias']!=[]){
                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);
                $i=1;
                $forensias=$data['Forensias'];
                foreach ($forensias as $forensia){
                    if($i!=1||$this->GetY()>=230){
                        $aux=$this->revisaY($this->GetY());
                        $this->SetY($aux);
                    }
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont('helvetica','',11);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode('DATO ENTREVISTA'.$i),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$forensia->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->ln(2);
                    $i++;
                    $width = 75;
                    $height = 65;
                    $y=$this->GetY();
                    if($forensia->Foto!=null&&$forensia->Foto!='SD'&&$forensia->Foto!=''){
    
                        $filenameForensia = base_url."public/files/Entrevistas/" . $data['Principales']->Id_Persona_Entrevista  . "/ForensiasRelevantes/".$forensia->Foto;
                        $type = exif_imagetype($filenameForensia);
                        $extension = '';
    
                        switch($type){
                            case 1:
                                $extension = 'gif';
                            break;
                            case 2:
                                $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                $image = imagecreatefromjpeg($filenameForensia);
                                imageinterlace($image, false);
                                $nombre="forencia".rand().".jpeg";
                                imagejpeg($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,130, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                            case 3:
                                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                $image = imagecreatefrompng($filenameForensia);
                                imageinterlace($image, false);
                                $nombre="forencia".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,130, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                        } 
                    }
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(130, 7, utf8_decode('DESCRIPCION DEL DATO: '));
                    $this->Ln(7);
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(115,7, utf8_decode($forensia->Descripcion_Forensia),0,1,'C',true);
                    $this->Ln(3);
                    if($forensia->Foto!=null&&$forensia->Foto!='SD'&&$forensia->Foto!=''){//solo si existe fot realiza esta funcion
                        $y1=$this->GetY();
                        $operacion=$y-$y1;
                        $diferencia=abs($operacion);
                        if($diferencia<65){ 
                            $y2=$y+65-$y1;
                            $this->Ln($y2+5);
                        }
                        
                    }
                }
            }
        }
        function DatosCombinadosPersona($dataEntrevista,$data,$indice){
            $this->SetFont('helvetica','',11);
            $this->SetTextColor(255, 255, 255);
            $this->SetFillColor(41,41,95); 
            $this->Cell(190, 5, utf8_decode('PERSONA IDENTIFICADA '.$indice. ' (CUENTA CON INFORMACION DE ENTREVISTAS)'), 0, 0, 'C',true);
            $this->ln(6);
            $this->Cell(155, 4, '');
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica','B',6);
            $this->Cell(30, 2, utf8_decode('CAPTURO ('.$dataEntrevista['Principales']->Capturo.')'));
            $this->SetFont('Avenir','',11);
            $this->ln(1);
            $width = 73;
            $height = 115;
            $y=$this->GetY()+21;
            if($dataEntrevista['Principales']->Foto!=null&&$dataEntrevista['Principales']->Foto!='SD'&&$dataEntrevista['Principales']->Foto!=''){
                $filename = base_url."public/files/Entrevistas/" . $dataEntrevista['Principales']->Id_Persona_Entrevista . "/".$dataEntrevista['Principales']->Foto;
                $type = exif_imagetype($filename);
                $extension = '';
                switch($type){
                    case 1:
                        $extension = 'gif';
                    break;
                    case 2:
                        $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                        $image = imagecreatefromjpeg($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".jpeg";
                        imagejpeg($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y+5, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                    case 3:
                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                        $image = imagecreatefrompng($filename);
                        imageinterlace($image, false);
                        $nombre="persona".rand().".png";
                        imagepng($image,$nombre);
                        $imagennueva=base_url."public/".$nombre;
                        $this->Image($imagennueva,10, $y+5, $width, $height, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                } 
            }else{
                if($data['datos_persona']->Foto!=null&&$data['datos_persona']->Foto!='SD'&&$data['datos_persona']->Foto!=''){
                    $filename = base_url."public/files/Seguimientos/" . $data['datos_persona']->Id_Seguimiento . "/Personas/".$data['datos_persona']->Foto;
                    $type = exif_imagetype($filename);
                    $extension = '';
                    switch($type){
                        case 1:
                            $extension = 'gif';
                        break;
                        case 2:
                            $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                            $image = imagecreatefromjpeg($filename);
                            imageinterlace($image, false);
                            $nombre="persona".rand().".jpeg";
                            imagejpeg($image,$nombre);
                            $imagennueva=base_url."public/".$nombre;
                            $this->Image($imagennueva,10, $y+5, $width, $height, $extension);
                            imagedestroy($image);
                            unlink($nombre);
                        break;
                        case 3:
                            $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                            $image = imagecreatefrompng($filename);
                            imageinterlace($image, false);
                            $nombre="persona".rand().".png";
                            imagepng($image,$nombre);
                            $imagennueva=base_url."public/".$nombre;
                            $this->Image($imagennueva,10, $y+5, $width, $height, $extension);
                            imagedestroy($image);
                            unlink($nombre);
                        break;
                    } 
                }else{
                    $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                    $imagennueva=base_url."public/media/images/frentesilueta.png";
                    $this->Image($imagennueva,10, $y+5, $width, $height, $extension);
                }
            }
            $width=$width+2;
            
            $this->Cell(30, 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica','B',11);
            $this->Cell(45, 7, utf8_decode('ROL DE LA PERSONA: '));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(40, 7, utf8_decode($data['datos_persona']->Rol),0,0,'C',true);
            $this->Ln(9);

            $this->Cell(30, 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('FECHA:'), 0,0);
            $this->SetFont('helvetica','',11);
            $this->SetFillColor(236,236,236);
            $SoloFecha=explode(' ',$dataEntrevista['Principales']->FechaHora_Creacion);
            $arrayFecha=explode('-',$SoloFecha[0]);
            $arrayFecha=array_reverse($arrayFecha);
            $FechaHora_Creacion=implode("/", $arrayFecha); 
            $this->Cell(45,7, utf8_decode($FechaHora_Creacion),0,0,'C',true);
            $this->Cell(20, 7);
            $this->SetFont('helvetica','B',11);
            $this->Cell(30, 7, utf8_decode('NO.REMISIÓN:'), 0,0);
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236); 
            $this->Cell(45,7, utf8_decode($dataEntrevista['Principales']->Remisiones),0,0,'C',true);
    
            $this->Ln(9);
            $this->Cell(30, 7);
            $this->SetTextColor(0, 0, 0);
            $this->SetFont('helvetica','B',11);
            $this->Cell(35, 7, utf8_decode('REMITIDO POR:'), 0,0);
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236); 
            $this->Cell(125,7, utf8_decode($dataEntrevista['Principales']->Detenido_por),0,0,'J',true);
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell($width, 7);
            $this->Cell(20, 7, utf8_decode('NOMBRE:'));
            $this->SetFont('helvetica','',11);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($dataEntrevista['Principales']->Nombre.' '.$dataEntrevista['Principales']->Ap_Paterno.' '.$dataEntrevista['Principales']->Ap_Materno),0,0,'J',true);
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell($width, 7);
            $this->Cell(20, 7, utf8_decode('CALLE 1:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($dataEntrevista['Principales']->Calle_Domicilio),0,0,'J',true);
    
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('CALLE 2:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($dataEntrevista['Principales']->Calle2_Domicilio),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('NO.ETX:'));
            $this->SetFont('helvetica','',9);
            $this->Cell(30,7, utf8_decode($dataEntrevista['Principales']->No_Exterior_Domicilio),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7,'');
            $this->Cell(20, 7, utf8_decode('NO.INT:'));
            $this->SetFont('helvetica','',9);
            $this->Cell(30,7, utf8_decode($dataEntrevista['Principales']->No_Interior_Domicilio),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(22, 7, utf8_decode('COLONIA:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(93,7, utf8_decode($dataEntrevista['Principales']->Colonia_Domicilio),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7, utf8_decode('EDAD:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(10,7, utf8_decode($dataEntrevista['Principales']->Edad),0,0,'C',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7, utf8_decode('CURP:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(75,7, utf8_decode($dataEntrevista['Principales']->CURP),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(50, 7, utf8_decode('FECHA DE NACIMIENTO: '));
            $arrayFecha=explode('-',$dataEntrevista['Principales']->Fecha_Nacimiento);
            $arrayFecha=array_reverse($arrayFecha);
            $Fecha=implode("/", $arrayFecha);
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(65,7, utf8_decode($Fecha),0,0,'J',true);
            
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(15, 7, utf8_decode('ALIAS:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(100,7, utf8_decode($dataEntrevista['Principales']->Alias),0,0,'J',true);
    
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(30, 7, utf8_decode('ASOCIADO A:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->MultiCell(85,7, utf8_decode($dataEntrevista['Principales']->Asociado_A),0,1,'C',true);
            //$this->Cell(85,7, utf8_decode($dataEntrevista['Principales']->Asociado_A),0,0,'J',true);
    
            $this->Ln(5);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('BANDA:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(95,7, utf8_decode($dataEntrevista['Principales']->Banda),0,0,'J',true);
            
            $this->Ln(9);
            $this->Cell($width, 4);
            $this->SetFont('helvetica','B',11);
            $this->Cell(25, 7, utf8_decode('TELEFONO: '));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(90,7, utf8_decode($dataEntrevista['Principales']->Telefono),0,0,'J',true);
    
            $this->Ln(29);
            $this->SetFont('helvetica','B',11);
            $this->Cell(25, 7, utf8_decode('LUGAR DE DETENCIÓN:'));
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('CALLE 1:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(75,7, utf8_decode($dataEntrevista['Principales']->Calle_Detencion),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('CALLE 2:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(75,7, utf8_decode($dataEntrevista['Principales']->Calle2_Detencion),0,0,'J',true);
    
            $this->Ln(9);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('NO.EXT:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(20,7, utf8_decode($dataEntrevista['Principales']->No_Exterior_Detencion),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(20, 7, utf8_decode('NO.EXT:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(20,7, utf8_decode($dataEntrevista['Principales']->No_Interior_Detencion),0,0,'J',true);
            $this->SetFont('helvetica','B',11);
            $this->Cell(25, 7, utf8_decode('COLONIA:'));
            $this->SetFont('helvetica','',9);
            $this->SetFillColor(236,236,236);
            $this->Cell(85,7, utf8_decode($dataEntrevista['Principales']->Colonia_Detencion),0,0,'J',true);
            $this->Ln(19);
            $this->SetFont('helvetica','B',11);
            $this->Cell(60, 7, utf8_decode('ELEMENTO QUE CAPTURO:'));
            $this->Cell(50, 7, utf8_decode($dataEntrevista['Principales']->Capturo));
            $aux=$this->revisaYAntecedente($this->GetY());
            if(($data['domicilios']!=[])||($data['forencias']!=[])||($data['redes_sociales']!=[])||($data['antecedentes']!=[])){

                $this->AddPage();
                $this->SetFont('helvetica','',11);
                $this->SetTextColor(255, 255, 255);
                $this->SetFillColor(41,41,95); 
                $this->Cell(190, 5, utf8_decode('DATOS DE PERSONA '.$indice.' SEGUIMIENTO '), 0, 0, 'C',true);
                $this->Ln(7);
                $this->SetFont('Avenir','',11);
                if($data['antecedentes']!=[]){
                    $i=1;
                    $antecedentes=$data['antecedentes'];
                    foreach ($antecedentes as $antecedente){
                        $this->ln(4);
                        if($i!=1 ||$this->GetY()>=265){
                            $aux=$this->revisaYAntecedente($this->GetY());
                            $this->SetY($aux);
                        }
                        if($antecedente->Fecha_Antecedente!='SD'){
                            $fechalimpia=$this->fechaMes($antecedente->Fecha_Antecedente);
                        }else{
                            $fechalimpia='';
                        }
                        $this->SetFont('helvetica','',11);
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFillColor(156,156,156); 
                        $this->Cell(190, 4, utf8_decode('ANTECEDENTE '.$i.' '.$fechalimpia),0,0,'C',true);
                        $this->ln(4);
                        $this->Cell(155, 4, '');
                        $this->SetFont('helvetica','B',6);
                        $this->Cell(30, 4, utf8_decode('CAPTURO ('.$antecedente->Capturo.')'));
                        $this->Ln(4);
                        $this->SetFont('Avenir','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(190,7, utf8_decode($antecedente->Descripcion_Antecedente),0,1,'C',true);
                        $i++;
                    }
                    $this->ln(1);
                    //$this->Cell(190, 1, $this->GetY());
                }

                if($data['domicilios']!=[]){
                    $i=1;
                    $domicilios=$data['domicilios'];
                    foreach ($domicilios as $domicilio){
                        if($i!=1 ||$this->GetY()>=265){
                            $aux=$this->revisaYAntecedente($this->GetY());
                            $this->SetY($aux);
                        }
                        $this->SetFont('helvetica','',11);
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFillColor(156,156,156); 
                        $this->Cell(190, 4, utf8_decode('DOMICILIO '.$i.' ('.$domicilio->Estatus.')'),0,0,'C',true);
                        $this->ln(4);
                        $this->Cell(155, 4, '');
                        $this->SetFont('helvetica','B',6);
                        $this->Cell(30, 4, utf8_decode('CAPTURO ('.$domicilio->Capturo.')'));
                        
                        $this->ln(2);
                        $this->SetFont('helvetica','B',11);
                        $this->Cell(130, 7, utf8_decode('UBICACION DEL DOMICILIO: '));
                        $this->Ln(7);
                        $domicilioCompleto='CALLE: '.$domicilio->Calle;
                        $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE2: '.$domicilio->Calle2: '';
                        $domicilioCompleto.=', COLONIA: '.$domicilio->Colonia;
                        $domicilioCompleto .= ($domicilio->NumExt!='SD') ? ', NUM.EXT: '.$domicilio->NumExt: '';
                        $domicilioCompleto .= ($domicilio->NumInt!='SD') ? ', NUM.INT: '.$domicilio->NumInt: '';
                        $domicilioCompleto .= ($domicilio->CP!='SD') ? ', CP: '.$domicilio->CP: '';
                        $domicilioCompleto .= ($domicilio->CoordY!='SD') ? ', COORDY: '.$domicilio->CoordY: '';
                        $domicilioCompleto .= ($domicilio->CoordX!='SD') ? ', COORDX: '.$domicilio->CoordX: '';
                        $this->SetFont('Avenir','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(190,7, utf8_decode($domicilioCompleto),0,1,'C',true);
                        $this->ln(2);
                        $this->SetFont('helvetica','B',11);
                        $this->Cell(130, 7, utf8_decode('OBSERVACION DEL DOMICILIO: '));
                        $this->Ln(7);
                        $this->SetFont('helvetica','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(190,7, utf8_decode($domicilio->Observaciones_Ubicacion),0,1,'C',true);
                        $i++;
                    }
                    $this->ln(1);
                }
                if($data['redes_sociales']!=[]){
                    $aux=$this->revisaY($this->GetY());
                    $this->SetY($aux);
                    $i=1;
                    $redes_sociales=$data['redes_sociales'];
                    foreach ($redes_sociales as $red_social){
                        if($i!=1||$this->GetY()>=230){
                            $aux=$this->revisaY($this->GetY());
                            $this->SetY($aux);
                        }
                        $this->SetFont('helvetica','',11);
                        $this->SetTextColor(0, 0, 0);
                        $this->SetFillColor(156,156,156); 
                        $this->Cell(190, 4, utf8_decode('DATO RED SOCIAL '.$i),0,0,'C',true);
                        $this->ln(4);
                        $this->Cell(155, 4, '');
                        $this->SetFont('helvetica','B',6);
                        $this->Cell(30, 4, utf8_decode('CAPTURO ('.$red_social->Capturo.')'));
                        $this->SetFont('Avenir','',11);
                        $this->ln(4);
                        $i++;
                        $width = 65;
                        $height = 60;
                        $y=$this->GetY();
                        if($red_social->Foto_Nombre!=null&&$red_social->Foto_Nombre!='SD'&&$red_social->Foto_Nombre!=''){

                            $filenameForencia = base_url."public/files/Seguimientos/" . $GLOBALS['PDFId_Seguimiento']  . "/Redes_Sociales/".$red_social->Foto_Nombre;
                            $type = exif_imagetype($filenameForencia);
                            $extension = '';

                            switch($type){
                                case 1:
                                    $extension = 'gif';
                                break;
                                case 2:
                                    $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                    $image = imagecreatefromjpeg($filenameForencia);
                                    imageinterlace($image, false);
                                    $nombre="red_social".rand().".jpeg";
                                    imagejpeg($image,$nombre);
                                    $imagennueva=base_url."public/".$nombre;
                                    $this->Image($imagennueva,135, $y+1, $width, $height, $extension);
                                    imagedestroy($image);
                                    unlink($nombre);
                                break;
                                case 3:
                                    $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                    $image = imagecreatefrompng($filenameForencia);
                                    imageinterlace($image, false);
                                    $nombre="red_social".rand().".png";
                                    imagepng($image,$nombre);
                                    $imagennueva=base_url."public/".$nombre;
                                    $this->Image($imagennueva,135, $y+1, $width, $height, $extension);
                                    imagedestroy($image);
                                    unlink($nombre);
                                break;
                            } 
                        }

                        $this->SetFont('helvetica','B',11);
                        $this->Cell(125, 7, utf8_decode('NOMBRE DE USUARIO (PERFIL): '));
                        $this->Ln(7);
                        $this->SetFont('helvetica','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(120,7, utf8_decode($red_social->Usuario),0,1,'C',true);
                        $this->Ln(3);

                        $this->SetFont('helvetica','B',11);
                        $this->Cell(125, 7, utf8_decode('TIPO DE ENLACE: '));
                        $this->Ln(7);
                        $this->SetFont('helvetica','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(120,7, utf8_decode($red_social->Tipo_Enlace),0,1,'C',true);
                        $this->Ln(3);

                        $this->SetFont('helvetica','B',11);
                        $this->Cell(125, 7, utf8_decode('ENLACE: '));
                        $this->Ln(7);
                        $this->SetFont('helvetica','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(120,7, utf8_decode($red_social->Enlace),0,1,'C',true);
                        $this->Ln(3);

                        $this->SetFont('helvetica','B',11);
                        $this->Cell(125, 7, utf8_decode('OBSERVACION DE ENLACE: '));
                        $this->Ln(7);
                        $this->SetFont('helvetica','',9);
                        $this->SetFillColor(236,236,236);
                        $this->MultiCell(120,7, utf8_decode($red_social->Observacion_Enlace),0,1,'C',true);
                        if($red_social->Foto_Nombre!=null&&$red_social->Foto_Nombre!='SD'&&$red_social->Foto_Nombre!=''){//solo si existe fot realiza esta funcion
                            $y1=$this->GetY();
                            $operacion=$y-$y1;
                            $diferencia=abs($operacion);
                            if($diferencia<65){ 
                                $y2=$y+65-$y1;
                                $this->Ln($y2+5);
                            }
                        }
                    }
                    if($data['forencias']!=[]&&$_SESSION['userdataSIC']->Visualizacion==1){
                        $aux=$this->revisaY($this->GetY());
                        $this->SetY($aux);
                        $i=1;
                        $forencias=$data['forencias'];
                        foreach ($forencias as $forencia){
                            if($i!=1||$this->GetY()>=230){
                                $aux=$this->revisaY($this->GetY());
                                $this->SetY($aux);
                            }
                            $this->SetFont('helvetica','',11);
                            $this->SetTextColor(0, 0, 0);
                            $this->SetFillColor(156,156,156); 
                            $this->Cell(190, 4, utf8_decode('DATO SEGUIMIENTO '.$i),0,0,'C',true);
                            $this->ln(4);
                            $this->Cell(155, 4, '');
                            $this->SetFont('helvetica','B',6);
                            $this->Cell(30, 4, utf8_decode('CAPTURO ('.$forencia->Capturo.')'));
    
                            $i++;
                            $width = 60;
                            $height = 65;
                            $y=$this->GetY();
                            if($forencia->Foto_Nombre!=null&&$forencia->Foto_Nombre!='SD'&&$forencia->Foto_Nombre!=''){
    
                                $filenameForencia = base_url."public/files/Seguimientos/" . $GLOBALS['PDFId_Seguimiento']  . "/Forencias/".$forencia->Foto_Nombre;
                                $type = exif_imagetype($filenameForencia);
                                $extension = '';
    
                                switch($type){
                                    case 1:
                                        $extension = 'gif';
                                    break;
                                    case 2:
                                        $extension = 'jpeg';//Por si tiene interlancia la imagen genera un archivo temporal jpeg
                                        $image = imagecreatefromjpeg($filenameForencia);
                                        imageinterlace($image, false);
                                        $nombre="forencia".rand().".jpeg";
                                        imagejpeg($image,$nombre);
                                        $imagennueva=base_url."public/".$nombre;
                                        $this->Image($imagennueva,135, $y+1, $width, $height, $extension);
                                        imagedestroy($image);
                                        unlink($nombre);
                                    break;
                                    case 3:
                                        $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                                        $image = imagecreatefrompng($filenameForencia);
                                        imageinterlace($image, false);
                                        $nombre="forencia".rand().".png";
                                        imagepng($image,$nombre);
                                        $imagennueva=base_url."public/".$nombre;
                                        $this->Image($imagennueva,135, $y+1, $width, $height, $extension);
                                        imagedestroy($image);
                                        unlink($nombre);
                                    break;
                                } 
                            }
    
                            $this->ln(2);
                            $this->SetFont('helvetica','B',11);
                            $this->Cell(130, 7, utf8_decode('DESCRIPCION DEL DATO: '));
                            $this->Ln(7);
                            $this->SetFont('helvetica','',9);
                            $this->SetFillColor(236,236,236);
                            $this->MultiCell(115,7, utf8_decode($forencia->Descripcion_Forencia),0,1,'C',true);
                            if($forencia->Foto_Nombre!=null&&$forencia->Foto_Nombre!='SD'&&$forencia->Foto_Nombre!=''){//solo si existe fot realiza esta funcion
                                $y1=$this->GetY();
                                $operacion=$y-$y1;
                                $diferencia=abs($operacion);
                                if($diferencia<65){ 
                                    $y2=$y+65-$y1;
                                    $this->Ln($y2+5);
                                }
                            }
                        }
                        $this->ln(1);
                    }
                    $this->Ln(1);
                }   
            }
        }
        function Header(){
            $banner = base_url.'public/media/images/logo2.png';
            $this->Image($banner,12,13,35);
            $banner = base_url.'public/media/images/logo22.png';
            $this->Image($banner,65,13,35); 
            $this->SetFont('Avenir','',11);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(135, 4);
            $this->Cell(33,  4, utf8_decode("FOLIO:"), 0, 0, 'R');
            $this->SetTextColor(128, 128, 128);
            global $PDFId_Seguimiento;
            $Id_Red = ($PDFId_Seguimiento != "") ? $PDFId_Seguimiento : $GLOBALS['PDFId_SeguimientoGlo'] ;
            $this->Cell(20,  4,$Id_Red, '', 1, 'C');
            $this->Ln(1);
            $this->Cell(99, 4);
            $this->SetFillColor(51,51,51); //242
            $this->SetTextColor(255, 255, 255);
            $this->MultiCell(105,7, utf8_decode('DIRECCIÓN DE INTELIGENCIA Y POLÍTICA CRIMINAL'),0,1,'L');
            $this->Ln(0);
            $this->Cell(99, 4);
            $this->SetFont('Avenir','',8);
            $this->MultiCell(105,3, utf8_decode('SECRETARÍA DE SEGURIDAD CIUDADANA DEL MUNICIPIO DE PUEBLA'),0,1,'L');
            $this->Ln(1);
            $this->Cell(99, 4);
            $this->SetFont('Avenir','',5);
            $this->SetTextColor(256, 0, 0);
            $this->Cell(26, 4, utf8_decode("DOCUMENTO CONFIDENCIAL:"), 0, 0, 'L');
            $this->SetTextColor(51, 51,51);
            $this->Cell(33, 4, utf8_decode("INFORMACIÓN SENSIBLE. NO COMPARTIR SIN PREVIA AUTORIZACIÓN"), 0, 0, 'L');
            $this->Ln(6);
            $this->SetFont('Avenir','',11);
        }

        function Footer(){
            $this->SetY(-8);
            $this->SetFont('Avenir','',7);
            $this->Cell(0,10,utf8_decode('AURA'),0,0,'C');
            $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'R');
        }

    }
    $pdf = new PDF();
    $pdf->AddFont('Avenir','','avenir.php');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->OnePage($data['datos_seguimiento'],$data['ConteoPersonas']);
    
    $conteoPersonas=1;
    if($data['datos_personas_entrevistadas_si']!=[]){
        $PersonasEntrevistadas=$data['datos_personas_entrevistadas_si'];
    }else{
        $PersonasEntrevistadas=[];
    }
    if($data['datos_personas']!=[]){
        $Personas=$data['datos_personas'];
        foreach($Personas as $Persona){
            $bandera=false;
            if($PersonasEntrevistadas!=[]){
                foreach($PersonasEntrevistadas as $PersonaEntrevistada){
                   
                    if(($PersonaEntrevistada['Principales']->Nombre==$Persona['datos_persona']->Nombre)&&($PersonaEntrevistada['Principales']->Ap_Paterno==$Persona['datos_persona']->Ap_Paterno)&&($PersonaEntrevistada['Principales']->Ap_Materno==$Persona['datos_persona']->Ap_Materno)){
                        $bandera=true;
                        if($pdf->GetY()> 180 ){
                            $pdf->AddPage();
                        }
                        $pdf->DatosCombinadosPersona($PersonaEntrevistada,$Persona,$conteoPersonas);
                    }
                }
            }
            if($bandera==false){
                if($pdf->GetY()> 180 ){
                    $pdf->AddPage();
                }
                $pdf->PagePersona($Persona,$conteoPersonas);
            }
            $conteoPersonas++;
        }
    }

    if($data['data_hijos']!=[]){
        $datos_hijos = $data['data_hijos'];
        foreach($datos_hijos as $dato_hijo){
           
            if($pdf->GetY()> 180 ){
                $pdf->AddPage();
            }
           
            if($dato_hijo["datos_personas"]!= []){
                $Personas = $dato_hijo["datos_personas"];
                foreach($Personas as $Persona){
                    if($pdf->GetY()> 180 ){
                        $pdf->AddPage();
                    }
                    $pdf->PagePersona($Persona,$conteoPersonas);
                    $conteoPersonas++;
                }
            }
        }
    }
    $i=1;
    if($data['datos_personas_entrevistadas_no']!=[]){
        $Personas=$data['datos_personas_entrevistadas_no'];
        foreach($Personas as $Persona){
            if($pdf->GetY()> 180 ){
                $pdf->AddPage();
            }
            $pdf->PagePersonaEntrevistada($Persona,$i);
            $i++;
        }
    }
    $contadorVeh=1;
    if($data['datos_vehiculos']!=[]){
        $Vehiculos=$data['datos_vehiculos'];
        foreach($Vehiculos as $Vehiculo){
            if($pdf->GetY()> 180 ){
                $pdf->AddPage();
            }
            $pdf->PageVehiculo($Vehiculo,$contadorVeh);
            $contadorVeh++;
        }
    }
    if($data['data_hijos']!=[]){
        $datos_hijos = $data['data_hijos'];
        foreach($datos_hijos as $dato_hijo){     
            if($dato_hijo['datos_vehiculos']!=[]){
                $Vehiculos=$dato_hijo['datos_vehiculos'];
                foreach($Vehiculos as $Vehiculo){
                    if($pdf->GetY()> 180 ){
                        $pdf->AddPage();
                    }
                    $pdf->PageVehiculo($Vehiculo,$contadorVeh);
                    $contadorVeh++;
                }
            }
        }
    }

    $conteoEventos=1;
    if($data['datos_eventos']!=[]){
        $Eventos=$data['datos_eventos'];
        foreach($Eventos as $Evento){
            if($pdf->GetY()> 180 ){
                $pdf->AddPage();
            }
            $pdf->PageEvento($Evento,$conteoEventos);
            $conteoEventos++;
        }
    }
    if($data['data_hijos']!=[]){
        $datos_hijos = $data['data_hijos'];
        foreach($datos_hijos as $dato_hijo){
            if($dato_hijo['datos_eventos']!=[]){
                $Eventos=$dato_hijo['datos_eventos'];
                foreach($Eventos as $Evento){
                    if($pdf->GetY()> 180 ){
                        $pdf->AddPage();
                    }
                    $pdf->PageEvento($Evento,$conteoEventos);
                    $conteoEventos++;
                }
            }
        }
    }

    //print_r($data['datos_seguimiento']['principal']->Nombre_PDF);
    if($data['datos_seguimiento']['principal']->Nombre_PDF!='SD'){
            $archivoExistente = '../public/files/Seguimientos/'.$PDFId_SeguimientoGlo.'/'.$data['datos_seguimiento']['principal']->Nombre_PDF;
            
            $pageCount = $pdf->setSourceFile($archivoExistente);
            // Añadir todas las páginas del archivo fuente al nuevo PDF
            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                $pageId = $pdf->importPage($pageNumber, PdfReader\PageBoundaries::MEDIA_BOX);
                $pdf->addPage();
                $pdf->useTemplate($pageId, 0, 0, 210, 230, true);
            }
            // Añadir la página del primer archivo al nuevo PDF
            //$pageId = $pdf->importPage(1,PdfReader\PageBoundaries::MEDIA_BOX);
            //$pdf->addPage();
            //$pdf->useTemplate($pageId,0,0,210,230,true);
            $pdf->Output();
    }else{
        $pdf->Output();
    }
    $pdf->Output();
?>