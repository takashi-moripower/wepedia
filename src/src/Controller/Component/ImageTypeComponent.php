<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Controller\Component;

use Cake\Controller\Component;

/**
 * Description of ImageTypeCOmponent
 *
 * @author tsukasa
 */
class ImageTypeComponent extends Component {

	//put your code here
	public function getTypeFromByte( &$buf) {
		$head = substr($buf, 0, 8);

		$result = "unknown";
		if ($head === "\x89PNG\x0d\x0a\x1a\x0a") {
			$result = "image/png";
		}else if(substr($head, 0, 2) === "\xff\xd8"){
			$result = "image/jpeg";
		}else if(preg_match('/^GIF8[79]a/', $head)){
			$result = "image/gif";
		}
		
		return $result;
	}

}
