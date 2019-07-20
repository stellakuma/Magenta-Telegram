<?php

class Magenta{
	
	//include_once "C:/Users/PC001/Magenta-Telegram/BotToken.php";
	
	//$bt = new BotToken;
	public $token = "";
	public $aurl = "https://api.telegram.org/bot";
	
	public function sendMessage( $token, $chatid, $text ){
		
		$url = '{$this->aurl.$this->token}/sendMessage?chat_id={$chatid}&text="{$text}"';
		
		$curl = curl_init;
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1);
		
		var_dump(curl_exec($curl));
		
		echo "done!";
		
	}
	
}

$a = new Magenta;

$a->sendMessage( $a->token, "-1001139061606", "phpcli에서 보낸 메세지 입니다.");

?>