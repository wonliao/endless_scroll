<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
require_once("./db/db.php");
require_once("lib/simple_html_dom.php");


//设置PHP超时时间
set_time_limit(0);

/*
// 建立目錄
for($i=0; $i<100; $i++ ) {

	$sub_name = "";
	
	if( $i<10 )	$sub_name = "0".$i;
	else 			$sub_name = $i;

	echo $sub_name."<br />";
	$dir_name = "../wp-content/uploads/new_swf/".$sub_name;
	mkdir($dir_name);
}
exit();
*/


//while( 1 ) {
//for($j=0;$j<1;$j++) {

	clearstatcache();

	$begin_time = time();

	$cmd = "SELECT `index` FROM `7k7k2` WHERE 1";
	$result = doquery($cmd, true);
	$i = $result['index'];
	
	$cmd = "UPDATE `7k7k2` SET `index` = `index` - 1 WHERE 1;";
	$result = doquery($cmd);
	
	echo " ========================================<br />";
	
	if( $i <= 0 ) exit();

	// gid
	$ID = 10000 + $i;

	$sub_name  = "";
	$mod = floor( $ID % 100 );
	if( $mod<10 )	$sub_name = "0".$mod;
	else 					$sub_name = $mod;
	
	// 檢查本地端檔案，小於 20k時，重新下載.swf
	$swf_file = "wp-content/uploads/swf/".$sub_name."/7_".$ID.".swf";
	$file_size = filesize("../".$swf_file);
	echo "$swf_file ==> file_size(".$file_size.")<br />";
	if( $file_size > 0 && $file_size < 20480 ) {
	
		// 檔名
		$file_name = "http://www.7k7k.com/swf/".$i.".htm";
		echo $file_name."<br />";
	
		$file = file_get_contents($file_name);
		if( $file ) {
	
			// simple HTML DOM Paser
			$html = file_get_html($file_name);
	
			// 插入 wp_postmeta
			insert_postmeta( $ID, $file_name, $html, $file, $sub_name );
	
			echo "花費時間(".(time()-$begin_time).")<br />";
		}
	}

	
//}


function insert_postmeta( $ID, $file_name, $html, $file, $sub_name ) {

	// gamepath
	$str1 = stristr( $file, "_gamepath" );
	$temp = explode( "=", $str1 );
	$str2 = str_ireplace( "_gamevar", "", $temp[1] );
	$str2 = str_ireplace( ",", "", $str2 );
	$str2 = str_ireplace( "\"", "", $str2 );
	$str2 = trim($str2);
	$gamepath = $str2;
	echo "gamepath(".$gamepath.")<br />";
	
	if( stristr( $gamepath, ".htm" ) != false ) {

		echo "html<br />";

		$gamepath .= '?r='.rand(0, 9999);
		echo "gamepath(".$gamepath.")<br />";
		clearstatcache();
		$file2 = file_get_contents($gamepath);
		//echo $file2;

		// swf
		$a1 = stristr( $file2, "_src_" );
		$temp = explode( "=", $a1 );
		$a2 = str_ireplace( "'", "", $temp[1] );
		$temp = explode( ";", $a2 );
		$a2 = $temp[0];
		$a2 = trim($a2);
		echo "a2(".$a2.")<br />";

		$new_path_pos = strripos( $gamepath, "/");
		echo "new_path_pos(".$new_path_pos.")<br />";
		$new_path = "";
		for( $i=0; $i<=$new_path_pos; $i++) {
			$new_path .= $gamepath[$i];
		}
		$new_path .= $a2;
		echo "new_path(".$new_path.")<br />";
	
		// 檢查 .swf 檔案
		if( stristr( $new_path, ".swf" ) != false ) {

			// 下載至 wp-content/uploads 下的目錄
			$swf_file = "wp-content/uploads/new_swf/".$sub_name."/7_".$ID.".swf";
			echo "(".copy( (string)($new_path), "../".$swf_file ).") => ".$swf_file."<br />";
		}

	} else {
		echo "swf<br />";
	}
}


?>


</body>
</html>