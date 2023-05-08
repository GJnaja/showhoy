<?php
/*
Plugin Name: sh-plugin
Description: [showhoy width="100%" height="2400"] เพิ่ม shortcode นี้ เพื่อเพิ่มตารางแสดงเลขถูกรางวัล และ [showhoy2 width="100%" height="650"] เพิ่ม shortcode นี้ การตรวจสอบผลรางวัล
Author: GJ
*/

class wp_showhoy_plugin extends WP_Widget {
	// constructor
	function __construct() {
			parent::__construct(
        'wp_showhoy_plugin', // Base ID
        'Showhoy Widget: style 1', // Name
        array( 'description' => 'widget สำหรับการแสดงผลสลากกินแบ่งรัฐบาลรูปแบบที่ 1 นำไปวางในส่วนที่ท่านต้องการให้แสดงผล.' ) // Args
		  );
  }
  function form($instance) {
  if( $instance) {
      $title = esc_attr($instance['title']);
      $width = esc_attr($instance['width']);
      $height = esc_attr($instance['height']);
  } else {
      $title = '';
      $width = '100%';
      $height = '650';
  }
}

}
?>

<?php

function sshowhoy($atts, $content = null ) {
  
    //echo '<div id="my-plugin-value-container"></div>';
    $a = shortcode_atts( array(
        'width' => '100%',
        'height' => '650',
    ), $atts );
    //return '<iframe href="http://localhost/showhoy/wp-content/plugins/sh-plugin/show.php" width='.$a['width'].' height='.$a['height'].' frameborder=0></iframe>';
    //include 'show.php';
    return '<iframe src="/wp-content/plugins/sh-plugin/show.php" width='.$a['width'].' height='.$a['height'].' frameborder=0></iframe>';
    
}

function sshowhoy2($atts, $content = null ) {
  
  //echo '<div id="my-plugin-value-container"></div>';
  $a = shortcode_atts( array(
      'width' => '100%',
      'height' => '100%',
  ), $atts );
  return '<iframe src="/wp-content/plugins/sh-plugin/checker.php" width='.$a['width'].' height='.$a['height'].' frameborder=0></iframe>';
}

function rssshowhoy($atts, $content = null ) {
  $a = shortcode_atts( array(
    'numbers' => '10',
  ), $atts );
  $i = 0;
	$url = "https://www.lottery.co.th/lotto/feed";
	ob_start();
	$rss = simplexml_load_file($url);
	foreach($rss->channel->item as $item) { 
    if ($i < $a['numbers']) { 
	    print '<h3><a href="'.$item->link.'" title="'.$item->title.'">'.$item->title.'</a></h3><br />'.$item->description.''; 
    } $i++; 
	}
	$output = ob_get_clean();
	return $output;
}
add_shortcode('showhoy', 'sshowhoy');
add_shortcode('showhoy2', 'sshowhoy2');

?>

