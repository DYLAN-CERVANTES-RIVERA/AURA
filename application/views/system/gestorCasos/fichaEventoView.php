<?php
    /*VISTA PARA LA EXPORTACION DE PDF*/
    error_reporting(E_ALL & ~E_WARNING );
    use setasign\Fpdi\Fpdi;
    use setasign\Fpdi\PdfReader;
    global $PDFFOLIO;
    $PDFFOLIO=$data['principales']->Folio_infra;
    class PDF extends Fpdi{
        function OnePage($data, $base_url){
            
            $Folio_infra = $data['principales']->Folio_infra;
            /*$y1 = $this->GetY();//INICIA EL RECTANGULO
            $this->ln(1);*/
            $this->SetTextColor(51, 51, 51);
            $this->SetFillColor(156,156,156); 
            $this->Cell(190, 4, utf8_decode('DATOS PRINCIPALES'),0,0,'C',true);
            $this->ln(6);
            
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
                $this->Cell(190, 5, utf8_decode('INFORMACIÓN DEL PRIMER RESPONDIENTE:'));
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
            if($y>205){///si se encuentra en el rango
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

    $pdf = new PDF();
    $pdf->AddFont('Avenir','','avenir.php');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->OnePage($data, base_url);

    /*if($data['evento']->Path_Pdf!='SD' && $data['evento']->Path_Pdf!=null){
        $archivoExistente = '../public/files/GestorCasos/'.$PDFFOLIO.'/'.$data['evento']->Path_Pdf;

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
    }*/
    $pdf->Output();
?>