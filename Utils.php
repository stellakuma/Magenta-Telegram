<?php
namespace MagentaTelegram;

class Utils{

	public static $note = [];

	public static function editNote( $name, $id, $text ){
		
		self::$note[$id] = $text;
		
		return "{$name} 님의 노트가 저장되었습니다!";
		
	}
	
	public static function getNote( $id ){
		
		if ( isset ( self::$note[$id] ) ){
			
			return self::$note[$id];
			
		} else {
		
			return "아직 작성한 노트가 없습니다.";
			
		}
		
	}

	public static function getSunrise( $sunriseid ){

	$zenith = ini_get("date.sunrise_zenith");

	$idarr = [ 

	"INCHEON" => "37.46;126.71;".$zenith.";9", 
	"SEOUL" => "37.566;126.9784;".$zenith.";9", 
	"BUSAN" => "35.10278;129.04028;".$zenith.";9",
	"ULSAN" => "35.53722;129.31667;".$zenith.";9"

	];

	$result = explode( ";", $idarr[$sunriseid] );

	//INCHEON : 37.46, 126.71, 90, 9
	return date_sunrise(time(), SUNFUNCS_RET_STRING, $result[0], $result[1], $result[2], $result[3]);

	}

	public static function getTime( $timezoneid, $format ){
	
		date_default_timezone_set( $timezoneid );
	
		return date( $format );
	
	}

	//thk stackoverflow
	public static function utf8( $num ){
	
		if($num<=0x7F)       return chr($num);
		if($num<=0x7FF)      return chr(($num>>6)+192).chr(($num&63)+128);
		if($num<=0xFFFF)     return chr(($num>>12)+224).chr((($num>>6)&63)+128).chr(($num&63)+128);
		if($num<=0x1FFFFF)   return chr(($num>>18)+240).chr((($num>>12)&63)+128).chr((($num>>6)&63)+128).chr(($num&63)+128);

	}


	public static function conv_utf8( $nums ){
	
		$a = explode ( chr(32), $nums );
		$t = sizeof($a);
		$rstr = "";
		
		for ( $i = 0; ; $i++ ){
		
				if ( $i == $t ){
		
				break;
		
			}
		
		$arr = explode( chr(92), $a[$i] );
		$tarr = sizeof($arr);
		
			for ( $k = 1; ; $k++ ){
		
				if ( $k == $tarr ){
				
					$rstr = $rstr.chr(32);
			
				break;
			
				}
			
				if ( ( $arr[$k][0] == "n" ) & ( !isset ( $arr[$k][1] ) ) ){
			
					$rst = $rst.chr(10);
			
					break;
			
				} else {
				
				$rstr = $rstr.self::utf8(hexdec(chr(92).$arr[$k][0].$arr[$k][1].$arr[$k][2].$arr[$k][3].$arr[$k][4]));
			
				}
				
				for ( $p = 5; ; $p++ ){
			
					if ( isset ( $arr[$k][$p] ) ){
				
					$rstr = $rstr.$arr[$k][$p];
				
					} else {
				
					break;
				
					}
			
				}
			
			}
	
	
		}
	
		return $rstr;

	}


	public static function setData( $data, $filename ){
	
		@mkdir( "c:/Magenta/" );
		$f = fopen( "c:/Magenta/{$filename}.json", "w" ) or die ( "Cannot open file" ); 
		fwrite( $f, json_encode($data) );
		fclose( $f );
	
	}

	public static function getData( $filename ){
	
		$fp = fopen("c:/Magenta/{$filename}.json","r");
		$fr = json_decode(fread($fp, filesize("c:/Magenta/{$filename}.json")), true);
		fclose($fp);
		return $fr;
	
	}

}
?>