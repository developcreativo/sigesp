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
	require_once("class_funciones_scg.php");
	$io_fun_scg=new class_funciones_scg();
	$io_fun_scg->uf_load_seguridad("SCG","sigesp_scg_r_balance_general.php",$ls_permisos,$la_seguridad,$la_permisos);
	$ls_reporte = $io_fun_scg->uf_select_config("SCG","REPORTE","BALANCE_GENERAL","sigesp_scg_rpp_balance_general.php","C");
	//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$ls_codemp=$_SESSION["la_empresa"]["codemp"];

$li_diasem = date('w');
switch ($li_diasem){
  case '0': $ls_diasem='Domingo';
  break; 
  case '1': $ls_diasem='Lunes';
  break;
  case '2': $ls_diasem='Martes';
  break;
  case '3': $ls_diasem='Mi&eacute;rcoles';
  break;
  case '4': $ls_diasem='Jueves';
  break;
  case '5': $ls_diasem='Viernes';
  break;
  case '6': $ls_diasem='S&aacute;bado';
  break;
}?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<script type="text/javascript" language="JavaScript1.2" src="../shared/js/disabled_keys.js"></script>
<title>Balance General</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="../spg/js/stm31.js"></script>
<meta http-equiv="" content="text/html; charset=iso-8859-1"><meta http-equiv="" content="text/html; charset=iso-8859-1">
<meta http-equiv="Content-Type" content="text/html; charset=">
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
</style>
<link href="../shared/css/cabecera.css" rel="stylesheet" type="text/css">
<link href="../shared/css/general.css" rel="stylesheet" type="text/css">
<link href="../shared/css/tablas.css" rel="stylesheet" type="text/css">
<link href="../shared/css/ventanas.css" rel="stylesheet" type="text/css">
<style type="text/css">
<!--
.Estilo1 {font-weight: bold}
.Estilo2 {font-size: 14px}
-->
</style>
<link href="../shared/js/css_intra/datepickercontrol.css" rel="stylesheet" type="text/css">
</head>
<body>
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="12" class="cd-logo"><img src="../shared/imagebank/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Contabilidad Patrimonial</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?php print $ls_diasem." ".date("d/m/Y")." - ".date("h:i a ");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?php print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>	 </td>
  </tr>
  <tr>
    <td height="20" colspan="12" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="js/menu.js"></script></td>
  </tr>
  <tr>
    <td height="13" colspan="12" class="toolbar"></td>
  </tr>
  <tr>
    <td height="20" width="20" class="toolbar"><div align="center"><a href="javascript:ue_search('<? print $ls_codemp; ?>','<? print $ls_reporte; ?>');"><img src="../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" title="Imprimir" width="20" height="20" border="0"></a></div></td>
   <!--  <td class="toolbar" width="20"><div align="center"><a href="javascript:ue_openexcel('<? print $ls_codemp; ?>');"><img src="../shared/imagebank/tools20/excel.jpg" alt="Excel" title="Excel" width="20" height="20" border="0"></a></div></td>  -->
    <td class="toolbar" width="20"><a href="javascript: ue_cerrar();"><img src="../shared/imagebank/tools20/salir.gif" alt="Salir" title="Salir" width="20" height="20" border="0"></a><a href="javascript:ue_graficos('<? print $ls_codemp; ?>');"></a></td>
    <td class="toolbar" width="20"><div align="center"><img src="../shared/imagebank/tools20/ayuda.gif" alt="Ayuda" title="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="20"><div align="center"></div></td>
    <td class="toolbar" width="678">&nbsp;</td>
  </tr>
