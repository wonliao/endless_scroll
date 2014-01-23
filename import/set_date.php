<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body>
<?php
require_once("./db/db.php");
require_once("lib/simple_html_dom.php");
require_once("lib/big2gb.php");


function remoteFileExists($url) {
    $curl = curl_init($url);
 
    //don't fetch the actual page, you only want to check the connection is ok
    curl_setopt($curl, CURLOPT_NOBODY, true);
 
    //do request
    $result = curl_exec($curl);
 
    $ret = false;
 
    //if request did not fail
    if ($result !== false) {
        //if request was ok, check response code
        $statusCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);  
 
        if ($statusCode == 200) {
            $ret = true;   
        }
    }
 
    curl_close($curl);
 
    return $ret;
}
 

// 簡繁轉換用的物件
$obj=new big2gb;

$count = 0;

$pear_day = 0;
$day = 0;

$cmd = "SELECT 
			`ID`, 
			`post_date`,
			`post_status`
		FROM 
			`wp_posts`
		WHERE 
			`post_status` = 'publish'
			AND `post_date` >= '2013-01-21'
		;
	   ";
$result = doquery($cmd);
while( $row = mysql_fetch_array( $result ) ) {

	$id = $row['ID'];
	$post_date = $row['post_date'];
	
	$cmd2 = "SELECT 
				`meta_key`,
				`meta_value`
			FROM 
				`wp_postmeta`
			WHERE 
				`post_id` = '".$id."'
			;";
	$result2 = doquery($cmd2);
	$count = 0;
	while( $row2 = mysql_fetch_array( $result2 ) ) {
		
		switch( $row2['meta_key'] ) {
		case 'thumb':
			$value = $row2['meta_value'];
			
			if( remoteFileExists( $value ) )	$count++;
			break;
		case 'thumb_b':
			$value = $row2['meta_value'];
			if( remoteFileExists( $value ) )	$count++;
			break;
		case 'gameswf':
			$value = $row2['meta_value'].".swf";
			if( remoteFileExists( $value ) )	$count++;
			break;
		}
	}
	
	echo "id(".$id.") post_date(".$post_date.") count(".$count.")<br />";
	if( $count >= 3 ) {

		// 一天更新18個遊戲
		if( $pear_day > 18 ) {
			
			$day++;
			$pear_day = 0;
		}

		// 更新系統時間
		$public_time = time() + ( $day * 86400 );
		$my_t = getdate( $public_time );
		$time_str = $my_t['year'] . "-". $my_t['mon'] . "-" . $my_t['mday'] . " 4:00:00";
		echo "time_str(".$time_str.")<br />";

		$cmd3 = "UPDATE 
					`wp_posts` 
				 SET 
				 	`post_date` = '".$time_str."',
					`post_date_gmt` = '".$time_str."',
					`post_modified` = '".$time_str."',
					`post_modified_gmt` = '".$time_str."'
				 WHERE 
				 	`wp_posts`.`ID` = '".$id."';
				";
		//echo "cmd3(".$cmd3.")<br />";
		$result3 = doquery($cmd3);
		//echo "result3(".$result3.")<br />";

		$pear_day++;
		$count++;
	} else {
		
		$cmd3 = "UPDATE 
					`wp_posts` 
				 SET 
				 	`post_status` = 'private'
				 WHERE 
				 	`wp_posts`.`ID` = '".$id."';
				";
		//echo "cmd3(".$cmd3.")<br />";
		$result3 = doquery($cmd3);
	}

}

exit();
// 找出 狀態為私密 有flash 的遊戲 
$cmd = "SELECT 
			a.`ID` AS `ID`, 
			a.`post_status` AS `post_status`, 
			b.`meta_value` AS `meta_value` 
		FROM 
			`wp_posts` as a, 
			`wp_postmeta` as b 
		WHERE 
			a.`ID` = b.`post_id` 
			AND a.`post_status` = 'private'  
			AND b.`meta_key` = 'gameswf' 
			AND b.`meta_value` != ''
		;
	   ";
$result = doquery($cmd);
while( $row = mysql_fetch_array( $result ) ) {

	$id = $row['ID'];
	$post_status = $row['post_status'];
	$meta_value = $row['meta_value'];
	
	//echo "id(".$id.") post_status(".$post_status.") meta_value(".$meta_value.")<br />";
	
	// 找出有分類的遊戲
	$cmd2 = "SELECT 
				b.`term_id` AS `term_id`
			 FROM 
			 	`wp_term_relationships` as a, 
				`wp_term_taxonomy` as b
			 WHERE 
			 	a.`object_id` = '".$id."' 
				AND a.`term_taxonomy_id` = b.`term_taxonomy_id` 
				AND b.`taxonomy` = 'category'
			";
	$result2 = doquery($cmd2);
	while( $row2 = mysql_fetch_array( $result2 ) ) {

		$term_id = $row2['term_id'];
		echo "id(".$id.") post_status(".$post_status.") meta_value(".$meta_value.") term_id(".$term_id.")<br />";

		// 一天更新18個遊戲
		if( $pear_day > 18 ) {
			
			$day++;
			$pear_day = 0;
		}

		// 更新系統時間
		$public_time = time() + ( $day * 86400 );
		$my_t = getdate( $public_time );
		$time_str = $my_t['year'] . "-". $my_t['mon'] . "-" . $my_t['mday'] . " 12:00:00";
		echo "time_str(".$time_str.")<br />";

		$post_status = "future";
		if( $day == 0 )	 $post_status = "publish";

		$cmd3 = "UPDATE 
					`wp_posts` 
				 SET 
				 	`post_status` = '".$post_status."',
				 	`post_date` = '".$time_str."',
					`post_date_gmt` = '".$time_str."',
					`post_modified` = '".$time_str."',
					`post_modified_gmt` = '".$time_str."'
				 WHERE 
				 	`wp_posts`.`ID` = '".$id."';
				";
		//echo "cmd3(".$cmd3.")<br />";
		$result3 = doquery($cmd3);
		echo "result3(".$result3.")<br />";
		
		$pear_day++;

		$count++;
	}
}

echo "count(".$count.")<br />";

?>
<br />
<br />
<a href="import_7k7k_single.php">返回</a>

</body>
</html>