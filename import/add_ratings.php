<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
<?php
require_once("./db/db.php");

$cmd = "SELECT `ID` FROM `wp_posts` WHERE 1";
$result = doquery($cmd);
while( $row = mysql_fetch_array( $result ) ) {

	$cmd2 = "INSERT INTO `wp_postmeta` ( `post_id`, `meta_key`, `meta_value`) VALUES
		( '".$row['ID']."', 'ratings_average', '4'),
		( '".$row['ID']."', 'ratings_users', '1'),
		( '".$row['ID']."', 'ratings_score', '4')";
	$result2 = doquery($cmd2);
	
	echo $row['ID']." ==> ".$result2."<br />";

}
?>
</body>
</html>