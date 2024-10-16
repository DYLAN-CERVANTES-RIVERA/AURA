<?php
error_reporting(E_ALL & ~E_WARNING );
global $PDFId_Persona_Entrevista;
$PDFId_Persona_Entrevista=$data['Principales']->Id_Persona_Entrevista;
class PDF extends FPDF{
    public $EntrevistasGB = array();
    public $ForensiasGB = array();
    public $UbicacionesGB = array();
    public $RedesGB = array();
    
    public function agregarEntrevistas($data) {
        global $EntrevistasGB;
        $EntrevistasGB = $data;
    }
    public function agregarForensias($data) {
       // var_dump($data);
       if($_SESSION['userdataSIC']->Visualizacion==0){
        global $ForensiasGB;
        $ForensiasGB = [];
       }else{
            global $ForensiasGB;
            $ForensiasGB = $data;
       }
        
    }
    public function agregarUbicaciones($data) {
        global $UbicacionesGB;
        $UbicacionesGB = $data;
    }
    public function agregarRedes($data) {
        global $RedesGB;
        $RedesGB = $data;
    }
    function PagePersonaEntrevistada($data){
        $this->SetFont('helvetica','',11);
        $this->SetTextColor(255, 255, 255);
        $this->SetFillColor(41,41,95); 
        $this->Cell(190, 5, utf8_decode('ENTREVISTAS CON INFORMACIÓN RELEVANTE'), 0, 0, 'C',true);
        $this->ln(10);
        $this->Cell(155, 4, '');
        $this->SetFont('helvetica','B',6);
        $this->Cell(30, 4, utf8_decode('CAPTURO ('.$data['Principales']->Capturo.')'));
        $this->SetFont('Avenir','',11);
        $this->ln(4);
        $width = 73;
        $height = 115;

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
        $this->MultiCell(125,7, utf8_decode($data['Principales']->Detenido_por),0,1,'C',true);
        $y=$this->GetY()+2;
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
                    $this->Image($imagennueva,10, $y, $width-2, $height, $extension);
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
                    $this->Image($imagennueva,10, $y, $width-2, $height, $extension);
                    imagedestroy($image);
                    unlink($nombre);
                break;
            } 
        }else{
            $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
            $imagennueva=base_url."public/media/images/frentesilueta.png";
            $this->Image($imagennueva,10, $y, $width-2, $height, $extension);
        }
        $this->Ln(2);
        $this->SetFont('helvetica','B',11);
        $this->Cell($width, 7);
        $this->Cell(20, 7, utf8_decode('NOMBRE:'));
        $this->SetFont('helvetica','',11);
        $this->SetFillColor(236,236,236);
        $zona = ($data['Principales']->Zona != 'SD')?'('.$data['Principales']->Zona.') '."\n" : '';
        $this->multiCell(95,7, utf8_decode($zona.$data['Principales']->Nombre.' '.$data['Principales']->Ap_Paterno.' '.$data['Principales']->Ap_Materno),0,0,'J',true);

        $this->Ln(3);
        $this->SetFont('helvetica','B',11);
        $this->Cell($width, 7);
        $this->Cell(20, 7, utf8_decode('CALLE 1:'));
        $this->SetFont('helvetica','',9);
        $this->SetFillColor(236,236,236);
        $cadena = str_replace('CALLE ','',$data['Principales']->Calle_Domicilio);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
        $this->Cell(95,7, utf8_decode($cadena),0,0,'J',true);


        $this->Ln(9);
        $this->Cell($width, 4);
        $this->SetFont('helvetica','B',11);
        $this->Cell(20, 7, utf8_decode('CALLE 2:'));
        $this->SetFont('helvetica','',9);
        $this->SetFillColor(236,236,236);
        $cadena = str_replace('CALLE ','',$data['Principales']->Calle2_Domicilio);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
        $this->Cell(95,7, utf8_decode($cadena),0,0,'J',true);

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

        $this->Ln(3);
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

        $this->Ln(9);
        $this->Cell($width, 4);
        $this->SetFont('helvetica','B',11);
        $this->Cell(30, 7, utf8_decode('ASIGNADO A: '));
        $this->SetFont('helvetica','',9);
        $this->SetFillColor(236,236,236);
        $this->Cell(85,7, utf8_decode($data['Principales']->Asignado_a),0,0,'J',true);

        $this->Ln(25);
        $this->SetFont('helvetica','B',11);
        $this->Cell(25, 7, utf8_decode('LUGAR DE DETENCIÓN:'));

        $this->Ln(9);
        $this->SetFont('helvetica','B',11);
        $this->Cell(20, 7, utf8_decode('CALLE 1:'));
        $this->SetFont('helvetica','',9);
        $this->SetFillColor(236,236,236);
        $cadena = str_replace('CALLE ','',$data['Principales']->Calle_Detencion);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
        $this->Cell(170,7, utf8_decode($cadena),0,0,'J',true);
        $this->Ln(9);
        $this->SetFont('helvetica','B',11);
        $this->Cell(20, 7, utf8_decode('CALLE 2:'));
        $this->SetFont('helvetica','',9);
        $this->SetFillColor(236,236,236);
        $cadena = str_replace('CALLE ','',$data['Principales']->Calle2_Detencion);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
        $this->Cell(170,7, utf8_decode($cadena),0,0,'J',true);

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
        /*$this->SetFont('helvetica','B',11);
        $this->Cell(60, 7, utf8_decode('ELEMENTO QUE CAPTURO:'));
        $this->Cell(50, 7, utf8_decode($data['Principales']->Capturo));*/
    }

    function DatosRelevantesPersona(){
        global $EntrevistasGB;
        $entrevistas = $EntrevistasGB;
        if($entrevistas!=[]){
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
                $this->Ln(4);
                $this->SetFont('helvetica','B',11);
                $this->Cell(20, 8, utf8_decode('ENTREVISTA DETENIDO:'));
                $this->Ln(7);
                $this->SetFillColor(236,236,236);
                $this->SetFont('helvetica','',9);
                $this->MultiCell(190,7, utf8_decode('-'.$entrevista->Entrevista),0,1,'C',true);
                $this->Ln(7);
                $this->SetFont('helvetica','B',11);
                $this->Cell(40, 8, utf8_decode('ALIAS REFERIDOS:'));
                $this->SetFont('helvetica','',9);
                $this->SetFillColor(236,236,236);
                $this->Cell(150,7, utf8_decode($entrevista->Alias_Referidos),0,0,'C',true);
                $this->Ln(8);
                
                $width = 75;
                $height = 80;
                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);
                
                $y=$this->GetY();
                if($entrevista->Foto!=null && $entrevista->Foto!='SD' && $entrevista->Foto!=''){
                    $filenameentrevista = base_url."public/files/Entrevistas/" . $GLOBALS['PDFId_Persona_Entrevista']  . "/FotosEntrevistas/".$entrevista->Foto;
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
              
                $this->buscahijos($entrevista->Id_Entrevista,'ENTREVISTA','ENTREVISTA '.$i);
                $i++;   
            }
        }
        global $ForensiasGB;
        $forensias = $ForensiasGB;
        if($forensias!=[]){
            $j=0;
            $padresForensia = [];
            $auxCount =count($forensias);
            for($i=0; $i< $auxCount; $i++){
                if($forensias[$i]->Id_Dato == -1 && $forensias[$i]->Tipo_Relacion == 'SD'){
                    $padresForensia[$j] = $forensias[$i];
                    unset($forensias[$i]);
                    $j++;
                }
            }
            $forensias = array_values($forensias);
            $this->agregarForensias($forensias);

            $this->Ln(7);
            $aux=$this->revisaY($this->GetY());
            $this->SetY($aux);

            $this->SetFont('helvetica','b',11);
            $this->SetFillColor(156,156,156);
            $this->Cell(190, 4, utf8_decode('DATOS NO ASOCIADOS A ENTREVISTAS'),0,0,'C');
            $this->Ln(7);
        
            foreach ($padresForensia as $forensia){
                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);
                
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('helvetica','',11);
                $this->SetFillColor(156,156,156);
                $this->Cell(190, 4, utf8_decode($forensia->clavePDF),0,0,'C',true);
                $this->ln(4);

                $this->Cell(155, 4, '');
                $this->SetFont('Avenir','',11);
                $this->ln(2);
                $width = 75;
                $height = 65;
                $y=$this->GetY();
                if($forensia->Foto!=null && $forensia->Foto!='SD' && $forensia->Foto!=''){

                    $filenameForensia = base_url."public/files/Entrevistas/" . $GLOBALS['PDFId_Persona_Entrevista']  . "/ForensiasRelevantes/".$forensia->Foto;
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
                $this->Cell(130, 7, utf8_decode('DESCRIPCION: '));
                $this->Ln(7);
                $this->SetFont('helvetica','',9);
                $this->SetFillColor(236,236,236);
                $this->MultiCell(115,7, utf8_decode($forensia->Descripcion_Forensia),0,1,'C',true);
                $this->Ln(3);
                if($forensia->Tipo_Dato!='SD'){
                    $this->SetTextColor(255, 0, 0);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(130, 7, utf8_decode('RELEVANTE ('.$forensia->Tipo_Dato.'):'));
                    $this->Ln(7);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(115,7, utf8_decode($forensia->Dato_Relevante),0,1,'C',true);
                    $this->Ln(3);
                }
                if($forensia->Foto!=null&&$forensia->Foto!='SD'&&$forensia->Foto!=''){//solo si existe fot realiza esta funcion
                    $y1=$this->GetY();
                    $operacion=$y-$y1;
                    $diferencia=abs($operacion);
                    if($diferencia<65){ 
                        $y2=$y+65-$y1;
                        $this->Ln($y2+5);
                    } 
                }
                $this->buscahijos($forensia->Id_Forensia_Entrevista ,'DATO',$forensia->clavePDF);
               
            }
        }
        global $UbicacionesGB;
        $ubicaciones = $UbicacionesGB;
        global $RedesGB;
        $redes = $RedesGB;
        if($ubicaciones!=[]||$redes!=[]){

            $this->Ln(7);
            $aux=$this->revisaY($this->GetY());
            $this->SetY($aux);

            $this->SetFont('helvetica','b',11);
            $this->SetFillColor(156,156,156);
            $this->Cell(190, 4, utf8_decode('ADICIONALES NO ASOCIADOS A ENTREVISTAS NI A DATOS'),0,0,'C');
            $this->Ln(7);

            if($ubicaciones!=[]){
                $domicilios = $ubicaciones;
                foreach ($domicilios as $domicilio){
    
                    $aux=$this->revisaY($this->GetY());
                    $this->SetY($aux);
    
                    $this->SetFont('helvetica','',11);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode($domicilio->clavePDF),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',11);
                    $this->ln(2);
                    $this->Cell(130, 7, utf8_decode('DIRECCIÓN DE LA UBICACIÓN:'));
                    $this->Ln(7);
                    $cadena = str_replace('CALLE ','',$domicilio->Calle);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
                    $domicilioCompleto='CALLE: '.$cadena;
    
                    $cadena = str_replace('CALLE ','',$domicilio->Calle2);
                    $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE 2: '.$cadena: '';
                    $domicilioCompleto .=', COLONIA: '.$domicilio->Colonia;
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
    
    
                    $y=$this->GetY();
                    if($domicilio->Foto!=null && $domicilio->Foto!='SD' && $domicilio->Foto!=''){
    
                        $width = 65;
                        $height = 70;
    
                        $aux=$this->revisaFotoY($this->GetY());
                        $this->SetY($aux);
                        $y=$this->GetY();
    
                        $filenameUbicacion = base_url."public/files/Entrevistas/" . $GLOBALS['PDFId_Persona_Entrevista']  . "/UbicacionesRelevantes/".$domicilio->Foto;
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
                        $this->Ln(75);
                    }else{
                        $this->Ln(2);
                    }
                }
            }
            if($redes!=[]){
                $Redes_Sociales = $redes;
                foreach ($Redes_Sociales as $Red){
    
                    $aux=$this->revisaY($this->GetY());
                    $this->SetY($aux);
    
                    $this->SetFont('helvetica','',11);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFillColor(156,156,156);
                    $this->Cell(190, 4, utf8_decode($Red->clavePDF.' ('.$Red->Tipo_Enlace.')'),0,0,'C',true);
                    $this->ln(4);
                    $this->Cell(155, 4, '');
                    $this->SetFont('helvetica','B',11);
                    $this->ln(2);
                    $this->Cell(130, 7, utf8_decode('USUARIO: '));
                    $this->Ln(7);
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(190,7, utf8_decode($Red->Usuario),0,1,'C',true);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(130, 7, utf8_decode('ENLACE: '));
                    $this->Ln(7);
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(190,7, utf8_decode($Red->Enlace),0,1,'C',true);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(130, 7, utf8_decode('OBSERVACIÓN DE ENLACE: '));
                    $this->Ln(7);
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(190,7, utf8_decode($Red->Observacion_Enlace.$this->GetY()),0,1,'C',true);
                    $this->Ln(7);
    
                    $y=$this->GetY();
    
                    if($Red->Foto_Nombre!=null && $Red->Foto_Nombre!='SD' && $Red->Foto_Nombre!=''){
    
                        $width = 65;
                        $height = 70;
    
                        $aux=$this->revisaFotoY($this->GetY());
                        $this->SetY($aux);
                        $y=$this->GetY();
    
                        $filenameUbicacion = base_url."public/files/Entrevistas/" . $GLOBALS['PDFId_Persona_Entrevista']  . "/Redes_Sociales/".$Red->Foto_Nombre;
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
                                $nombre="Red".rand().".jpeg";
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
                                $nombre="Red".rand().".png";
                                imagepng($image,$nombre);
                                $imagennueva=base_url."public/".$nombre;
                                $this->Image($imagennueva,65, $y+1, $width, $height, $extension);
                                imagedestroy($image);
                                unlink($nombre);
                            break;
                        } 
                        $this->Ln(75);
                    }else{
                        $this->Ln(2);
                    }
                }
            }
        }
       

    }
    public function buscahijos($Id_dato,$Tipo_Relacion,$padre){
        $hijoUbicacion = [];
        $hijoRed = [];
        $hijoForensia = [];

        global $UbicacionesGB;
        $ubicaciones = $UbicacionesGB;
        if($ubicaciones!=[]){
            $j=0;
            $auxCount = count($ubicaciones);
            for($i=0; $i<$auxCount; $i++){
                if($ubicaciones[$i]->Id_Dato==$Id_dato && $ubicaciones[$i]->Tipo_Relacion == $Tipo_Relacion){
                    $hijoUbicacion[$j] = $ubicaciones[$i];
                    unset($ubicaciones[$i]);
                    $j++;
                }
            }
            $ubicaciones = array_values($ubicaciones);
            $this->agregarUbicaciones($ubicaciones);
        }
        
        global $RedesGB;
        $redes = $RedesGB;
        if($redes!=[]){
            $j=0;
            $auxCount = count($redes);
            for($i=0; $i<$auxCount; $i++){
                if($redes[$i]->Id_Dato==$Id_dato && $redes[$i]->Tipo_Relacion == $Tipo_Relacion){
                    $hijoRed[$j] = $redes[$i];
                    unset($redes[$i]);
                    $j++;
                }
            }
            $redes = array_values($redes);
            $this->agregarRedes($redes);
        }

        global $ForensiasGB;
        $forensias = $ForensiasGB;

        if($forensias!=[]){
            $j=0;
            $auxCount =count($forensias);
            for($i=0; $i< $auxCount; $i++){
                if($forensias[$i]->Id_Dato==$Id_dato && $forensias[$i]->Tipo_Relacion == $Tipo_Relacion){
                    $hijoForensia[$j] = $forensias[$i];
                    unset($forensias[$i]);
                    $j++;
                }
            }
            $forensias = array_values($forensias);
            $this->agregarForensias($forensias);

        }
        if($hijoForensia!=[]){
            $forensias = $hijoForensia;
            $i=1;

            foreach ($forensias as $forensia){

                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);
                
                $this->SetTextColor(0, 0, 0);
                $this->SetFont('helvetica','',11);
                $this->SetFillColor(156,156,156);

                $this->Cell(190, 4, utf8_decode($forensia->clavePDF.' RELACIONADO A '.$padre),0,0,'C',true);
                $this->ln(4);
        
                $this->Cell(155, 4, '');
                $this->SetFont('Avenir','',11);
                $this->ln(2);
                $width = 75;
                $height = 65;
                $y=$this->GetY();
                if($forensia->Foto!=null && $forensia->Foto!='SD' && $forensia->Foto!=''){

                    $filenameForensia = base_url."public/files/Entrevistas/" . $GLOBALS['PDFId_Persona_Entrevista']  . "/ForensiasRelevantes/".$forensia->Foto;
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
                $this->Cell(130, 7, utf8_decode('DESCRIPCION: '));
                $this->Ln(7);
                $this->SetFont('helvetica','',9);
                $this->SetFillColor(236,236,236);
                $this->MultiCell(115,7, utf8_decode($forensia->Descripcion_Forensia),0,1,'C',true);
                $this->Ln(3);
                if($forensia->Tipo_Dato!='SD'){
                    $this->SetTextColor(255, 0, 0);
                    $this->SetFont('helvetica','B',11);
                    $this->Cell(130, 7, utf8_decode('RELEVANTE ('.$forensia->Tipo_Dato.'):'));
                    $this->Ln(7);
                    $this->SetTextColor(0, 0, 0);
                    $this->SetFont('helvetica','',9);
                    $this->SetFillColor(236,236,236);
                    $this->MultiCell(115,7, utf8_decode($forensia->Dato_Relevante),0,1,'C',true);
                    $this->Ln(3);
                }
                if($forensia->Foto!=null&&$forensia->Foto!='SD'&&$forensia->Foto!=''){//solo si existe fot realiza esta funcion
                    $y1=$this->GetY();
                    $operacion=$y-$y1;
                    $diferencia=abs($operacion);
                    if($diferencia<65){ 
                        $y2=$y+65-$y1;
                        $this->Ln($y2+5);
                    } 
                }
                $this->buscahijos($forensia->Id_Forensia_Entrevista ,'DATO',$forensia->clavePDF);
                $i++;
            }
        }
        if($hijoUbicacion!=[]){
            $i=1;
            $domicilios = $hijoUbicacion;
            foreach ($domicilios as $domicilio){

                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);

                $this->SetFont('helvetica','',11);
                $this->SetTextColor(0, 0, 0);
                $this->SetFillColor(156,156,156);
                $this->Cell(190, 4, utf8_decode($domicilio->clavePDF.' RELACIONADO A '.$padre),0,0,'C',true);
                $this->ln(4);
                $this->Cell(155, 4, '');
                $this->SetFont('helvetica','B',11);
                $this->ln(2);
                $this->Cell(130, 7, utf8_decode('DIRECCIÓN DE LA UBICACIÓN:'));
                $this->Ln(7);
                $cadena = str_replace('CALLE ','',$domicilio->Calle);///POR SI SE REPITE LA ETIQUETA CON EL CONTENIDO DE LA INFORMACION
                $domicilioCompleto='CALLE: '.$cadena;

                $cadena = str_replace('CALLE ','',$domicilio->Calle2);
                $domicilioCompleto .= ($domicilio->Calle2!='SD') ? ', CALLE 2: '.$cadena: '';
                $domicilioCompleto .=', COLONIA: '.$domicilio->Colonia;
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


                $y=$this->GetY();
                if($domicilio->Foto!=null && $domicilio->Foto!='SD' && $domicilio->Foto!=''){

                    $width = 65;
                    $height = 70;

                    $aux=$this->revisaFotoY($this->GetY());
                    $this->SetY($aux);
                    $y=$this->GetY();

                    $filenameUbicacion = base_url."public/files/Entrevistas/" . $GLOBALS['PDFId_Persona_Entrevista']  . "/UbicacionesRelevantes/".$domicilio->Foto;
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
                    $this->Ln(75);
                }else{
                    $this->Ln(2);
                }
                $i++;
            }
        }
        if($hijoRed!=[]){
            $i=1;
            $Redes_Sociales = $hijoRed;
            foreach ($Redes_Sociales as $Red){

                $aux=$this->revisaY($this->GetY());
                $this->SetY($aux);

                $this->SetFont('helvetica','',11);
                $this->SetTextColor(0, 0, 0);
                $this->SetFillColor(156,156,156);
                $this->Cell(190, 4, utf8_decode($Red->clavePDF.' ('.$Red->Tipo_Enlace.') RELACIONADO A '.$padre),0,0,'C',true);
                $this->ln(4);
                $this->Cell(155, 4, '');
                $this->SetFont('helvetica','B',11);
                $this->ln(2);
                $this->Cell(130, 7, utf8_decode('USUARIO: '));
                $this->Ln(7);
                $this->SetFont('helvetica','',9);
                $this->SetFillColor(236,236,236);
                $this->MultiCell(190,7, utf8_decode($Red->Usuario),0,1,'C',true);
                $this->SetFont('helvetica','B',11);
                $this->Cell(130, 7, utf8_decode('ENLACE: '));
                $this->Ln(7);
                $this->SetFont('helvetica','',9);
                $this->SetFillColor(236,236,236);
                $this->MultiCell(190,7, utf8_decode($Red->Enlace),0,1,'C',true);
                $this->SetFont('helvetica','B',11);
                $this->Cell(130, 7, utf8_decode('OBSERVACIÓN DE ENLACE: '));
                $this->Ln(7);
                $this->SetFont('helvetica','',9);
                $this->SetFillColor(236,236,236);
                $this->MultiCell(190,7, utf8_decode($Red->Observacion_Enlace.$this->GetY()),0,1,'C',true);
                $this->Ln(7);

                $y=$this->GetY();

                if($Red->Foto_Nombre!=null && $Red->Foto_Nombre!='SD' && $Red->Foto_Nombre!=''){

                    $width = 65;
                    $height = 70;

                    $aux=$this->revisaFotoY($this->GetY());
                    $this->SetY($aux);
                    $y=$this->GetY();

                    $filenameUbicacion = base_url."public/files/Entrevistas/" . $GLOBALS['PDFId_Persona_Entrevista']  . "/Redes_Sociales/".$Red->Foto_Nombre;
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
                            $nombre="Red".rand().".jpeg";
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
                            $nombre="Red".rand().".png";
                            imagepng($image,$nombre);
                            $imagennueva=base_url."public/".$nombre;
                            $this->Image($imagennueva,65, $y+1, $width, $height, $extension);
                            imagedestroy($image);
                            unlink($nombre);
                        break;
                    } 
                    $this->Ln(75);
                }else{
                    $this->Ln(2);
                }
                $i++;
            }
        }

    }
    function DatosTareas($tareas){
        if($tareas!=null && $tareas!=[]){
            $aux = $this->revisaYEvento($this->GetY());
            $this->SetY($aux);
            $this->SetFont('Avenir','',11);
            foreach ($tareas as $element) {
                if (!empty($element['Principales'])) {
                    $actualizaciones = $element['Principales'];
                    foreach ($actualizaciones as $dato) {
                        //print_r($dato);
                        $this->SetTextColor(51, 51, 51);
                        $this->SetFillColor(156,156,156); 
                        $this->SetFont('helvetica','B',11);
                        $this->Cell(190, 4, utf8_decode('REPORTE ZEN ('.$element['Tipo'].')'),0,0,'C',true);
                        $this->SetFont('helvetica','',11);
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
    function revisaYEvento($y){
        if($y<275&&$y>250){
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
    function revisaY($y){
        if($y>225){///si se encuentra en el rango
            $this->AddPage();
            return 32;
        }else{
            return $y;
        }
    }
    function revisaFotoY($y){
        if($y>=210){
            $this->AddPage();
            return 32;
        }else{
            return $y;
        }
    }
    function Header(){

        
        $this->SetFont('Avenir','',11);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(135, 4);

        $this->Cell(33,  4, utf8_decode("FOLIO PERSONA ENTREVISTADA:"), 0, 0, 'R');
        $this->SetTextColor(128, 128, 128);
        $this->Cell(20,  4,$GLOBALS['PDFId_Persona_Entrevista'] , '', 1, 'C');
        $this->Ln(1);
    
        
        $this->Cell(99, 4);
        $this->SetFont('Avenir','',5);
        $this->SetTextColor(256, 0, 0);
        $this->Cell(26, 4, utf8_decode("DOCUMENTO CONFIDENCIAL:"), 0, 0, 'L');
        $this->SetTextColor(51, 51,51);
        $this->Cell(33, 4, utf8_decode("INFORMACIÓN SENSIBLE. NO COMPARTIR SIN PREVIA AUTORIZACIÓN"), 0, 0, 'L');

        $this->Ln(11);
        $this->SetFont('Avenir','',11);
    }
    function Footer(){
        $this->SetFont('Avenir','',9);
        $this->SetY(-8);
        $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'R');
    }
}

$pdf = new PDF();
$pdf->AddFont('Avenir','','avenir.php');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->PagePersonaEntrevistada($data);
if($data['Entrevistas']!=[]||$data['Ubicaciones']!=[]||$data['Forensias']!=[]||$data['Redes_Sociales']!=[]){

    for($i=0;$i<count($data['Forensias']);$i++){
        $data['Forensias'][$i]->clavePDF = (string)'DATO '.($i+1);
    }
    for($i=0;$i<count($data['Ubicaciones']);$i++){
        $data['Ubicaciones'][$i]->clavePDF = (string)'UBICACION '.($i+1);
    }
    for($i=0;$i<count($data['Redes_Sociales']);$i++){
        $data['Redes_Sociales'][$i]->clavePDF = (string)'RED SOCIAL '.($i+1);
    }
    
    $pdf->agregarEntrevistas($data['Entrevistas']);
    $pdf->agregarForensias($data['Forensias']);
    $pdf->agregarUbicaciones($data['Ubicaciones']);
    $pdf->agregarRedes($data['Redes_Sociales']);
    $pdf->AddPage();
    $pdf->DatosRelevantesPersona();
    $pdf->DatosTareas($data['tareas']);

}

$pdf->Output();
?>