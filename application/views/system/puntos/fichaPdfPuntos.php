
<?php
//print_r($data);
error_reporting(E_ALL & ~E_WARNING );
global $PDFId_Punto;
$PDFId_Punto = $data['principales']->Id_Punto;
class PDF extends FPDF{

    function Header(){
        $this->SetFont('Avenir','',11);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(135, 4);

        $this->Cell(33,  4, utf8_decode("FOLIO AURA PUNTO:"), 0, 0, 'R');
        $this->SetTextColor(128, 128, 128);
        $this->Cell(20,  4,$GLOBALS['PDFId_Punto'] , '', 1, 'C');
        $this->Ln(1);
    
        $this->Cell(99, 4);
        $this->SetFont('Avenir','',5);
        $this->SetTextColor(256, 0, 0);
        $this->Cell(26, 4, utf8_decode("DOCUMENTO CONFIDENCIAL:"), 0, 0, 'L');
        $this->SetTextColor(51, 51,51);
        $this->Cell(33, 4, utf8_decode("INFORMACIÓN SENSIBLE. NO COMPARTIR SIN PREVIA AUTORIZACIÓN"), 0, 0, 'L');

        $this->Ln(3);
        $this->SetFont('Avenir','',11);
    }
    function Footer(){
        $this->SetFont('Avenir','',9);
        $this->SetY(-8);
        $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'R');
    }
    function PagePuntos($data){
        $this->SetFont('Avenir','',11);
        $this->SetTextColor(255, 255, 255);
        $this->SetFillColor(41,41,95); 
        $this->Cell(190, 5, utf8_decode('INFORMACIÓN RELEVANTE PUNTO DE DISTRIBUCIÓN'), 0, 0, 'C',true);
        $this->ln(10);
        $width = 95;
        $height = 105;
        $y = $this->GetY();
        if($data['principales']->Path_Img_Google != null && $data['principales']->Path_Img_Google != 'SD' && $data['principales']->Path_Img_Google != ''){
            $filename = base_url."public/files/Puntos/" . $data['principales']->Id_Punto . "/".$data['principales']->Path_Img_Google;
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
                    $nombre="punto".rand().".jpeg";
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
                    $nombre="punto".rand().".png";
                    imagepng($image,$nombre);
                    $imagennueva=base_url."public/".$nombre;
                    $this->Image($imagennueva,10, $y, $width-2, $height, $extension);
                    imagedestroy($image);
                    unlink($nombre);
                break;
            } 
        }else{
            $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
            $imagennueva=base_url."public/media/icons/ubicacion.png";
            $this->Image($imagennueva,10, $y, $width-2, $height, $extension);
        }
        $this->Ln(3);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(53, 4, utf8_decode('FUENTE DE INFORMACIÓN:'));
        $this->SetTextColor(128, 128, 128);
        $this->Cell(60, 4, utf8_decode($data['principales']->Fuente_Info));

        $this->ln(7);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(190, 5, utf8_decode('UBICACION DEL PUNTO:'));
        $this->ln(5);
        $this->Cell($width, 4);
        $ubicacion="";
        $ubicacion = ($data['principales']->Calle!='SD'&&$data['principales']->Calle!="") ? 'CALLE: '.$data['principales']->Calle: '';
        $ubicacion .= ($data['principales']->Calle2!='SD'&&$data['principales']->Calle2!="") ? ', CALLE2: '.$data['principales']->Calle2: '';
        $ubicacion .=', COLONIA: '.$data['principales']->Colonia;
        $ubicacion .= ($data['principales']->NoExt!='SD'&&$data['principales']->NoExt!=""&&$data['principales']->NoExt!="null"&&$data['principales']->NoExt!="undefined") ? ', NOEXT: '.$data['principales']->NoExt: '';
        $this->SetTextColor(128, 128, 128);
        $this->MultiCell(90, 4, utf8_decode($ubicacion));

        $this->ln(3);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(35, 4, utf8_decode('COORDENADA Y:'));
        $this->SetTextColor(128, 128, 128);
        $this->Cell(55, 4, utf8_decode($data['principales']->CoordY));

        $this->ln(7);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(35, 4, utf8_decode('COORDENADA X:'));
        $this->SetTextColor(128, 128, 128);
        $this->Cell(55, 4, utf8_decode($data['principales']->CoordX));

        $this->ln(7);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(14, 4, utf8_decode('ZONA:'));
        $this->SetTextColor(128, 128, 128);
        $this->Cell(40, 4, utf8_decode($data['principales']->Zona));
        $this->SetTextColor(51, 51, 51);
        $this->Cell(18, 4, utf8_decode('VECTOR:'));
        $this->SetTextColor(128, 128, 128);
        $this->Cell(15, 4, utf8_decode($data['principales']->Vector));

        $this->ln(7);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(42, 4, utf8_decode('ESTATUS DEL PUNTO:'));
        $this->SetTextColor(128, 128, 128);
        $this->Cell(55, 4, utf8_decode($data['principales']->Estatus_Punto));

        $this->ln(7);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(33, 4, utf8_decode('IDENTIFICADOR:'));
        $this->SetTextColor(128, 128, 128);
        $this->Cell(55, 4, utf8_decode($data['principales']->Identificador));

        $this->ln(7);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(190, 5, utf8_decode('DESCRIPCION ADICIONAL:'));
        $this->ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(128, 128, 128);
        $this->MultiCell(90, 4, utf8_decode($data['principales']->Descripcion_Adicional));
        
        $this->ln(3);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(35, 4, utf8_decode('NOMBRE O ALIAS DEL DISTRIBUIDOR:'));
        $this->ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(55, 4, utf8_decode($data['principales']->Distribuidor));

        $this->ln(7);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(190, 5, utf8_decode('ENLACE DE UBICACION:'));
        $this->ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(0, 0, 255);
        $this->MultiCell(90, 4, $data['principales']->Enlace_Google);
        $y = $this->GetY();
        if($y<140){
            $this->SetY(140);
        }else{
            $this->SetY($y);
        }

        $this->SetTextColor(51, 51, 51);
        $this->Cell(100, 4, utf8_decode('FECHA OBTENCION DE INFORMACIÓN DEL PUNTO:'));
        $fechalimpia=$this->formatearFecha($data['principales']->Fecha_Punto.' 00:00:00');
        $this->SetTextColor(128, 128, 128);
        $this->Cell(20, 4, utf8_decode($fechalimpia));
        $this->ln(7);

        if($data['principales']->Nombre_Detenido != null && $data['principales']->Nombre_Detenido != 'SD' && $data['principales']->Nombre_Detenido != '' && $data['principales']->Fuente_Info == 'DETENIDO'){
            $this->SetTextColor(51, 51, 51);
            $this->Cell(55, 4, utf8_decode('NOMBRE DEL DETENIDO:'));
          
            $this->SetTextColor(255, 0, 0);
            $this->Cell(55, 4, utf8_decode($data['principales']->Nombre_Detenido));

            $this->ln(7);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(190, 5, utf8_decode('NARRATIVA DEL DETENIDO:'));
            $this->ln(5);
            $this->SetTextColor(128, 128, 128);
            $auxNarrativa = str_replace("\n", " ", $data['principales']->Narrativa);
            $this->MultiCell(190, 4, utf8_decode($auxNarrativa));

        }

        if($data['principales']->Atendido_Por != null && $data['principales']->Atendido_Por != 'SD' && $data['principales']->Atendido_Por != '' && $data['principales']->Atendido_Por != 'NULL'){
            $this->ln(3);
            $this->SetTextColor(51, 51, 51);
            $this->Cell(55, 4, utf8_decode('ELEMENTO QUE ATENDIO:'));
          
            $this->SetTextColor(255, 0, 0);
            $this->Cell(55, 4, utf8_decode($data['principales']->Atendido_Por));

            $this->ln(9);
        }else{
            $this->ln(3); 
        }


        if($data['principales']->Path_Img != null && $data['principales']->Path_Img != 'SD' && $data['principales']->Path_Img != ''){
            $aux = $this->revisaY($this->GetY());
            $this->SetY($aux);
            $width = 100;
            $height = 55;
            $y = $this->GetY();
            
            $this->SetTextColor(51, 51, 51);
            $this->Cell(55, 4, utf8_decode('IMAGEN ATENDIDA:'));
            $filename = base_url."public/files/Puntos/" . $data['principales']->Id_Punto . "/".$data['principales']->Path_Img;
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
                    $nombre="punto".rand().".jpeg";
                    imagejpeg($image,$nombre);
                    $imagennueva=base_url."public/".$nombre;
                    $this->Image($imagennueva,60, $y, $width-2, $height, $extension);
                    imagedestroy($image);
                    unlink($nombre);
                break;
                case 3:
                    $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                    $image = imagecreatefrompng($filename);
                    imageinterlace($image, false);
                    $nombre="punto".rand().".png";
                    imagepng($image,$nombre);
                    $imagennueva=base_url."public/".$nombre;
                    $this->Image($imagennueva,60, $y, $width-2, $height, $extension);
                    imagedestroy($image);
                    unlink($nombre);
                break;
            } 
            $this->SetY($y+55);
        }else{
            $this->ln(3); 
        }
        


        if($data['datos']!=null && $data['datos']!=[]){

            $aux = $this->revisaY($this->GetY());
            $this->SetY($aux);

            $datos = $data['datos'];
            $this->SetTextColor(51, 51, 51);
            $this->SetFillColor(156,156,156);
            $this->Cell(190, 4, utf8_decode('DATOS RELACIONADOS AL PUNTO'),0,0,'C',true);
            $this->ln(6);
            $i=1;
            foreach($datos as $dato){

                $aux = $this->revisaY($this->GetY());
                $this->SetY($aux);
                $this->Ln(3);
                    
                $this->SetTextColor(51, 51, 51);
                $this->Cell(30, 4, utf8_decode('TIPO DE DATO:'));
                $this->SetTextColor(128, 128, 128);
                $this->Cell(60, 4, utf8_decode($dato->Tipo_Dato));

                $this->ln(7);
                
                $this->SetTextColor(51, 51, 51);
                $this->Cell(53, 4, utf8_decode('DESCRIPCION DEL DATO:'));
                $this->ln(5);
                
                $this->SetTextColor(128, 128, 128);
                $Descripcion_Dato = str_replace("\n", " ", $dato->Descripcion_Dato);
                $this->MultiCell(190, 4, utf8_decode($Descripcion_Dato));
                $this->ln(3);
                if($dato->Path_Imagen_Dato != null && $dato->Path_Imagen_Dato != 'SD' && $dato->Path_Imagen_Dato != ''){
                    $width = 100;
                    $height = 55;
                    $y =  $this->revisaYFoto($this->GetY());
                    $this->SetY($y);
                    
                    $filename = base_url."public/files/Puntos/" . $dato->Id_Punto . "/Datos/".$dato->Path_Imagen_Dato;
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
                            $nombre="datopunto".rand().".jpeg";
                            imagejpeg($image,$nombre);
                            $imagennueva=base_url."public/".$nombre;
                            $this->Image($imagennueva,60, $y, $width-2, $height, $extension);
                            imagedestroy($image);
                            unlink($nombre);
                        break;
                        case 3:
                            $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
                            $image = imagecreatefrompng($filename);
                            imageinterlace($image, false);
                            $nombre="datopunto".rand().".png";
                            imagepng($image,$nombre);
                            $imagennueva=base_url."public/".$nombre;
                            $this->Image($imagennueva,60, $y, $width-2, $height, $extension);
                            imagedestroy($image);
                            unlink($nombre);
                        break;
                    } 
                    
                    $this->SetY($y+65);
                }

            }

        }
        
    } 

    function revisaY($y){
        if($y>235){///si se encuentra en el rango
            $this->AddPage();
            return 19;
        }else{
            return $y;
        }
    }
    function revisaYFoto($y){
        if($y>240){///si se encuentra en el rango
            $this->AddPage();
            return 19;
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
        $results =$day_of_the_week.strftime(", %d  de ", strtotime($fecha)).$mes. strftime(" del %G", strtotime($fecha));
        return strtoupper($results);
    }  
}
$pdf = new PDF();
$pdf->AddFont('Avenir','','avenir.php');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->PagePuntos($data);
$pdf->Output();

?>