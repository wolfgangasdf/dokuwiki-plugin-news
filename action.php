<?php
/**
  *  @author Myron Turner
  *  @email turnermm02@shaw.ca
  *  action plugin completes processing of news data
 */

if(!defined('DOKU_INC')) die();
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once DOKU_PLUGIN.'action.php';


class action_plugin_news extends DokuWiki_Action_Plugin {
    var $helper;
  
    /**
     * Register its handlers with the DokuWiki's event controller
     */
    function register(&$controller) {
        $controller->register_hook('DOKUWIKI_DONE', 'BEFORE', $this,
                                   'process_feed');
    }

	function action_plugin_news() {
	    global $newsUpdated;
		$this->helper = $this->loadHelper('news', true);
    }		
	

    /**
        check permissions and send page to be processed 
     */
    function process_feed(&$event, $param) {
	    global $ID,$INFO;

        $perm = $this->helper->has_permission();      
        if($perm == 2 && $INFO['perm'] != 1) {       //INFO['perm'] = 1 when logging out     
            msg($this->getLang('no_permission'));
            return;
        }
        if($this->helper->pageUpdated() ) {
            $metafile = $this->helper->getMetaFN('wasupdated','.meta') ;        		  
			io_saveFile($metafile,time() . "\n" . $ID ."\n");
			$this->helper->saveFeedData($ID); 
		}
    }
}
