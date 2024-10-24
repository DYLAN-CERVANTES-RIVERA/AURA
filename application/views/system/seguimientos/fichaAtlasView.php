<?php
    error_reporting(E_ALL & ~E_WARNING );
    global $PDFId_Seguimiento;
    $PDFId_Seguimiento=$data['datos_seguimiento']['principal']->Id_Seguimiento;
    class PDF extends FPDF{
        protected $col = 0; // Current column
        function GrupoDelictivoPage($data){
            $this->SetFont('helvetica','',30);
            $this->SetTextColor(31, 56, 100);
            $this->SetY(45);
            $this->SetX(16);
            $this->Cell(5,4,"\"".utf8_decode($data['principal']->Nombre_grupo_delictivo)."\"");
            $this->SetTextColor(156, 156, 156);
            $this->SetFont('helvetica','',9);
            $this->SetY(55);
            $this->SetX(16);
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
            $this->Cell(5,4,utf8_decode($Caddelitos));
            $this->Ln(20);
            $this->SetFont('helvetica','B',10);
            $this->SetTextColor(31, 56, 100);
            $this->SetDrawColor(31,56,100);
            $this->Multicell(75,4,"MODUS OPERANDI",'C');
            $this->Line(10, $this->GetY()+2, 85, $this->GetY()+2);
            $this->SetFont('helvetica','',8);
            $this->Ln();
            
            $this->Multicell(75,4,utf8_decode($data['principal']->Modus_operandi));
            $this->Ln();

            $this->SetFont('helvetica','B',10);
            $this->SetTextColor(31, 56, 100);
            $this->SetDrawColor(31,56,100);
            $this->Multicell(75,4,"OBSERVACIONES",'C');
            $this->Line(10, $this->GetY()+2, 85, $this->GetY()+2);
            $this->SetFont('helvetica','',8);
            $this->Ln();
            $this->Multicell(75,4,utf8_decode($data['principal']->Observaciones));
            if($data['principal']->Foto_grupo_delictivo!=null&&$data['principal']->Foto_grupo_delictivo!='SD'&&$data['principal']->Foto_grupo_delictivo!=''){
                $filename = base_url."public/files/Seguimientos/" . $data['principal']->Id_Seguimiento . "/".$data['principal']->Foto_grupo_delictivo;
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
                        $this->Image($imagennueva,102,69,90,68, $extension);
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
                        $this->Image($imagennueva,102,69,90,68, $extension);
                        imagedestroy($image);
                        unlink($nombre);
                    break;
                } 
            }else{
                $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                $imagennueva=base_url."public/media/images/logo7.png";
                $this->Image($imagennueva,102,69,90,68, $extension);
            }
            $this->SetY(148);
            $this->SetX(100);
            $this->SetFont('helvetica','B',10);
            $this->SetTextColor(31, 56, 100);
            $this->SetDrawColor(31,56,100);
            $this->Multicell(75,4,utf8_decode("PELIGROSIDAD"),'C');
            $this->Line(100, $this->GetY()+2, 190, $this->GetY()+2);
            $this->SetFont('helvetica','',10);
            $this->Ln(4);
            $this->SetX(102);
            $this->Multicell(75,4,utf8_decode($data['principal']->Peligrosidad));

            $this->Ln(4);
            $this->SetX(100);
            $this->SetFont('helvetica','B',10);
            $this->SetTextColor(31, 56, 100);
            $this->SetDrawColor(31,56,100);
            $this->Multicell(75,4,utf8_decode("ZONAS CON EVENTOS"),'C');
            $this->Line(100, $this->GetY()+2, 190, $this->GetY()+2);
            $this->Ln(4);
            $this->SetX(102);
            $this->SetFont('helvetica','',10);
            $this->Multicell(85,4,utf8_decode($data['filtro']->EventosEn));

            $this->Ln(4);
            $this->SetX(100);
            $this->SetFont('helvetica','B',10);
            $this->SetTextColor(31, 56, 100);
            $this->SetDrawColor(31,56,100);
            $this->Multicell(75,4,utf8_decode("ACTIVIDADES ILEGALES"),'C');
            $this->Line(100, $this->GetY()+2, 190, $this->GetY()+2);
            $this->Ln(4);
            $this->SetX(102);
            $this->SetFont('helvetica','',10);
            $this->Multicell(85,4,utf8_decode($Caddelitos));

            $this->Ln(4);
            $this->SetX(100);
            $this->SetFont('helvetica','B',10);
            $this->SetTextColor(31, 56, 100);
            $this->SetDrawColor(31,56,100);
            $this->Multicell(75,4,utf8_decode("LIDERES"),'C');
            $this->Line(100, $this->GetY()+2, 190, $this->GetY()+2);
            $this->Ln(4);
            $this->SetX(102);
            $this->SetFont('helvetica','',10);
            $this->Multicell(85,4,utf8_decode($data['filtro']->Lideres));
        }
        function IntegrantesPage($data){
            $this->SetCol(0);
            $Integrantes=$data;
            foreach($Integrantes as $Integrante){
                $this->revisaEspacio();
                $this->SetTextColor(31, 56, 100);
                $this->SetY($this->GetY()+3);
                $this->SetFont('helvetica','B',10);
                $this->Cell(5,4,utf8_decode(mb_strtoupper($Integrante['datos_persona']->Nombre." ".$Integrante['datos_persona']->Ap_Paterno ." ".$Integrante['datos_persona']->Ap_Materno)));  
                $this->Line($this->GetX()-5, $this->GetY()+5, $this->GetX()+80, $this->GetY()+5);

                if($Integrante['datos_persona']->Foto!=null&&$Integrante['datos_persona']->Foto!='SD'&&$Integrante['datos_persona']->Foto!=''){
                    $filename = base_url."public/files/Seguimientos/" . $GLOBALS['PDFId_Seguimiento'] . "/Personas/".$Integrante['datos_persona']->Foto;
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
                            $this->SetLineWidth(0.5);
                            $this->Rect($this->GetX()-5,$this->GetY()+7,40,44,"D");
                            $this->Image($imagennueva,$this->GetX()-4,$this->GetY()+8,38,42,$extension);
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
                            $this->SetLineWidth(0.5);
                            $this->Rect($this->GetX()-5,$this->GetY()+7,40,44,"D");
                            $this->Image($imagennueva,$this->GetX()-4,$this->GetY()+8,38,42,$extension);
                            imagedestroy($image);
                            unlink($nombre);
                        break;
                    } 
                }else{
                    $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                    $imagennueva=base_url."public/media/images/frentesilueta.png";
                    $this->SetLineWidth(0.5);
                    $this->Rect($this->GetX()-5,$this->GetY()+7,40,44,"D");
                    $this->Image($imagennueva,$this->GetX()-4,$this->GetY()+8,38,42,$extension);
                }
                $this->SetTextColor(255, 0, 0);
                $this->SetY($this->GetY()+10);
                $this->SetX($this->GetX()+45);
                $this->Cell(37,10,utf8_decode($Integrante['datos_persona']->Rol),1,0,'C');
                $this->SetFillColor(200,200,200);
                $this->SetTextColor(31, 56, 100);
                $this->Ln();
                $this->SetY($this->GetY()+33);
                //AQUI EMPEZAMOS CON LAS COLUMNAS DE INFORMACION PINTAMOS PRIMERO LA PARTE DERECHA Y LUEGO PARTE IZQUIERDA
                $this->SetFont('helvetica','',9);

                $this->revisaEspacioEntreEscritura();

                $x1=$this->GetX();
                $y1=$this->GetY();
                $this->Cell(40,4);
                $this->SetFillColor(242,242,242);
                $this->MultiCell(50, 4,utf8_decode($Integrante['datos_persona']->Alias),0,1,'L');
                $y2=$this->GetY();
                $Etiqueta="ALIAS";
                $Saltos=abs($y2)-abs($y1);
                $Saltos=$Saltos/4;
                for($i=0;$i<$Saltos;$i++){
                    $Etiqueta.="\n";
                }
                $this->SetX($x1);
                $this->SetY($y1);
                $this->SetFillColor(200,200,200);
                $this->MultiCell(40, 4, utf8_decode($Etiqueta),0,1,'L');

                $this->revisaEspacioEntreEscritura();

                $x1=$this->GetX();
                $y1=$this->GetY();
                $this->Cell(40,4);
                $this->SetFillColor(242,242,242);
                $this->MultiCell(50, 4,utf8_decode($Integrante['datos_persona']->Curp),0,1,'L');
                $y2=$this->GetY();
                $Etiqueta="CURP";
                $Saltos=abs($y2)-abs($y1);
                $Saltos=$Saltos/4;
                for($i=0;$i<$Saltos;$i++){
                    $Etiqueta.="\n";
                }
                $this->SetX($x1);
                $this->SetY($y1);
                $this->SetFillColor(200,200,200);
                $this->MultiCell(40, 4, utf8_decode($Etiqueta),0,1,'L');

                if($Integrante['domicilios_confirmados']!=[]){
                    $this->revisaEspacioEntreEscritura();
                    $domicilios=$Integrante['domicilios_confirmados'];
                    $domicilioCompleto='';
                    $i=1;
                    foreach($domicilios as $domicilio){
                        if($i>1){$domicilioCompleto.=', ';}
                        $domicilioCompleto.=$i.'. ';
                        $domicilioCompleto='CALLE: '.$domicilio->Calle;
                        $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE2: '.$domicilio->Calle2: '';
                        $domicilioCompleto.=', COLONIA: '.$domicilio->Colonia;
                        $domicilioCompleto .= ($domicilio->NumExt!='SD') ? ', NUM.EXT: '.$domicilio->NumExt: '';
                        $domicilioCompleto .= ($domicilio->NumInt!='SD') ? ', NUM.INT: '.$domicilio->NumInt: '';
                        $domicilioCompleto .= ($domicilio->CP!='SD') ? ', CP: '.$domicilio->CP: '';
                        $domicilioCompleto .= ($domicilio->CoordY!='SD') ? ', COORDY: '.$domicilio->CoordY: '';
                        $domicilioCompleto .= ($domicilio->CoordX!='SD') ? ', COORDX: '.$domicilio->CoordX: '';
                        $i++;
                    }
                    $x1=$this->GetX();
                    $y1=$this->GetY();
                    $this->Cell(40,4);
                    $this->SetFillColor(242,242,242);
                    $this->MultiCell(50, 4,utf8_decode($domicilioCompleto),0,1,'L');
                    $y2=$this->GetY();
                    $Etiqueta="UDC";
                    $Saltos=abs($y2)-abs($y1);
                    $Saltos=$Saltos/4;
                    for($i=0;$i<$Saltos-1;$i++){
                        $Etiqueta.="\n";
                    }
                    $this->SetX($x1);
                    $this->SetY($y1);
                    $this->SetFillColor(200,200,200);
                    $this->MultiCell(40, 4, utf8_decode($Etiqueta),0,1,'L');
                }

                $this->revisaEspacioEntreEscritura();

                $x1=$this->GetX();
                $y1=$this->GetY();
                $this->Cell(40,4);
                $this->SetFillColor(242,242,242);
                $this->MultiCell(50, 4,utf8_decode($Integrante['datos_persona']->Telefono),0,1,'L');
                $y2=$this->GetY();
                $Etiqueta="UTC";
                $Saltos=abs($y2)-abs($y1);
                $Saltos=$Saltos/4;
                for($i=0;$i<$Saltos;$i++){
                    $Etiqueta.="\n";
                }
                $this->SetX($x1);
                $this->SetY($y1);
                $this->SetFillColor(200,200,200);
                $this->MultiCell(40, 4, utf8_decode($Etiqueta),0,1,'L');

                if($Integrante['perfiles']!=[]){
                    $this->revisaEspacioEntreEscritura();
                    $Enlaces=$Integrante['perfiles'];
                    $TodosEnlaces='';
                    $i=1;
                    foreach($Enlaces as $Enlace){
                        if($i>1){$TodosEnlaces.=' , ';}
                        $TodosEnlaces.=$Enlace->Enlace;
                    }

                    $x1=$this->GetX();
                    $y1=$this->GetY();
                    $this->Cell(40,4);
                    $this->SetFillColor(242,242,242);
                    $this->MultiCell(50, 4,utf8_decode($TodosEnlaces),0,1,'L');
                    $y2=$this->GetY();
                    $Etiqueta="PERFILES";
                    $Saltos=abs($y2)-abs($y1);
                    $Saltos=$Saltos/4;
                    for($i=0;$i<$Saltos;$i++){
                        $Etiqueta.="\n";
                    }
                    $this->SetX($x1);
                    $this->SetY($y1);
                    $this->SetFillColor(200,200,200);
                    $this->MultiCell(40, 4, utf8_decode($Etiqueta),0,1,'L');
                    
                }
                if($Integrante['domicilios_presuntos']!=[]){
                    $this->Ln(1);
                    $this->revisaEspacioEntreEscritura();
                    $this->SetFont('helvetica','B',9);
                    $this->Cell(4,4,utf8_decode("PRESUNTOS DOMICILIOS: "));
                    $this->Ln(4);
                    $Domicilios=$Integrante['domicilios_presuntos'];
                    $i=1;
                    $this->SetFont('helvetica','',9);
                    foreach($Domicilios as $domicilio){
                        $this->revisaEspacioEntreEscritura();
                        $domicilioCompleto=$i.'. ';
                        $domicilioCompleto='CALLE: '.$domicilio->Calle;
                        $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE2: '.$domicilio->Calle2: '';
                        $domicilioCompleto.=', COLONIA: '.$domicilio->Colonia;
                        $domicilioCompleto .= ($domicilio->NumExt!='SD') ? ', NUM.EXT: '.$domicilio->NumExt: '';
                        $domicilioCompleto .= ($domicilio->NumInt!='SD') ? ', NUM.INT: '.$domicilio->NumInt: '';
                        $domicilioCompleto .= ($domicilio->CP!='SD') ? ', CP: '.$domicilio->CP: '';
                        $domicilioCompleto .= ($domicilio->CoordY!='SD') ? ', COORDY: '.$domicilio->CoordY: '';
                        $domicilioCompleto .= ($domicilio->CoordX!='SD') ? ', COORDX: '.$domicilio->CoordX: '';
                        $this->Multicell(90,4,utf8_decode($domicilioCompleto));
                       $i++;
                    }
                }

                if($Integrante['antecedentes']!=[]){
                    $this->Ln(1);
                    $this->revisaEspacioEntreEscritura();
                    $this->SetFont('helvetica','B',9);
                    $this->Cell(4,4,utf8_decode("ANTECEDENTES: "));
                    $this->Ln(4);
                    $Antecedentes=$Integrante['antecedentes'];
                    $i=1;
                    $this->SetFont('helvetica','',9);
                    foreach($Antecedentes as $Antecedente){
                        $this->revisaEspacioEntreEscritura();
                        $this->Multicell(90,4,utf8_decode($i.". ".$Antecedente->Descripcion_Antecedente." ".$Antecedente->Fecha_Antecedente));
                       $i++;
                    }
                }
                
            }
        }
        function SetCol($col){
            // Set position at a given column
            $this->col = $col;
            $x = 10+$col*97.5; 
            $this->SetLeftMargin($x);
            $this->SetX($x);
        }
        function revisaEspacio(){
            if($this->GetY()>220){
                if($this->col==0){
                    $this->SetY(32);
                    $this->SetCol(1);
                }else{
                    if($this->GetX()>=97){
                        $this->SetCol(0);
                        $this->AddPage();
                        $this->SetY(32);   
                    }else{
                        $this->SetY(32);
                        $this->SetCol(0);
                    }
                }
            }
        }
        function revisaEspacioEntreEscritura(){
            if($this->GetY()>265){
                if($this->col==0){
                    $this->SetY(32);
                    $this->SetCol(1);
                }else{
                    if($this->GetX()>=97){
                        $this->SetCol(0);
                        $this->AddPage();
                        $this->SetY(32);   
                    }else{
                        $this->SetY(32);
                        $this->SetCol(0);
                    }
                }
            }
        }
        function Header(){
            $banner = base_url.'public/media/images/logo2.png';
            $this->Image($banner,12,13,35);
            $banner = base_url.'public/media/images/LOGOSSC.jpg';
            $this->Image($banner,65,13,35); 
            $this->SetFont('Avenir','',11);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(135, 4);
            $this->Cell(33,  4, utf8_decode("FOLIO:"), 0, 0, 'R');
            $this->SetTextColor(128, 128, 128);
            $this->Cell(20,  4,$GLOBALS['PDFId_Seguimiento'] , '', 1, 'C');
            $this->Ln(1);
            $this->Cell(99, 4);
            $this->SetFillColor(127, 36, 71); //242
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
    $pdf->GrupoDelictivoPage($data['datos_seguimiento']);    
    if($data['datos_personas']!=[]){
        $pdf->AddPage();
        $pdf->IntegrantesPage($data['datos_personas']);

    }
    $pdf->Output();

?>