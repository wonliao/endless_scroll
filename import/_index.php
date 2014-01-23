<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>小遊戲資料庫匯入工具</title>
</head>

<body>
<?php
require_once("./db/db.php");


// ============ 初始化資料庫 ============
$tcmd = "TRUNCATE TABLE `wp_postmeta`";
$tresult = doquery($tcmd);
$tcmd = "TRUNCATE TABLE `wp_posts`";
$tresult = doquery($tcmd);
$tcmd = "TRUNCATE TABLE `wp_term_relationships`";
$tresult = doquery($tcmd);

$tcmd = "UPDATE `wp_term_taxonomy` SET `count` =  '0' WHERE 1";
$tresult = doquery($tcmd);



$tcmd = "TRUNCATE TABLE `wp_terms`";
$tresult = doquery($tcmd);
$tcmd = "INSERT INTO `wp_terms` (`term_id`, `name`, `slug`, `term_group`) VALUES
(1, '體育', 'sports', 0),
(2, '冒險', 'adventure', 0),
(3, '棋牌', 'chess', 0),
(4, '動作', 'action', 0),
(5, '益智', 'puzzle', 0),
(6, '搞怪', 'funny', 0),
(7, '趣味', 'interest', 0),
(8, '賽車', 'racing', 0),
(9, '射擊', 'shooting', 0),
(10, '反應', 'reaction', 0),
(11, '策略', 'strategy', 0),
(12, '闖關', 'checkpoints', 0),
(13, '戰鬥', 'fighting', 0),
(14, 'RPG', 'rpg', 0),
(15, '女生遊戲', 'girls', 0),
(16, '記憶', 'memory', 0),
(17, '麻吉', 'machi', 0);";
$tresult = doquery($tcmd);


$tcmd = "TRUNCATE TABLE `wp_term_taxonomy`";
$tresult = doquery($tcmd);
$tcmd = "INSERT INTO `wp_term_taxonomy` ( `term_taxonomy_id`, `term_id`, `taxonomy` ) VALUES
(1, 1, 'category'),
(2, '2', 'category'),
(3, '3', 'category'),
(4, '4', 'category'),
(5, '5', 'category'),
(6, '6', 'category'),
(7, '7', 'category'),
(8, '8', 'category'),
(9, '9', 'category'),
(10, '10', 'category'),
(11, '11', 'category'),
(12, '12', 'category'),
(13, '13', 'category'),
(14, '14', 'category'),
(15, '15', 'category'),
(16, '16', 'category'),
(17, '17', 'category');";
$tresult = doquery($tcmd);

// ============ 初始化資料庫 end ========



// ============ 插入 熱門標籤 ============
$keyword_list = array();
$cmd0 = "SELECT `kw` FROM `game_keyword` WHERE 1 GROUP BY `kw`";
$result0 = doquery($cmd0);
$count = 18;
while( $row = mysql_fetch_array( $result0 ) ) {

	array_push( $keyword_list, array( $count++, $row['kw'] ) );
}
//print_r($keyword_list);

foreach( $keyword_list as $keyword ) {

	$cmd01 = "INSERT INTO `wp_terms` ( `name`, `slug`, `term_group` ) VALUES 
		( '".$keyword[1]."', '".urlencode($keyword[1])."', 0 );";	
	//echo "cmd01(".$cmd01.")<br />";
	$result01 = doquery($cmd01);
}
// ============ 插入 熱門標籤 end ========	
	


