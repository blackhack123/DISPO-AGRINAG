	$(document).ready(function () {    
    	/*Parser personalizado para el plugin tablesorter, que permite ordenador con el separador de miles*/
		$.tablesorter.addParser({id: 'numerico',is: function(s){return false;},			
			format: function(s) {
				s = s.replace("$","");
				s = s.replace(/,/g,"");
				s = parseFloat(s);
				return s;
				//return s.replace(/[\,\.]/g,'');
			},
			type: 'numeric'
		});	
	
		
		//efecto ZEBRA
		jQuery(".tablesorter > tbody").each(function(i,e){
			$(e).find("tr:odd").removeClass("row_odd");
			$(e).find("tr:odd").removeClass("row_even");
			$(e).find("tr:even").removeClass("row_odd");
			$(e).find("tr:even").removeClass("row_even");

			$(e).find("tr:odd").addClass("row_odd");
			$(e).find("tr:even").addClass("row_even");
		});

		//Efecto MouseOver
		$(".tablesorter > tbody > tr").on("mouseover",function() {
			if ($(this).hasClass("row_odd")){
				$(this).removeClass("row_odd");
				$(this).addClass("row_odd_over");
				return;
			}//end if
			if ($(this).hasClass("row_even")){
				$(this).removeClass("row_even");
				$(this).addClass("row_even_over");
				return;
			}//end if			
		});			

		//Efecto MouseOut
		$(".tablesorter > tbody > tr").on("mouseout",function() {	
			if ($(this).hasClass("row_odd_over")){
				$(this).removeClass("row_odd_over");
				$(this).addClass("row_odd");
				return;
			}//end if
			if ($(this).hasClass("row_even_over")){
				$(this).removeClass("row_even_over");
				$(this).addClass("row_even");
				return;
			}//end if			
		});			
		
		$(".tablesorter").on("sortStart",function() { 
			$(".tablesorter > tbody").find("tr:odd").removeClass("row_odd");
			$(".tablesorter > tbody").find("tr:odd").removeClass("row_even");
			$(".tablesorter > tbody").find("tr:even").removeClass("row_odd");
			$(".tablesorter > tbody").find("tr:even").removeClass("row_even");
		}).on("sortEnd",function() {
			$(".tablesorter > tbody").find("tr:odd").addClass("row_odd");
			$(".tablesorter > tbody").find("tr:even").addClass("row_even");
		}); 			

		
		$(".tablesorter_numberrow").on("sortEnd",function() {
		    var i = 1;
		    $(this).find("tbody > tr").each(function(){
		    	//console.log('entra ' + i);
		        $(this).find("td:eq(0)").text(i);
		        i++;
		    });
		});		
		
		//Indicar al puntero del rat�n que no se ponga la mano sino la flecha del puntero por defecto
		$(".sorter-false").css({ "cursor": 'default'});
	});