<?php
    /**
     *  newfeed plugin  
     * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)       
     */
     
    // must be run within DokuWiki
    if(!defined('DOKU_INC')) die();
     
    if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
    require_once(DOKU_PLUGIN.'syntax.php');
    

     
    /**
     * All DokuWiki plugins to extend the parser/rendering mechanism
     * need to inherit from this class
     */
    class syntax_plugin_news_feed extends DokuWiki_Syntax_Plugin {
        var $helper;  
		var $is_news_mgr;
	    function __construct() {
		   $this->helper =& plugin_load('helper', 'news');
		}		
        /**
         * return some info
         */
        function getInfo(){
            return array(
                'author' => 'Myron Turner',
                'email'  => 'turnermm02@shaw.ca',
                'date'   => '2010-03-19',
                'name'   => 'news Plugin',
                'desc'   => 'identifies newsfeed page',
                'url'    => 'http://www.mturner.org',
            );
        }
     
        function getType(){ return 'substition'; }
        
		function getSort(){ return 168; }
        
		function connectTo($mode) {			
		    $this->Lexer->addSpecialPattern('~~NEWSFEED.*?~~',$mode,'plugin_news_feed');
		}
     
        /**
         * Handle the match
         */
        function handle($match, $state, $pos, Doku_Handler $handler){
        
            $match=substr($match,11,-2);
			if($match) {
			   $match = trim($match);            
               if(is_numeric($match)) {                
                   $match = array($match);
               }
               else if(is_string($match)) {              
                    $match = explode(';;',$match);
                    if(count($match) == 1) {
                       array_unshift($match,0);
                    }
               }
              
			}			
            switch ($state) {
            
			    case DOKU_LEXER_SPECIAL : return array($state, $match);
			  
            }
       
            return false;
        }
     
        /**
         * Create output
         */
        function render($mode, Doku_Renderer $renderer, $data) {
		if (empty($data)) return false;
		    global $ID;
            if($mode == 'xhtml'){
                if(!$this->is_manager()) {
                     $this->helper->set_permission(2); 
                     return false;
               }
               $this->helper->set_permission(3);
                list($state, $match) = $data;
               
                switch ($state) {				            
				  case DOKU_LEXER_SPECIAL : 				  
				  $this->helper->setUpdate($match);
                  $metafile = $this->helper->getMetaFN('timestamp','.meta') ;				
				  io_saveFile($metafile,time());				 
				  $renderer->doc .= ""; 				  
				  break;  ;
				
                }
                return true;
            }
            return false;
        }
     

      function is_manager() {
            global $INFO,$USERINFO;

            if(!isset($USERINFO)) return false;
            if(!$this->getConf('chkperm')) return true;  //by-pass news permission checks
            
            $is_news_mgr = false;
            $news_mgr = $this->getConf('mgr');
            $admins_only =  $this->getConf('adminsonly');

            if(!$news_mgr && !$admins_only) {                 
                   return true;
            }
           else  if($INFO['isadmin']  || $INFO['ismanager'] ) {                  
                   return true;
            }
           if(in_array(trim($news_mgr),$USERINFO['grps']) && !$admins_only) {
                    return true;
            }
      

            return false;



      }
}
