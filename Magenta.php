<?php
namespace MagentaTelegram;

include_once "c:/MagentaTelegram/BotToken.php";
include_once "c:/MagentaTelegram/Utils.php";

use MagentaTelegram\Utils;
use MagentaTelegram\BotToken;

class Magenta{
	
	public static $aurl = "https://api.telegram.org/bot";
	//latest updated id
	public static $luid = "";
	public static $wait = false;
	
	public static function sendMessage( $chatid, $text, $upid ){
		
		if ( $text == "Magenta의 종료가 취소되었습니다." ){
			
			echo "\nAre you sure to off Magenta?\n";
			
			$close = "";
			
			fscanf( STDIN, "%s\n", $close );
			
			if ( $close == "yes" ){
			
			die();
			
		}
		
		}
		
		$url = self::$aurl.BotToken::getToken()."/sendMessage?chat_id=".$chatid."&text=".urlencode($text);
		
		$curl = curl_init();
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		
		curl_exec($curl);
		
		self::$luid = $upid;
		
		echo "Sending Message to {$chatid}\n".Utils::getTime("Asia/Seoul", "Y-m-d h:i:s")."\n\n";
		
	}		
	
	public static function checkUpdate( $offset = -1 ){
		
		$url = self::$aurl.BotToken::getToken()."/getUpdates?offset=".$offset;
		
		$curl = curl_init();
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		
		$json = curl_exec($curl);
		
		$arr = json_decode($json, true);
		
		//json to array interpretation
		//print_r ($arr);
		
		$rarr = [];
		
		//cases
		 
		if ( null !== @$arr["result"][0]["channel_post"]["chat"]["type"] ){
			
			//echo $arr["result"][0]["channel_post"]["chat"]["type"];
			
			if ( @$arr["result"][0]["channel_post"]["chat"]["type"] == "channel" ){

			$rarr["upid"] = $arr["result"][0]["update_id"];
			$rarr["chat_id"] = $arr["result"][0]["channel_post"]["chat"]["id"];
			$rarr["rawtitle"] = $arr["result"][0]["channel_post"]["chat"]["title"];
			$rarr["name"] = $arr["result"][0]["channel_post"]["chat"]["username"];
			$rarr["type"] = $arr["result"][0]["channel_post"]["chat"]["type"];
			$rarr["rawtext"] = $arr["result"][0]["channel_post"]["text"];
			
			} 
			
		}
				
		if ( null !== @$arr["result"][0]["message"]["chat"]["type"] ) {
			
			if ( @$arr["result"][0]["message"]["chat"]["type"] == "supergroup" ){
			
			$rarr["upid"] = $arr["result"][0]["update_id"];
			$rarr["is_bot"] = $arr["result"][0]["message"]["from"]["is_bot"];
			$rarr["rawname"] = $arr["result"][0]["message"]["from"]["first_name"];
			$rarr["user_id"] = $arr["result"][0]["message"]["from"]["username"];
			$rarr["chat_id"] = $arr["result"][0]["message"]["chat"]["id"];
			$rarr["rawtitle"] = $arr["result"][0]["message"]["chat"]["title"];
			$rarr["type"] = $arr["result"][0]["message"]["chat"]["type"];
			$rarr["text"] = $arr["result"][0]["message"]["text"];
			$rarr["texttype"] = $arr["result"][0]["message"]["entities"][0]["type"];
			$rarr["lang"] = $arr["result"][0]["message"]["from"]["language_code"];
			
			}
			
		}
			
		if ( null !== @$arr["result"][0]["message"]["chat"]["type"] ) {	
			
			if ( @$arr["result"][0]["message"]["chat"]["type"] == "private" ){
			
			$rarr["upid"] = $arr["result"][0]["update_id"];
			$rarr["is_bot"] = $arr["result"][0]["message"]["from"]["is_bot"];
			$rarr["rawname"] = $arr["result"][0]["message"]["from"]["first_name"];
			$rarr["user_id"] = $arr["result"][0]["message"]["from"]["username"];
			$rarr["chat_id"] = $arr["result"][0]["message"]["chat"]["id"];
			$rarr["type"] = $arr["result"][0]["message"]["chat"]["type"];
			$rarr["text"] = $arr["result"][0]["message"]["text"];
			$rarr["texttype"] = ( @$arr["result"][0]["message"]["entities"][0]["type"] !== null )? $arr["result"][0]["message"]["entities"][0]["type"] : null;
			$rarr["lang"] = $arr["result"][0]["message"]["from"]["language_code"];
			
			}
			
		}
			
		else{
			
			$rarr = [
			
			"error" => "undefined type of chat"
			
			];
			
		}
		
		//interpretation function
		//print_r($rarr);
		//echo "\n".@$arr["result"][0]["message"]["chat"]["type"]."\n";
		
		return $rarr;
		
	}
	
	public static function response( $command, $darr ){
		
		$srd = ( Utils::getTime("Asia/Seoul", "H") >= explode( ":", Utils::getSunrise("INCHEON"))[0] )? "내일":"오늘";
		$name = ( $darr["type"] == "channel" )? "this function only working on supergroup and private" : $darr["rawname"];
		$title = ( $darr["type"] == "private" )? "Magenta" : $darr["rawtitle"];
		$lang = ( $darr["type"] == "channel" )? "this function only working on supergroup and private" : $darr["lang"];
		
		$rarr = [
		
			"/sunrise" => $srd."의 일출 시각은 ".Utils::getSunrise("INCHEON")." 입니다.",
			"/time" => "현재 시각은 ".Utils::getTime("Asia/Seoul", "Y-m-d h:i:s")." 입니다.",
			"/roominfo" => "채팅방명 : {$title}\n채팅방의 유형 : {$darr["type"]}",
			"/userinfo" => "마젠타가 인지하는 정보를 표시합니다.\n"."유저명 : {$name}\n유저호출명 : @{$darr["user_id"]}\n사용 언어 : {$lang}",
			"/off" => ( $darr["user_id"] == "CYANPEN" )? "Magenta의 종료가 취소되었습니다." : "관리자만 실행할 수 있습니다."
			
		];
		
		if ( isset ( $rarr[ $command ] ) ){

			return $rarr[ $command ];
			
		} else {

			return "마젠타에 등록되지 않은 명령어입니다.";
			
		}
		
	}
	
	public static function Run(){
		
		$arr = self::checkUpdate();
		
		if ( @$arr["upid"] !== self::$luid ) {
		
		if ( isset ( $arr["texttype"] ) ) {
		
		if ( $arr["texttype"] == "bot_command" ){
			
			//echo str_replace( "@magenta_bot", "", $arr[7]);
			
			self::sendMessage( $arr["chat_id"], self::response( str_replace( "@magenta_bot", "", $arr["text"]), $arr ), $arr["upid"] );
			
		} 
		
		}
		
		} else {
			
			if ( self::$wait == false ){
			
			echo "\nMagenta is waiting..\n\n";
			sleep ( 1 );
			
			}
			
		}
		
	}
	
	public function __destruct(){
		
		Utils::setLuid( self::$luid );
		
	}

}

for ( $i = 0 ; ; $i++ ){

Magenta::Run();

}

?>