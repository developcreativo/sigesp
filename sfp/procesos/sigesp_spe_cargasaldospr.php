<?php
require_once('../class_folder/dao/sigesp_sfp_plan_unico_reDao.php');
require_once('../class_folder/dao/sigesp_sfp_saldosconDao.php');
require_once('../class_folder/dao/sigesp_sfp_cuentascontDao.php');
require_once('../librerias/php/general/funciones.php');
require_once("../librerias/php/general/class_funciones_seguridad.php");
require_once('../class_folder/dao/sigesp_sfp_planingresoDao.php');
require_once('../class_folder/dao/sigesp_sfp_variacionDao.php');
require_once('../librerias/php/general/CrearReporte.php');
require_once('../librerias/php/general/Json.php');


if ($_POST['ObjSon']) 		
{
	$submit = str_replace("\\","",$_POST['ObjSon']);
	$json = new Services_JSON;
	$ArJson = $json->decode($submit);
	$oSaldos = new SaldosCont();
	PasarDatos($oSaldos,$ArJson);
	$ArObjetos = array();
	$Evento = $ArJson->oper;
	if($ArJson->datos)
	{
		$Evento = $ArJson->oper;
	//	$Nivel= DatosNivel($ArJson->numest);
		for($j=0;$j<=count($ArJson->datos)-1;$j++)
		{
			$ArObjetos[$j] = new SaldosCont();
			PasarDatos($ArObjetos[$j],$ArJson->datos[$j]);	
			//PasarDatos(&$Nivel['obj'],$ArJson->datos[$j]);
			
		}		
	}
	
	switch ($Evento)
	{
		case 'ObtenerSesion':
    		if(!array_key_exists("la_logusr",$_SESSION))
			{
				echo "|nosesion";
				break;	
			}
			$io_fun_activo=new class_funciones_seguridad();
			$io_fun_activo->uf_load_seguridad("SFP",$ArJson->pantalla,$ls_permisos,$la_seguridad,$la_permisos);
			if($ls_permisos===true)
			{
				$jla_seguridad = $json->encode($la_seguridad);
				$jla_permisos = $json->encode($la_permisos);
				echo "{$jla_seguridad}|{$jla_permisos}|{$ls_permisos}";
			}
			else
			{
				echo "0|0|0";
			}
		break;    	
		case 'incluir':
			//var_dump($ArObjetos);
			//die();
			foreach($ArObjetos as $oSaldos)
			{
				$existe = $oSaldos->buscarCuenta();
				if ($existe)
				{
					if($oSaldos->actualizar())
					{
					/*
						$oVariacion= new variacionDao();
						$oVariacion->cuentacontable=$oSaldos->sc_cuenta;
						$cuentaVarHaber = $oVariacion->LeerCuentaHaber();
						
						if($cuentaVarHaber->fields['cuentahaber']!='')
						{
						
							$oplaIn = new planIngreso();
							$oplaIn->ano_presupuesto=date("Y")+1;
							$oplaIn->codemp='0001';
							$oplaIn->sig_cuenta=$cuentaVarHaber->fields['cuentahaber'];
							$oplaIn->sig_codemp='0';
							$oPlaIn->montoanoanterior=000;
							$oPlaIn->montoanoactual=$oSaldos->monto_anest;
							$oplaIn->enero=$oSaldos->monto_anest;
							$oplaIn->disponible=$oSaldos->monto_anest;
							$oplaIn->febrero=00;
							$oplaIn->marzo=00;
							$oplaIn->abril=00;
							$oplaIn->mayo=00;
							$oplaIn->julio=00;
							$oplaIn->junio=00;
							$oplaIn->agosto=00;
							$oplaIn->septiembre=00;
							$oplaIn->octubre=00;
							$oplaIn->noviembre=00;
							$oplaIn->diciembre=00;
							if($oplaIn->Incluir())
							{
								$IngresoVar=1;
							}
							else
							{
								$IngresoVar=0;
							}
							
						}
						
					*/	
						
						
						$est =  1;
					}
					else
					{
						$est = 0;
					}
					
				}
				else
				{				
					if($oSaldos->incluir())
					{
						
						$oVariacion= new variacionDao();
						$oVariacion->cuentacontable=$oSaldos->sc_cuenta;
						$cuentaVarHaber = $oVariacion->LeerCuentaHaber();
						
						if($cuentaVarHaber->fields['cuentahaber']!='')
						{
							$oplaIn = new planIngreso();
							$oplaIn->ano_presupuesto=date("Y")+1;
							//$oplaIn->codemp='0001';
							$oplaIn->codemp=$oSaldos->codemp;
							$oplaIn->sig_cuenta=$cuentaVarHaber->fields['cuentahaber'];
							$oplaIn->sig_codemp='0';
							$oPlaIn->montoanoanterior=000;
							$oPlaIn->montoanoactual=$oSaldos->monto_anest;
							$oplaIn->enero=$oSaldos->monto_anest;
							$oplaIn->disponible=$oSaldos->monto_anest;
							$oplaIn->febrero=00;
							$oplaIn->marzo=00;
							$oplaIn->abril=00;
							$oplaIn->mayo=00;
							$oplaIn->junio=00;
							$oplaIn->julio=00;
							$oplaIn->agosto=00;
							$oplaIn->septiembre=00;
							$oplaIn->octubre=00;
							$oplaIn->noviembre=00;
							$oplaIn->diciembre=00;
							if($oplaIn->Incluir())
							{
								$IngresoVar=1;
							}
							else
							{
								$IngresoVar=0;
							}
							
						}

						$est =  1;

					}
					else
					{
						$est = 0;
					}
				}	
			}
			if($est==1)
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;
			
		/*	case 'actualizar':
			if($oSaldos->Modificar())
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;*/
	
		case 'eliminar':
			if($oSaldos->Eliminar()==1)
			{
				echo "|1";
			}
			else
			{
				echo "|0";
			}
			break;

		case 'buscarcadena':
			$Datos = $oSaldos->LeerTodasCuentas($GLOBALS["criterio"],$GLOBALS["cadena"]);
			$ObjSon = GenerarJson2($Datos);
			echo $ObjSon;
			break;
		case 'catalogo':
			$Data = $oSaldos->LeerTodas($_SESSION["la_empresa"]["codemp"]);
			$TextJso = array("raiz"=>$Data);
			$TextJson = $json->encode($TextJso);
			echo $TextJson;
			break;	
	}	
	
}

