<?php
    /*VISTA PARA LA EXPORTACION DE PDF DE LOS VEHICULOS INVOLUCRADOS AL EVENTO*/
    error_reporting(E_ALL & ~E_WARNING );
    global $PDFFOLIO;
    $PDFFOLIO=$data['principales'][0];
    class PDF extends FPDF{
        function VehiculosI($vehiculos){
            $Folio_infra  = $GLOBALS['PDFFOLIO'];
            $this->SetTextColor(51, 51, 51);
            $this->SetFillColor(156,156,156); 
            $this->Cell(190, 4, utf8_decode('VEHICULOS INVOLUCRADOS AL EVENTO'),0,0,'C',true);
            $this->ln(7);
            $i=1;
            foreach($vehiculos as $vehiculo){
                $aux = $this->revisaYVehI($this->GetY());
                $this->SetY($aux);

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
                if($vehiculo->Path_Imagen!=null&&$vehiculo->Path_Imagen!=''){
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
                
                $this->Cell(190, 4, utf8_decode('_______________________________________________________________________________________________'));
                $this->Ln(7);
                $i++;
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
        function Header(){
            $banner = base_url.'public/media/images/logo2.png';
            $this->Image($banner,12,13,35);
            $banner = base_url.'public/media/images/logo22.png';
            $this->Image($banner,65,13,35); 
            $this->SetFont('Avenir','',11);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(135, 4);
            $this->Cell(33,  4, utf8_decode("FOLIO INFRA:"), 0, 0, 'R');
            $this->SetTextColor(128, 128, 128);
            $this->Cell(20,  4,$GLOBALS['PDFFOLIO'] , '', 1, 'C');
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

    if($data['vehiculos']!=null && $data['vehiculos']!=[]){
        $pdf = new PDF();
        $pdf->AddFont('Avenir','','avenir.php');
        $pdf->AliasNbPages();
        $pdf->AddPage();
        $pdf->VehiculosI($data['vehiculos']);
        $pdf->Output();
    }else{
        echo "<script type ='text/JavaScript'>";  
        echo "alert('NO HAY VEHICULOS');window.location.href='GestorCasos.php'"; 
        echo "</script>";        
    }
?>