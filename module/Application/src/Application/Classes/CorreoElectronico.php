<?php
namespace Application\Classes;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;

class CorreoElectronico
{
	public function setEmail($emailDestino,$emailTitulo,$emailCuerpo)
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
			return $e;
		}
	}
}
?>