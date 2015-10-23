<?php
namespace Application\Classes;

use Application\Classes\PHPMailerApp;

class CorreoElectronico
{
	
	public function SendMail($emailDestino, $emailCC, $emailTitulo, $emailCuerpo, $emailReplyTo = null, $files = null)
	{
		
		$reader = new \Zend\Config\Reader\Ini();
		$config  = $reader->fromFile('ini/config.ini');

		$emailDestinatario_Defecto = '';
		if (!empty($config['email']['destinatario_defecto']))
		{
			$emailDestinatario_Defecto = $config['email']['destinatario_defecto'];
		}//end if

		
		/**
		 * This example shows making an SMTP connection with authentication.
		 */
	
		//SMTP needs accurate times, and the PHP time zone MUST be set
		//This should be done in your php.ini, but this is how to do it if you don't have access to that
		//date_default_timezone_set('Etc/UTC');

		$PHPMailerApp = new PHPMailerApp();
	
		//Create a new PHPMailer instance
		$mail = new \PHPMailer;
		//Tell PHPMailer to use SMTP
		$mail->isSMTP();
		//Enable SMTP debugging
		// 0 = off (for production use)
		// 1 = client messages
		// 2 = client and server messages
		$mail->SMTPDebug = 0;
		//Ask for HTML-friendly debug output
		$mail->Debugoutput = 'html';
		//Set the hostname of the mail server
		$mail->Host = "smtp.gmail.com";
		//Set the SMTP port number - likely to be 25, 465 or 587
		$mail->Port = 587;
		//Whether to use SMTP authentication
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "tls";
		//Username to use for SMTP authentication
		$mail->Username = "documentsale@agrinag.com";
		//Password to use for SMTP authentication
		$mail->Password = "web@Ag1nag$2015";
		
		//Set who the message is to be sent from
		$mail->setFrom('documentsale@agrinag.com', 'Documentos Agrinag');
				
		//Set an alternative reply-to address
		if (!empty($emailCC))
		{
			if (is_array($emailCC))
			{
				foreach ($emailCC as $email)
				{
					$mail->addCC($emailCC, $emailCC);
				}//end foreach			
			}else{				
				$mail->addCC($emailCC, $emailCC);
			}//end if
		}//end if

		if (!empty($emailReplyTo))
		{
			$mail->addReplyTo($emailReplyTo, $emailReplyTo);
		}//end if
			
		
		//Set who the message is to be sent to
		if (!empty($emailDestinatario_Defecto))
		{
			$mail->addAddress($emailDestinatario_Defecto, $emailDestinatario_Defecto);
			//die('paso01 *'.$emailDestinatario_Defecto);
		}else{
			if (is_array($emailDestino))
			{
				foreach ($emailDestino as $email)
				{
					$mail->addAddress($email, $email);
				}//end foreach
			}else{
				$mail->addAddress($emailDestino, $emailDestino);
			}//end if
			
			//die('paso02 *');
		}//end if

		//Set the subject line
		$mail->Subject = $emailTitulo;
		//Read an HTML message body from an external file, convert referenced images to embedded,
		//convert HTML into a basic plain-text alternative body
		//$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
		$mail->msgHTML($emailCuerpo);
		//Replace the plain text body with one created manually
		$mail->AltBody = $emailCuerpo;
		
		//Attach an image file
		if (!empty($files))
		{
			if (is_array($files))
			{
				foreach($files as $file)
				{
					$mail->addAttachment($file);
				}//end foreach
			}else{
				$mail->addAttachment($files);
			}//end if
		}//end if
		
		
		$mail->SMTPOptions = array(
				'ssl' => array(
						'verify_peer' => false,
						'verify_peer_name' => false,
						'allow_self_signed' => true
				)
		);
	
		//send the message, check for errors
		if (!$mail->send()) {
			return "Mailer Error: " . $mail->ErrorInfo;
		} else {
			return "OK";
		}//end if

	}//end send
	
}
?>