function PasarDatos(&$ObjDao,$ObJson)
{
	$ArDao = $ObjDao->getAttributeNames();
	foreach($ObjDao as $IndiceD =>$valorD)
	{
		foreach($ObJson as $Indice =>$valor)
		{
			if($Indice==$IndiceD && $Indice!="ano_presupuesto" && $Indice!="codemp")
			{
				if ($Indice=="monto_anest" || $Indice=="monto_anreal")
				{	
					$pos = strpos($valor,",");
					if($pos!=false)
					{
						echo "{$pos}-";
						$valor = substr($valor,0,$pos);
						$valor=str_replace(".","",$valor);
					}
				}
				$ObjDao->$Indice = utf8_decode($valor);					
			}
			else
			{
				
				$GLOBALS[$Indice] = $valor;
				
			}
			
			
			
		}
	}
}

function GenerarJson($Datos)
{
	global $ArJson,$json;
	$obj = $Datos[0];
		if(is_object($obj))
		{
			foreach($obj as $Propiedad=>$valor)
			{
				$i=0;
				foreach($Datos as $obj)
				{
		
					if(array_key_exists($Propiedad,$ArJson))	
					{	
						
						$arRegistros[$i][$Propiedad]= $Datos[$i]->$Propiedad;
						$i++;
					}
				
				}
		
					
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;
			
		}
}


function GenerarJson2($Datos)
{
			global $json;
			$i=0;
			while($Datos2=$Datos->FetchRow())
			{
			
				foreach($Datos2 as $Propiedad=>$valor)
				{
					if(!is_numeric($Propiedad))
					{
						if ((is_numeric($valor) && $valor!="") && ($Propiedad==="monto_anest" || $Propiedad=="monto_anreal"))
						{							
							$valor = number_format($valor,2,",",".");	
						}
					
						$arRegistros[$i][$Propiedad]= utf8_encode($valor);
					}		
				}
		
				$i++;		
			}
			//aqui se pasa el arreglo de arreglos a un objeto json
			$TextJso = array("raiz"=>$arRegistros);
			$TextJson = $json->encode($TextJso);
			return $TextJson;
			
		
}


function GenerarJsonDeObjetos($Datos)
{
		global $json;
		$i=0;
		foreach($Datos as $Datos2)
		{
			foreach($Datos2 as $Propiedad=>$valor)
			{
				if(!is_numeric($Propiedad))
				{
					$arRegistros[$i][$Propiedad]= utf8_encode($valor);
				}		
			}
		
			$i++;		
		}
			//aqui se pasa el arreglo de arreglos a un objeto json
		$TextJso = array("raiz"=>$arRegistros);
		$TextJson = $json->encode($TextJso);
		return $TextJson;					
}

?>