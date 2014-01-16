<?php
    session_start();   
	header("Pragma: public");
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	header("Cache-Control: private",false);
	if (!array_key_exists("la_logusr",$_SESSION))
	   {
		 echo "<script language=JavaScript>";
		 echo "opener.document.form1.submit();"	;	
		 echo "close();";
		 echo "</script>";		
	   }	
	$x_pos = 0;//mientras mas grande el numero, mas a la derecha.
	$y_pos = -1;//Mientras mas peque�o el numero, mas alto.
    $ls_directorio="cheque_configurable";


	function uf_inicializar_variables($as_archivo,$as_contenido,$ai_medidas)
	{
	///////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_inicializar_variables
	//		    Acess: private 
	//	    Arguments: $as_archivo   = Nombre del Archivo desde donde se cargar�n las medidas.
	//                 $as_contenido = Par�metros de Ubicaci�n de los elemento del Cheque Voucher.
	//                 $ai_medidas   = N�mero de Par�metros de Ubicaci�n del Formato del Cheque Voucher.
	//    Description: Funci�n indica las medidas de ubicaci�n iniciales de la Parte Superior del Cheque Voucher.
	//	   Creado Por: Ing. N�stor Falc�n
	// Fecha Creaci�n: 07/01/2009. 
	///////////////////////////////////////////////////////////////////////////////////////////////

	  global $valores;
	  global $ls_directorio;
	  
	  if (!file_exists ($ls_directorio))
		 {
		   $lb_exito = mkdir($ls_directorio,0777);
		   if (!$lb_exito)
			  {
			    print "<script>";
				print "alert('Error al crear directorio cheque_configurable');";
				print "close();";
				print "</script>";
			  }
		 }
		
	  if ((!file_exists ($as_archivo)) || (filesize($as_archivo)==0))
		 {	
		   if (file_exists ($ls_directorio))
			  {
			    $archivo = fopen($as_archivo, "w");			
				fwrite($archivo,$as_contenido);
				fclose($archivo);
			  }
		 }
			
	  if ((file_exists($as_archivo)) && (filesize($as_archivo)>0))
		 {
			$archivo   = fopen($as_archivo, "r");
			$contenido = fread($archivo, filesize($as_archivo));			
			fclose($archivo);			
			$valores = explode("-",$contenido);	
			if (count($valores)<>$ai_medidas)
			   {
				 $archivo = fopen($ls_archivo, "w");
				 fclose($archivo);			
				 print "<script>";
				 print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
				 print "close();";
				 print "</script>";
			   }
		 }
	  else
		 {
		   print "<script>";
		   print "alert('Ocurrio un error, por favor cargue de nuevo el cheque (Las medidas seran inicializadas por fallo de lectura y escritura)');";
		   print "close();";
		   print "</script>";
		 }
	}// end function uf_inicializar_variables.
	
	function uf_print_encabezado_pagina($ldec_monto,$ls_nomproben,$ls_monto,$ls_fecha,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_encabezado_pagina
		//		    Acess: private 
		//	    Arguments: ldec_monto : Monto del cheque
		//	    		   ls_nomproben:  Nombre del proveedor o beneficiario
		//	    		   ls_monto : Monto en letras
		//	    		   ls_fecha : Fecha del cheque
		//				   io_pdf   : Instancia de objeto pdf
		//    Description: funci�n que imprime los encabezados por p�gina
		//	   Creado Por: Ing. N�stor Falc�n
		// Fecha Creaci�n: 25/04/2006 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		global $valores;
		//Imprimo el monto
		$io_pdf->add_texto($valores[0],$valores[1],10,"<b>***".$ldec_monto."***</b>");
		//Beneficiario del Cheque
		$io_pdf->add_texto($valores[2],$valores[3],11,"<b>$ls_nomproben</b>");
		//Monto en letras del Cheque
		//Cortando el monto en caso de que sea muy largo		
		$ls_monto_cortado=wordwrap($ls_monto,70,"?");
		$la_arreglo=array();
		$la_arreglo=explode("?",$ls_monto_cortado);
		if(array_key_exists(0,$la_arreglo))
			$io_pdf->add_texto($valores[4],$valores[5],9,"<b>$la_arreglo[0]</b>");
		if(array_key_exists(1,$la_arreglo))
			$io_pdf->add_texto($valores[6],$valores[7],9,"<b>$la_arreglo[1]</b>");
		$ls_anio=substr($ls_fecha,-4);
		$ls_fecha_corta=substr($ls_fecha,0,(strlen($ls_fecha)-5));	
		//Fecha del Cheque
		$io_pdf->add_texto($valores[8],$valores[9],9,"<b>$ls_fecha_corta</b>");
		$io_pdf->add_texto($valores[10],$valores[11],9,"<b>$ls_anio</b>");	
		$io_pdf->add_texto($valores[12],$valores[13],9,"<b>NO ENDOSABLE</b>");
		$io_pdf->add_texto($valores[14],$valores[15],9,"<b>CADUCA A LOS ".$_SESSION["la_empresa"]["diacadche"]." DIAS</b>");		
	}// end function uf_print_encabezadopagina.

	function uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_cabecera
		//		   Access: private 
		//	    Arguments: ls_numdoc : Numero de documento
		//	    		   ls_nomban : Nombre del banco
		//				   ls_cbtan  : Cuenta del banco
		//				   ls_chevau : Voucher del cheuqe
		//				   ls_nomproben: Nombre del proveedor o beneficiario
		//				   ls_solicitudes: Solicitudes canceladas con el cheque					  
		//	    		   io_pdf // total de registros que va a tener el reporte
		//    Description: funci�n que imprime los datos basicos del cheque
		//	   Creado Por: Ing. N�stor Falc�n
		// Fecha Creaci�n: 02/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
	//ubicacion directa de los campos indicados del formato preimpreso
	$ld_fechaact=date("d/m/Y");
	$ls_chevau=substr($ls_chevau,-10);
	$io_pdf->addText(60,500,10,$ls_chevau);			//numero de cheque voucher
	$io_pdf->addText(280,500,10,$ls_nomban);	     	//nombre del banco
	$io_pdf->addText(480,500,10,$ls_ctaban);			//cuenta de banco
	
	$io_pdf->addText(60,470,10,$ls_numdoc);        	//numero de documento
	$io_pdf->addText(280,470,10,$ld_fechaact);        	//numero de documento
	$io_pdf->addText(480,470,10,$ls_solicitudes);		//numero de solicitudes canceladas con el cheque	
	
	$io_pdf->addText(60,445,10,$ls_nomproben);		//nombre del proveedor o beneficiario	

	//cuadro ajustando el concepto a un arreglo
	$io_pdf->setStrokeColor(0,0,0);
	$li_pos=156.9;
	$io_pdf->convertir_valor_mm_px($li_pos);
	$io_pdf->ezSetY($li_pos);
	$la_data=array(array('ordenes'=>''.$ls_conmov));
	$la_columna=array('ordenes'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
					 'fontSize' =>9, // Tama�o de Letras
					 'showLines'=>0, // Mostrar L�neas
					 'shaded'=>0, // Sombra entre l�neas
					 'shadeCol2'=>array(0.9,0.9,0.9), // Color de la sombra
					 'xPos'=>310, // Orientaci�n de la tabla
					 'width'=>500, // Ancho de la tabla
					 'maxWidth'=>500,
					 'cols'=>array('ordenes'=>array('justification'=>'left','width'=>500))); // Ancho M�ximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);

	}// end function uf_print_cabecera.

	function uf_print_detalle($la_title,$la_data,&$io_pdf)
	{
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//       Function: uf_print_detalle
		//		    Acess: private 
		//	    Arguments: la_data // arreglo de informaci�n
		//	   			   io_pdf // Objeto PDF
		//    Description: funci�n que imprime el detalle
		//	   Creado Por: Ing. N�stor Falc�n
		// Fecha Creaci�n: 02/04/2008 
		//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		$io_pdf->setStrokeColor(0,0,0);
		
		$io_pdf->ezSetDy(-20);
		//Imprimo los detalles tanto de presupuesto como contablwe del movimiento
		$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' =>8, // Tama�o de Letras
						 'titleFontSize' => 8,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>550, // Ancho de la tabla
						 'maxWidth'=>550, // Ancho M�ximo de la tabla
						 'xPos'=>295, // Orientaci�n de la tabla
						 'cols'=>array('estpro'=>array('justification'=>'left','width'=>120),
						 			   'spg_cuenta'=>array('justification'=>'center','width'=>120),
									   'monto_spg'=>array('justification'=>'left','width'=>100),
						 			   'scg_cuenta'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>75), // Justificaci�n y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>75))); // Justificaci�n y ancho de la columna
		$la_columnas=array('estpro'=>'',
						   'spg_cuenta'=>'',
						   'monto_spg'=>'',
						   'scg_cuenta'=>'',
						   'debe'=>'',
						   'haber'=>'');
		$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
		$io_pdf->ezText('                     ',10);//Inserto una linea en blanco
	}// end function uf_print_detalle.
	
