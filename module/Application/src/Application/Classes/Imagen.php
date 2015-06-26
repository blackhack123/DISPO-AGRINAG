<?php

namespace Application\Classes;
//clase faltante
class Imagen {
	/*-----------------------------------------------------------------------------*/
	public function getDimensionForResizeImage($url_image,$max_width = 150, $max_height = 150)
	/*-----------------------------------------------------------------------------*/
	{
		if ($max_height==0) {
			$max_height = $max_width - 1;
		}
		if ($max_width==0)  {
			$max_width = $max_height - 1;
		}
	
	
		list($original_width, $original_height, $type, $attr) = getimagesize($url_image);
					
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

		return array($new_width, $new_heigth);	
	}//end function

}//end class