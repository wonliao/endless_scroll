<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
<?php
require_once("./db/db.php");

/*
$name_list = array(
"Jacob","Isabella",
"Ethan","Sophia",
"Michael","Emma",
"Jayden","Olivia",
"William","Ava",
"Alexander","Emily",
"Noah","Abigail",
"Daniel","Madison",
"Aiden","Chloe",
"Anthony","Mia",
"Joshua","Addison",
"Mason","Elizabeth",
"Christopher","Ella",
"Andrew","Natalie",
"David","Samantha",
"Matthew","Alexis",
"Logan","Lily",
"Elijah","Grace",
"James","Hailey",
"Joseph","Alyssa",
"张伟","王伟","王芳","李伟","王秀","李秀英","李娜","张秀英","刘伟","张敏","李静","张丽","王静","王丽","李强","张静","李敏","王敏","王磊","李军","刘洋","王勇","张勇","王艳","李杰","张磊","王强","王军","张杰","李娟","张艳","张涛","王涛","李明","李艳","王超","李勇","王娟","刘杰","王秀兰","李霞","刘敏","张军","李丽","张强","王平","王刚","王杰","李桂英","刘芳" );

//print_r( $name_list );


// 新增 wp_users

foreach( $name_list as $id ) {

	$cmd = "INSERT INTO `wp_users` ( 
	`user_login`, 
	`user_pass`, 
	`user_nicename`, 
	`user_email`, 
	`user_url`, 
	`user_registered`, 
	`user_activation_key`, 
	`user_status`, 
	`display_name`
	) VALUES (  
	'$id', 
	'\$P\$BKSzX0RARTX992AZs3u6VC0DKwgz1c1', 
	'$id', 
	'$id@gmail.com', 
	'',
	'2012-05-11 09:13:45', 
	'', 
	0, 
	'$id'
	);";

	//echo $cmd."<r />";
	$result = doquery($cmd);
	echo "result(".$result.")(".$cmd.")<br />";
}

*/


?>
</body>
</html>
