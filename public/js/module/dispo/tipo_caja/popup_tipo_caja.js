

$(document).ready(function () 
{		
			function crearModal() { 
		
				
				//DIV MODAL
				var Modal = document.createElement('div');
				Modal.id = 'modal_popup_tipo_caja';
				Modal.className ='modal fade';
				Modal.setAttribute("data-backdrop", "static");
				Modal.setAttribute("role", "dialog");
				Modal.setAttribute("data-keyboard", false);
				document.body.appendChild(Modal);


				//DIV MODAL-DIALOG
				var dialog = document.createElement('div');
				dialog.className ='modal-dialog modal-dialog-fullscreen';
				dialog.setAttribute("role", "document");
				Modal.appendChild(dialog);

				//DIV CONTENT
				var content = document.createElement('div');
				content.className ='modal-content';
				dialog.appendChild(content);

				//DIV MODAL-HEADER
				 var header = document.createElement('div');
				header.className ='modal-header';
				content.appendChild(header);
				
				//TITLE
				var title = document.createElement('h4');
				//title.document.createTextNode('Box Type');
				title.appendChild( document.createTextNode("Box Type") );
				title.className = 'modal-title';
				header.appendChild(title);
				
				//BUTTON CLOSE
				 var closeBtn = document.createElement("BUTTON");
				closeBtn.setAttribute("type", "button");
				closeBtn.className = 'close';
				closeBtn.setAttribute("data-dismiss", "modal");
				closeBtn.setAttribute("aria-label", "Close");
				title.appendChild(closeBtn);
				
				//SPAN TO BUTTON
				var BtnSpan = document.createElement('span');
				BtnSpan.setAttribute("aria-hidden","true");
				BtnSpan.appendChild( document.createTextNode("x") );
				closeBtn.appendChild(BtnSpan);
				
				
				
				//MODAL BODY
				var body = document.createElement('div');
				body.className = 'modal-body';
				content.appendChild(body);
				
				//MODAL-FOOTER
				var footer = document.createElement('div');
	            footer.className = 'modal-footer';
	            content.appendChild(footer);
	            
	            
			}//end function crearModal
			
			
			$("#popup_tipo_caja").click(function() {
				crearModal();
				openModal();
			});//end funtion click
	
			function openModal() {
				
				$('#modal_popup_tipo_caja .modal-body').load("../../dispo/prueba/mantenimiento #row_box");
				$('#modal_popup_tipo_caja').modal('show');
			} //end openModal
		
}); //end document.ready
	