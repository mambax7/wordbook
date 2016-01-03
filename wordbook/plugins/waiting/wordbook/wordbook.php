<?php

function b_waiting_wordbook()
{
	$xoopsDB =& Database::getInstance();
	$ret = array() ;

	// Waiting
	$block = array();
//	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("wbentries")." WHERE submit=1 AND categoryID>0");
 	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("wbentries")." WHERE submit=1");
	if ( $result ) {
//		$block['adminlink'] = XOOPS_URL."/modules/wordbook/admin/index.php#esp." ;
  		$block['adminlink'] = XOOPS_URL."/modules/wordbook/admin/submissions.php" ;
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_WAITINGS ;
	}
	$ret[] = $block ;

	// Request
//	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("wbentries")." WHERE submit=1 AND categoryID=0");
 	$result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("wbentries")." WHERE submit=1");
	if ( $result ) {
//		$block['adminlink'] = XOOPS_URL."/modules/wordbook/admin/index.php#sol." ;
  		$block['adminlink'] = XOOPS_URL."/modules/wordbook/admin/submissions.php" ;
		list($block['pendingnum']) = $xoopsDB->fetchRow($result);
		$block['lang_linkname'] = _PI_WAITING_REQUESTS ;
	}
	$ret[] = $block ;

	return $ret ;
}
?>
