<?php
$lib_exe = false;
if(strpos(dirname(__FILE__), 'lib/exe')) {
   define("INC_DIR", "../../inc");
   $lib_exe = true;
}
else {
  define("INC_DIR", "./inc"); 
 }


require_once  INC_DIR . '/init.php';
require_once DOKU_INC . "lib/plugins/news/scripts/rss.php";
global $conf;
global $newsChannelTitle;
global $newsChannelDescription;	

$minute = 60;
$default_ttl = 720*$minute;  
$ttl = 0; 
$filetime = 0;
$curent_time = time();
$xml_file = DOKU_INC . 'news_feed.xml';

	if(isset($conf['plugin']['news'])) {
		if(isset($conf['plugin']['news']['ttl'])) {
			$ttl = $conf['plugin']['news']['ttl'];
			if($ttl) $ttl *= $minute;
		}
		if(isset($conf['plugin']['news']['title'])) {
			$newsChannelTitle = $conf['plugin']['news']['title'];
		}
		if(isset($conf['plugin']['news']['desc'])) {
			$newsChannelDescription = $conf['plugin']['news']['desc'];
		}
	}
	if(!$ttl) $ttl = $default_ttl;
    if(@file_exists($xml_file)) {
	    $filetime = filemtime($xml_file);
	}
	
	$time_elapsed = ($curent_time - $filetime);
	if($time_elapsed >= $ttl || $lib_exe) {
		new externalNewsFeed($xml_file,$ttl/$minute);	
	}
	if(!$lib_exe) {
	  readfile($xml_file);
	  }


 exit;

?>