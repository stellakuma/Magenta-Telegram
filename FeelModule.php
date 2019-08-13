<?php
namespace MagentaTelegram;

include_once "c:/MagentaTelegram/Utils.php";

use MagentaTelegram\Utils;

class Feel{
	
	private $feeling = [
	
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
	
	public function addJoy( $num ){
		
		$this->feeling["joy"] = $this->feeling["joy"] + $num;
		
	}
	
	public function addSad( $num ){
		
		$this->feeling["sad"] = $this->feeling["sad"] + $num;
		
	}
	
	public function addAnger( $num ){
		
		$this->feeling["anger"] = $this->feeling["anger"] + $num;
		
	}
	
	public function addFear( $num ){
		
		$this->feeling["fear"] = $this->feeling["fear"] + $num;
		
	}
	
	public function addDisgust( $num ){
		
		$this->feeling["disgust"] = $this->feeling["disgust"] + $num;
		
	}

	public function addContempt( $num ){
		
		$this->feeling["contempt"] = $this->feeling["contempt"] + $num;
		
	}

	public function addSurprise( $num ){
		
		$this->feeling["surprise"] = $this->feeling["surprise"] + $num;
		
	}	
	
}

?>