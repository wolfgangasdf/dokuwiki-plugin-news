<?php
/**
 * Admin panel for news feed plugin
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Myron Turner <turnermm02@shaw.ca>
 */
 if(!defined('DOKU_INC')) die();
require_once DOKU_INC . "lib/plugins/news/scripts/rss.php";
 
/**
 * All DokuWiki plugins to extend the admin function
 * need to inherit from this class
 */
class admin_plugin_news extends DokuWiki_Admin_Plugin {

    var $output = '';
	var $helper;
	var $pagedata;
	var $prev_deleted = "";
    var $is_prev_deleted = array();
	var $subfeed_selected = 0;
	var $subfeed_name = "";
    
  	function admin_plugin_news() {
		   $this->helper =& plugin_load('helper', 'news');
           if($_REQUEST['subfeeds'] != 'NotSet') {
               $this->helper->setSubFeed($_REQUEST['subfeeds']);
           }           		  
           $this->pagedata = $this->helper->_readFile($this->helper->getMetaFN('pagedata','.ser'),true);    
        
	}		
    /**
     * handle user request
     */
    function handle() {
      
      if (!isset($_REQUEST['cmd'])) return;   // first time - nothing to do

      $this->output = '';
      if (!checkSecurityToken()) return;
      if (!is_array($_REQUEST['cmd'])) return;
      
      // verify valid values
      switch (key($_REQUEST['cmd'])) {
        case 'prune' :  
		   $this->prune(); 
		   break;
		case 'confirm' :  
		   $this->confirm();		  
		   break;
        case 'restore' :  		
		   $this->is_prev_deleted =array();
		   $this->prev_deleted = "";
           if(isset($_REQUEST[subfeed_dir])) {
                  $this->helper->setSubFeed($_REQUEST['subfeed_dir']);
                  $this->pagedata = $this->helper->_readFile($this->helper->getMetaFN('pagedata','.ser'),true);   
           }
           // $this->output=$_REQUEST;
		   return;
		 case 'generate':        
           $this->output=$this->generate($_REQUEST['subfeeds']);
		   return;
         case 'subfeed':
           $this->subfeed_selected = $_REQUEST['subfeed_inx'];
           if($this->subfeed_selected  > 0) {
              $this->subfeed_name= $_REQUEST['subfeeds'];
           }
           break;          
           		   
      }   
	     
          $deleted = array();
		  if(isset($_REQUEST['delete']) && $_REQUEST['delete']) {
			$deletes = $_REQUEST['delete'];		  
			$deleted = array_keys($deletes);	
		  }
		  
		 
		  if($_REQUEST['prev_del']) {  
		     $prev_deleted = $_REQUEST['prev_del'];
			 $prev_deleted = explode(',',$prev_deleted);
			 $prev_deleted = array_merge($prev_deleted, $deleted);
			 $prev_deleted = array_unique($prev_deleted);			 
			 $this->prev_deleted = implode(",", $prev_deleted);
			 $this->is_prev_deleted = $prev_deleted;
		  }
        // $this->output=$_REQUEST;
    }
 
    /**
     * output appropriate html
     */
    function html() {

      ptln('<div style="width:90%;margin:auto;display:none;"  class="news_info"><p>' . $this->getLang('instructions') . '</p></div>');     
      ptln('<form action="'.wl($ID).'" method="post" name="news_data" onsubmit="newshandler(this);">');          
      ptln('  <input type="hidden" name="do"   value="admin" />');
      ptln('  <input type="hidden" name="page" value="'.$this->getPluginName().'" />');
	  ptln('  <input type="hidden" name="prev_del" id ="prev_del" value="' .$this->prev_deleted. '" />');
   	  
      ptln('  <input type="hidden" name="subfeed_inx" id ="subfeed_inx" value="0" />');
      ptln('  <input type="hidden" name="subfeed_dir" id ="subfeed_dir" value="" />');
      formSecurityToken();

      ptln('  <input type="submit" name="cmd[prune]"  value="'.$this->getLang('btn_prune').'" />');
      ptln('  <input type="submit" name="cmd[restore]"  value="'.$this->getLang('btn_restore').'" />');
	  ptln('  <input type="submit" name="cmd[confirm]" onclick="return confirm_del();" value="'.$this->getLang('btn_confirm').'" />');
	  ptln('  <input type="submit" name="cmd[generate]" value="'.$this->getLang('btn_generate').'" />');	
      ptln('  <select  id="subfeeds" name="subfeeds" onchange="subfeedshandler()" ><option value="NotSet">' . $this->getLang('select_feed') .'</option>'); 
      $this->subfeed_options();      
      ptln('</select>');  
      ptln('  <input type="submit" id = "subfeedbtn" name="cmd[subfeed]" value="'.$this->getLang('btn_confirmfeed').'" />');	
      ptln('  <input type="button" id = "news_infobtn" value="'.$this->getLang('btn_info').'" />');	
	  ptln('<div id="pagedata_news"><br />');
	  $this->table_header();	 		
			foreach($this->pagedata as $md5=>$pageinfo) {
			   $this->pagedata_row($md5,$pageinfo);
			}
	
	  $this->table_footer(); 
	  ptln('</div>'); 
      ptln('</form>');
      
	  if($this->output) {
		  ptln('<p><pre>');	  
		  echo print_r($this->output,true);
          echo  $this->subfeed_name;
		  ptln('</pre></p>');	  	  
     }
	 }
	 
