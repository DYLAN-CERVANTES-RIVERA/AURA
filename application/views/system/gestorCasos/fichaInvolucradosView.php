<?php
    /*VISTA PARA LA EXPORTACION DE PDF SOLO LOS INVOLUCRADOS CORROBORADOS*/
    error_reporting(E_ALL & ~E_WARNING );
    global $PDFFOLIO;
    $PDFFOLIO=$data['principales'][0];
    class PDF extends FPDF{
        function Involucrados($personas){

            $this->SetTextColor(51, 51, 51);
            $this->SetFillColor(156,156,156); 
            $this->Cell(190, 4, utf8_decode('INVOLUCRADOS'),0,0,'C',true);
            $this->ln(7);
            $Folio_infra = $GLOBALS['PDFFOLIO'];
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
                $this->SetTextColor(128, 128, 128);
                $auxDescripcion=str_replace("\n", " ", $persona->Descripcion_Responsable);
                $this->MultiCell(150, 5, utf8_decode($auxDescripcion), 0, 'J');
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
                $this->Cell(190, 4, utf8_decode('_______________________________________________________________________________________________'));
                $this->Ln(7);
                $i++;
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
        function Header(){
            $banner = base_url.'public/media/images/logo2.png';
            $this->Image($banner,12,13,35);
            $banner = base_url.'public/media/images/logo22.png';
            $this->Image($banner,65,13,35); 
            $this->SetFont('Avenir','',11);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(135, 4);
            $this->Cell(33,  4, utf8_decode("FOLIO AURA:"), 0, 0, 'R');
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

    if($data['personas']!=null && $data['personas']!=[]){
        $pdf = new PDF();
        $pdf->AddFont('Avenir','','avenir.php');
        $pdf->AliasNbPages();
        $pdf->Addpage();
        $pdf->Involucrados($data['personas']);
        $pdf->Output();
    }else{
        echo "<script type ='text/JavaScript'>";  
        echo "alert('NO HAY INVOLUCRADOS');window.location.href='GestorCasos.php'"; 
        echo "</script>"; 
    }
    
?>
