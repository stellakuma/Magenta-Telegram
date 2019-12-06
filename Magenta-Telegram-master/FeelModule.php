<?php
namespace MagentaTelegram;

include_once "c:/MagentaTelegram/Utils.php";

use MagentaTelegram\Utils;

class Feel{
	
	private static $feel = [
	
		"joy" => 0,
		"sad" => 0,
		"anger" => 0,
		"fear" => 0,
		"disgust" => 0,
		"contempt" => 0,
		"surprise" => 0
	
	];

	public function expEmotion( $mode = null ){
		
		
		
	}
	
	public function editEmotion( $type, $param ){
	
		if ( isset ( self::feel[$type] ) ) {
		
			self::feel[$type] = $param;
			
		} else {
		
			echo "\n There's any emotion type on array. \n Did you find AddEmotion? \n";	
			return -1;
		
		}
			
	}
	
	
	public function addEmotion( $type ){
	
		if ( ! isset ( self::feel[$type] ) ){
		
			self::feel[$type] = 0;
		
		} else {
		
			echo "\n Already exist emotion type \n";
			return -1;
		
		}	
	}
	
}

?>
