<?php
/**
 * English language file
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Myron Turner <turnermm02@shaw.ca>
 */

// for admin plugins, the menu prompt to be displayed in the admin menu
// if set here, the plugin doesn't need to override the getMenuText() method
$lang['menu'] = 'Admin News...'; 

$lang['btn_prune'] = 'Delete Selections';
$lang['btn_restore'] = 'Restore';
$lang['btn_review'] = 'Review Deletions';
$lang['btn_confirm'] = 'Confirm Deletions';
$lang['invalid'] = 'invalid input detected!';
$lang['btn_generate'] = 'Generate Feed';
$lang['menu'] = 'News plugin administration';
$lang['btn_info'] = 'Show/Hide Help';
$lang['btn_confirmfeed'] = 'Confirm Subfeed';
$lang['select_feed'] = 'Select  Subfeed';
$lang['no_permission'] = 'You do not have permission to create or update news feeds.';
$lang['instructions'] ='<p><h3>Help</h3>To delete a feed, check the "Delete" checkbox beside the feed name.  Then click 
the "Delete Selections" button at the top of the screen.  You can delete multiple feeds in a single delete operation
by checking more than one checkbox.  The table listing the feeds will be updated to reflect the
deletions but deletions will not be final until you click the "Confirm Deletions" button, at which point the database
will be updated. Clicking "Restore" before Confirming will reset the table to its original state.</p>
<p>
A deletion only affects the current state of the database.  You must also remove the plugin syntax from the pages which 
generated the feed.  If the plugin syntax remains on a page, the feed from that page will be regenerated the
next time the Dokuwiki cache is updated.  
</p>    
<p>
If your site allows more than one feed, they are referred to here as "subfeeds".  If you want to modify and/or generate a subfeed, select the subfeed by its
title from the "Select Subfeed" drop-down menu and then click the "Confirm Subfeed" button.  Then proceed as described above.
</p> 
';
