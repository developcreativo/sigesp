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
require_once("../../../class_folder/utilidades/class_funciones_srh.php");
$io_fun_srh=new class_funciones_srh('../../../../');
$io_fun_srh->uf_load_seguridad("SRH","sigesp_srh_r_listado_solicitudes_empleo.php",$ls_permisos,$la_seguridad,$la_permisos);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
   
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title >Reporte Listado de Solicitudes de Empleo</title>
<meta http-equiv="" content="text/html; charset=iso-8859-1">
<style type="text/css">
<!--
body {
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-color: #EFEBEF;
}

a:link {
	color: #006699;
}
a:visited {
	color: #006699;
}
a:active {
	color: #006699;
}

-->
</style>
<script type="text/javascript" language="JavaScript1.2" src="../../../public/js/librerias_comunes.js"></script>



</head>

<body >
<table width="762" border="0" align="center" cellpadding="0" cellspacing="0" class="contorno">
  <tr>
    <td height="30" colspan="11" class="cd-logo"><img src="../../../public/imagenes/header.jpg" width="778" height="40"></td>
  </tr>
   <tr>
    <td width="432" height="20" colspan="11" bgcolor="#E7E7E7">
		<table width="762" border="0" align="center" cellpadding="0" cellspacing="0">
			  <td width="432" height="20" bgcolor="#E7E7E7" class="descripcion_sistema">Sistema de Recursos Humanos</td>
			    <td width="346" bgcolor="#E7E7E7"><div align="right"><span class="letras-pequenas"><b><?PHP print date("j/n/Y")." - ".date("h:i a");?></b></span></div></td>
				<tr>
	  	      <td height="20" bgcolor="#E7E7E7" class="descripcion_sistema">&nbsp;</td>
	  	      <td bgcolor="#E7E7E7" class="letras-pequenas"><div align="right"><b><?PHP print $_SESSION["la_nomusu"]." ".$_SESSION["la_apeusu"];?></b></div></td> </tr>
	  	</table>
	 </td>
  </tr>
 <tr>
    <td height="20" colspan="11" bgcolor="#E7E7E7" class="cd-menu"><script type="text/javascript" language="JavaScript1.2" src="../../js/menu/menu.js"></script></td>
  </tr>
  <tr>
    <td width="780" height="13" colspan="11" class="toolbar"></td>
  </tr>
  
  <tr>
   <td height="20" width="25" class="toolbar"><div align="center"><a href="javascript: ue_print();"><img src="../../../../shared/imagebank/tools20/imprimir.gif" alt="Imprimir" width="20" height="20" border="0"></a></div></td>
   <td class="toolbar" width="24"><div align="center"><a href="javascript: ue_cerrar();"><img src="../../../public/imagenes/salir.gif" alt="Salir" width="20" height="20" border="0"></a></div></td>
    <td class="toolbar" width="24"><div align="center"><img src="../../../public/imagenes/ayuda.gif" alt="Ayuda" width="20" height="20"></div></td>
    <td class="toolbar" width="24"><div align="center"></div></td>
    <td class="toolbar" width="618">&nbsp;</td>
  </tr>
</table>
<?php

	

	$arre=$_SESSION["la_empresa"];
	$ls_empresa=$arre["codemp"];
	if (array_key_exists("operacion",$_POST))
	{
		$ls_operacion=$_POST["operacion"];
	}
	else
	{
		$ls_operacion="";
		}

	
	
	//
?>

<p>&nbsp;</p>
<div align="center">
  
          <form name="form1" method="post" action="">
<?php
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
	$io_fun_srh->uf_print_permisos($ls_permisos,$la_permisos,$ls_logusr,"location.href='sigespwindow_blank.php'");
	unset($io_fun_srh);
