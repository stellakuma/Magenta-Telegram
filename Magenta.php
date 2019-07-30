<?php
namespace MagentaTelegram;

include_once "c:/MagentaTelegram/BotToken.php";
include_once "c:/MagentaTelegram/Utils.php";
include_once "c:/MagentaTelegram/FeelModule.php";

use MagentaTelegram\Utils;
use MagentaTelegram\BotToken;
use MagentaTelegram\FeelModule;

class Magenta{
	
	public static $aurl = "https://api.telegram.org/bot";
	//latest updated id
	public static $luid = "";
	public static $wait = false;
	
	
	
	/*****************************************
	*
	* Send, Edit, checkUpdate
	* Use curl.
	* checkUpdate also need optimization
	*
	*****************************************/
	
	public static function sendMessage( $chatid, $text, $upid, $keyboard = null ){
		
		$url = self::$aurl.BotToken::getToken()."/sendMessage?chat_id=".$chatid."&text=".urlencode($text);
		
		$kb = ( $keyboard !== null )? "&reply_markup=".$keyboard : null;
		
		$url = $url.$kb;

		
		$curl = curl_init();
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		
		curl_exec($curl);
		
		self::$luid = $upid;

		echo "\nSending Message to {$chatid}\n".Utils::getTime("Asia/Seoul", "Y-m-d h:i:s")."\n\n";
		
	}

	public static function editMessage( $chatid, $messageid, $text, $markup = null, $upid ){
		
		$url = self::$aurl.BotToken::getToken()."/editMessageText?chat_id=".$chatid."&message_id=".$messageid."&text=".urlencode($text);
		
		$kb = ( $markup !== null )? "&reply_markup=".$markup : null;
		
		$url = $url.$kb;
		
		$curl = curl_init();
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		
		curl_exec($curl);
		
		self::$luid = $upid;

		echo "\nEditing Message at {$chatid}, {$messageid}\n".Utils::getTime("Asia/Seoul", "Y-m-d h:i:s")."\n\n";
		
	}
	
	public static function checkUpdate( $offset = -1 ){
		
		$url = self::$aurl.BotToken::getToken()."/getUpdates?offset=".$offset;
		
		$curl = curl_init();
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
		curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, true );
		
		$json = curl_exec($curl);
		
		$arr = json_decode($json, true);
		
		$rarr = [];
				
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
			$rarr["texttype"] = @$arr["result"][0]["message"]["entities"][0]["type"];
			$rarr["lang"] = @$arr["result"][0]["message"]["from"]["language_code"];
			
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
		
		if ( null !== @$arr["result"][0]["callback_query"] ){
			
			$rarr["upid"] = $arr["result"][0]["update_id"];
			$rarr["cbtext"] = $arr["result"][0]["callback_query"]["data"];
			$rarr["cbmid"] = $arr["result"][0]["callback_query"]["message"]["message_id"];
			$rarr["cbcid"] = $arr["result"][0]["callback_query"]["message"]["chat"]["id"];
			$rarr["ikb"] = $arr["result"][0]["callback_query"]["message"]["reply_markup"];
			$rarr["texttype"] = "callback_query";
			
		}
		
		//interpretation function
		//print_r($rarr);
		
