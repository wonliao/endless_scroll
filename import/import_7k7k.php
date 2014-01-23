<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Untitled Document</title>
</head>

<body style="color:#999999;">
<?php
require_once("./db/db.php");
require_once("lib/simple_html_dom.php");
require_once("lib/big2gb.php");
//include('../wp-config.php');


ini_set ('allow_url_fopen', '1');
set_time_limit(86400);

/*
// 建立目錄
for($i=0; $i<100; $i++ ) {

	$sub_name = "";
	
	if( $i<10 )	$sub_name = "0".$i;
	else 			$sub_name = $i;

	echo $sub_name."<br />";
	$dir_name = "../wp-content/uploads/swf/".$sub_name;
	mkdir($dir_name);
	
	$dir_name = "../wp-content/uploads/img/".$sub_name;
	mkdir($dir_name);
	
	$dir_name = "../wp-content/uploads/img_big/".$sub_name;
	mkdir($dir_name);
	
}
exit();
*/




// 簡繁轉換用的物件
$obj=new big2gb;


$begin_time = time()."<br />";

echo "================================================================================<br />";



// 檔名
//$file_name = "http://60.199.253.136/api/action/datastore_search?resource_id=c57f54e2-8ac3-4d30-bce0-637a8968796e&limit=500";
//echo $file_name."<br />";
$file_name = "pet.json";

$file = file_get_contents($file_name);
if( $file ) {

	// simple HTML DOM Paser
	$html = file_get_html($file_name);
	
	$json = json_decode($html);
	
	//var_dump($json->result->records);
	
	$petList = $json->result->records;
	
	
	foreach($petList as $key => $petInfo) {
		
		//echo "($key)$petInfo->Name<br />$petInfo->ImageName";	
		//echo $petInof->ImageName;
		//echo "<img src='$petInfo->ImageName' name='$petInfo->AcceptNum' />";
		
		$source = $petInfo->ImageName;
		$target = "../pet_img/".$petInfo->AcceptNum.".jpg";
		
		$flag = copy($source, $target);

		echo "$key => flag($flag) source($source) target($target)<br />";
	}
	
	
	
/*
	// 標題
	$title = "";
	foreach( $html->find("h1") as $item ) {
		$title = $obj->chg_utfcode( strip_tags( $item ), 'big5' );
	}
	echo "遊戲名:".$title."<br />";
	
	// 遊戲說明
	$content = "";
	foreach( $html->find("div[class=game-intro dotted-line] p") as $item ) {	
		$content .= strip_tags( $item );
	}
	$content = $obj->chg_utfcode($content,'big5');
	echo "遊戲說明:".$content."<br />";

	// 日期
	//$date = "2012"."-"."04"."-"."28"." "."12".":"."10".":00";
	//echo "date(".$date.")<br />";
	
	// 2012-04-28 12:10:00
	$my_t = getdate();
	//print_r( $my_t );
	$date = $my_t['year']."-".$my_t['mon']."-".$my_t['mday']." ".$my_t['hours'].":".$my_t['minutes'].":".$my_t['seconds'];
	//echo "date(".$date.")<br />";

	// 類型
	$type = set_type( $html, $obj );
	//echo $type."<br />";
 	
	// 插入文章
	insert_posts( $ID, $data, $content, $title, $date );
	
	// 插入分類
	//insert_term_relationships( $ID, $type );
	
	// 插入標籤
	insert_tags( $ID, $type, $html, $obj );
	
	// 插入 wp_postmeta
	insert_postmeta( $ID, $file_name, $html, $obj, $file );

	
	
	echo "花費時間(".(time()-$begin_time).")<br />";
*/
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

	//echo $cmd."<br />";
	$result = doquery($cmd);
	//echo "insert_posts(".$result.")<br />";
}

