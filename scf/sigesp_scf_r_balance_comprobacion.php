<?php
    session_start();   
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	if(!array_key_exists("la_logusr",$_SESSION))
	{
		print "<script language=JavaScript>";
		print "location.href='../sigesp_inicio_sesion.php'";
		print "</script>";		
	}
	$ls_logusr=$_SESSION["la_logusr"];
	require_once("class_folder/class_funciones_scf.php");
	$io_fun_scf=new class_funciones_scf("../");
	$io_fun_scf->uf_load_seguridad("SCF","sigesp_scf_r_balance_comprobacion.php",$ls_permisos,$la_seguridad,$la_permisos);
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ld_fecdes="01/01/".substr($_SESSION["la_empresa"]["periodo"],0,4);
	$ld_fechas=date("d/m/Y");
	//$lb_valido=$io_fun_scf->uf_convertir_scgsaldos($la_seguridad);
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<script language="javascript">
	if(document.all)
	{ //ie 
		document.onkeydown = function(){ 
		if(window.event && (window.event.keyCode == 122 || window.event.keyCode == 116 || window.event.ctrlKey)){
		window.event.keyCode = 505; 
		}
		if(window.event.keyCode == 505){ 
		return false; 
		} 
		} 
	}
</script>
<title>Reporte Balance  de  Comprobaci&oacute;n</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="js/stm31.js"></script>
<script type="text/javascript" language="JavaScript1.2" src="js/funcion_scf.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
<link href="css/scf.css" rel="stylesheet" type="text/css">
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:hover {
	color: #006699;
}
a:active {
	color: #006699;
}
-->
</style></head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td width="780" height="30" colspan="11" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu">
	<table width="779" border="0" align="center" cellpadding="0" cellspacing="0">
			
            <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Fiscal </td>
			<td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-peque�as"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
	  	    <tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td>
      </table> </td>
  </tr>
  <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript:ue_search();"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript:ue_openexcel();"><img src="../shared/imagebank/tools20/excel.jpg" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="25"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="25"><div align="center"></div></td>
    <td class="toolbar" width="530">&nbsp;</td>
  </tr>
</table>
</div>
<p>&nbsp;</p>
<form name="formulario" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scf->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_scf);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>		  
  <table width="650" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
    <tr>
      <td height="136"><p>&nbsp;</p>
          <table width="600" height="22" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
            <tr>
              <td width="98"></td>
            </tr>
            <tr class="titulo-ventana">
              <td height="22" colspan="4" align="center">Reporte Balance de Comprobaci&oacute;n </td>
            </tr>
            <tr>
              <td height="15" colspan="4" align="center">&nbsp;</td>
            </tr>
            <tr style="display:none">
              <td height="13" align="center"><div align="right">Reporte en </div></td>
              <td height="22" colspan="3" align="center"><div align="left">
                  <select name="cmbbsf" id="cmbbsf">
                    <option value="0" selected>Bs.</option>
                    <option value="1">Bs.F.</option>
                  </select>
              </div></td>
            </tr>
            <tr class="titulo-celdanew">
              <td height="22" colspan="4" align="center">Cuentas Contables </td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Desde</div></td>
              <td align="center"><div align="left">
                  <input name="txtcuentadesde" type="text" id="txtcuentadesde" size="22">
              <a href="javascript:ue_buscarcuenta('REPDES')"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
              <td align="center"><div align="right">Hasta</div></td>
              <td align="center"><div align="left">
                  <input name="txtcuentahasta" type="text" id="txtcuentahasta" size="22">
              <a href="javascript:ue_buscarcuenta('REPHAS')"><img src="../shared/imagebank/tools15/buscar.gif" alt="Catalogo Cuentas" width="15" height="15" border="0"></a></div></td>
            </tr>
            <tr class="titulo-celdanew">
              <td height="22" colspan="4" align="center">Per&iacute;odo</td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Desde</div></td>
              <td height="22" align="center"><div align="left">
                  <input name="txtfecdes" type="text" id="txtfecdes"  onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print  $ld_fecdes; ?>" size="22" maxlength="10"  datepicker="true">
              </div></td>
              <td height="22" align="center"><div align="right">Hasta</div></td>
              <td height="22" align="center"><div align="left">
                  <input name="txtfechas" type="text" id="txtfechas"  onKeyPress="javascript: ue_formatofecha(this,'/',patron,true);" value="<?php print  $ld_fechas; ?>" size="22" maxlength="10"  datepicker="true">
              </div></td>
            </tr>
            <tr class="titulo-celdanew">
              <td height="22" colspan="4" align="center">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" align="center"><div align="right">Nivel</div>
                  <div align="left"></div></td>
              <td width="152" align="center"><div align="left">
                <select name="nivel" id="nivel">
                  <option value="1">1</option>
                  <option value="2">2</option>
                  <option value="3">3</option>
                  <option value="4">4</option>
                  <option value="5">5</option>
                  <option value="6">6</option>
                  <option value="7">7</option>
                </select>
              </div></td>
              <td width="85" align="center">&nbsp;</td>
              <td width="198" align="center">&nbsp;</td>
            </tr>
            <tr>
              <td height="22" colspan="4" align="center"></td>
            </tr>
          </table>
        <p>&nbsp;</p></td>
    </tr>
  </table>
  <p>&nbsp;</p>
  </table>
  </p>
