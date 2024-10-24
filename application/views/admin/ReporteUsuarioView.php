<?php
///print_r($data);
error_reporting(E_ALL & ~E_WARNING );
global $PDFId_Usuario;
$PDFId_Usuario=$data['Numeros']['Id_Usuario'];
class PDF extends FPDF{

    function revisaY($y){
        if($y<275 && $y>225){
            $this->AddPage();
            return 32;
        }else{
            return $y;
        }
    }
    function Estadistica($Usuario){
        $width = 80;
        $height = 90;
        $y=$this->GetY();
        if($Usuario['Path_Imagen_User']!=null && $Usuario['Path_Imagen_User']!='SD' && $Usuario['Path_Imagen_User']!=''){
            $filename = base_url."public/media/users_img/" .$GLOBALS['PDFId_Usuario']."/".$Usuario['Path_Imagen_User'];
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
                    $nombre="usuario".rand().".jpeg";
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
                    $nombre="usuario".rand().".png";
                    imagepng($image,$nombre);
                    $imagennueva=base_url."public/".$nombre;
                    $this->Image($imagennueva,10, $y, $width, $height, $extension);
                    imagedestroy($image);
                    unlink($nombre);
                break;
            } 
        }else{
            $extension = 'png';//Por si tiene interlancia la imagen genera un archivo temporal png
            $imagennueva=base_url."public/media/images/default.png";
            $this->Image($imagennueva,10, $y, $width, $height, $extension);
        }

        $width=$width+5;
        $this->Ln(25);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(21, 4, utf8_decode('USUARIO:'));

        $this->SetTextColor(128, 128, 128);
        $this->MultiCell(90, 4, utf8_decode($Usuario['User_Name']), 0, 'J');

        $this->Ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(21, 4, utf8_decode('NOMBRE COMPLETO:'));

        $this->Ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(128, 128, 128);
        $this->MultiCell(90, 4, utf8_decode($Usuario['Nombre_Completo']), 0, 'J');

        $this->Ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(13, 4, utf8_decode('AREA:'));

        $this->SetTextColor(128, 128, 128);
        $this->MultiCell(90, 4, utf8_decode($Usuario['Area']), 0, 'J');
        


        $FECHA = $this->formatearFecha($Usuario['Fecha_Registro_Usuario']);

        $this->Ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(51, 51, 51);
        $this->Cell(21, 4, utf8_decode('FECHA DE REGISTRO:'));