		return $rarr;
		
	}
	
	
	
	
	/*****************************************
	* 
	* Function about response
	* cmd response & inlinebutton reply.
	* todo : inline cmd reply
	* 
	*****************************************/
	
	public static function response( $command, $darr ){
		
		$name = ( $darr["type"] == "channel" )? "this function only working on supergroup and private" : $darr["rawname"];
		$title = ( $darr["type"] == "private" )? "Magenta" : $darr["rawtitle"];
		$lang = ( $darr["type"] == "channel" )? "this function only working on supergroup and private" : $darr["lang"];
		$note = "";
		$kb = "";
		
		
		if ( is_array ( $command ) ){
			
			$command = $command[0];
			
			for ( $i = 1; ; $i++ ){
				
				if ( isset ( $a[$i] ) ){
					
					$note = $note.chr(32).$a[$i];
					
				} else {
					
					break;
					
				}
				
			}
			
		} elseif ( $command == "/sunrise" ){
		//sunrise keyboard
		$kb = [ 'inline_keyboard' => [ [ 
		
		[ 'text' => '인천', 'callback_data' => 'sr@INCHEON' ], 
		[ 'text' => '서울', 'callback_data' => 'sr@SEOUL' ],
		[ 'text' => '부산', 'callback_data' => 'sr@BUSAN' ],
		[ 'text' => '울산', 'callback_data' => 'sr@ULSAN' ],

		] ] ];
		
		} elseif ( $command == "/random" ) {
		//random keyboard
		$kb = [ 'inline_keyboard' => [ [
		
		[ 'text' => '숫자 생성', 'callback_data' => 'rd@STANDARD' ] 
		
		] ] ];

		}
		
		$kb = json_encode( $kb );
		
		$rarr = [
		
			"/sunrise" => "일출 시각을 알고 싶은 지역의\n 버튼을 눌러주세요!"."#".$kb,
			"/time" => "현재 시각은 ".Utils::getTime("Asia/Seoul", "Y-m-d h:i:s")." 입니다.",
			"/roominfo" => "채팅방명 : {$title}\n채팅방의 유형 : {$darr["type"]}",
			"/userinfo" => "마젠타가 인지하는 정보를 표시합니다.\n"."유저명 : {$name}\n유저호출명 : @{$darr["user_id"]}\n사용 언어 : {$lang}",
			"/off" => ( $command == "/off" )? ( $darr["user_id"] == "CYANPEN" )? self::off("s?", $darr["upid"]) : "관리자만 실행할 수 있습니다." : null,
			"/fcstock" => "개발중입니다",
			"/feel" => "개발중입니다",
			"/editnote" => ($command == "/editnote")? Utils::editNote( $name, $darr["user_id"], $note ) : null,
			"/note" => Utils::getNote( $darr["user_id"] ),
			"/random" => "0부터 100 사이의 난수를 출력합니다!#".$kb
			
		];
		
		
		
		if ( isset ( $rarr[ $command ] ) ){

			return $rarr[ $command ];
			
		} else {

			return "마젠타에 등록되지 않은 명령어입니다.";
			
		}
		
		
		
	}
	
	public static function inlineReply( $cmdtype, $str ){
		
		$srd = ( Utils::getTime("Asia/Seoul", "H") >= explode( ":", Utils::getSunrise("INCHEON"))[0] )? "내일":"오늘";
		$srln = [ 

			"INCHEON" => "인천",
			"SEOUL" => "서울",
			"BUSAN" => "부산",
			"ULSAN" => "울산"

		];
		
		$arr = [
		
		"sr" => $srd.chr(32).@$srln[$str]."의 일출 시각은 ".@Utils::getSunrise($str)." 입니다.",
		"rd" => "생성된 숫자는 ".mt_rand(0, 100)." 입니다!"
		
		];
		
		return $arr[$cmdtype];
		
	}
	
	
	
	/*****************************************
	*
	* Off Magenta.
	* If you are owner of magenta, it will reply on cli. 
	* when type yes+enter, it will off.
	*
	*****************************************/
	
	public static function off( $text, $upid ){
		
		if ( $text == "s?" ){
			
			echo "\nAre you sure to off Magenta?\n";
			
			fscanf( STDIN, "%s\n", $text );
			
			if ( $text == "yes" ){
			
				//when stop magenta
				self::$luid = $upid;
				Utils::setData( self::$luid, "luid" );
				Utils::setData( Utils::$note, "note" );
			
				exit();
			
			} else {
			
				return "Magenta의 종료가 취소되었습니다.";
			
			}
		
		}
		
	}
	
	
	
	/*****************************************
	*
	* Run Magenta.
	* also needs optimization. 
	* todo: optimization
	*
	*****************************************/
	
	public static function Run(){

		$arr = self::checkUpdate();
		
		$allow = ($arr["upid"] == self::$luid)? false : true;
		

		if ( $allow == true ) {

			if ( isset ( $arr["texttype"] ) ) {

				if ( $arr["texttype"] == "bot_command" ){
							
						if ( !strpos( $arr["text"], chr(32) ) ){
							//전달된 텍스트에 커맨드 외의 다른 요소가 있을 경우
				
							$rp = explode( "#", self::response( str_replace( "@magenta_bot", "", $arr["text"]), $arr ) );
							$text = $rp[0];
							$kb = @$rp[1];
							
							self::sendMessage( $arr["chat_id"], $text, $arr["upid"], $kb );
			
							} else {
							//전달된 텍스트에 커맨드만 있을 경우
							
							self::sendMessage( $arr["chat_id"], self::response( explode ( chr(32), str_replace( "@magenta_bot", "", $arr["text"] ) ), $arr ) , $arr["upid"] );
						
							}	 
					  
				} elseif ( $arr["texttype"] == "callback_query" ){				
							//인라인 키보드의 답변인 경우

						$a = explode( "@", $arr["cbtext"] );
						$rpl = self::inlineReply( $a[0], $a[1] );
						self::editMessage( $arr["cbcid"], $arr["cbmid"], $rpl, json_encode($arr["ikb"]), $arr["upid"] );											
					
				} 
		
			}
		
		} else {
		
			if ( self::$wait == false ){
			
			echo "\nMagenta is waiting..\n\n";
			//sleep ( 0.1 );
			
			}
			
		}
		
	}
	
	
	}

for ( $i = 0 ; ; $i++ ){

//when start magenta
if ( $i == 0 ){
	
		Magenta::$luid = Utils::getData("luid");
		Utils::$note = Utils::getData("note");

}

Magenta::Run();

}

?>