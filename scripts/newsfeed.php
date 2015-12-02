<?php
global $lib_exe;
$lib_exe = false;
if(strpos(dirname(__FILE__), 'lib/exe')) {
   define("INC_DIR", "../../inc");   
   $lib_exe = true;
}
else {
  define("INC_DIR", "./inc"); 
 }

require_once  INC_DIR . '/init.php';
require_once DOKU_INC . 'lib/plugins/news/scripts/rss.php';
$newsfeed_ini = DOKU_INC . 'lib/plugins/news/scripts/newsfeed.ini';
global $conf;
global $newsChannelTitle;
global $newsChannelDescription;	
global $newsFeedURL,$INPUT;
$newsFeedURL = "";
$refresh=false;
$title = "";
$test = "";

if(isset($_POST) && isset($_POST['feed']) && $_POST['feed']=='refresh') {
  $refresh = true;
  if(isset($_POST['title'])) {
      $title = $INPUT->str('title'); 
  }
}
else if($argc > 1) {
    $title = $argv[1]; 
}
else if(isset($_GET) && isset($_GET['feed'])) {
  $title = $INPUT->str('feed');
}
if(isset($_POST)  || isset($_GET) ) {
  if(isset($_REQUEST['test']) && !is_writable(DOKU_INC) ) {
     echo  DOKU_INC . " is not writable by the web server.<br />Please check your permissions.";
     return;
  }  
}
$minute = 60;
$default_ttl = 720*$minute;  
$ttl = 0; 
$filetime = 0;
$curent_time = time();
if($title) {
   $xml_file = DOKU_INC . $title . '_news.xml';
}   
else {
 $xml_file = DOKU_INC . 'news_feed.xml';
 }
 if(file_exists($newsfeed_ini)) {
    $ini_array = parse_ini_file($newsfeed_ini, true);   
    $which = isset($ini_array[$title]) ? $title : 'default';
    $newsChannelTitle = $ini_array[$which]['title'];
    $newsChannelDescription = $ini_array[$which]['description'] ;
}

	if(isset($conf['plugin']['news'])) {
		if(isset($conf['plugin']['news']['ttl'])) {
			$ttl = $conf['plugin']['news']['ttl'];
			if($ttl) $ttl *= $minute;
		}
		if(!$newsChannelTitle && isset($conf['plugin']['news']['title'])) {
			$newsChannelTitle = $conf['plugin']['news']['title'];
		}
		if(!$newsChannelDescription && isset($conf['plugin']['news']['desc'])) {
			$newsChannelDescription = $conf['plugin']['news']['desc'];
		}
	 	if(isset($conf['plugin']['news']['url'])) {
			$newsFeedURL = $conf['plugin']['news']['url'];			
		}	
	}
		
	if(!$ttl) $ttl = $default_ttl;
    if(@file_exists($xml_file)) {
	    $filetime = filemtime($xml_file);
	}
	
	$time_elapsed = ($curent_time - $filetime);
	if($time_elapsed >= $ttl || $lib_exe || $refresh) {
		new externalNewsFeed($xml_file,$ttl/$minute,$title);	
		if($lib_exe) {
		    chmod($xml_file, 0666);
		}
	}
	if(!$lib_exe && ! $refresh) {
      header('Content-type: application/xml');
	  readfile($xml_file);
	  }
	  
	if($refresh) {
            header('Content-type: text/html');
			if(@file_exists($xml_file)) {
				$create_time= filectime($xml_file);
				if($create_time >= $current_time) {
				  echo '<span style="font-size: 11pt">';
				   echo 'Feed generated: ' . date('r',$create_time) , '</span><br/>';
				}
				else echo "New Feed may not have been not created<br />";
			}

			$id = htmlentities($_POST['feed_ref']);
			$ret_url = DOKU_URL . 'doku.php?id=' . $id;
			echo '<br /><a href ="' . $ret_url . '" style="font-size: 12pt;color:#8cacbb">Return to ' . $id . '</a>';
	}


 exit;

?>