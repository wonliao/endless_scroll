<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>
<?php
require_once("./db/db.php");

$cmd = "SELECT *
FROM  `wp_postmeta` 
GROUP BY `post_id`
HAVING (
  (
    `meta_key` = 'mouseleft' OR
    `meta_key` = 'mousescroll' OR
    `meta_key` = 'mousemove' OR
    `meta_key` = 'drag'
  ) 
  AND
  (
    `meta_key` != 'alt' AND
    `meta_key` != 'enter' AND
    `meta_key` != 'shift' AND
    `meta_key` != 'ctrl' AND
    `meta_key` != 'tab' AND
    `meta_key` != 'end' AND
    `meta_key` != 'space' AND
    `meta_key` != 'left' AND
    `meta_key` != 'right' AND
    `meta_key` != 'up' AND
    `meta_key` != 'down' AND
    `meta_key` != 'lr' AND
    `meta_key` != 'ud' AND
    `meta_key` != 'wasd' AND
    `meta_key` != 'arrows' AND
    `meta_key` != 'key_0' AND
    `meta_key` != 'key_1' AND
    `meta_key` != 'key_2' AND
    `meta_key` != 'key_3' AND
    `meta_key` != 'key_4' AND
    `meta_key` != 'key_5' AND
    `meta_key` != 'key_6' AND
    `meta_key` != 'key_7' AND
    `meta_key` != 'key_8' AND
    `meta_key` != 'key_9' AND
    `meta_key` != 'key_a' AND
    `meta_key` != 'key_b' AND
    `meta_key` != 'key_c' AND
    `meta_key` != 'key_d' AND
    `meta_key` != 'key_e' AND
    `meta_key` != 'key_f' AND
    `meta_key` != 'key_g' AND
    `meta_key` != 'key_h' AND
    `meta_key` != 'key_i' AND
    `meta_key` != 'key_j' AND
    `meta_key` != 'key_k' AND
    `meta_key` != 'key_l' AND
    `meta_key` != 'key_m' AND
    `meta_key` != 'key_n' AND
    `meta_key` != 'key_o' AND
    `meta_key` != 'key_p' AND
    `meta_key` != 'key_q' AND
    `meta_key` != 'key_r' AND
    `meta_key` != 'key_s' AND
    `meta_key` != 'key_t' AND
    `meta_key` != 'key_u' AND
    `meta_key` != 'key_v' AND
    `meta_key` != 'key_w' AND
    `meta_key` != 'key_x' AND
    `meta_key` != 'key_y' AND
    `meta_key` != 'key_z' 
  )
)";
$result = doquery($cmd);
while( $row = mysql_fetch_array( $result ) ) {

	list( $meta_id, $post_id, $meta_key, $meta_value ) = $row;

	//echo "meta_id(".$meta_id.") post_id(".$post_id.") meta_key(".$meta_key.") meta_value(".$meta_value.")<br />";
	
	$cmd2 = "INSERT INTO `wp_term_relationships` (`object_id`, `term_taxonomy_id`, `term_order`) VALUES ('".$post_id."', '2888', '0');";
	
	$result2 = doquery($cmd2);

	echo $post_id." ==> ".$result2."<br />";

}



?>
</body>
</html>