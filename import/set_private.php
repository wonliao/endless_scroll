<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>將遊戲設為私密</title>
</head>

<body>
<?php
require_once("db/db.php");


function check_remote_file_exists($url) {

	$curl = curl_init($url);

    // 不取回数据
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, false);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, false);
	curl_setopt($curl, CURLOPT_MAXREDIRS, 1);

    // 发送请求
    $result = curl_exec($curl);
    $found = false;
    // 如果请求没有发送失败
    if ($result !== false) {
        // 再检查http响应码是否为200
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
        if ($statusCode == 200) {
            $found = true;   
        }
    }
    curl_close($curl);
 
    return $found;
}


echo "<h1>step 1</h1><br />";
$cmd = "SELECT `post_id`, `meta_value` FROM `wp_postmeta` WHERE  `meta_key` = 'gameswf' ORDER BY `post_id` DESC";
$result = doquery($cmd);

$games = array();
while( $row = mysql_fetch_array( $result ) ) {

	$game = array_combine( array( 'gid', 'swf' ) , array( $row['post_id'], $row['meta_value'] ) );
	array_push( $games, $game );
}

echo "<h1>step 2</h1><br />";
$ids = "";
foreach( $games as $game ) {

	$url = $game['swf'];

	if( check_remote_file_exists( $url ) == false ) {
		echo "gid(".$game['gid'].") swf(".$game['swf'].")<br />";
		$ids .= $game['gid'] . ",";
	}
}

echo "<h1>step 3</h1><br />";
$ids2 = "";
for( $i=0; $i<strlen( $ids )-1; $i++ ) {
	$ids2 .= $ids[$i];
}

echo "<h1>step 4</h1><br />";
$cmd2 = "UPDATE `wp_posts` SET `post_status` = 'private' WHERE `ID` IN ( ".$ids2." );";
echo $cmd2."<br />";

echo "<h1>step 5</h1><br />";
$result2 = doquery($cmd2);
echo "result2(".$result2.")<br />";

?>
</body>
</html>