//////////////////////////////////////////////         SEGURIDAD               /////////////////////////////////////////////
?>
<table width="600" height="138" border="0" align="center" cellpadding="0" cellspacing="0" class="formato-blanco">
  <tr>
    <td height="136">
      <p>&nbsp;</p>
      <table width="550" border="0" align="center" cellpadding="1" cellspacing="0" class="formato-blanco">
        <tr class="titulo-ventana">
          <td height="20" colspan="6" class="titulo-ventana">Reporte Listado de Solicitudes de Empleo</td>
        </tr>
       
        <tr>
          <td height="20" colspan="6" class="titulo-celdanew">Intervalo de Fecha </td>
          </tr>
        <tr>
          <td width="136" height="22"><div align="right"> Desde </div></td>
          <td width="161"><div align="left"><input name="txtfecini" type="text" id="txtfecini"   size="16"   style="text-align:center" readonly > <input name="reset" type="reset" onclick="return showCalendar('txtfecini', '%d/%m/%Y');" value=" ... " /> </div></td>
          <td width="212"><div align="left">Hasta 
            <input name="txtfecfin" type="text" id="txtfecfin"   size="16"   style="text-align:center" readonly >
            <input name="reset2" type="reset" onClick="return showCalendar('txtfecfin', '%d/%m/%Y');" value=" ... " />
          </div></td>
          <td width="33" colspan="3"><div align="left"></div></td>
        </tr>
        <tr>
          <td height="22" td colspan="6" class="titulo-celdanew">Intervalo de Solicitudes </td>
        </tr>
        <tr>
          <td height="22"><div align="right"> Desde </div></td>
          <td><input name="txtnrosoldes" type="text" id="txtnrosoldes"   size="16"   style="text-align:center" readonly >
            <a href="javascript:ue_buscarsolicituddesde();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo Factores de Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
          <td>Hasta
            <input name="txtnrosolhas" type="text" id="txtnrosolhas"   size="16"   style="text-align:center" readonly >
            <a href="javascript:ue_buscarsolicitudhasta();"><img src="../../../public/imagenes/buscar.gif" alt="Cat&aacute;logo Factores de Evaluaci&oacute;n" name="buscartip" width="15" height="15" border="0" id="buscartip"></a></td>
          <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
          <td height="20" td colspan="6" class="titulo-celdanew">G&eacute;nero</td>
        </tr>
        <tr>
          <td height="22"><div align="right">G&eacute;nero</div></td>
          <td><select name="cmbsexper" id="cmbsexper">
            <option value="">--Seleccione--</option>
            <option value="F" >Femenino</option>
            <option value="M" >Masculino</option>
          </select></td>
          <td>&nbsp;</td>
          <td colspan="3">&nbsp;</td>
        </tr>
              
      
        <tr>
          <td height="20" colspan="6" class="titulo-celdanew"><div align="right" class="titulo-celdanew">Ordenado por </div></td>
          </tr>
        <tr>
          <td height="22"><div align="right">N&uacute;mero de Solicitud </div></td>
          <td colspan="4"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="1" checked>
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Fecha de Solicitud</div></td>
          <td colspan="4"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="2">
          </div></td>
        </tr>
        <tr>
          <td height="22"><div align="right">Apellido del Solicitante</div></td>
          <td colspan="4"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="3">
          </div></td>
        </tr>
		<tr>
          <td height="22"><div align="right">Nombre del Solicitante</div></td>
          <td colspan="4"><div align="left">
            <input name="rdborden" type="radio" class="sin-borde" value="4">
          </div></td>
        </tr>
        <tr>
          <td height="22"><input name="txttipo" type="hidden" id="txttipo" size="2" maxlength="2" value="R" readonly></td>
          <td colspan="4"> <div align="right"></div></td>
        </tr>
      </table>
      <p>&nbsp;</p>
	  <input name="hidcontrol" type="hidden" id="hidcontrol" value="2"></td>
  </tr>
</table>
</form>      
</html>


<script language="javascript">


