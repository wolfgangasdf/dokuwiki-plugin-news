<?php
include "feedData.php";

class externalNewsFeed extends feedData {

function externalNewsFeed($outfile=null) {
    parent::feedData();
	if($outfile) {
	   $handle = fopen($outfile,'ab');
	}
	else { 
	   $handle = fopen("php://stdout",'ab');
	 }
	fwrite($handle,$this->write_header());
    while($this->feed_data()) {
        fwrite($handle,$this->write_item());
    }
    fwrite($handle,$this->footer());
}

	

	function write_item() { 

		$src_url=$this->news_feed_url();
		$src_title = $this->title();   ;
		$link = $this->url() . '#' . $this->rss_id() ;	
		$title = $this->title();   	
		$date = $this->date();
		$guid = $link;
		$desc = $this->description();
  
return <<<ITEM
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


	function write_header () {

	$date = $this->news_feed_date();
	$link = $this->news_feed_url();

return <<<HEAD
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
	  return "\n</channel>\n</rss>\n";
	}

}
 // new externalNewsFeed('tmp.xml');
  new externalNewsFeed();
?>