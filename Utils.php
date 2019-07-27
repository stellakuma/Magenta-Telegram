<?php
namespace MagentaTelegram;

class Utils{

public static function getSunrise( $sunriseid ){

$idarr = [ "INCHEON" => "37.46;126.71;90;9" ];

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


public static function setLuid( $luid ){
	
	mkdir( "Magenta/luid" );
	$f = fopen( "Magenta/luid/luid.json", "w+" ) or die ( "Cannot open file" ); 
	fwrite( $f, $luid );
	fclose();
	
}

}
?>