require_once("sigesp_scb_report.php");
require_once('../../shared/class_folder/class_pdf.php');
require_once("../../shared/class_folder/class_sql.php");
require_once("../../shared/class_folder/class_funciones.php");
require_once("../../shared/class_folder/sigesp_include.php");
require_once("../../shared/class_folder/class_datastore.php");
require_once("../../shared/class_folder/class_numero_a_letra.php");

$io_include   = new sigesp_include();
$ls_conect    = $io_include->uf_conectar();
$io_sql		  = new class_sql($ls_conect);	
$class_report = new sigesp_scb_report($ls_conect);
$io_funciones = new class_funciones();				
$ds_voucher	  = new class_datastore();	
$ds_dt_scg	  = new class_datastore();				
$ds_dt_spg	  = new class_datastore();
$numalet	  = new class_numero_a_letra();

//imprime numero con los valore por defecto
//cambia a minusculas
$numalet->setMayusculas(1);
//cambia a femenino
$numalet->setGenero(1);
//cambia moneda
$numalet->setMoneda("Bolivares");
//cambia prefijo
$numalet->setPrefijo("***");
//cambia sufijo
$numalet->setSufijo("***");


$ls_tipimp = "";
	if (array_key_exists("tipimp",$_GET))
	{
		$ls_tipimp = $_GET["tipimp"];
	}	

	if ($ls_tipimp=='lote')
	{
		$ls_codemp		=$_SESSION["la_empresa"]["codemp"];
		$ls_codban      = $_GET["codban"];
		$ls_ctaban      = $_GET["ctaban"];
		$ls_documentos  = $_GET["documentos"];
		$ls_fechas      = $_GET["fechas"];
		$ld_fecdes      = $_GET["fecdesde"];
		$ld_fechas      = $_GET["fechasta"];
		$ls_operaciones = $_GET["operaciones"];
			
		//Descompongo la cadena de documentos en un arreglo tomando como separaci�n el ','
		$arr_documentos = split(",",$ls_documentos);
		$li_totdoc		= count($arr_documentos);
		//Descompongo la cadena de fechas en un arreglo tomando como separaci�n el '-'
		$arr_fecmov = split("-",$ls_fechas);
		$li_totfec  = count($arr_fecmov);
	   //Descompongo la cadena de operaciones en un arreglo tomando como separaci�n el '-'
		$arr_operaciones = split("-",$ls_operaciones);
		$li_totdoc	= count($arr_operaciones);		
		$class_report->uf_buscar_cheques_vouchers($arr_documentos,$arr_fecmov,$arr_operaciones,$ls_codban,$ls_ctaban);
		
		$li_total   = $class_report->ds_voucher1->getRowCount("numdoc");

		if ($li_total>0)
		{	
			error_reporting(E_ALL);
			$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
			$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
			$io_pdf->set_margenes(0,55,0,0);
		    $io_pdf->ezStartPageNumbers(570,30,10,'','',1); // Insertar el n�mero de p�gina

			if (trim($ls_codban)=="001")//Banco Central, actualmente Bicentenario.
			   {
				 $ls_archivo="cheque_configurable/medidas_central.txt";
				 $ls_contenido="144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
				 $li_medidas=22;
			   }
		    elseif(trim($ls_codban)=="002")//Banco Banfoandes.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_banfoandes.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			     $li_medidas   = 22;
			   }
			elseif(trim($ls_codban)=="003")//Banco de Vzla.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_venezuela.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			     $li_medidas   = 22;
			   }
			elseif(trim($ls_codban)=="004")//Banco Casa Propia.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_casapropia.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			     $li_medidas   = 22;
			   }
			else
			   {
			     $ls_archivo   = "cheque_configurable/medidas.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			     $li_medidas   = 22;
			   }
			uf_inicializar_variables($ls_archivo,$ls_contenido,$li_medidas);
			for ($i=1;$i<=$li_total;$i++)
			{
				$ls_numdoc=$class_report->ds_voucher1->getValue("numdoc",$i);
				
				$ls_chevau=$class_report->ds_voucher1->getValue("chevau",$i);
				$ls_codope='CH';
				
				$data 	   = $class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				$lb_valido = $class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
				$class_report->SQL->begin_transaction();
				if (!$lb_valido)
				   {
					 print "Error al actualizar";
					 $class_report->is_msg_error;	
					 $class_report->SQL->rollback();
				   }
				else
				   {
					 $class_report->SQL->commit();
				   }
				$ds_voucher->data=$data;
				
				$li_totrow=$ds_voucher->getRowCount("numdoc");
				$io_pdf->transaction('start'); // Iniciamos la transacci�n
				$thisPageNum=$io_pdf->ezPageCount;
				
				for($li_i=1;$li_i<=$li_totrow;$li_i++)
				{
					
					$li_totprenom = 0;
					$ldec_mondeb  = 0;
					$ldec_monhab  = 0;
					$li_totant	  = 0;
					$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
					$ls_codban		= $ds_voucher->data["codban"][$li_i];
					$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
					$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
					$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
					$ld_fecmov	  	= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
					$ls_nomproben 	= $ds_voucher->data["nomproben"][$li_i];
					$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
					$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
					$ldec_monret	= $ds_voucher->getValue("monret",$li_i);
					$ldec_monto		= $ds_voucher->getValue("monto",$li_i);
					$ldec_total		= $ldec_monto-$ldec_monret;
					//Asigno el monto a la clase numero-letras para la conversion.
					$numalet->setNumero($ldec_total);
					//Obtengo el texto del monto enviado.
					$ls_monto= $numalet->letra();
					uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf); // Imprimimos el encabezado de la p�gina
					
					uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
					
					$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
					$la_items = array('0'=>'scg_cuenta');
					$la_suma  = array('0'=>'monto');
					$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
					$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
					
					$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
					$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
					$la_suma  = array('0'=>'monto');
					$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
					$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					// Ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a imprimirlos.
					///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
					if ($li_totrow_det>=$li_totrow_spg)
					   {
						 for ($li_s=1;$li_s<=$li_totrow_det;$li_s++)
							 {
							   $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
							   $ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
							   $ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
							   if ($ls_debhab=='D')
								  {
									$ldec_mondeb = number_format($ldec_monto,2,",",".");
									$ldec_monhab = "";
								  }
							   else
								  {
									$ldec_monhab = number_format($ldec_monto,2,",",".");
									$ldec_mondeb = "";
								  }
							   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
								  {
									if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
									   {
										 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
										 $ls_estpro      = str_replace('-','    ',$ds_dt_spg->getValue("estpro",$li_s));	  
										 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
									   }
									else
									   {
										 $ls_cuentaspg   = "";	
										 $ls_estpro      = "";	  
										 $ldec_monto_spg = "";
									   }
								  }
							   else
								  {
									$ls_cuentaspg   = "";	
									$ls_estpro      = "";	  
									$ldec_monto_spg = "";
								  }
							   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
							 }
					   }
					if ($li_totrow_spg>$li_totrow_det)
					   {
						 for ($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
							 {
							   if (array_key_exists("scg_cuenta",$ds_dt_scg->data))
								  {
									if (array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
									   {
										 $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
										 $ls_debhab 	= $ds_dt_scg->data["debhab"][$li_s];
										 $ldec_monto	= $ds_dt_scg->data["monto"][$li_s];
										 if ($ls_debhab=='D')
											{
											  $ldec_mondeb = number_format($ldec_monto,2,",",".");
											  $ldec_monhab = "";
											}
										 else
											{
											  $ldec_monhab = number_format($ldec_monto,2,",",".");
											  $ldec_mondeb = "";
											}
									   }
									else
									   {
										 $ls_scg_cuenta = "";
										 $ls_debhab 	= "";
										 $ldec_monto	= "";
										 $ldec_mondeb	= "";
										 $ldec_monhab   = "";					
									   }
								  }
							   else
								  {
									$ls_scg_cuenta = "";
									$ls_debhab 	   = "";
									$ldec_monto	   = "";
									$ldec_mondeb   = "";
									$ldec_monhab   = "";					
								  }
							   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
								  {
									if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
									   {
										 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
										 $ls_estpro      = str_replace('-','    ',$ds_dt_spg->getValue("estpro",$li_s));	  
										 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
									   }
									else
									   {
										 $ls_cuentaspg   = "";	
										 $ls_estpro      = "";	  
										 $ldec_monto_spg = "";
									   }
								  }
							   else
								  {
									$ls_cuentaspg   = "";	
									$ls_estpro      = "";	  
									$ldec_monto_spg = "";
								  }
							   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
							 }
					   }
					uf_print_detalle(array('title'=>'','title2'=>''),$la_data,$io_pdf);
				} // Fin del for 2
			    //uf_print_autorizacion($io_pdf);	
				if ($i<$li_total)
				{			
					$io_pdf->ezNewPage(); // Insertar una nueva p�gina
					$io_pdf->set_margenes(0,55,0,0);
				}
				unset($la_data);
			}// Fin del for 1
			$io_pdf->ezStopPageNumbers(1,1);
			$io_pdf->ezStream();
			unset($io_pdf,$class_report,$io_funciones,$ds_dt_spg,$ds_dt_scg,$ds_voucher,$la_data);
		}//Fin del if ($li_total>0)
		else
		{
			 print("<script language=JavaScript>");
			 print(" alert('No hay nada que Reportar');"); 
			 print(" close();");
			 print("</script>");	  
		} 
		
	} // Fin de si es lote
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// SI NO ES POR LOTE ENTONCES
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	else 
	{
		$ls_codemp = $_SESSION["la_empresa"]["codemp"];
		$ls_codban = $_GET["codban"];
		$ls_ctaban = $_GET["ctaban"];
		$ls_numdoc = $_GET["numdoc"];
		$ls_chevau = $_GET["chevau"];
		$ls_codope = $_GET["codope"];				
	
		$data 	   = $class_report->uf_cargar_chq_voucher($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		$lb_valido = $class_report->uf_actualizar_status_impreso($ls_numdoc,$ls_chevau,$ls_codban,$ls_ctaban,$ls_codope);
		$class_report->SQL->begin_transaction();
		if (!$lb_valido)
		   {
			 print "Error al actualizar";
			 $class_report->is_msg_error;	
			 $class_report->SQL->rollback();
		   }
		else
		   {
			 $class_report->SQL->commit();
		   }
		$ds_voucher->data=$data;
		error_reporting(E_ALL);
		$io_pdf=new class_pdf('LETTER','portrait'); // Instancia de la clase PDF
		$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
		$io_pdf->set_margenes(0,10,0,0);
		$li_totrow=$ds_voucher->getRowCount("numdoc");
		$io_pdf->transaction('start'); // Iniciamos la transacci�n
		$thisPageNum=$io_pdf->ezPageCount;
		//$io_pdf->ezStartPageNumbers(570,30,10,'','',1); // Insertar el n�mero de p�gina
			if (trim($ls_codban)=="001")//Banco Casa Propia.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_casapropia.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00";
				 $li_medidas   = 16;
			   }
		    elseif(trim($ls_codban)=="002")//Banco Bicentenario.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_bicentenario.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00";
				 $li_medidas   = 16;
			   }
			elseif(trim($ls_codban)=="003")//Banco Agricola.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_agricola.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00";
				 $li_medidas   = 16;
			   }
			elseif(trim($ls_codban)=="004")//Banco de Vzla.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_venezuela.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			     $li_medidas   = 22;
			   }
			elseif(trim($ls_codban)=="007")//Banco Industrial.
			   {
			     $ls_archivo   = "cheque_configurable/medidas_industrial.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			     $li_medidas   = 22;
			   }
			else
			   {
			     $ls_archivo   = "cheque_configurable/medidas.txt";
			     $ls_contenido = "144.00-0.00-24.00-20.00-26.00-30.00-27.00-33.00-22.00-38.00-79.00-38.00-120.00-60.00-130.00-65.00-10.00-91.00-8.00-100.00-12.00-117.00";
			     $li_medidas   = 22;
			   }
		uf_inicializar_variables($ls_archivo,$ls_contenido,$li_medidas);
		//uf_print_autorizacion($io_pdf);	
		for($li_i=1;$li_i<=$li_totrow;$li_i++)
		{
			unset($la_data);
			$li_totprenom = 0;
			$ldec_mondeb  = 0;
			$ldec_monhab  = 0;
			$li_totant	  = 0;
			$ls_numdoc		= $ds_voucher->data["numdoc"][$li_i];
			$ls_codban		= $ds_voucher->data["codban"][$li_i];
			$ls_nomban		= $class_report->uf_select_data($io_sql,"SELECT nomban FROM scb_banco WHERE codban ='".$ls_codban."' AND codemp='".$ls_codemp."'","nomban");
			$ls_ctaban		= $ds_voucher->data["ctaban"][$li_i];
			$ls_chevau		= $ds_voucher->data["chevau"][$li_i];
			$ld_fecmov	  	= $io_funciones->uf_convertirfecmostrar($ds_voucher->data["fecmov"][$li_i]);
			$ls_nomproben 	= $ds_voucher->data["nomproben"][$li_i];
			$ls_solicitudes = $class_report->uf_select_solicitudes($ls_numdoc,$ls_codban,$ls_ctaban);
			$ls_conmov		= $ds_voucher->getValue("conmov",$li_i);
			$ldec_monret	= $ds_voucher->getValue("monret",$li_i);
			$ldec_monto		= $ds_voucher->getValue("monto",$li_i);
			$ldec_total		= $ldec_monto-$ldec_monret;
			//Asigno el monto a la clase numero-letras para la conversion.
			$numalet->setNumero($ldec_total);
			//Obtengo el texto del monto enviado.
			$ls_monto= $numalet->letra();
			uf_print_encabezado_pagina(number_format($ldec_total,2,",","."),$ls_nomproben,$ls_monto,$_SESSION["la_empresa"]["ciuemp"].", ".$ld_fecmov,$io_pdf); // Imprimimos el encabezado de la p�gina
			uf_print_cabecera($ls_numdoc,$ls_nomban,$ls_ctaban,$ls_chevau,$ls_nomproben,$ls_solicitudes,$ls_conmov,$io_pdf); // Imprimimos la cabecera del registro
			
			$ds_dt_scg->data=$class_report->uf_cargar_dt_scg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope); // Obtenemos el detalle del reporte
			$la_items = array('0'=>'scg_cuenta');
			$la_suma  = array('0'=>'monto');
			$ds_dt_scg->group_by($la_items,$la_suma,'scg_cuenta');
			$li_totrow_det=$ds_dt_scg->getRowCount("scg_cuenta");
			
			$ds_dt_spg->data=$class_report->uf_cargar_dt_spg($ls_numdoc,$ls_codban,$ls_ctaban,$ls_codope);
			$la_items = array('0'=>'estpro','1'=>'spg_cuenta');
			$la_suma  = array('0'=>'monto');
			$ds_dt_spg->group_by($la_items,$la_suma,'spg_cuenta');
			$li_totrow_spg=$ds_dt_spg->getRowCount("spg_cuenta");
			$li_con1=0;
			$li_con2=0;
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			// Ciclo para unir en una sola matriz los detalles de presupuesto y los contables para proceder luego a imprimirlos.
			///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			if ($li_totrow_det>=$li_totrow_spg)
			   {
				 for ($li_s=1;$li_s<=$li_totrow_det;$li_s++)
					 {
					   $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
					   $ls_debhab     = $ds_dt_scg->data["debhab"][$li_s];
					   $ldec_monto    = $ds_dt_scg->data["monto"][$li_s];
					   if ($ls_debhab=='D')
						  {
							$ldec_mondeb = number_format($ldec_monto,2,",",".");
							$ldec_monhab = "";
						  }
					   else
						  {
							$ldec_monhab = number_format($ldec_monto,2,",",".");
							$ldec_mondeb = "";
						  }
					   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
						  {
							if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
							   {
								 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
								 $ls_estpro      = str_replace('-','    ',$ds_dt_spg->getValue("estpro",$li_s));	  
								 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
							   }
							else
							   {
								 $ls_cuentaspg   = "";	
								 $ls_estpro      = "";	  
								 $ldec_monto_spg = "";
							   }
						  }
					   else
						  {
							$ls_cuentaspg   = "";	
							$ls_estpro      = "";	  
							$ldec_monto_spg = "";
						  }
					   $li_con1++;
					   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
					 }
			   }
			if ($li_totrow_spg>$li_totrow_det)
			   {
				 for ($li_s=1;$li_s<=$li_totrow_spg;$li_s++)
					 {
					   if (array_key_exists("scg_cuenta",$ds_dt_scg->data))
						  {
							if (array_key_exists($li_s,$ds_dt_scg->data["scg_cuenta"]))
							   {
								 $ls_scg_cuenta = trim($ds_dt_scg->data["scg_cuenta"][$li_s]);
								 $ls_debhab 	= $ds_dt_scg->data["debhab"][$li_s];
								 $ldec_monto	= $ds_dt_scg->data["monto"][$li_s];
								 if ($ls_debhab=='D')
									{
									  $ldec_mondeb = number_format($ldec_monto,2,",",".");
									  $ldec_monhab = "";
									}
								 else
									{
									  $ldec_monhab = number_format($ldec_monto,2,",",".");
									  $ldec_mondeb = "";
									}
							   }
							else
							   {
								 $ls_scg_cuenta = "";
								 $ls_debhab 	= "";
								 $ldec_monto	= "";
								 $ldec_mondeb	= "";
								 $ldec_monhab   = "";					
							   }
						  }
					   else
						  {
							$ls_scg_cuenta = "";
							$ls_debhab 	   = "";
							$ldec_monto	   = "";
							$ldec_mondeb   = "";
							$ldec_monhab   = "";					
						  }
					   if (array_key_exists("spg_cuenta",$ds_dt_spg->data))
						  {
							if (array_key_exists($li_s,$ds_dt_spg->data["spg_cuenta"]))
							   {
								 $ls_cuentaspg   = trim($ds_dt_spg->getValue("spg_cuenta",$li_s));
								 $ls_estpro      = str_replace('-','    ',$ds_dt_spg->getValue("estpro",$li_s));	  
								 $ldec_monto_spg = number_format($ds_dt_spg->getValue("monto",$li_s),2,",",".");
							   }
							else
							   {
								 $ls_cuentaspg   = "";	
								 $ls_estpro      = "";	  
								 $ldec_monto_spg = "";
							   }
						  }
					   else
						  {
							$ls_cuentaspg   = "";	
							$ls_estpro      = "";	  
							$ldec_monto_spg = "";
						  }
					   $li_con2++;
					   $la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);
					 }
			   }
			if (($li_con1==0)&&($li_con2==0))
			{
				$ls_scg_cuenta = "";
				$ls_debhab 	   = "";
				$ldec_monto	   = "";
				$ldec_mondeb   = "";
				$ldec_monhab   = "";
				$ls_cuentaspg   = "";	
				$ls_estpro      = "";	  
				$ldec_monto_spg = "";
				$la_data[$li_s]=array('spg_cuenta'=>$ls_cuentaspg,'estpro'=>$ls_estpro,'monto_spg'=>$ldec_monto_spg,'scg_cuenta'=>$ls_scg_cuenta,'debe'=>$ldec_mondeb,'haber'=>$ldec_monhab);		
			}   
			uf_print_detalle(array('title'=>'','title2'=>''),$la_data,$io_pdf);
		}
		//$io_pdf->ezStopPageNumbers(1,1);
		$io_pdf->ezStream();
		unset($io_pdf,$class_report,$io_funciones);
	}
?> 