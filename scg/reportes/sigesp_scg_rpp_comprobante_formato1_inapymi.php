<?php
session_start();
header("Pragma: public");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
if(!array_key_exists("la_logusr",$_SESSION))
{
	print "<script language=JavaScript>";
	print "close();";
	print "</script>";
}

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_insert_seguridad($as_titulo)
{
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_insert_seguridad
	//		   Access: private
	//	    Arguments: as_titulo // T�tulo del Reporte
	//    Description: funci�n que guarda la seguridad de quien gener� el reporte
	//	   Creado Por: Ing. Yesenia Moreno
	// Fecha Creaci�n: 22/09/2006
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $io_fun_scg;

	$ls_descripcion="Gener� el Reporte ".$as_titulo;
	$lb_valido=$io_fun_scg->uf_load_seguridad_reporte("SCG","sigesp_scg_r_comprobante_formato1.php",$ls_descripcion);
	return $lb_valido;
}
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_encabezado_pagina($as_titulo,$as_fecha,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_encabezadopagina
	//		    Acess: private
	//	    Arguments: as_titulo // T�tulo del Reporte
	//	    		   io_pdf // Instancia de objeto pdf
	//    Description: funci�n que imprime los encabezados por p�gina
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaci�n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$io_encabezado=$io_pdf->openObject();
	$io_pdf->saveState();
	//$io_pdf->line(20,40,578,40);
	$io_pdf->addJpegFromFile('../../shared/imagebank/'.$_SESSION["ls_logo"],15,720,$_SESSION["ls_width"],$_SESSION["ls_height"]); // Agregar Logo

	$li_tm=$io_pdf->getTextWidth(9,$as_titulo);
	$tm=310-($li_tm/2);
	$io_pdf->addText($tm,730,9,$as_titulo); // Agregar el t�tulo

	$li_tm=$io_pdf->getTextWidth(9,$as_fecha);
	$tm=310-($li_tm/2);
	$io_pdf->addText($tm,715,9,$as_fecha); // Agregar el fecha

	$io_pdf->addText(530,740,7,$_SESSION["ls_database"]); // Agregar la Base de datos
	$io_pdf->addText(530,730,9,date("d/m/Y")); // Agregar la Fecha
	$io_pdf->addText(530,720,9,date("h:i a")); // Agregar la Fecha
	
	$io_pdf->setStrokeColor(0,0,0);
	$io_pdf->setLineStyle(1);
	$io_pdf->Rectangle(19,100,570,80);
	$io_pdf->line(19,115,590,115);
	$io_pdf->line(290,100,290,180);
	$io_pdf->addText(90,170,7,"<b>ELABORADO POR</b>"); // Agregar el t�tulo
    $io_pdf->addText(40,105,7,"<b>Usuario: ".$_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"]."</b>"); // Agregar el t�tulo 
	$io_pdf->addText(390,170,7,"<b>APROBADO POR</b>"); // Agregar el t�tulo
    $io_pdf->addText(370,105,7,"<b>Coordinador de Contabilidad</b>"); // Agregar el t�tulo
    
	$io_pdf->restoreState();
	$io_pdf->closeObject();
	$io_pdf->addObject($io_encabezado,'all');
}// end function uf_print_encabezadopagina
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_cabecera($as_procede,$as_comprobante,$as_nomprobene,$adt_fecha,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_cabecera
	//		   Access: private
	//	    Arguments: as_procede // procede
	//	    		   as_comprobante // comprobante
	//                 as_nomprobene   // nombre del proveedor
	//                 adt_fecha     // fecha del comprobante
	//	    		   io_pdf // Objeto PDF
	//    Description: funci�n que imprime la cabecera de cada p�gina
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaci�n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	$r=(237)/255;
	$g=(244)/255;
	$b=(248)/255;
	$la_data=array(array('name'=>'<b>Comprobante</b>  '.$as_procede.'---'.$as_comprobante.'                '.$adt_fecha.''),
	array('name'=>'<b>Beneficiario</b>  '.$as_nomprobene.''));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>1, // Mostrar L�neas
						 'shadeCol'=>array($r,$g,$b), // Color de la sombra
						 'shadeCol2'=>array($r,$g,$b), // Color de la sombra 
						 'fontSize' => 8, // Tama�o de Letras
						 'shaded'=>2, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580); // Ancho M�ximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
}// end function uf_print_cabecera
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_detalle($la_data,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function: uf_print_detalle
	//		    Acess: private
	//	    Arguments: la_data // arreglo de informaci�n
	//	   			   io_pdf // Objeto PDF
	//    Description: funci�n que imprime el detalle
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaci�n: 28/04/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	$la_config=array('showHeadings'=>1, // Mostrar encabezados
						 'fontSize' => 7, // Tama�o de Letras
						 'titleFontSize' => 9,  // Tama�o de Letras de los t�tulos
						 'showLines'=>0, // Mostrar L�neas
						 'colGap'=>0.5, // separacion entre tablas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>580, // Ancho de la tabla
						 'maxWidth'=>580, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'cols'=>array('cuenta'=>array('justification'=>'center','width'=>80), // Justificaci�n y ancho de la columna
									   'denominacion'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
									   'descripcion'=>array('justification'=>'left','width'=>120), // Justificaci�n y ancho de la columna
						 			   'documento'=>array('justification'=>'left','width'=>100), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
									   'haber'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
	$la_columnas=array('cuenta'=>'<b>Cuenta</b>',
						   'denominacion'=>'<b>Denominaci�n</b>',
						   'descripcion'=>'<b>Descripci�n</b>',
						   'documento'=>'<b>Documento</b>',
						   'debe'=>'<b>Debe</b>',
						   'haber'=>'<b>Haber</b>');
	$io_pdf->ezTable($la_data,$la_columnas,'',$la_config);
}// end function uf_print_detalle
//--------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
function uf_print_pie_cabecera($ad_totaldebe,$ad_totalhaber,$as_tipo,&$io_pdf)
{
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	//       Function : uf_print_pie_cabecera
	//		    Acess : private
	//	    Arguments : ad_totaldebe // Total debe
	//	   				ad_totalhaber // Total haber
	//    Description : funci�n que imprime el fin de la cabecera de cada p�gina
	//	   Creado Por: Ing. Yozelin Barragan
	// Fecha Creaci�n : 18/02/2006
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	global $ls_bolivares;

	$la_data=array(array('name'=>'--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------'));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'fontSize' => 8, // Tama�o de Letras
						 'showLines'=>0, // Mostrar L�neas
						 'shaded'=>0, // Sombra entre l�neas
						 'xOrientation'=>'center', // Orientaci�n de la tabla
						 'width'=>560); // Ancho M�ximo de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	if($as_tipo=="1")// comprobante
	{
		$la_data=array(array('total'=>'<b>Total Comprobante '.$ls_bolivares.'</b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber));
	}
	elseif($as_tipo=="2")//total general
	{
		$la_data=array(array('total'=>'<b>Total '.$ls_bolivares.'</b>','debe'=>$ad_totaldebe,'haber'=>$ad_totalhaber));
	}
	$la_columna=array('total'=>'','debe'=>'','haber'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' => 8, // Tama�o de Letras
						 'colGap'=>0.5, // separacion entre tablas
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>580, // Ancho M�ximo de la tabla
						 'maxWidth'=>580, // Ancho M�ximo de la tabla
						 'xPos'=>300, // Orientaci�n de la tabla
						 'xOrientation'=>'center', // Orientaci�n de la tabla
				 		 'cols'=>array('total'=>array('justification'=>'right','width'=>400), // Justificaci�n y ancho de la columna
						 			   'debe'=>array('justification'=>'right','width'=>80), // Justificaci�n y ancho de la columna
						 			   'haber'=>array('justification'=>'right','width'=>80))); // Justificaci�n y ancho de la columna
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	$la_data=array(array('name'=>''));
	$la_columna=array('name'=>'');
	$la_config=array('showHeadings'=>0, // Mostrar encabezados
						 'showLines'=>0, // Mostrar L�neas
						 'fontSize' => 8, // Tama�o de Letras
						 'shaded'=>0, // Sombra entre l�neas
						 'width'=>560, // Ancho M�ximo de la tabla
						 'xOrientation'=>'center'); // Orientaci�n de la tabla
	$io_pdf->ezTable($la_data,$la_columna,'',$la_config);
	
}// end function uf_print_pie_cabecera

//-----------------------------------------------------------------------------------------------------------------------------------
function uf_init_niveles()
{	///////////////////////////////////////////////////////////////////////////////////////////////////////
//	   Function: uf_init_niveles
//	     Access: public
//	    Returns: vacio
//	Description: Este m�todo realiza una consulta a los formatos de las cuentas
//               para conocer los niveles de la escalera de las cuentas contables
//////////////////////////////////////////////////////////////////////////////////////////////////////
global $io_funciones,$ia_niveles_scg;

$ls_formato=""; $li_posicion=0; $li_indice=0;
$dat_emp=$_SESSION["la_empresa"];
//contable
$ls_formato = trim($dat_emp["formcont"])."-";
$li_posicion = 1 ;
$li_indice   = 1 ;
$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
do
{
	$ia_niveles_scg[$li_indice] = $li_posicion;
	$li_indice   = $li_indice+1;
	$li_posicion = $io_funciones->uf_posocurrencia($ls_formato, "-" , $li_indice ) - $li_indice;
} while ($li_posicion>=0);
}// end function uf_init_niveles
//-----------------------------------------------------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------------------------------------------------
require_once("../../shared/ezpdf/class.ezpdf.php");
require_once("../../shared/class_folder/class_funciones.php");
$io_funciones=new class_funciones();
require_once("../class_funciones_scg.php");
$io_fun_scg=new class_funciones_scg();
$ls_tiporeporte="0";
$ls_bolivares="";

if (array_key_exists("tiporeporte",$_GET))
{
	$ls_tiporeporte=$_GET["tiporeporte"];
}
switch($ls_tiporeporte)
{
	case "0":
		require_once("sigesp_scg_reporte.php");
		$io_report  = new sigesp_scg_reporte();
		$ls_bolivares ="Bs.";
		break;

	case "1":
		require_once("sigesp_scg_reportebsf.php");
		$io_report  = new sigesp_scg_reportebsf();
		$ls_bolivares ="Bs.F.";
		break;
}
$ia_niveles_scg[0]="";
uf_init_niveles();
$li_total=count($ia_niveles_scg)-1;
//--------------------------------------------------  Par�metros para Filtar el Reporte  -----------------------------------------
$ls_compdes=$_GET["txtcompdes"];
$ls_comphas=$_GET["txtcomphas"];
$ls_procdes=$_GET["txtprocdes"];
$ls_prochas=$_GET["txtprochas"];
$li_orddoc =$_GET["orderdocumento"];
$fecdes=$_GET["txtfecdes"];
if (!empty($fecdes))
{
	$ldt_fecdes=$io_funciones->uf_convertirdatetobd($fecdes);
}	else {  $ldt_fecdes=""; }
$fechas=$_GET["txtfechas"];
if (!empty($fechas))
{
	$ldt_fechas=$io_funciones->uf_convertirdatetobd($fechas);
}	else {  $ldt_fechas=""; }

$ls_orden=$_GET["rborden"];
//----------------------------------------------------  Par�metros del encabezado  -----------------------------------------------
$ldt_periodo=$_SESSION["la_empresa"]["periodo"];
$li_ano=substr($ldt_periodo,0,4);

$ldt_fecdes=substr($ldt_fecdes,0,10);
$ldt_fecdes_cab=$io_funciones->uf_convertirfecmostrar($ldt_fecdes);
$ldt_fechas=substr($ldt_fechas,0,10);
$ldt_fechas_cab=$io_funciones->uf_convertirfecmostrar($ldt_fechas);
$ldt_fecha_cab=" <b>Desde  ".$ldt_fecdes_cab."  al  ".$ldt_fechas_cab." </b>"  ;
$ls_titulo=" <b>COMPROBANTES  CONTABLE  FORMATO 1 </b> ";
//--------------------------------------------------------------------------------------------------------------------------------
// Cargar el dts_cab con los datos de la cabecera del reporte( Selecciono todos comprobantes )
$lb_valido=uf_insert_seguridad("<b>Comprobante Formato 1 en PDF</b>"); // Seguridad de Reporte
if($lb_valido)
{
	$lb_valido=$io_report->uf_scg_reporte_select_comprobante_formato1($ls_procdes,$ls_prochas,$ls_compdes,$ls_comphas,$ldt_fecdes,$ldt_fechas,$ls_orden);
}
if(!$io_report->rs_data->RowCount()) // Existe alg�n error � no hay registros
{
	print("<script language=JavaScript>");
	print(" alert('No hay nada que Reportar');");
	print(" close();");
	print("</script>");
}
else // Imprimimos el reporte
{
	error_reporting(E_ALL);
	$io_pdf=new Cezpdf('LETTER','portrait'); // Instancia de la clase PDF
	$io_pdf->selectFont('../../shared/ezpdf/fonts/Helvetica.afm'); // Seleccionamos el tipo de letra
	$io_pdf->ezSetCmMargins(3.5,8,3,3); // Configuraci�n de los margenes en cent�metros
	uf_print_encabezado_pagina($ls_titulo,$ldt_fecha_cab,$io_pdf); // Imprimimos el encabezado de la p�gina
	$io_pdf->ezStartPageNumbers(550,50,10,'','',1); // Insertar el n�mero de p�gina
	$li_tot=$io_report->rs_data->RowCount();
	//echo $io_report->rs_data->RowCount();
	$ld_totald=0;
	$ld_totalh=0;
	$li_i=0;

	while($row=$io_report->SQL->fetch_row($io_report->rs_data))
	{
		$li_i++;
		$io_pdf->transaction('start'); // Iniciamos la transacci�n
		$thisPageNum=$io_pdf->ezPageCount;
		$ld_totaldebe=0;
		$ld_totalhaber=0;
		$ls_comprobante=$row["comprobante"];
		$ldt_fecha=$row["fecha"];
		$ls_procede=$row["procede"];
		$ls_ced_bene=$row["ced_bene"];
		$ls_cod_pro=$row["cod_pro"];
		$ls_nomproben=$row["nombre"];
		$ls_tipo_destino=$row["tipo_destino"];

		$ls_codban=$row["codban"];
		$ls_ctaban=$row["ctaban"];

		$ldt_fec=$io_funciones->uf_convertirfecmostrar($ldt_fecha);
		uf_print_cabecera($ls_procede,$ls_comprobante,$ls_nomproben,$ldt_fec,$io_pdf); // Imprimimos la cabecera del registro
		$lb_valido=$io_report->uf_scg_reporte_comprobante_formato1($ls_procede,$ls_comprobante,$ldt_fecha,$ls_orden,$ls_codban,$ls_ctaban,$li_orddoc);
			
		if($lb_valido)
		{
			$li_totrow_det=$io_report->rs_data_comp->RowCount();
			$li_s=0;
			while($row_comp=$io_report->SQL->fetch_row($io_report->rs_data_comp))
			{
				//$ls_procede=$row_comp["procede"];
				$ls_comprobante=$row_comp["comprobante"];
				//$fecha=$row_comp["fecha"];
				//$ldt_fecha=$io_funciones->uf_convertirfecmostrar($fecha);
				$ls_sc_cuenta=trim($row_comp["sc_cuenta"]);
				$li_totfil=0;
				$as_cuenta="";
				for($li=$li_total;$li>1;$li--)
				{
					$li_ant=$ia_niveles_scg[$li-1];
					$li_act=$ia_niveles_scg[$li];
					$li_fila=$li_act-$li_ant;
					$li_len=strlen($ls_sc_cuenta);
					$li_totfil=$li_totfil+$li_fila;
					$li_inicio=$li_len-$li_totfil;
					if($li==$li_total)
					{
						$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila);
					}
					else
					{
						$as_cuenta=substr($ls_sc_cuenta,$li_inicio,$li_fila)."-".$as_cuenta;
					}
				}
				$li_fila=$ia_niveles_scg[1]+1;
				$as_cuenta=substr($ls_sc_cuenta,0,$li_fila)."-".$as_cuenta;

				$ls_procede_doc=$row_comp["procede_doc"];
				$ls_documento=$row_comp["documento"];
				$ls_debhab=$row_comp["debhab"];
				$ld_monto=$row_comp["monto"];
				//$ls_descripcion=$row_comp["descripcion"];
				$ls_denominacion=$row_comp["denominacion"];
					
				$ls_CMP_descripcion=$row_comp["cmp_descripcion"];
					
				if($ls_debhab=='D')
				{
					$ld_debe=number_format($ld_monto,2,",",".");
					$ld_totaldebe=$ld_totaldebe+$ld_monto;
					$ld_haber=" ";
				}
				if($ls_debhab=='H')
				{
					$ld_haber=number_format($ld_monto,2,",",".");
					$ld_totalhaber=$ld_totalhaber+$ld_monto;
					$ld_debe=" ";
				}
					
				$ls_documentoproc=$ls_procede_doc."-".$ls_documento;
				$li_s++;
				$la_data[$li_s]=array('cuenta'=>$as_cuenta,'denominacion'=>$ls_denominacion,'descripcion'=>$ls_CMP_descripcion,'documento'=>$ls_documentoproc,'debe'=>$ld_debe,'haber'=>$ld_haber);
					
			}
			 
			uf_print_detalle($la_data,$io_pdf); // Imprimimos el detalle
			$ld_totald=$ld_totald+$ld_totaldebe;
			$ld_totalh=$ld_totalh+$ld_totalhaber;

			$ld_totaldebe=number_format($ld_totaldebe,2,",",".");
			$ld_totalhaber=number_format($ld_totalhaber,2,",",".");
			$ld_totalde=$ld_totaldebe;
			$ld_totalha=$ld_totalhaber;

			uf_print_pie_cabecera($ld_totaldebe,$ld_totalhaber,1,$io_pdf); // Imprimimos pie de la cabecera
			$ld_totaldebe=str_replace('.','',$ld_totaldebe);
			$ld_totaldebe=str_replace(',','.',$ld_totaldebe);
			$ld_totalhaber=str_replace('.','',$ld_totalhaber);
			$ld_totalhaber=str_replace(',','.',$ld_totalhaber);

		}
		
		if ($li_tot-1>=$li_i) {
			$io_pdf->ezNewPage();
		}
		
		
		unset($la_data);
	}//for
	$io_pdf->ezStopPageNumbers(1,1);
	if (isset($d) && $d)
	{
		$ls_pdfcode = $io_pdf->ezOutput(1);
		$ls_pdfcode = str_replace("\n","\n<br>",htmlspecialchars($ls_pdfcode));
		echo '<html><body>';
		echo trim($ls_pdfcode);
		echo '</body></html>';
	}
	else
	{
		$io_pdf->ezStream();
	}
	unset($io_pdf);
}
unset($io_report);
unset($io_funciones);
?>
