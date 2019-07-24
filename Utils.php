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

}
?>