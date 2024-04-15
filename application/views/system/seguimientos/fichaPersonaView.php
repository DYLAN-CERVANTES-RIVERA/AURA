<?php
    error_reporting(0);
    global $PDFIDPERSONA;
    $PDFIDPERSONA=$data['datos_persona']->Id_Seguimiento;
    class PDF extends FPDF{
        function PagePersona($data){
            $this->SetTextColor(255, 255, 255);
            $this->SetFillColor(41,41,95); 
            $this->Cell(190, 5, utf8_decode('PERSONA IDENTIFICADA ('.$data['grupo_delictivo']->Nombre_grupo_delictivo.')'), 0, 0, 'C',true);
            $this->ln(5);
            $this->Cell(155, 4, '');
            $this->SetFont('helvetica','B',6);
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
            $this->Cell(45, 4, utf8_decode('NOMBRE COMPLETO: '));

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

            $aux=$this->revisaYAntecedente($this->GetY());
            $this->SetY($aux);
            if($data['domicilios']!=[]){
                $i=1;
                $domicilios=$data['domicilios'];
                foreach ($domicilios as $domicilio){
                    if($i!=1 ||$this->GetY()>=265){
                        $aux=$this->revisaYAntecedente($this->GetY());
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

                        $filenameForencia = base_url."public/files/Seguimientos/" . $GLOBALS['PDFIDPERSONA']  . "/Redes_Sociales/".$red_social->Foto_Nombre;
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
                    $this->Cell(130, 4, utf8_decode('NOMBRE DE USUARIO (PERFIL): '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(115, 4, utf8_decode($red_social->Usuario), 0, 'J');
                    $this->Ln(4);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('TIPO DE ENLACE: '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(115, 4, utf8_decode($red_social->Tipo_Enlace), 0, 'J');
                    $this->Ln(4);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('ENLACE: '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(115, 4, utf8_decode($red_social->Enlace), 0, 'J');
                    $this->Ln(4);

                    $this->SetTextColor(51, 51, 51);
                    $this->Cell(130, 4, utf8_decode('OBSERVACION DE ENLACE: '));
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(115, 4, utf8_decode($red_social->Observacion_Enlace), 0, 'J');
                    if($red_social->Foto_Nombre!=null&&$red_social->Foto_Nombre!='SD'&&$red_social->Foto_Nombre!=''){//solo si existe fot realiza esta funcion
                        $y1=$this->GetY();
                        if($y1<$y+60){
                            $y2=$y+60-$y1;
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

                        $filenameForencia = base_url."public/files/Seguimientos/" . $GLOBALS['PDFIDPERSONA']  . "/Forencias/".$forencia->Foto_Nombre;
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
                        if($y1<$y+65){
                            $y2=$y+65-$y1;
                            $this->Ln($y2+5);
                        }
                    }
                }
                $this->ln(4);
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
            $this->Cell(20,  4,$GLOBALS['PDFIDPERSONA'] , '', 1, 'C');
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
    //print_r($data);
    $pdf = new PDF();
    $pdf->AddFont('Avenir','','avenir.php');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->PagePersona($data);
    $pdf->Output();
?>