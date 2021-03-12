<?php
function instablock_build( $atts = array() ) {
	
	$atts = shortcode_atts( array( 'id' => '17841403336741569', 'row' => '8', 'limit' => '8', 'mobile' => '4', 'accesstoken' => 'IGQVJVUEpBV0x3el9kNjh2d1ZApTDZAKelBsWGFWT3pUdjMzZAHlfWDRKb05kYWxLX0pXQzVFbVFSN2FPMWdfcDlZAVFJ0X3dOM1NMaUxoanFEQkU0Y0hKNi1lOXVUU1E4UkhUdV9sdjc0TlczM19LS2lieQZDZD' ), $atts );
	
	$client_token = "a3641beed22018cc9a9a960f0a7b06cf";
	$app_id = '250845666659538';

	$feedid = $atts['id'];
	$access_token = $atts['accesstoken'];
	$row = $atts['row'];
	function isMobileDevice() { 
		return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i" , $_SERVER["HTTP_USER_AGENT"]); 
	} 
	if(isMobileDevice()){ 
		$limit = $atts['mobile'];; 
	} 
	else { 
		$limit = $atts['limit'];; 
	} 
	wp_enqueue_style('instablock-styles', plugin_dir_url( __FILE__ ) . '../css/instablock.css','1.0', true);

	function fetchData($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	$result = json_decode( fetchData("https://graph.instagram.com/" . $feedid . "/media?fields=id,media_type,media_url,thumbnail_url,permalink&access_token=" . $access_token), true );

	ob_start();
	echo "<div class='insta-block ib-row-" . $row . "'>";
	$Count = 0;
	foreach($result['data'] as $ig) {
		$smimg = json_decode( fetchData("https://graph.facebook.com/v10.0/instagram_oembed?url=" . $ig['permalink'] . "&maxwidth=320&access_token=" . $app_id . "|" . $client_token), true );
		echo "<div><a href='" . $ig['permalink'] . "' target='_blank'>" ;
		echo "<img src='" . $smimg['thumbnail_url'] . "' alt='instagram photo'>";
		echo "</a></div>";
		$Count++;
	    if($Count == $limit):
		  break;
		endif;
	}
	echo "</div>";

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode('instablock', 'instablock_build');

?>