</form>      
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
</body>
<script language="JavaScript">
var patron = new Array(2,2,4);
var patron2 = new Array(1,3,3,3,3);
function ue_search()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	valido=true;
	if(li_imprimir==1)
	{	
		fecdes=f.txtfecdes.value;
		fechas=f.txtfechas.value;
		cuentadesde=f.txtcuentadesde.value;
		cuentahasta=f.txtcuentahasta.value;
		nivel=f.nivel.value;
		tiporeporte=f.cmbbsf.value;
		if((fecdes=="")||(fechas==""))
		{
			alert("Debe colocar un rango de fechas.");
			valido=false;
		}
		if(cuentadesde>cuentahasta)
		{
			alert("Intervalo de cuentas incorrecto.");
			valido=false;
		}
		if(valido)
		{
			pagina="reportes/sigesp_scg_rpp_balance_comprobacion_pdf.php?fecdes="+fecdes+"&fechas="+fechas;
			pagina=pagina+"&cuentadesde="+cuentadesde+"&cuentahasta="+cuentahasta+"&nivel="+nivel+"&tiporeporte="+tiporeporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaci�n");
   	}		
}

function ue_openexcel()
{
	f=document.formulario;
	li_imprimir=f.imprimir.value;
	valido=true;
	if(li_imprimir==1)
	{	
		fecdes=f.txtfecdes.value;
		fechas=f.txtfechas.value;
		cuentadesde=f.txtcuentadesde.value;
		cuentahasta=f.txtcuentahasta.value;
		nivel=f.nivel.value;
		tiporeporte=f.cmbbsf.value;
		if((fecdes=="")||(fechas==""))
		{
			alert("Debe colocar un rango de fechas.");
			valido=false;
		}
		if(cuentadesde>cuentahasta)
		{
			alert("Intervalo de cuentas incorrecto.");
			valido=false;
		}
		if(valido)
		{
			pagina="reportes/sigesp_scg_rpp_balance_comprobacion_excel.php?fecdes="+fecdes+"&fechas="+fechas;
			pagina=pagina+"&cuentadesde="+cuentadesde+"&cuentahasta="+cuentahasta+"&nivel="+nivel+"&tiporeporte="+tiporeporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaci�n");
   	}		
}
   
function ue_cerrar()
{
	location.href = "sigespwindow_blank.php";
}

function ue_buscarcuenta(tipo)
{
	window.open("sigesp_scf_cat_cuentasscg.php?tipo="+tipo,"catalogo","menubar=no,toolbar=no,scrollbars=yes,width=600,height=400,left=50,top=50,location=no,resizable=yes");
}
</script>
<script language="javascript" src="../shared/js/js_intra/datepickercontrol.js"></script>
</html>