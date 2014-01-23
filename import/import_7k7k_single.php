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

// 簡繁轉換用的物件
$obj=new big2gb;


$begin_time = time()."<br />";

$game_id = $_GET['game_id'];
//echo "game_id(".$game_id.")<br />";

if( !$game_id ) {
	?>
    <form action="import_7k7k_single.php" method="get">
	<label>請輸入遊戲編號：</label>
    <input type="text" id="game_id" name="game_id"/>
    <input type="submit" value="提交"/>
	</form>
	<?php
	
	exit();
} else {

	$i = 	floor($game_id);
}

/*
$cmd = "SELECT `index` FROM `7k7k` WHERE 1";
$result = doquery($cmd, true);
$i = $result['index'];


$cmd = "UPDATE `7k7k` SET `index` = `index` + 1 WHERE 1;";
$result = doquery($cmd);
*/

echo " ========================================<br />";

// gid
$ID = 10000 + $i;

// 檔名
$file_name = "http://www.7k7k.com/swf/".$i.".htm";
echo $file_name."<br />";

$file = file_get_contents($file_name);
if( $file ) {

	// simple HTML DOM Paser
	$html = file_get_html($file_name);
	
	
	// 標題
	$title = "";
	foreach( $html->find("h1") as $item ) {
		$title = $obj->chg_utfcode( strip_tags( $item ), 'big5' );
	}
	echo $title."<br />";
	
	// 遊戲說明
	$content = "";
	foreach( $html->find("div[class=game-intro dotted-line] p") as $item ) {	
		$content .= strip_tags( $item );
	}
	$content = $obj->chg_utfcode($content,'big5');
	echo $content."<br />";
	
	// 類型
	$type = set_type( $html, $obj );
	//echo $type."<br />";
	
	// 日期
	$date = "2012"."-"."04"."-"."28"." "."12".":"."10".":00";
	
	// 插入文章
	insert_posts( $ID, $data, $content, $title, $date );
	
	// 插入分類
	//insert_term_relationships( $ID, $type );
	
	// 插入標籤
	insert_tags( $ID, $type, $html, $obj );
	
	// 插入 wp_postmeta
	insert_postmeta( $ID, $file_name, $html, $obj, $file );
	
	
	
	echo "花費時間(".(time()-$begin_time).")<br />";

}




function set_type( $html, $obj ) {

	$type_name = $obj->chg_utfcode( strip_tags( $html->find("ul[class=game-items layout] li a", 0) ), 'big5');
	
	$term_taxonomy_id = 138;
	switch( $type_name ) {
	case "女生":	$term_taxonomy_id = 15;		break;
	case "精品":	$term_taxonomy_id = 138;	break;
	case "休閒":	$term_taxonomy_id = 118;	break;
	case "動作":	$term_taxonomy_id = 4;		break;
	case "冒險";	$term_taxonomy_id = 2;		break;
	case "換裝":	$term_taxonomy_id = 15;		break;
	case "射擊":	$term_taxonomy_id = 9;		break;
	case "戰爭":	$term_taxonomy_id = 138;	break;
	case "雙人":	$term_taxonomy_id = 120;	break;
	case "益智":	$term_taxonomy_id = 5;		break;
	case "體育":	$term_taxonomy_id = 1;		break;
	case "動畫":	$term_taxonomy_id = 123;	break;
	case "漫畫":	$term_taxonomy_id = 123;	break;
	default:	$term_taxonomy_id = 138;	break;
	}

	return $term_taxonomy_id;
}


function insert_posts( $ID, $data, $content, $title, $date ) {

	$cmd = "INSERT INTO `wp_posts` ( 
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
			'".$ID."',   
			1, 
			'".$date."',   
			'".$date."',  
			'".$content."',  
			'".$title."',  
			'', 
			'private', 
			'open', 
			'open', 
			'', 
			'".$title."', 
			'', 
			'', 
			'".$date."', 
			'".$date."', 
			'', 
			0, 
			'', 
			0, 
			'post', 
			'', 
			0
			);";	

	echo $cmd."<br />";
	$result = doquery($cmd);
	echo "insert_posts(".$result.")<br />";
}

function insert_term_relationships( $ID, $type ) {

	$cmd = "INSERT INTO `wp_term_relationships` ( `object_id`, `term_taxonomy_id`, `term_order`) VALUES
		( '".$ID ."', '".$type."', '0' );";
	echo $cmd."<br />";
	$result = doquery($cmd);
	echo "insert_term_relationships(".$result.")<br />";
}

