<?php
include "feedData.php";
$feed = new feedData();



write_header($feed);
while($feed->feed_data()) {
  write_item($feed);
}
footer();


function write_item($feed) { 

    $src_url=$feed->news_feed_url();
    $src_title = $feed->title();   ;
	$link = $feed->url() . '#' . $feed->rss_id() ;	
    $title = $feed->title();   	
	$date = $feed->date();
    $guid = $link;
	$desc = $feed->description();
  
echo <<<ITEM
<item>
      <title><![CDATA[$title]]></title>
      <link><![CDATA[$link]]></link>
      <description><![CDATA[$desc]]></description>
      <pubDate>$date</pubDate>
      <guid isPermaLink ="false">$guid</guid>
      <source url="$src_url"><![CDATA[$src_title]]></source>
 </item>      
ITEM;

}


function write_header ($feed) {

$date = $feed->news_feed_date();
$link = $feed->news_feed_url();

echo <<<HEAD
<?xml version="1.0" encoding="utf-8"?>
<rss version="2.0">
  <channel>
    <title>fckgLIteDev News Feed</title>
    <link>$link</link>
    <description>What is happening on fckgLiteDev</description>
    <language>en-us</language>
    <pubDate>$date</pubDate>
    <ttl>240</ttl>
HEAD;

}

function footer() {
  echo "\n</channel>\n</rss>\n";
}

function get_description() {
    return file_get_contents('temp.txt');
}
?>