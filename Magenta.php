<?php
namespace MagentaTelegram;

include_once "c:/MagentaTelegram/BotToken.php";
include_once "c:/MagentaTelegram/Utils.php";

use MagentaTelegram\Utils;
use MagentaTelegram\BotToken;

class Magenta{
	

	public $aurl = "https://api.telegram.org/bot";

	
	public function sendMessage( $chatid, $text ){
		
		$url = $this->aurl.BotToken::getToken()."/sendMessage?chat_id=".$chatid."&text=".urlencode($text);
		
		$curl = curl_init();
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		
		curl_exec($curl);
		
		echo "Sending Message to {$chatid}\n".Utils::getTime("Asia/Seoul", "Y-m-d h:i:s")."\n";
		
	}		
	
	public function checkUpdate( $isLoop ){
		
		
		
	}
	
	public function response( $command, $adres = "" ){
		
		$srd = ( Utils::getTime("Asia/Seoul", "H") >= explode( ":", Utils::getSunrise("INCHEON"))[0] )? "내일":"오늘";
		
		$rarr = [
		
			"/sunrise" => $srd."의 일출 시각은 ".Utils::getSunrise("INCHEON")." 입니다.",
			"/time" => "현재 시각은 ".Utils::getTime("Asia/Seoul", "Y-m-d h:i:s")." 입니다."
			"/roominfo" => "개발중입니다.",
			"/userinfo" => 
		];
		
		if ( isset ( $rarr[ $command ] ) ){
			
			return $rarr[ $command ];
			
		} else {
			
			return "마젠타에 등록되지 않은 명령어입니다.";
			
		}
		
	}
	
	public static function Run(){
		
		
		
	}

}

//Magenta::Run();
$a = new Magenta;
$a-> sendMessage ( "", $a -> response( "/sunrise" ) );
?>