	 function table_header() {
	    ptln('<table cellspacing="8">');
		$theader = $this->theader("Delete") . $this->theader("Page") . $this->theader("GM Time") . 
		            $this->theader("Local Time");
        ptln("<tr>$theader</tr>");		
	 }
	 function table_footer() {
	    ptln('</table>');
	 }
	 
	 function pagedata_row($md5,$info) {
	   static $inx = 0;
	   
	   if(in_array($md5,$this->is_prev_deleted)) return;
	   
	   $type = 'delete';
	   $cb_id =$type . '_' . $inx;
		ptln('<tr>');
				
		$row = '<td align="center">' . "<input type = 'checkbox'  id='$cb_id'  name ='" . $type . "[$md5]' value = '$md5'>" .'</td>';
		$row .= $this->cell($info['id'] ) .  $this->cell($info['gmtime']) .  
		       $this->cell(date('r',$info['time']));
	    ptln($row);		   
		ptln('</td></tr>');
		
		$index++;
	 }
	 function cell($data="") {
	     return "<td class='right'>$data</td>";
	 }
	 function theader($name="") {
	
	    return "<th align='center'>$name</th>";  
	 }
     
	 function subfeed_options() {
         $dir = DOKU_INC . 'data/meta/newsfeed/';  
       
          if($this->subfeed_selected == 0 && isset($_REQUEST['subfeed_dir'])) {
              $sbname_check = $_REQUEST['subfeed_dir'];
          } 
         else  $sbname_check = 'NotSet';   
       
         $index = 1;
         if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
              if($file == '.' || $file == '..') continue;              
              if(is_dir($dir . $file)) {  
                 if($this->subfeed_selected == $index || $file == $sbname_check) {
                     ptln("<option value='$file' selected>$file</option>"); 
                 }
                 else {
                     ptln("<option value='$file' >$file</option>"); 
                    }
                $index++;    
             }
         }
       
        }
          closedir($dh);         
   
     }
     
	 function prune() {
	     
		  
		  $deletes = $_REQUEST['delete'];
		  $deleted = array_keys($deletes);		  
		 
		  foreach($deleted as $d) {
			  unset($this->pagedata[$d]);
		  }
		  
	 }
	 
	 function confirm() {

		 $deleted = explode(',',$_REQUEST['prev_del']);
         if(isset($_REQUEST['subfeed_dir']) && $_REQUEST['subfeed_dir'] != 'NotSet') {
               $this->helper->setSubFeed($_REQUEST['subfeed_dir']);
               $meta_dir =$this->helper->getMetaDirectory();
           }
           else $meta_dir = "newsfeed:";

   
  
		  foreach($deleted as $d) {
			  unset($this->pagedata[$d]);
		  }
          $pfile =$this->helper->getMetaFN('pagedata', '.ser');
          $this->helper->_writeFile($pfile,$this->pagedata,true);      
		  
  		  foreach($deleted as $d) {
		     $file = metaFN($meta_dir . $d, '.gz');
			 if(file_exists($file)) {           
			   @unlink($file);			   
			 }
		  }
        	  
	 }
	 
	 function generate($subfeed) {	
		global $newsChannelTitle;
        global $newsChannelDescription;	
        $newsfeed_ini = DOKU_INC . 'lib/plugins/news/scripts/newsfeed.ini';
         
         if(file_exists($newsfeed_ini)) {
            $ini_array = parse_ini_file($newsfeed_ini, true);   
            $which = isset($ini_array[$subfeed]) ? $subfeed : 'default';
            $newsChannelTitle = $ini_array[$which]['title'];
            $newsChannelDescription = $ini_array[$which]['description'] ;         
        }
        else {
            $subfeed = "";
            $newsChannelDescription = $this->getConf('desc');
            $newsChannelTitle=$this->getConf('title');
        }
    
		    $create_time = 0;
			$ttl = $this->getConf('ttl');
            if($subfeed && $subfeed !='NotSet') {
                $xml_file = DOKU_INC . $subfeed . '_news.xml';
            }
	        else $xml_file = DOKU_INC . 'news_feed.xml';
            
            $current_time = time();
			
	 		new externalNewsFeed($xml_file,$ttl,$subfeed);	

			if(@file_exists($xml_file)) {
			     $create_time= filectime($xml_file);	             
	         }
			if($create_time >= $current_time) {
			   return 'Feed generated: ' . date('r',$create_time) ;
			}
			else {
			   return "A new feed may not have been generated.  Check $xml_file.";
			}
	 }
}
