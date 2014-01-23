<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>轉換pty 為 code 的gamelink </title>
</head>

<body>
<?php
require_once("./db/db.php");


$cmd = "SELECT `gid`, `gamelink` FROM `game_list` WHERE `pty` = 'code' AND `gid` = '5191' ORDER BY `gid` LIMIT 100;";

$result = doquery($cmd);

while( $row = mysql_fetch_array( $result ) ) {

	//echo "gid(".$row['gid'].")(".stripos( $gamelink, "<OBJECT") .")<br />";
	$gid = $row['gid'];
	$gamelink = $row['gamelink'];

	$swf_url = "";
	if( $gamelink[0] == "<" && ( $gamelink[1] == "e" || $gamelink[1] == "E" ) ) {

		$temp = explode("'", $gamelink);
		foreach( $temp as $str ) {
			if( stripos( $str, ".swf" ) > 0 ) {
				$swf_url = $str;
				break;
			}
		}
		
		echo "swf_url(".$swf_url.")<br />";
		
	} else 
	if( $gamelink[0] == "<" && $gamelink[1] == "e" ) {
	{
	
	
	
	}
/*
		$token = strtok($gamelink, "=");
		while ($token !== false)
		{
			if( stripos( $token, ".swf" ) > 0 ) {
				echo "token(".$token.")<br />";
			}
			
			$token = strtok("=");
		}
*/

		//$temp1 = str_ireplace( 'PARAM NAME=movie VALUE="' , '', $list[2] );
	
		//$swf_url = str_ireplace( '.swf" />' , '', $temp1 );
		//echo "gid(".$row['gid'].") swf(".$swf_url."<br />";
/*	
		$cmd2 = "UPDATE `wp_postmeta` SET `meta_value` = '".$swf_url."' WHERE `post_id` = '".$gid."' AND `meta_key` = 'gameswf';";
		$result2 = doquery($cmd2);
	
		$cmd3 = "UPDATE `wp_posts` SET `post_status` = 'publish' WHERE `ID` = '".$gid."';";
		$result3 = doquery($cmd3);
	
		echo "(".$result2.")(".$result3.") ==> gid(".$gid.") swf(".$swf_url.")<br />";
*/
	} else {
	
	}
	
}




?>
</body>
</html>