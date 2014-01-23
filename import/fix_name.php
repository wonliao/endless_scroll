<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
<?php
require_once("./db/db.php");

$fix_list = array(
'體育',
'冒險',
'棋牌',
'動作',
'益智',
'搞怪',
'趣味',
'賽車',
'射擊',
'反應',
'策略',
'闖關',
'戰鬥',
'RPG',
'女生遊戲',
'記憶',
'麻吉');

foreach( $fix_list as $name ) {

	//echo $name."<br />";
	$cmd = "UPDATE  `wp_posts` SET `post_title` = REPLACE(`post_title`, '【".$name."】', ''), `post_name` = REPLACE(`post_name`, '【".$name."】', '') WHERE 1;";
	$result = doquery($cmd);
	echo "【".$name."】 ==> ".$result."<br />";
}

?>
</body>
</html>