<?php
namespace Application\Classes;

use Application\Classes\PHPMailerApp;

class CorreoElectronico
{
	
	public function SendMail($emailDestino, $emailCC, $emailTitulo, $emailCuerpo, $files)
	{
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
					$mail->addReplyTo($email, $email);
				}//end foreach			
			}else{
				$mail->addReplyTo($emailCC, $emailCC);
			}//end if
		}//end if	
			
		
		//Set who the message is to be sent to
		if (is_array($emailDestino))
		{
			foreach ($emailDestino as $email)
			{
				$mail->addAddress($email, $email);
			}//end foreach
		}else{
			$mail->addAddress($emailDestino, $emailDestino);
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
			if (is_array($emailCC))
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
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			echo "Message sent!";
		}
	
	}//end send
	
	
	
	public function setEmailOldxxxx($emailDestino,$emailTitulo,$emailCuerpo)
	{
		$message = new Message();
		$message->addTo('moroni@agrinag.com')
		->addFrom('documentsale@agrinag.com')
		->setSubject('Mi primer email x')
		->setBody("Por favor llegal");
		
		// Setup SMTP transport using LOGIN authentication
		$transport = new SmtpTransport();
		$options   = new SmtpOptions(array(
				'name'              => 'agrinag.com',
				'host'              => 'smtp.gmail.com',
				'connection_class'  => 'login',
				'port' 				=> '587',
				
				'connection_config' => array(
					'username' => 'documentsale@agrinag.com',
					'password' => 'web@Ag1nag$2015',
					'ssl'      => 'tls',
				),
		));
		$transport->setOptions($options);
		$transport->send($message);
/*				
		$mail = new Mail\Message();
		$mail->setBody('Este es un email de prueba');
		$mail->setFrom('moroni@agrinag.com', 'El mashi de la Republica del Ecuador');
		$mail->addTo('moroni@agrinag.com', 'Prueba de Email');
		$mail->setSubject('Mi primer email');
		
		$transport = new Mail\Transport\Sendmail('moroni@agrinag.com');
		$transport->send($mail);
*/	}//end function setEmailOld
	
	public function setEmailOld2($emailDestino,$emailTitulo,$emailCuerpo)
	{
		try 
		{
			$htmlPart = new MimePart($emailCuerpo);
			$htmlPart->type = "text/html";

			$textPart = new MimePart($emailCuerpo);
			$textPart->type = "text/plain";
				
			$body = new MimeMessage();
			$body->setParts(array($textPart, $htmlPart));
			
			$message = new Message();
			
			$arr_email = explode(";", $emailDestino);
			foreach($arr_email as $reg)
			{
				$message->addTo($reg);
			}//end foreach
			
			$message->addFrom('erp-esig@etinar.com', "Sistema ERP e-SIG");
			$message->setSubject($emailTitulo);
			$message->setBody($body);
			$message->getHeaders()->get('content-type')->setType('multipart/alternative');
			
			// Setup SMTP transport using LOGIN authentication
			$transport = new SmtpTransport();
			$options   = new SmtpOptions(array(
					'name'              => 'stmp.gmail.com',
					'host'              => 'stmp.gmail.com',
					'port'              => 465, 
					'connection_class'  => 'login',
					'connection_config' => array(
							'username' => 'documentsale@agrinag.com',
							'password' => 'web@Ag1nag$2015',
							/*'ssl'      => 'ssl',*/
					),

/*					'name'              => 'mail.etinar.com',
					'host'              => 'mail.etinar.com',
					'port'              => 465,
					'connection_class'  => 'login',
					'connection_config' => array(
							'username' => 'erp-esig',
							'password' => '$1$t3ma$@#3$1G',
							//'ssl'      => 'ssl',
					),
*/			));
			$transport->setOptions($options);
			$transport->send($message);
			return "OK";
		
		}catch(\Exception $e){
			var_dump($e);
			return $e;
		}
	}//end function setEmailOld
	
	
	
	public function setEmail($emailDestino,$emailTitulo,$emailCuerpo, $arr_files)
	{
		try
		{
			$content  = new MimeMessage();
			
			$htmlPart = new MimePart($emailCuerpo);
			$htmlPart->type = "text/html";	
			$textPart = new MimePart($emailCuerpo);
			$textPart->type = "text/plain";
			$content->setParts(array($textPart, $htmlPart));

			$contentPart = new MimePart($content->generateMessage());
			$contentPart->type = 'multipart/alternative;' . PHP_EOL . ' boundary="' . $content->getMime()->boundary() . '"';			
			
			$body 		= new MimeMessage();
			$body->addPart($contentPart);
			
			if ($arr_files)
			{
				foreach($arr_files as $file)
				{
					$attachment = new MimePart(fopen($file, 'r'));
					$attachment->type = 'application/pdf';
					$attachment->encoding    = \Zend\Mime\Mime::ENCODING_BASE64;
					$attachment->disposition = \Zend\Mime\Mime::DISPOSITION_ATTACHMENT;
					
					$body->addPart($attachment);
					
					//$messageType = 'multipart/related';
				}//end foreach
			}else{
				//$messageType = 'multipart/related';
			}//end if
			
			
			//$body = new MimeMessage();
			//$body->setParts(array($contentPart, $attachment));
			
			$message = new Message();
				
			$arr_email = explode(";", $emailDestino);
			foreach($arr_email as $reg)
			{
				$message->addTo($reg);
			}//end foreach

			$message->addFrom('documentsale@agrinag.com', "Dispo - Agrinag");
			$message->setSubject($emailTitulo);
			$message->setBody($body);
			$message->getHeaders()->get('content-type')->setType('multipart/alternative');

			// Setup SMTP transport using LOGIN authentication
			$transport = new SmtpTransport();
			$options   = new SmtpOptions(array(
/*					'name'              => 'stmp.agrinag.com',
					'host'              => 'stmp.agrinag.com',
					'port'              => 465, 
					'connection_class'  => 'login',
					'connection_config' => array(
							'username' => 'documentsale@agrinag.com',
							'password' => 'web@Ag1nag$2015',
							'ssl'      => 'tls',
					),
*/
					'name'              => 'mail.etinar.com',
					'host'              => 'mail.etinar.com',
					'port'              => 465,
					'connection_class'  => 'login',
					'connection_config' => array(
							'username' => 'erp-esig',
							'password' => '$1$t3ma$@#3$1G',
							'ssl'      => 'ssl',
					),
			));
			$transport->setOptions($options);
			$transport->send($message);
			return "OK";
	
		}catch(\Exception $e){
			var_dump($e);
			return $e;
		}
	}//end function setEmail
	
	
}
?>