/*
Creado por: 	Moroni Salazar
Fecha Creación:	16-Abr-2012
Motivo:			Validación de la fecha
*/

	function ValidateCedulaRuc(xtipo, xruc) 
	{ 
		
		
		try{
			$validador = new ValidarIdentificacion();
			$resultado =  $validador->validarRuc('fgdgfgdfgdf');
			//$resultado =  $validador->validarRucSociedadPublica('1768152560001');
		}catch (ValidarIdentificacion_Cedula_Exception $e) {
			echo 'Cédula incorrecta: '.$e->getMessage();
		}catch (ValidarIdentificacion_Ruc_Exception $e) {
			echo 'RUC incorrecto: '.$e->getMessage();
		}catch (\Exception $e) {
			echo 'Identificacion incorrecta: '.$e->getMessage();
		}
		
			
		
		
		
	
	}//end ValidateCedulaRuc 