function insert_tags( $ID, $type, $html, $obj ) {

	$count = 0;
	foreach( $html->find("ul[class=game-items layout] li[class=tags]  a" )  as $item ) {
		
		if( $count != 0 ) {

			$tag = $obj->chg_utfcode( strip_tags( $item ), 'big5');
	
			$term_id = 0;

			$cmd = "SELECT `term_id`, `name` FROM `wp_terms` WHERE `name` = '".$tag."';";
			$result = doquery($cmd, true);
			if( $result ) {

				// find
				$term_id = $result['term_id'];
				$cmd41 = "UPDATE `wp_term_taxonomy` SET `count` = `count` + 1 WHERE `term_id` = '".$term_id."';";
				$result41 = doquery($cmd41);
				
				$cmd411 = "SELECT `term_taxonomy_id` FROM `wp_term_taxonomy` WHERE `term_id` = '".$term_id."';";
				$result411 = doquery($cmd411, true);
				$term_taxonomy_id = $result411['term_taxonomy_id'];
				echo "result41(".$result411.") term_taxonomy_id(".$term_taxonomy_id.")<br />";
			} else {
			
				// not find
				$cmd41 = "INSERT INTO `wp_terms` ( `name`, `slug` ) VALUES ( '".$tag."', '".urlencode($tag)."' );";
				$result41 = doquery($cmd41);
				$term_id = mysql_insert_id();
				
				$cmd41 = "INSERT INTO `wp_term_taxonomy` ( `term_id`, `taxonomy`, `description`, `count`) VALUES ( '".$term_id."', 'post_tag', '', 1 );";
				echo "cmd41(".$cmd41.")<br />";
				$result41 = doquery($cmd41);
				$term_taxonomy_id = mysql_insert_id();
			}

			$cmd42 = "INSERT INTO `wp_term_relationships` ( `object_id`, `term_taxonomy_id` ) VALUES ( '".$ID ."', '".$term_taxonomy_id."' );";
			echo "cmd42(".$cmd42.")<br />";
			$result42 = doquery($cmd42);
			echo "result42(".$result42.")<br />";
		}
		$count++;
	}	
}

function insert_postmeta( $ID, $file_name, $html, $obj, $file ) {

	// views
	$views = $html->find("ul[class=game-items layout] li span", 1 );

	// 下載大小圖檔及swf	
	

	// gamepic
	$str1 = stristr( $file, "_gamepic" );
	$temp = explode( "=", $str1 );
	$str2 = str_ireplace( "_gamebigpic", "", $temp[1] );
	$str2 = str_ireplace( ",", "", $str2 );
	$str2 = str_ireplace( "\"", "", $str2 );
	$str2 = trim($str2);
	$gamepic = $str2;
	
	// gamebigpic
	$str1 = stristr( $file, "_gamebigpic" );
	$temp = explode( "=", $str1 );
	$str2 = str_ireplace( "_gamepath", "", $temp[1] );
	$str2 = str_ireplace( ",", "", $str2 );
	$str2 = str_ireplace( "\"", "", $str2 );
	$str2 = trim($str2);
	$gamebigpic = $str2;
	
	// gamepath
	$str1 = stristr( $file, "_gamepath" );
	$temp = explode( "=", $str1 );
	$str2 = str_ireplace( "_gamevar", "", $temp[1] );
	$str2 = str_ireplace( ",", "", $str2 );
	$str2 = str_ireplace( "\"", "", $str2 );
	$str2 = trim($str2);
	$gamepath = $str2;

	$sub_name  = "";
	$mod = floor( $ID % 100 );
	if( $mod<10 )	$sub_name = "0".$mod;
	else 					$sub_name = $mod;

	$pic_file 	= "wp-content/uploads/img/".$sub_name."/7_".$ID.".jpg";
	$pic_b_file = "wp-content/uploads/img_big/".$sub_name."/7_".$ID.".jpg";
	$swf_file 	= "wp-content/uploads/swf/".$sub_name."/7_".$ID.".swf";

	// 下載至 wp-content/uploads 下的目錄
	echo "(".copy( (string)($gamepic), "../".$pic_file ).") => ".$pic_file."<br />";
	echo "(".copy( (string)($gamebigpic), "../".$pic_b_file ).") => ".$pic_b_file."<br />";
	echo "(".copy( (string)($gamepath), "../".$swf_file ).") => ".$swf_file."<br />";
	
	
	$dir = "http://192.168.1.143:8888/";
	
	$cmd = "INSERT INTO `wp_postmeta` ( `post_id`, `meta_key`, `meta_value`) VALUES
		( '".$ID."', 'thumb', '".$dir.$pic_file."'),
		( '".$ID."', 'thumb_b', '".$dir.$pic_b_file."'),
		( '".$ID."', 'gameswf', '".$dir."wp-content/uploads/swf/".$sub_name."/7_".$ID."'),
		( '".$ID."', 'views', '".$views."'),
		( '".$ID."', 'evaluate', '0'),
		( '".$ID."', 'ratings_average', '4'),
		( '".$ID."', 'ratings_users', '1'),
		( '".$ID."', 'ratings_score', '4')";

	$result = doquery($cmd);
	echo "result(".$result.")<br />";
}


?>
<br />
<br />
<a href="import_7k7k_single.php">返回</a>

</body>
</html>