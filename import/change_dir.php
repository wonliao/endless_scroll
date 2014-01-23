<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
require_once("./db/db.php");



$begin_time = time();



/*
// 建立目錄
for($i=0; $i<100; $i++ ) {

	$sub_name = "";
	
	if( $i<10 )	$sub_name = "0".$i;
	else 			$sub_name = $i;

	echo $sub_name."<br />";
	$dir_name = "../wp-content/uploads/img/".$sub_name;
	mkdir($dir_name);
	$dir_name = "../wp-content/uploads/img_big/".$sub_name;
	mkdir($dir_name);
	$dir_name = "../wp-content/uploads/swf/".$sub_name;
	mkdir($dir_name);
}
*/


while( 1 ) {

	$cmd = "SELECT `index` FROM `change` WHERE 1";
	$result = doquery($cmd, true);
	$i = $result['index'];
	
	if( $i > 59285 )	break;
	
	
	
	// gid
	$ID = 10000 + $i;
	
	echo $ID."==><br />";
	
	$sub_name  = "";
	$mod = floor( $ID % 100 );
	if( $mod<10 )	$sub_name = "0".$mod;
	else 					$sub_name = $mod;
	
	
	// 搬移目錄
	$old_file = "../wp-content/uploads/_img/7_".$ID.".jpg";
	$new_file = "../wp-content/uploads/img/".$sub_name."/7_".$ID.".jpg";
	echo " copy  $new_file(".copy( $old_file, $new_file ).")<br />";
	
	$old_file = "../wp-content/uploads/_img_big/7_".$ID.".jpg";
	$new_file = "../wp-content/uploads/img_big/".$sub_name."/7_".$ID.".jpg";
	echo " copy  $new_file(".copy( $old_file, $new_file ).")<br />";
	
	$old_file = "../wp-content/uploads/_swf/7_".$ID.".swf";
	$new_file = "../wp-content/uploads/swf/".$sub_name."/7_".$ID.".swf";
	echo " copy  $new_file(".copy( $old_file, $new_file ).")<br />";
	
	
	// 更新 postmeta
	$cmd = "UPDATE  `wp_postmeta` SET `meta_value` =  'http://192.168.1.143:8888/wp-content/uploads/img/".$sub_name."/7_".$ID.".jpg' WHERE `meta_key` = 'thumb' AND `post_id` = '".$ID."';";
	echo "update img(".doquery($cmd).")<br />";
	
	$cmd = "UPDATE  `wp_postmeta` SET `meta_value` =  'http://192.168.1.143:8888/wp-content/uploads/img_big/".$sub_name."/7_".$ID.".jpg' WHERE `meta_key` = 'thumb_b' AND `post_id` = '".$ID."';";
	echo "update img_big(".doquery($cmd).")<br />";
	
	$cmd = "UPDATE  `wp_postmeta` SET `meta_value` =  'http://192.168.1.143:8888/wp-content/uploads/swf/".$sub_name."/7_".$ID."' WHERE `meta_key` = 'gameswf' AND `post_id` = '".$ID."';";
	echo "update swf(".doquery($cmd).")<br />";
	
	
	
	$cmd = "UPDATE `change` SET `index` = `index` + 1 WHERE 1;";
	$result = doquery($cmd);
}

echo "花費時間(".(time()-$begin_time).")<br />";


?>
<br />
<br />


</body>
</html>