function ue_buscarsolicituddesde()
{
	window.open("../catalogos/sigesp_srh_cat_solicitud_empleo.php?valor_cat=0&tipo=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
}

function ue_buscarsolicitudhasta()
{
	f=document.form1;
	if(f.txtnrosoldes.value!="")
	{
		window.open("../catalogos/sigesp_srh_cat_solicitud_empleo.php?valor_cat=0&tipo=2","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=530,height=400,left=50,top=50,location=no,resizable=no");
	}
	else
	{
		alert("Debe seleccionar un una solicitud desde.");
	}
}


function ue_print()
{
	f=document.form1;
	li_imprimir=f.imprimir.value;
	if(li_imprimir==1)
	{	
	    fechades=f.txtfecini.value;
		fechahas=f.txtfecfin.value;
		nrosoldes= f.txtnrosoldes.value;
		nrosolhas= f.txtnrosolhas.value;
		cmbsexo=f.cmbsexper.value;
	  /* if (fechades=="")
	   {
	     alert ('Debe llenar el intervalo de fecha desde');
	   }	
	  else  if (fechahas=="")
	   {
	     alert ('Debe llenar el intervalo de fecha hasta');
	   }	
	  else  if (nrosoldes=="")
	   {
	     alert ('Debe llenar el intervalo de soliciutd desde');
	   }
	   else  if (nrosolhas=="")
	   {
	     alert ('Debe llenar el intervalo de soliciutd hasta');
	   }	
		else
		{*/
	     if(nrosoldes<=nrosolhas)
		 {
		 if(f.rdborden[0].checked==true)
		    {
			  orden="1";
		     }
		   if(f.rdborden[1].checked==true)
		     {
			   orden="2";
			 }
			if(f.rdborden[2].checked==true)
			 {
			   orden="3";
			  }	  
			if(f.rdborden[3].checked)
			  {
				orden="4";
			   }	
			
			     pagina="../../../reporte/sigesp_srh_rpp_listado_solicitudes_empleo.php?fechades="+fechades+"&fechahas="+fechahas;
				 pagina=pagina+"&nrosoldes="+nrosoldes+"&nrosolhas="+nrosolhas+"&orden="+orden+"&cmbsexo="+cmbsexo+"";
			     window.open(pagina,"Reporte","menubar=no,toolbar=no,scrollbars=yes,width=800,height=600,resizable=yes,location=no");	
		   } //fin del if
		  else
			{
			 alert("El rango del personal est� erroneo");
			}
	    //}
   	 } // fin del if imprimir
   else		
    {
	 alert("No tiene permiso para realizar esta operaci�n");
	 }
} // fin de la funcion print




function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}


//FUNCIONES PARA EL CALENDARIO

// Esta es la funcion que detecta cuando el usuario hace click en el calendario, necesaria
function selected(cal, date) {
  cal.sel.value = date; // just update the date in the input field.
                           
  if (cal.dateClicked )
      cal.callCloseHandler();
}


function closeHandler(cal) {
  cal.hide();                        // hide the calendar

  _dynarch_popupCalendar = null;
}


function showCalendar(id, format, showsTime, showsOtherMonths) {
  var el = document.getElementById(id);
  if (_dynarch_popupCalendar != null) {
    // we already have some calendar created
    _dynarch_popupCalendar.hide();                 // so we hide it first.
  } else {
    // first-time call, create the calendar.

    var cal = new Calendar(1, null, selected, closeHandler);
    if (typeof showsTime == "string") {
      cal.showsTime = true;
      cal.time24 = (showsTime == "24");
    }
    if (showsOtherMonths) {
      cal.showsOtherMonths = true;
    }
    _dynarch_popupCalendar = cal;                  // remember it in the global var
    cal.setRange(1900, 2070);        // min/max year allowed.
    cal.create();
  }
  _dynarch_popupCalendar.setDateFormat(format);    // set the specified date format
  _dynarch_popupCalendar.parseDate(el.value);      // try to parse the text in field
  _dynarch_popupCalendar.sel = el;                 // inform it what input field we use
 _dynarch_popupCalendar.showAtElement(el, "T");        // show the calendar

  return false;
}


</script> 