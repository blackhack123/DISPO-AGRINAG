/*
Creado por: 	Moroni Salazar
Fecha Creación:	26-jun-2013
Motivo:			Validación de la fecha
*/

	function ValidateCedula(cedula){
		var NC, LM_Cont, valor01;
		
		//Se valida que la longitud no sea igual a CERO
		if (cedula.length == 0){
			return false;		
		}//end if
		
		//Se valida que el número de cédula sea numérico
		if (isNaN(cedula)){
			return false;
		}//end if

		len_cedula = (parseFloat(cedula) + '').length;
		switch (len_cedula){
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
			case 7:
			case 8:
			case 11:
				//alert('Cédula no válida');
				return false;
				break;
			default:
				if ((len_cedula==15)&&(cedula.substring(0,1)==9)){
					//alert('cedula correcta');
					return true;
				}//end if
				switch (len_cedula){
					case 9:
						NC = '00'+ parseFloat(cedula);
						break;
					case 10:
						NC = '0'+ parseFloat(cedula);					
						break;
					case 12:
						NC = '0'+ parseFloat(cedula);
						break;
					case 13:
						NC = ''+ parseFloat(cedula);
						break;
				}//end switch
				var Total1, Total2;
				var R1, R2, NU;
				Total1=0;
				for (LM_cont=1;LM_cont <= NC.length - 2;LM_cont=LM_cont+2){
					valor01 =  NC.substring(LM_cont,LM_cont+1); 
					R1 = parseFloat(valor01) * 2;					
					if (R1>9){
						R1 = R1 + '';
						Rx = R1;
						R1 = parseFloat(R1.substring(0,1));
						if (Rx.length==2){
							R1 = parseFloat(R1) + parseFloat(Rx.substring(1,2))
						}//end if
					}//End if
					R2 =  R1 + '';
					Total1 = Total1 + parseFloat(R2.substring(R2.length-1,R2.length));
				}//end for
				Total2 = Total1;
				for (ML_cont=0;ML_cont<=NC.length-2;ML_cont=ML_cont+2){
					valor01 = NC.substring(ML_cont, ML_cont+1);
					Total2 = Total2 + parseFloat(valor01);
				}//End fo
				NU = Total2+'';
				Total2 = 10 - NU.substring(NU.length-1,NU.length);
				NU = Total2+'';
				//alert(cedula);
				if (cedula.length>11){				
					return true;
				}else{
					if ((NU.substring(NU.length-1, NU.length))==(NC.substring(NC.length-1, NC.length))){
						return true;
					}else{
						return false;
					}//end if
				}//end if
		}//end switch
   	}//end ValidaCedula