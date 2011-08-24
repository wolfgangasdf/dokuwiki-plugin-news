<?php
/**
 * Admin panel for news feed plugin
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Myron Turner <turnermm02@shaw.ca>
 */

 
/**
 * All DokuWiki plugins to extend the admin function
 * need to inherit from this class
 */
class admin_plugin_news extends DokuWiki_Admin_Plugin {

    var $output = '';
	var $helper;
	var $pagedata;
  
  	function admin_plugin_news() {
		   $this->helper =& plugin_load('helper', 'news');		 
		   $this->pagedata = $this->helper->_readFile(metaFN('newsfeed:pagedata', '.ser'),true);    
	}		
    /**
     * handle user request
     */
    function handle() {
      
      if (!isset($_REQUEST['cmd'])) return;   // first time - nothing to do

      $this->output = 'invalid';
      if (!checkSecurityToken()) return;
      if (!is_array($_REQUEST['cmd'])) return;
      
      // verify valid values
      switch (key($_REQUEST['cmd'])) {
        case 'prune' :  
		   $this->prune(); 
		   break;
		case 'confirm' :  
		   $this->output = 'confirm'; 
		   break;
        case 'restore' :  
		   $this->restore();
		   break;
      }   
     
    }
 
    /**
     * output appropriate html
     */
    function html() {
      ptln('<p>' . $this->getLang('instructions') . '</p>');     
      ptln('<form action="'.wl($ID).'" method="post" name="news_data">');          
      ptln('  <input type="hidden" name="do"   value="admin" />');
      ptln('  <input type="hidden" name="page" value="'.$this->getPluginName().'" />');
      formSecurityToken();

      ptln('  <input type="submit" name="cmd[prune]"  value="'.$this->getLang('btn_prune').'" />');
      ptln('  <input type="submit" name="cmd[restore]"  value="'.$this->getLang('btn_restore').'" />');
	  ptln('  <input type="submit" name="cmd[confirm]"  value="'.$this->getLang('btn_confirm').'" />');
	  ptln('<div id="pagedata_news"><br />');
	  $this->table_header();	 		
			foreach($this->pagedata as $md5=>$pageinfo) {
			   $this->pagedata_row($md5,$pageinfo);
			}
	
	  $this->table_footer(); 
	  ptln('</div>'); 
      ptln('</form>');
       
      ptln('<p><pre>');	  
	  echo print_r($this->output,true);
      ptln('</pre></p>');	  	  

	 }
	 
	 function table_header() {
	    ptln('<table cellspacing="8">');
		$theader = $this->theader("Delete") . $this->theader("Page") . $this->theader("GM Time") . 
		            $this->theader("Local Time");
        ptln("<tr>$theader</tr>");		
	 }
	 function table_footer() {
	    ptln('</table');
	 }
	 
	 function pagedata_row($md5,$info) {
	   
		ptln('<tr>');
		
		$row = $this->cell("<input type = 'checkbox' name ='delete[$md5]' value = '$md5'>");
		$row .= $this->cell($info['id'] ) .  $this->cell($info['gmtime']) .  
		       $this->cell(date('r',$info['time']));
	    ptln($row);		   
		ptln('</td></tr>');
	 }
	 function cell($data="") {
	     return "<td>$data</td>";
	 }
	 function theader($name="") {
	
	    return "<th align='center'>$name</th>";  
	 }
	 function restore() {
	    $this->pagedata = $this->helper->_readFile(metaFN('newsfeed:pagedata', '.ser'),true);    
	 }
	 
	 function prune() {
		  $deletes = $_REQUEST['delete'];
		  $this->output= print_r($deletes,true) ;	  
		  $deleted = array_keys($deletes);
		  $this->output .= print_r($deleted,true) ;	  
		  foreach($deleted as $d) {
			  unset($this->pagedata[$d]);
		  }
	 }
}