        $this->Ln(5);
        $this->Cell($width, 4);
        $this->SetTextColor(128, 128, 128);
        $this->MultiCell(100, 4, utf8_decode($FECHA), 0, 'J');
        $this->SetY($y+95);
        if($Usuario[1]['Eventos Asignados']>0){
            //Pie chart
            $this->MultiCell(0, 3, 'ASIGNACION DE EVENTOS (FECHA DE EVENTOS APARTIR DEL 01 DE ENERO DEL 2024)');
            $this->Ln(10);
            $valX = $this->GetX();
            $valY = $this->GetY();
            $this->Cell(56, 5, 'Numero de Eventos Asignados:');
            $this->Cell(15, 5, $Usuario[1]['Eventos Asignados'], 0, 0, 'J');
            $this->Ln();
            $this->Cell(87, 5, 'Numero de Eventos con Seguimiento Terminado:');
            $this->Cell(15, 5, $Usuario[1]['Eventos con Seguimiento Terminado'], 0, 0, 'J');
            $this->Ln();
            $this->Ln(8);
            $this->SetXY(140, $valY+25);
            $col1 = array(15,33,64);
            $col3 = array(246,26,132);
            $this->PieChart(70, 1, $Usuario[1], '%l (%p)', array($col1,$col3));
            $this->SetXY($valX, $valY + 25);
            $this->SetFont('Avenir','',11);
        }
        //Bar diagram
        $this->Ln(10);
        if (isset($Usuario['FechaInicio']) && isset($Usuario['FechaFin'])) { 
            $FECHAINICIO = $this->formatoFecha($Usuario['FechaInicio']);
            $FECHAFIN = $this->formatoFecha($Usuario['FechaFin']);
            $Cad_Fecha = "PERIODO ".$FECHAINICIO." AL ".$FECHAFIN;
        }else{
            $Cad_Fecha = "GENERAL";
        }
        $this->Cell(0, 5, utf8_decode('ACTIVIDAD DEL USUARIO '.$Cad_Fecha), 0, 1);
        $this->Ln(1);
        $valX = $this->GetX();
        $valY = $this->GetY();
        $this->BarDiagram(190, 70, $Usuario[0], '%l : %v (%p)', array(255,0,0));
        $this->SetXY($valX, $valY + 80);
    }
    var $legends;
    var $wLegend;
    var $sum;
    var $NbVal;
    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
	{
		$d0 = $a - $b;
		if($cw){
			$d = $b;
			$b = $o - $a;
			$a = $o - $d;
		}else{
			$b += $o;
			$a += $o;
		}
		while($a<0)
			$a += 360;
		while($a>360)
			$a -= 360;
		while($b<0)
			$b += 360;
		while($b>360)
			$b -= 360;
		if ($a > $b)
			$b += 360;
		$b = $b/360*2*M_PI;
		$a = $a/360*2*M_PI;
		$d = $b - $a;
		if ($d == 0 && $d0 != 0)
			$d = 2*M_PI;
		$k = $this->k;
		$hp = $this->h;
		if (sin($d/2))
			$MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
		else
			$MyArc = 0;
		//first put the center
		$this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
		//put the first point
		$this->_out(sprintf('%.2F %.2F l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
		//draw the arc
		if ($d < M_PI/2){
			$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
						$yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
						$xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
						$yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
						$xc+$r*cos($b),
						$yc-$r*sin($b)
						);
		}else{
			$b = $a + $d/4;
			$MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
			$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
						$yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
						$xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
						$yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
						$xc+$r*cos($b),
						$yc-$r*sin($b)
						);
			$a = $b;
			$b = $a + $d/4;
			$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
						$yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
						$xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
						$yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
						$xc+$r*cos($b),
						$yc-$r*sin($b)
						);
			$a = $b;
			$b = $a + $d/4;
			$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
						$yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
						$xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
						$yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
						$xc+$r*cos($b),
						$yc-$r*sin($b)
						);
			$a = $b;
			$b = $a + $d/4;
			$this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
						$yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
						$xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
						$yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
						$xc+$r*cos($b),
						$yc-$r*sin($b)
						);
		}
		//terminate drawing
		if($style=='F')
			$op='f';
		elseif($style=='FD' || $style=='DF')
			$op='b';
		else
			$op='s';
		$this->_out($op);
	}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3 )
	{
		$h = $this->h;
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
			$x1*$this->k,
			($h-$y1)*$this->k,
			$x2*$this->k,
			($h-$y2)*$this->k,
			$x3*$this->k,
			($h-$y3)*$this->k));
	}

    function PieChart($w, $h, $data, $format, $colors=null)
    {
        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data,$format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 3;
        $hLegend = 3;
        $radius = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
        $radius = floor($radius / 2);
        $XDiag = $XPage + $margin + $radius;
        $YDiag = $YPage + $margin + $radius;
        if($colors == null) {
            for($i = 0; $i < $this->NbVal; $i++) {
                $gray = $i * intval(255 / $this->NbVal);
                $colors[$i] = array($gray,$gray,$gray);
            }
        }

        //Sectors
        $this->SetLineWidth(0.2);
        $angleStart = 0;
        $angleEnd = 0;
        $i = 0;
        foreach($data as $val) {
            $angle = ($val * 360) / doubleval($this->sum);
            if ($angle != 0) {
                $angleEnd = $angleStart + $angle;
                $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
                $this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
                $angleStart += $angle;
            }
            $i++;
        }

        //Legends
        $this->SetFont('Courier', '', 7);
        $x1 = $XPage + 2 * $radius + 4 * $margin;
        $x2 = $x1 + $hLegend + $margin;
        $y1 = $YDiag - $radius + (2 * $radius - $this->NbVal*($hLegend + $margin)) / 2;
        for($i=0; $i<$this->NbVal; $i++) {
            $this->SetFillColor($colors[$i][0],$colors[$i][1],$colors[$i][2]);
            $this->Rect($x1+26, $y1, $hLegend, $hLegend, 'DF');
            $this->SetXY($x2+23,$y1);
            $this->SetTextColor(255, 0, 0);
            $this->Cell(0,$hLegend,$this->legends[$i]);
            $y1+=$hLegend + $margin;
        }
        $this->SetTextColor(128, 128, 128);
    }

    function BarDiagram($w, $h, $data, $format, $color=null, $maxVal=0, $nbDiv=4)
    {
        $this->SetFont('Courier', '', 10);
        $this->SetLegends($data,$format);

        $XPage = $this->GetX();
        $YPage = $this->GetY();
        $margin = 2;
        $YDiag = $YPage + $margin;
        $hDiag = floor($h - $margin * 2);
        $XDiag = $XPage + $margin * 2 + $this->wLegend;
        $lDiag = floor($w - $margin * 3 - $this->wLegend);
        if($color == null)
            $color=array(155,155,155);
        if ($maxVal == 0) {
            $maxVal = max($data);
        }
        $valIndRepere = ceil($maxVal / $nbDiv);
        $maxVal = $valIndRepere * $nbDiv;
        $lRepere = floor($lDiag / $nbDiv);
        $lDiag = $lRepere * $nbDiv;
        $unit = $lDiag / $maxVal;
        $hBar = floor($hDiag / ($this->NbVal + 1));
        $hDiag = $hBar * ($this->NbVal + 1);
        $eBaton = floor($hBar * 80 / 100);

        $this->SetLineWidth(0.2);
        $this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

        $this->SetFont('Courier', '', 10);
        $this->SetFillColor($color[0],$color[1],$color[2]);
        $i=0;
        foreach($data as $val) {
            //Bar
            $xval = $XDiag;
            $lval = (int)($val * $unit);
            $yval = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
            $hval = $eBaton;
            $this->Rect($xval, $yval, $lval, $hval, 'DF');
            //Legend
            $this->SetXY(0, $yval);
            $this->Cell($xval - $margin, $hval, $this->legends[$i],0,0,'R');
            $i++;
        }

        //Scales
        for ($i = 0; $i <= $nbDiv; $i++) {
            $xpos = $XDiag + $lRepere * $i;
            $this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
            $val = $i * $valIndRepere;
            $xpos = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
            $ypos = $YDiag + $hDiag - $margin;
            $this->Text($xpos, $ypos, $val);
        }
    }

    function SetLegends($data, $format)
    {
        $this->legends=array();
        $this->wLegend=0;
        $this->sum=array_sum($data);
        $this->NbVal=count($data);
        foreach($data as $l=>$val)
        {
            $p=sprintf('%.2f',$val/$this->sum*100).'%';
            $legend=str_replace(array('%l','%v','%p'),array($l,$val,$p),$format);
            $this->legends[]=$legend;
            $this->wLegend=max($this->GetStringWidth($legend),$this->wLegend);
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
        $this->Cell(33,  4, utf8_decode("ID USUARIO:"), 0, 0, 'R');
        $this->SetTextColor(128, 128, 128);
        $this->Cell(20,  4,$GLOBALS['PDFId_Usuario'] , '', 1, 'C');
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
        $this->Ln(8);
        $this->SetFont('Avenir','',11);
    }
    function Footer(){
        $this->SetY(-8);
        $this->SetFont('Avenir','',7);
        $this->Cell(0,10,utf8_decode('SISTEMA AURA USUARIO '),0,0,'C');
        $this->Cell(0,10,$this->PageNo().'/{nb}',0,0,'R');
    }
    public function formatoFecha($fecha = null){//genera un formato de fecha para la vista de informacion de usuario
		setlocale(LC_TIME, 'es_CO.UTF-8');
		$MESNUM=strftime("%m", strtotime($fecha));
		$mes="";
		switch ($MESNUM) {
            case '1':
                $mes="ENERO";
                break;
            case '2':
                $mes="FEBRERO";
                break;
            case '3':
                $mes="MARZO";
                break;
            case '4':
				$mes="ABRIL";
                break;
            case '5':
                $mes="MAYO";
                break;
            case '6':
                $mes="JUNIO";
                break;
            case '7':
                $mes="JULIO";
                break;
			case '8':
				$mes="AGOSTO";
				break;
			case '9':
				$mes="SEPTIEMBRE";
				break;
			case '10':
				$mes="OCTUBRE";
				break;			
			case '11':
				$mes="NOVIEMBRE";
				break;
			case '12':
				$mes="DICIEMBRE";
				break;
        }
		$results =strftime("%d DE ", strtotime($fecha)).$mes. strftime(" DEL %G", strtotime($fecha));
		return  strtoupper($results);
	}
    public function formatearFecha($fecha = null){//genera un formato de fecha para la vista de informacion de usuario
		setlocale(LC_TIME, 'es_CO.UTF-8');
        $DIA_INGLES=strftime("%A", strtotime($fecha));
        switch ($DIA_INGLES) {
            case 'Monday':
                $day_of_the_week = 'LUNES';
                break;
            case 'Tuesday':
                $day_of_the_week = 'MARTES';
                break;
            case 'Wednesday':
                $day_of_the_week = 'MIERCOLES';
                break;
            case 'Thursday':
                $day_of_the_week = 'JUEVES';
                break;
            case 'Friday':
                $day_of_the_week = 'VIERNES';
                break;
            case 'Saturday':
                $day_of_the_week = 'SABADO';
                break;
            case 'Sunday':
                $day_of_the_week = 'DOMINGO';
                break;
        }
		$MESNUM=strftime("%m", strtotime($fecha));
		$mes="";
		switch ($MESNUM) {
            case '1':
                $mes="ENERO";
                break;
            case '2':
                $mes="FEBRERO";
                break;
            case '3':
                $mes="MARZO";
                break;
            case '4':
				$mes="ABRIL";
                break;
            case '5':
                $mes="MAYO";
                break;
            case '6':
                $mes="JUNIO";
                break;
            case '7':
                $mes="JULIO";
                break;
			case '8':
				$mes="AGOSTO";
				break;
			case '9':
				$mes="SEPTIEMBRE";
				break;
			case '10':
				$mes="OCTUBRE";
				break;			
			case '11':
				$mes="NOVIEMBRE";
				break;
			case '12':
				$mes="DICIEMBRE";
				break;
        }
		$results =$day_of_the_week.strftime(", %d DE ", strtotime($fecha)).$mes. strftime(" DE %G", strtotime($fecha))." A LAS ".date('g:i a', strtotime($fecha));;
		return  strtoupper($results);
	}
}
$pdf = new PDF();
$pdf->AddFont('Avenir','','avenir.php');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->Estadistica($data['Numeros']);

$pdf->Output();
?>