// 全部遊戲
$cmd = "SELECT a.`gid` as `gid`, a.`gameid` as `gameid`, a.`title` as `title`, a.`user_id` as `user_id`, a.`uploaddate` as `uploaddate`, a.`content` as `content`, b.`pview` as `pview` FROM `game_list` a, `game_status` b WHERE a.`gid` = b.`gid`;";
$result = doquery($cmd);
while( $row = mysql_fetch_array( $result ) ) {

	//echo "result(";
	//print_r( $row);
	//echo ")<br /><br />";
	
	$gid = $row['gid'];
	$tit = $row['title'];
	$content = $row['content'];

	
	
	// ============ 插入 wp_post ============ 
	$url = "http://localhost/wordpress/?p=".$row['gid'];

	$t = $row['uploaddate'];
	$date = $t[0].$t[1].$t[2].$t[3]."-".$t[4].$t[5]."-".$t[6].$t[7]." ".$t[8].$t[9].":".$t[10].$t[11].":00";

	$cmd2 = "INSERT INTO `wp_posts` ( 
		`ID`,
		`post_author`, 
		`post_date`, 
		`post_date_gmt`, 
		`post_content`, 
		`post_title`, 
		`post_excerpt`, 
		`post_status`, 
		`comment_status`, 
		`ping_status`, 
		`post_password`, 
		`post_name`, 
		`to_ping`, 
		`pinged`, 
		`post_modified`, 
		`post_modified_gmt`, 
		`post_content_filtered`, 
		`post_parent`, 
		`guid`, 
		`menu_order`, 
		`post_type`, 
		`post_mime_type`, 
		`comment_count`
		) VALUES ( 
		'".$row['gid']."',  
		1, 
		'".$date."',   
		'".$date."',  
		'".$content."',  
		'".$row['title']."',  
		'', 
		'publish', 
		'open', 
		'open', 
		'', 
		'".$row['title']."', 
		'', 
		'', 
		'".$date."', 
		'".$date."', 
		'', 
		0, 
		'".$url."', 
		0, 
		'post', 
		'', 
		0
		);";

	$result2 = doquery($cmd2);

	$pic_dir = floor( $gid % 100 );
	if( $pic_dir < 10 )	{	$pic_dir = "0".$pic_dir;	}

	$gameid = $row['gameid'];
	$len = strlen($gameid);
	$swf_dir_1 = $gameid[0].$gameid[1];
	$swf_dir_2 = $gameid[$len-2].$gameid[$len-1];
	// ============ 插入 wp_post end ============ 



	// ============ 插入 wp_postmeta ============

	$cmd30 = "SELECT `flag1`, `flag2`, `flag3`, `flag4`, `flag5`, `flag6`, `flag7`, `flag8`, `flag9` FROM `game_status` WHERE `gid` = '".$row['gid']."';";
	$result30 = doquery($cmd30, true);
	$evaluate = 0;
	$evaluate += floor( $result30['flag1'] * 250 ); // 好玩 	
	$evaluate += floor( $result30['flag2'] * 150 ); // 困難
	$evaluate += floor( $result30['flag3'] * -200 ); // 爆爛 
	$evaluate += floor( $result30['flag4'] * 100 ); // 簡單
	$evaluate += floor( $result30['flag5'] * 300 ); // 上癮 
	$evaluate += floor( $result30['flag6'] * 400 ); // 超屌 	
	$evaluate += floor( $result30['flag7'] * -50 ); // 醜斃 	
	$evaluate += floor( $result30['flag8'] * 200 ); // 可愛 	
	$evaluate += floor( $result30['flag9'] * 100 ); // 普普 	

	$cmd3 = "INSERT INTO `wp_postmeta` ( `post_id`, `meta_key`, `meta_value`) VALUES
		( '".$row['gid']."', 'thumb', 'http://54.248.122.37/game/freegame_data_small_pic/".$pic_dir."/".$gid.".jpg'),
		( '".$row['gid']."', 'thumb_b', 'http://54.248.122.37/game/freegame_data/".$pic_dir."/".$gid.".jpg'),
		( '".$row['gid']."', 'gameswf', 'http://sp.youthwant.com.tw/850707190963/".$swf_dir_1."/".$swf_dir_2."/".$gameid."'),
		( '".$row['gid']."', 'views', '".$row['pview']."'),
		( '".$row['gid']."', 'evaluate', '".$evaluate."');";

	$result3 = doquery($cmd3);
	// ============ 插入 wp_postmeta end ======== 



	// ============ 插入 wp_term_relationships ============
	$temp = explode( "】", $tit );
	$terms = ltrim( $temp[0], "【" );
	//echo "terms(".$terms.")<br />";

	$term_taxonomy_id = 1;
	switch( $terms ) {
	case "體育":	$term_taxonomy_id = 1;	break;
	case "冒險":	$term_taxonomy_id = 2;	break;
	case "棋牌":	$term_taxonomy_id = 3;	break;
	case "動作":	$term_taxonomy_id = 4;	break;
	case "益智":	$term_taxonomy_id = 5;	break;
	case "搞怪":	$term_taxonomy_id = 6;	break;
	case "趣味":	$term_taxonomy_id = 7;	break;
	case "賽車":	$term_taxonomy_id = 8;	break;
	case "射擊":	$term_taxonomy_id = 9;	break;
	case "反應":	$term_taxonomy_id = 10;	break;
	case "策略":	$term_taxonomy_id = 11;	break;
	case "闖關":	$term_taxonomy_id = 12;	break;
	case "戰鬥":	$term_taxonomy_id = 13;	break;
	case "RPG":	$term_taxonomy_id = 14;	break;
	case "女生遊戲":	$term_taxonomy_id = 15;	break;
	case "記憶":	$term_taxonomy_id = 16;	break;
	case "麻吉":	$term_taxonomy_id = 17;	break;
	}

	$cmd4 = "INSERT INTO `wp_term_relationships` ( `object_id`, `term_taxonomy_id`, `term_order`) VALUES
		( '".$gid ."', '".$term_taxonomy_id."', '0' );";
	$result4 = doquery($cmd4);
	// ============ 插入 wp_term_relationships end ======== 


	// ============ 插入 tag ============
	$result41 = 0;
	$result42 = 0;
	foreach( $keyword_list as $keyword ) {
	
		//echo $keyword."<br />";
		if( stristr( $tit, $keyword[1] ) || stristr( $content, $keyword[1] ) ) {
			
			//echo "keyword(".$keyword[1].") tit(".$tit.")<br />";
			
			$cmd400 = "SELECT `term_id`, `term_taxonomy_id` FROM `wp_term_taxonomy` WHERE `term_id` = '".$keyword[0]."';";
			$result400 = doquery($cmd400, true);
			//echo "result400(".$result400['term_id'].")<br />";
			if( $result400 ) {
			
				$cmd41 = "UPDATE `wp_term_taxonomy` SET `count` = `count` + 1 WHERE `term_id` = '".$keyword[0]."';";
				//echo "cmd41(".$cmd41.")<br />";
				$result41 = doquery($cmd41);
				
				$term_taxonomy_id =$result400['term_taxonomy_id'];
			} else {
				$cmd41 = "INSERT INTO `wp_term_taxonomy` ( `term_id`, `taxonomy`, `count`) VALUES ( '".$keyword[0]."', 'post_tag', 1 );";
				//echo "cmd41(".$cmd41.")<br />";
				$result41 = doquery($cmd41);
				$term_taxonomy_id = mysql_insert_id();
			}
			

			$cmd42 = "INSERT INTO `wp_term_relationships` ( `object_id`, `term_taxonomy_id` ) VALUES
				( '".$gid ."', '".$term_taxonomy_id."' );";
			$result42 = doquery($cmd42);
			
		}
	}
	// ============ 插入 tag end ========


	echo $row['gid']." ==> result2(".$result2.") result3(".$result3.") result4(".$result4.") result41(".$result41.") result42(".$result42.")<br />";
}


// ============ 更新 wp_term_taxonomy ============
for( $i=1; $i<=17; $i++ ){

	$cmd5 = "SELECT count(`object_id`) AS count FROM `wp_term_relationships` WHERE `term_taxonomy_id` = ".$i;
	$result5 = doquery($cmd5, true);
	$count = $result5['count'];
	//echo "result5(".$count.")<br />";
	
		
	$cmd6 = "UPDATE `wp_term_taxonomy` SET `count` = ".$count." WHERE `term_taxonomy_id` = ".$i;
	//echo "cmd6(".$cmd6.")<br />";
	$result6 = doquery($cmd6);
	echo "result6(".$result6.") ==> count(".$count.")<br />";
}
// ============ 更新 wp_term_taxonomy end ======== 



?>
</body>
</html>