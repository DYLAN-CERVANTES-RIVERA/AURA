<?php
    ///error_reporting(0);
    global $PDFIDVEHICULO;
    $PDFIDVEHICULO=$data['datos_Vehiculo']->Id_Seguimiento;
    class PDF extends FPDF{
        function PageVehiculo($data){
            $this->SetTextColor(255, 255, 255);
            $this->SetFillColor(41,41,95); 
            $this->Cell(190, 5, utf8_decode('VEHICULO IDENTIFICADO ('.$data['grupo_delictivo']->Nombre_grupo_delictivo.')'), 0, 1, 'C',true);
            $this->ln(5);
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
                    if($i!=1 ||$this->GetY()>=260){
                        $aux=$this->revisaY($this->GetY());
                        $this->SetY($aux);
                    }
                    $fechalimpia=$this->fechaMes($antecedente->Fecha_Antecedente);
                    $this->SetTextColor(51, 51, 51);
                    $this->SetFillColor(156,156,156); 
                    $this->Cell(190, 4, utf8_decode('ANTECEDENTE '.$i.' ('.$fechalimpia.')'),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',6);
                    $this->Cell(30, 4, utf8_decode('CAPTURO ('.$antecedente->Capturo.')'));
                    $this->SetFont('Avenir','',11);
                    $this->Ln(4);
                    $this->SetTextColor(128, 128, 128);
                    $this->MultiCell(190, 4, utf8_decode($antecedente->Descripcion_Antecedente), 0, 'J');
                    $i++;
                }
                $this->SetTextColor(0, 0, 0);
                $this->Cell(190, 1, utf8_decode('_______________________________________________________________________________________________'));
                $this->ln(4);
            }
            $aux=$this->revisaY($this->GetY());
            $this->SetY($aux);
            if($data['domicilios']!=[]){
                $i=1;
                $domicilios=$data['domicilios'];
                foreach ($domicilios as $domicilio){
                    if($i!=1 ||$this->GetY()>=260){
                        $aux=$this->revisaY($this->GetY());
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
                    $domicilioCompleto='CALLE: '.$domicilio->Calle;
                    $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE2: '.$domicilio->Calle2: '';
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
                $this->SetTextColor(0, 0, 0);
                $this->Cell(190, 1, utf8_decode('_______________________________________________________________________________________________'));
                $this->ln(4);
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
        function fechaMes($cad){
            $mesaux=explode('-',$cad);
            $MESNUM=$mesaux[1];
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
            return strtoupper ($mes.' del '.$mesaux[0]);

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
            $this->Cell(20,  4,$GLOBALS['PDFIDVEHICULO'] , '', 1, 'C');
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
    $pdf->PageVehiculo($data);
    $pdf->Output();
?>