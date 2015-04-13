<?php
/**
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 */
if(!defined('DOKU_INC')) die();
if(!defined('DOKU_META')) define('DOKU_META',DOKU_INC.'data/meta/newsfeed/');

class helper_plugin_news extends Dokuwiki_Plugin {
    var $wasUpdated = false;
	var $header;
	var $sub_feed = "";
    var $has_permission = -1;
    function getMethods(){
        $result = array();
			
        $result[] = array(
                'name'   => 'pageUpdated',
                'desc'   => 'Check if news for $ID is updated',
                'params' => array(),
                'return' => array('result' => 'bool')
                );
			
        $result[] = array(
                'name'   => 'setUpdate',
                'desc'   => 'set boolean to signal page was updated',
                'params' => array(),
                'return' => array()
               );
        $result[] = array(
                'name'   => 'saveFeedData',
                'desc'   => 'Save feed data for page',
                'params' => array(),
                'return' => array()
               );			   
			   
       return $result;
    }

    /***     
     */
    function set_permission($perm) {
        $this->has_permission = $perm;
    }
    function has_permission() {
        return $this->has_permission;
    }
    
    function pageUpdated(){    
        return $this->wasUpdated;
    }
    function setUpdate($ar = null){	 
       if($ar) {
        $this->header = $ar[0];
        $this->sub_feed = trim($ar[1]);
        }
        $this->wasUpdated = true;
        
    }
   function setSubFeed($ns) {
        $this->sub_feed = trim($ns);
   }
    function getMetaDirectory() {
       if(preg_match("/notset/i", $this->sub_feed)) {          
          $this->sub_feed ='/'; 
          return "newsfeed:";          
       }
       return 'newsfeed:' .  $this->sub_feed . ':';
    } 
    function getMetaFN($file,$ext) {
          $ns = $this->getMetaDirectory();         
          $file = $ns . $file;
          return metaFN($file, $ext);
    }    
    
	function saveFeedData($id=null) {
	   if(!$id) return;
	
	    if(!$this->header) {
	        $md_5  = $this->_parse_items($id);	   
		}
		elseif($this->header) {
		   $md_5  = $this->_parse_headers($id) ;
		}
	   $metafile = $this->getMetaFN('pagedata', '.ser');
	  
	    $ar = $this->_readFile($metafile, true);
		
	    if(!$md_5) {
			if(isset($ar[$md_5])) {
				unset($ar[$md_5]);
				$this->_writeFile($metafile,$ar,true);
			}
			   
			return; 
	   }
	    $file_path = wikiFN($id); 
	   	$tm =  filemtime ($file_path);
	   // update page db
	    $result = array();
	    $result['id'] = $id;
	    $result['url'] = DOKU_URL . 'doku.php?id=' . $id;
	    $result['time'] = $tm;
	    $result['gmtime'] = gmdate('r',$tm);		
        if(empty($ar[$md_5]['create_time'])) { 
           $result['create_time'] = gmdate('r',$tm);		        
        }
        else $result['create_time'] = $ar[$md_5]['create_time'];		        
		$result['header'] = $this->header;		
	    $ar[$md_5] = $result;
     
	    $this->_writeFile($metafile,$ar,true);
		
	}
    
	function _parse_items($id) {
		   // get page contents
	    $data = file_get_contents(wikiFN($id));		
		$n = preg_match_all("#<news(.*?)>.*?(?=</news>)#ms", $data, $matches);
		if($n == 0) return false;
		
		$data = $matches[0];
		$titles = $matches[1];
		$feed_data = array();
		for($i=0; $i<count($data); $i++) {
			list($news,$title) = explode(':',$titles[$i]);
			if(isset($title)) {
				   $title = trim($title); 
			   }	   
			if(isset($title) && $title) {
			        $feed_data[$i]['title'] = $title; 					
			  }
			 else $feed_data[$i]['title'] = "News Item";         
			$data[$i] = preg_replace("#^\s*<news(.*?)>#","",$data[$i]);			
			$feed_data[$i]['item'] = $this->render($data[$i]);	
			$feed_data[$i]['name'] = 'rss_' . ($i+1);
       }
	   
	   $md_5 = md5($id);	   
       $metafile = $this->getMetaFN($md_5, '.gz'); 	   
	   $this->_writeFile($metafile,$feed_data,true);
       return $md_5;
	}
	
	function _parse_headers($id) {
		$data = file_get_contents(wikiFN($id));	
        
    	$segs = $this->header;
		$ar = preg_split("/(={" . $segs  . ",}.*?={" . $segs . ",})\s*\n/",$data, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
        if(count($ar) == 0) return false;				
        array_shift($ar);  //removes ~~NEWSFEED.*?~~ and any text above first header
		
		$feed_data = array(); 
		for($i=0, $j=0; $i<count($ar); $i+=2, $j++) {
		    $feed_data[$j]['title'] = str_replace('=',"",$ar[$i]);
		    $feed_data[$j]['item'] = $this->render($ar[$i+1]);
			$check=false;
			$feed_data[$j]['name'] =  sectionID($ar[$i],$check);
		}
        $md_5 = md5($id);
        $metafile = $this->getMetaFN($md_5, '.gz'); 	
	    $this->_writeFile($metafile,$feed_data,true); 
		return $md_5;
    }	
	
	function _readFile($file, $ser=false) {
	    $ret = io_readFile($file,$ser);
		if($ser) {
		  if(!$ret) return array();
		  return unserialize($ret);
		  }
		return $ret;  
		
	}
	
	function _writeFile($file,$data,$ser) {
			if($ser) {
			   $data = serialize($data);
			}
			io_saveFile($file,$data);
	}
}