</table>
</div> 
<p>&nbsp;</p>
<form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_scg->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	//unset($io_fun_scg);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="450" border="0" align="center" cellpadding="0" cellspacing="1" class="formato-blanco">
    <tr>
      <td width="83"></td>
    </tr>
    <tr>
      <td height="24" colspan="3" align="center" class="titulo-ventana">Balance General </td>
    </tr>
    <tr style="display:none">
      <td height="24" align="center"><div align="right">Reporte en</div></td>
      <td width="235" height="24" align="center"><div align="left">
        <select name="cmbbsf" id="cmbbsf">
          <option value="0" selected>Bs.</option>
          <option value="1">Bs.F.</option>
        </select>
      </div></td>
      <td width="6" height="24" align="center">&nbsp;</td>
    </tr>
    <tr>
      <td height="72" colspan="3" align="center"><div align="left"></div>        <div align="left"></div>        <div align="left" class="style14"></div>        <div align="left">
        <table width="350" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
          <tr class="titulo-celdanew">
            <td height="13" colspan="5"><strong class="titulo-celdanew">Mes y A&ntilde;o</strong></td>
            </tr>
          <tr>
            <td width="28" height="22">&nbsp;</td>
            <td width="36" height="22"><div align="right">Mes </div></td>
            <td width="89" height="22">
                <div align="left">
                  <select name="cmbmes" id="cmbmes">
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                  </select>
                </div></td>
            <td width="44" height="22"><div align="right">A&ntilde;o</div></td>
            <td width="94" height="22">		  <select name="cmbagno" id="cmbagno">
            <option value="2011">2011</option>
              <option value="2012">2012</option>
              <option value="2013">2013</option>
              <option value="2014">2014</option>
              <option value="2015">2015</option>
              <option value="2016">2016</option>
              <option value="2017">2017</option>
              <option value="2018">2018</option>
              <option value="2019">2019</option>
              <option value="2020">2020</option>
              <option value="2021">2021</option>
              <option value="2022">2022</option>
              <option value="2023">2023</option>
              <option value="2024">2024</option>
              <option value="2025">2025</option>
              <option value="2026">2026</option>
              <option value="2027">2027</option>
              <option value="2028">2028</option>
              <option value="2029">2029</option>
              <option value="2030">2030</option>
            </select></td>
          </tr>
          <tr class="titulo-celdanew">
            <td height="13" colspan="5">Nivel de la Cuenta </td>
          </tr>
          <tr>
            <td height="22">&nbsp;</td>
            <td height="22"><div align="right">Nivel</div></td>
            <td height="22" colspan="2"><div align="left">
              <select name="cmbnivel" id="cmbnivel">
			  <?php 
			  	$li_totniv= $io_fun_scg->uf_obtener_niveles();
				for($i=1;$i<=$li_totniv;$i++){
			   		echo '<option value="'.$i.'">'.$i.'</option>';
			  	}
			  	unset($io_fun_scg);
			  ?>
			  </select>
            </div>
            </td>
            <td height="22">&nbsp;</td>
          </tr>
          <tr class="titulo-celdanew">
            <td height="13" colspan="5">Titulo del Reporte</td>
          </tr>
          <tr>
            <td height="22"  colspan="3">
            <div align="left">
          			Estandar
            		 <input name="rbtitulo" type="radio" class="sin-borde" value="tit1" checked><br>
            		<!-- Titulo Mensual
					<input name="rbtitulo" type="radio" class="sin-borde" value="tit2"><br>
					Titulo Trimestral
					<input name="rbtitulo" type="radio" class="sin-borde" value="tit3"><br>
					Titulo Anual
					<input name="rbtitulo" type="radio" class="sin-borde" value="tit4"><br>-->
				</div>
            </td>
          </tr>
        </table>
        </div></td>
    </tr>
    <tr>
      <td height="22" colspan="3" align="center"></td>
    </tr>
  </table>
</form>      
</body>
<script language="JavaScript">
function ue_search(codemp,formato)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		cmbnivel=f.cmbnivel.value;
		cmbmes=f.cmbmes.value;
		cmbagno=f.cmbagno.value;
		tiporeporte=f.cmbbsf.value;
		if(cmbnivel=="s1")
		{
			alert ("Debe Seleccionar los Parametros de Busqueda");
		}
		else
		{
			//if(f.rbtitulo[0].checked){
				pagina="reportes/"+formato+"?hidcodemp="+codemp+"&cmbmes="+cmbmes;
				pagina=pagina+"&cmbagno="+cmbagno+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte+"&tituloreporte=N";
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			//}

		/*	if(f.rbtitulo[1].checked){
				pagina="reportes/"+formato+"?hidcodemp="+codemp+"&cmbmes="+cmbmes;
				pagina=pagina+"&cmbagno="+cmbagno+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte+"&tituloreporte=M";
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			}

			if(f.rbtitulo[2].checked){
				pagina="reportes/"+formato+"?hidcodemp="+codemp+"&cmbmes="+cmbmes;
				pagina=pagina+"&cmbagno="+cmbagno+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte+"&tituloreporte=T";
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			}

			if(f.rbtitulo[3].checked){
				pagina="reportes/"+formato+"?hidcodemp="+codemp+"&cmbmes="+cmbmes;
				pagina=pagina+"&cmbagno="+cmbagno+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte+"&tituloreporte=A";
				window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
			}*/
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaci�n");
   	}		
}

function ue_graficos(codemp)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		cmbnivel=f.cmbnivel.value;
		cmbmes=f.cmbmes.value;
		cmbagno=f.cmbagno.value;
		tiporeporte=f.cmbbsf.value;
		if(cmbnivel=="s1")
		{
			alert ("Debe Seleccionar los Parametros de Busqueda");
		}
		else
		{
			pagina="reportes/sigesp_scg_rpp_balance_general_gtorta.php?hidcodemp="+codemp+"&cmbmes="+cmbmes;
			pagina=pagina+"&cmbagno="+cmbagno+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte;
			window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");
		}
   	}
	else
   	{
 		alert("No tiene permiso para realizar esta operaci�n");
   	}		
}

function ue_openexcel(codemp)
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
		cmbnivel=f.cmbnivel.value;
		cmbmes=f.cmbmes.value;
		cmbagno=f.cmbagno.value;
		tiporeporte=f.cmbbsf.value;
		if(cmbnivel=="s1")
		{
			alert ("Debe Seleccionar los Parametros de Busqueda");
		}
		else
		{
			pagina="reportes/sigesp_scg_rpp_balance_general_excel.php?hidcodemp="+codemp+"&cmbmes="+cmbmes;
			pagina=pagina+"&cmbagno="+cmbagno+"&cmbnivel="+cmbnivel+"&tiporeporte="+tiporeporte;
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
</script>
</html>