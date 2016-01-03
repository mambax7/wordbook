<?php
// comment callback functions

function wordbook_com_update($entry_ID, $total_num){
	$db =& Database::getInstance();
	$sql = 'UPDATE '.$db->prefix('wbentries').' SET comments = '.$total_num.' WHERE entryID = '.$entry_ID;
	$db->query($sql);
}

function wordbook_com_approve(&$comment){
	// notification mail here
}
?>