function insert_term_relationships( $ID, $type ) {

	$cmd = "INSERT INTO `wp_term_relationships` ( `object_id`, `term_taxonomy_id`, `term_order`) VALUES
		( '".$ID ."', '".$type."', '0' );";
	///echo $cmd."<br />";
	$result = doquery($cmd);
	//echo "insert_term_relationships(".$result.")<br />";
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
				//echo "cmd411(".$cmd411.")<br />";
				$term_taxonomy_id = $result411['term_taxonomy_id'];
				//echo "result41(".$result411.") term_taxonomy_id(".$term_taxonomy_id.")<br />";
			} else {
			
				// not find
				$cmd41 = "INSERT INTO `wp_terms` ( `name`, `slug` ) VALUES ( '".$tag."', '".urlencode($tag)."' );";
				$result41 = doquery($cmd41);
				$term_id = mysql_insert_id();
				
				$cmd41 = "INSERT INTO `wp_term_taxonomy` ( `term_id`, `taxonomy`, `description`, `count`) VALUES ( '".$term_id."', 'post_tag', '', 1 );";
				//echo "cmd41(".$cmd41.")<br />";
				$result41 = doquery($cmd41);
				$term_taxonomy_id = mysql_insert_id();
			}

			$cmd42 = "INSERT INTO `wp_term_relationships` ( `object_id`, `term_taxonomy_id` ) VALUES ( '".$ID ."', '".$term_taxonomy_id."' );";
			//echo "cmd42(".$cmd42.")<br />";
			$result42 = doquery($cmd42);
			//echo "result42(".$result42.")<br />";
		}
		$count++;
	}	
}

function insert_postmeta( $ID, $file_name, $html, $obj, $file ) {

	// views
	$views = rand( 50000, 80000 ); //$html->find("ul[class=game-items layout] li span", 1 );
	echo "views(".$views.")<br />";
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

	if( stristr( $gamepath, ".htm" ) != false ) {

		echo "html<br />";

		$gamepath .= '?r='.rand(0, 9999);
		echo "gamepath(".$gamepath.")<br />";
		clearstatcache();
		$file2 = file_get_contents($gamepath);
		
		echo $file2;
		
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

			$gamepath = $new_path;
		}
	}
	


	$sub_name  = "";
	$mod = floor( $ID % 100 );
	if( $mod<10 )	$sub_name = "0".$mod;
	else 					$sub_name = $mod;

	$pic_file 	= "wp-content/uploads/img/".$sub_name."/7_".$ID.".jpg";
	$pic_b_file = "wp-content/uploads/img_big/".$sub_name."/7_".$ID.".jpg";
	$swf_file 	= "wp-content/uploads/swf/".$sub_name."/7_".$ID.".swf";
	

	// 下載至 wp-content/uploads 下的目錄
	copy( (string)($gamepic), "../".$pic_file );
	copy( (string)($gamebigpic), "../".$pic_b_file );
	copy( (string)($gamepath), "../".$swf_file );
	
	$dir = home_url()."/";// "http://192.168.1.143:8888/";
	//echo "dir(".$dir.")<br />";
	
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
	//echo "result(".$result.")<br />";
	
	gd( $ID, $pic_file );
	
	echo "<img src='../".$pic_file."' style='width:76px; height:77px;'/><br />";
	echo "<img src='../".$pic_b_file."' style='width:300px; height:200px;' /><br />";
	echo "<embed src='../".$swf_file."' style='width:900px; height:500px;'></embed><br />";
	
}


function gd( $ID, $source_file ) {

	// 底圖
	$bg = "./image/bg_7k7k.jpg";
	
	// 底圖尺寸
	$bg_size = getimagesize( $bg  );
	define("BG_W", $bg_size[0]);
	define("BG_H", $bg_size[1]);
	$img = imagecreatefromjpeg($bg);
	
	// 遮罩
	$hole = imagecreatefrompng("./image/mask_7k7k.png");

	$sub_name  = "";
	$mod = floor( $ID % 100 );
	if( $mod<10 )	$sub_name = "0".$mod;
	else 			$sub_name = $mod;

	//$target = "./uploads/old_img/".$sub_name."/".$ID.".jpg";
	
	$target = "../".$source_file;
	$result_img = "../".$source_file;

	// 檢查檔案是否存在
	if( file_get_contents($target) ) {

		// 下載圖檔至本地
		//echo "copy(".copy( $source_file, $target ).") ";

		// 底圖
		//$img = imagecreatefromjpeg($bg);
		$dest = imagecreatetruecolor(BG_W, BG_H);
		imagecopyresized($dest, $img, 0, 0, 0, 0, BG_W, BG_H, BG_W, BG_H);
		//imagedestroy($img);
		
		// 目標圖
		$img_1 = imagecreatefromjpeg($target);
		imagecopyresized($dest, $img_1, 0, 0, 0, 0, BG_W, BG_H, BG_W, BG_H);
		imagedestroy($img_1);

		// 遮罩
		//$hole = imagecreatefrompng("./image/mask.png");
		imagecopy($dest, $hole, 0, 0, 0, 0, 100, 100);
		//imagedestroy($hole);

		// 產生 jpg 結果檔
		imagejpeg($dest, $result_img, 100);

		// 清除暫存
		imagedestroy($dest);
	}

	imagedestroy($img);
	imagedestroy($hole);
}


?>



</body>
</html>