// JavaScript Document
function objetoAjax()
{
	var xmlhttp=false;
	try {
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	} catch (e) {
		try {
		   xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
		} catch (E) {
			xmlhttp = false;
  		}
	}

	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
		xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}


var url= "../../php/sigesp_srh_a_tipodeduccion.php";
var metodo="POST";
var img="<img src=\"../../../public/imagenes/progress.gif\"> ";
var ajax=objetoAjax();






function ue_validaexiste()
{
 	var divResultado= $('existe');
	var paran="txtcodtipded="+$F('txtcodtipded');
	if (($F('txtcodtipded')!="") && ($F('hidstatus')!='C'))
	{
	   ajax.open(metodo,url+"?valor=existe",true);
	   ajax.onreadystatechange=function() 
	   {
		  if (ajax.readyState==4)
		  {
				if(ajax.status==200)
				{
					 divResultado.innerHTML = ajax.responseText
					 if(divResultado.innerHTML=='')
					 {
					 }
					 else
					 {
						  Field.clear('txtcodtipded');
						  Field.activate('txtcodtipded');
						  
						  alert(divResultado.innerHTML);
						 
					 }
				}
				else
				{
					 alert('ERROR '+ajax.status);
				}
		  }
	   }
	
      ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	  ajax.send(paran);
	}
}//fin ue_validaexiste()


function ue_cancelar()
{
  document.form1.reset();
  document.form1.hidstatus.value="";
   scrollTo(0,0);
}

function ue_nuevo()
{
  function onNuevo(respuesta)
  {
	ue_cancelar();
	$('txtcodtipded').value  = trim(respuesta.responseText);
	$('txtdentipded').focus();
  }	

  params = "operacion=ue_nuevo";
  new Ajax.Request(url,{method:'get',parameters:params,onComplete:onNuevo});
}




function ue_validavacio()
{
  lb_valido=true;
  f=document.form1;
  
if(f.txtcodtipded.value=="")
  {
		alert('Falta Codigo del Tipo de Deducci�n de Seguro');
		lb_valido=false;
   }
   else if(f.txtdentipded.value=="")
   {
	   alert('Falta la Denominacion del Tip de Deducci�n de Seguro');
	   lb_valido=false;
   }
 
   
   return lb_valido;
 

}

function ue_guardar_registro()
{
	     //donde se mostrar� lo resultados
			  divResultado = document.getElementById('mostrar');
			// divMostrar = document.getElementById('resultado');
			  divResultado.innerHTML= img;
			
			  //valores de las cajas de texto
			  
			  dentipded=document.form1.txtdentipded.value;
			  codtipded=document.form1.txtcodtipded.value;
			
			  ajax=objetoAjax();
			  //uso del medoto POST
			  
			  
			  ajax.open("POST",url+"?valor=guardar",true);
			  ajax.onreadystatechange=function() 
			  {
				  if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				  
				  
				   if(divResultado.innerHTML)
					{
					   
					   
					   if(ajax.status==200)
					   {
					   	alert(divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					   //divResultado.innerHTML ='';
					}
					
				
				  }
			
				 ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtdentipded="+dentipded+"&txtcodtipded="+codtipded);
   

}



function ue_guardar()
{
	lb_valido=ue_validavacio();
	
	if(lb_valido)
	{
		
		ue_guardar_registro();
	
	}//lb_valido
}//ue_guardar








function ue_eliminar()
{
		
		
		if(document.form1.hidstatus.value=="C")
		{
			
			
			if (confirm("�Esta seguro de eliminar este registro?"))
			{
		
		
			  //donde se mostrar� lo resultados
  
			  divResultado = document.getElementById('mostrar');
			    divResultado.innerHTML= img;
			
			  codtipded=document.form1.txtcodtipded.value;
			 
			  //instanciamos el objetoAjax
			  ajax=objetoAjax();
			  //uso del medoto POST
			  //archivo que realizar� la operacion
		
			  
			  ajax.open("POST",url+"?valor=eliminar",true);
			  ajax.onreadystatechange=function() 
			  {
				  if (ajax.readyState==4)
				  {
				  //mostrar resultados en esta capa
				  divResultado.innerHTML = ajax.responseText;
				  
				  
				   if(divResultado.innerHTML)
					{
					   
					   
					   if(ajax.status==200)
					   {
					   	alert(divResultado.innerHTML);
					   }
					   else
					   {
						alert(ajax.statusText);   
						}
					}
					} 
				ue_nuevo();
			  }
   ajax.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
  //enviando los valores
   ajax.send("txtcodtipded="+codtipded);
   			 }
		}
		else
	   {
		
		alert('Debe elegir un Tipo de Deducci�n de Seguro del Catalogo');
		
		}
}





function ue_buscar()
{
	f=document.form1;
	li_leer=f.leer.value;
	if(li_leer==1)
	{
		window.open("../catalogos/sigesp_srh_cat_tipodeduccion.php?valor_cat=1","catalogo","menubar=no,toolbar=no,scrollbars=yes,width=518,height=400,left=50,top=50,location=no,resizable=yes");
	}
	else
	{
		alert("No tiene permiso para realizar esta operacion");
	}
}


function ue_cerrar()
{
	window.location.href="sigespwindow_blank.php";
}


  
  
  
