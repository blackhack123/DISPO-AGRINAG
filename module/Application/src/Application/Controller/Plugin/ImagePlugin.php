<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;
//	Zend\Authentication\AuthenticationService;


class ImagePlugin extends AbstractPlugin {

	/*-----------------------------------------------------------------------------*/
	public function resizeImage($url_image,$tipo ,$max_width = 150, $max_height = 150)
	/*-----------------------------------------------------------------------------*/
	{
		if ($max_height==0) {
			$max_height = $max_width - 1;
		}
		if ($max_width==0)  {
			$max_width = $max_height - 1;
		}


		if($tipo=='jpg'||$tipo=='jpeg')
			$img = imagecreatefromjpeg($url_image);

		if($tipo=='png')
			$img= imagecreatefrompng($url_image);

		// or $im = imagecreatefromjpeg( 'test.jpg' );
		//		$mw = 360; // max width
		//		$mh = 360; // max height
		$original_width = imagesx( $img );
		$original_height = imagesy( $img );
		if( $original_width > $max_width || $original_height > $max_height ) {
			if ($original_width > $original_height) {
				$new_width = $max_width;
				$new_heigth = $new_width * $original_height / $original_width;
			} else {
				$new_heigth = $max_height;
				$new_width = $new_heigth * $original_width / $original_height;
			}
		} else {
			// although within size restriction, we still do the copy/resize process
			// which can make an animated GIF still
			if ($original_width > $original_height) {
				$new_width = $original_width;
				$new_heigth = $new_width * $original_height / $original_width;
			}else{
				$new_heigth = $original_height;
				$new_width  = $new_heigth * $original_width / $original_height;
			}//end if
			//$new_width = $original_width;
			//$new_heigth = $original_height;
		}//end if
		$img_new = imagecreatetruecolor($new_width, $new_heigth );

		imagecopyresized($img_new, $img, 0, 0, 0, 0, $new_width, $new_heigth, $original_width, $original_height );
		//if($tipo=='jpg'||$tipo=='jpeg')
			imagejpeg( $img_new, $url_image,90 );
		//if($tipo=='png')
			//imagepng( $img_new, $url_image,90);
		imagedestroy( $img );
		imagedestroy( $img_new );
	}//end function

}//end class