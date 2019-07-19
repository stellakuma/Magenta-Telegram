<?php

class Magenta{
	
	private $token = "975610726:AAHNNHSU8AeiK7j7OjIGI033HAb7qhQlbtQ";
	private $baseUrl = 'https://api.telegram.org/bot';
    private $sendBool = false;
    private $messageChatId = '';
    private $messageText = '';
	
	public function __construct(){
		
		$this->baseUrl = $this->baseUrl.$this->token;
		
	}
	
	private function GetCurl($url, $data=array()) {
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        return json_decode($result, true);
    }
	
	public functino getData($offsetId){
		
		
		
	}
}

?>