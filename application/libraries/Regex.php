<?php 

final class Regex{

	function _genRegex($post,$type) {
	
		switch ($type) {
			case 'RGXINT':
				$rx =  '/[^0-9]/';
				break;
			case 'RGXFLOAT':
				$rx =  '/[^0-9.]/';
				break;
			case 'RGXAZ':
				$rx =  '/[^a-zA-Z]/';
				break;
			case 'RGXALNUM':
				$rx =  '/[^a-zA-Z0-9]/';
				break;
			case 'RGXALNUMDOT':
				$rx =  '/[^a-zA-Z0-9.]/';
				break;
			case 'RGXQSL':
				$rx =  "/[\"'\\\\]/";
				break;
			case 'RGXNAMAFILE':
				$rx =  "/[^a-zA-Z0-9._]/";
				break;
			
			default:
				$rx =  "/[\"'\\\\]/";
				break;
		}

		$post = preg_replace($rx,"",$post);

		return $post;


	}
}


?>