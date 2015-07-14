/*
Creado por: 	Moroni Salazar
Fecha Creación:	16-Abr-2012
Motivo:			Validación de la fecha
*/

	function ValidateEsBisiesto(anio) 
	{ 
		var BISIESTO = false; 
		if(parseInt(anio)%4==0){ 
			if(parseInt(anio)%100==0){ 
				if(parseInt(anio)%400==0){ 
					BISIESTO=true;	
				}//end if
			}else{ 
				BISIESTO=true; 
			}//end if
		}//end if
		
		return BISIESTO;	
	}//end ValidateEsBisiesto 



	function ValidateFecha(valor)
	{
		var arr_fecha = valor.split('-');
		if (arr_fecha.length!=3){
			return false;	
		}//end if
		dia = arr_fecha[0];
		mes = arr_fecha[1];
		anio = arr_fecha[2];
		
		if (isNaN(anio)||isNaN(mes)||isNaN(dia)){
			return false;
		}//end if

		anio = parseInt(anio);
		mes = parseInt(mes);
		dia = parseInt(dia);
		
		/*-------------------Valido el Año------------------*/
		if (anio<1900){  
			return false  
		} //end if
		
		
		/*-------------------Valido el Mes------------------*/
		if (mes<1 || mes>12){  
			return false  
		}  
		
		/*-------------------Valido el Dia------------------*/
		if (dia<1 || dia>31){  
			return false  
		} 
		if (mes==1||mes==3||mes==5||mes==7||mes==8||mes==10||mes==12){
			//ya se valido anteriormente
		}//end if
		if (mes==4||mes==6||mes==9||mes==11){
			if (dia<1 || dia>30){
				return false;
			}
		}//end if
		if (mes==2){
			if (ValidateEsBisiesto(anio)){
				if (dia<1 || dia>29){
					return false;
				}
			}else{
				if (dia<1 || dia>28){
					return false;
				}				
			}
		}//end if

		return true;
	}//end function ValidateFecha
