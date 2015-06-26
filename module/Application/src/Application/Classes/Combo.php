<?php

namespace Application\Classes;


class Combo {

	public static $tipo_combo_dropdown = 1;
	public static $tipo_combo_datagrid = 2;
	
	public static $primer_registro 	= null;	
		
	public static function getPrimerRegistro()	{return self::$primer_registro;}	
	
	
	/*-----------------------------------------------------------------------------*/		
	public static function getComboDataArray($arrData, $id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "", $tipo_cbo = 1)
	/*-----------------------------------------------------------------------------*/		
	{
		$opciones = "";	
		if ($tipo_cbo==self::$tipo_combo_dropdown){
			if ($texto_1er_elemento!=''){
				$opciones = '<option value="" style="color:\''.$color_1er_elemento.'\'">'.$texto_1er_elemento.'</option>';
			}//end if
			
			$selected = "";
			
			foreach($arrData as $clave => $valor){
				$selected  = "";
	
				if ($id==$clave){
					$selected = "selected";	
				}//end if
				//die(var_dump($clave));
				$opciones = $opciones . '<option value="'.$clave.'" '.$selected.'>'.$valor.'</option>';
			}//end foreach
		}//end if

		if ($tipo_cbo==self::$tipo_combo_datagrid){
/*			if ($texto_1er_elemento!=''){
				$opciones = '<option value="" style="color:\''.$color_1er_elemento.'\'">'.$texto_1er_elemento.'</option>';
			}//end if
			
			$selected = "";
*/			
			$bd_1eravez = true;
			foreach($arrData as $clave => $valor){
				if (!$bd_1eravez){
					$opciones = $opciones . ';';
				}//end if
				$opciones = $opciones . $clave.':'.$valor;
				$bd_1eravez = false;
			}//end foreach
		}//end if
		
		
		return $opciones;
	}//end function


	/*-----------------------------------------------------------------------------*/		
	public static function getComboDataResultset($rsData, $campo_id, $campo_texto, $id, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "", $tipo_cbo = 1)
	/*-----------------------------------------------------------------------------*/		
	{
		$opciones = "";
		
		if ($tipo_cbo==self::$tipo_combo_dropdown){		
			if ($texto_1er_elemento!=''){
				$opciones = '<option value="" style="color:\''.$color_1er_elemento.'\'">'.$texto_1er_elemento.'</option>';
			}//end if
			
			$selected = "";
			
			if (isset($rsData))
			{
				foreach($rsData as $reg){
					$selected  = "";
		
					if ($reg[$campo_id]==$id){
						$selected = "selected";	
					}//end if
					$opciones = $opciones . '<option value="'.$reg[$campo_id].'" '.$selected.'>'.$reg[$campo_texto].'</option>';
				}//end foreach
			}//end if
		}//end if
		

		if ($tipo_cbo==self::$tipo_combo_datagrid){
			$bd_1eravez = true;
			foreach($rsData as $reg){
				if (!$bd_1eravez){
					$opciones = $opciones . ';';
				}//end if
				$opciones = $opciones . $reg[$campo_id].':'.$reg[$campo_texto];
				$bd_1eravez = false;
			}//end foreach
		}//end if		
		return $opciones;
	}//end function
	

	/*-----------------------------------------------------------------------------*/
	public static function getComboDataResultset_DisabledItem($rsData, $campo_id, $campo_texto, $campo_enabled, $id, $visible_only_restriction = FALSE, $texto_1er_elemento = "&lt;Seleccione&gt;", $color_1er_elemento = "", $tipo_cbo = 1)
	/*-----------------------------------------------------------------------------*/
	{
		$opciones = "";
		$bd_1er_registro = true;
	
		if ($tipo_cbo==self::$tipo_combo_dropdown){
			if ($texto_1er_elemento!=''){
				$opciones = '<option value="" style="color:\''.$color_1er_elemento.'\'">'.$texto_1er_elemento.'</option>';
			}//end if

			$selected = "";
				
			if (isset($rsData))
			{
				foreach($rsData as $reg){
					$selected  = "";
					$disabled = "";
					
					if ($reg[$campo_id]==$id){
						$selected = "selected";
					}//end if
					
					if (($campo_enabled!='restriccion_nivel_0') && ($reg[$campo_enabled]==0)){
						$disabled = "disabled";
					}//end if
					
					if (($visible_only_restriction==true)&&($campo_enabled!='restriccion_nivel_0')){
						if($reg[$campo_enabled]==1){
							$opciones = $opciones . '<option value="'.$reg[$campo_id].'" '.$selected.' '.$disabled.'>'.$reg[$campo_texto].'</option>';	

							if ($bd_1er_registro) self::$primer_registro = $reg;
							$bd_1er_registro = false;
						}//end if
					}else{
						$opciones = $opciones . '<option value="'.$reg[$campo_id].'" '.$selected.' '.$disabled.'>'.$reg[$campo_texto].'</option>';
						if ($bd_1er_registro)  self::$primer_registro = $reg;
						$bd_1er_registro = false;
					}//end if

				}//end foreach
			}//end if
		}//end if
	
	
/*		if ($tipo_cbo==self::$tipo_combo_datagrid){
			$bd_1eravez = true;
			foreach($rsData as $reg){
				if (!$bd_1eravez){
					$opciones = $opciones . ';';
				}//end if
				$opciones = $opciones . $reg[$campo_id].':'.$reg[$campo_texto];
				$bd_1eravez = false;
			}//end foreach
		}//end if
*/
		return $opciones;
	}//end function
	

	/*-----------------------------------------------------------------------------*/
	public static function getNombrePorId($arrData, $id)
	/*-----------------------------------------------------------------------------*/
	{
		foreach($arrData as $clave => $valor){
			if ($id==$clave){
				return $valor; 				
			}//end if
		}//end foreach
		
		return '';
	}//end function	
	
}//end class Combo
