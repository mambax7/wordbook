<?php
/**
 * $Id: search.inc.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Wordbook
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

function wb_search( $queryarray, $andor, $limit, $offset, $userid )
	{
	global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModule, $xoopsModuleConfig;

	$ret = array();
	if ( $userid != 0 )
		{
		return $ret;
		} 
	$sql = "SELECT entryID, term, definition, ref, uid, datesub FROM " . $xoopsDB -> prefix( "wbentries" ) . " WHERE submit = 0 AND offline = 0 "; 

	// because count() returns 1 even if a supplied variable
	// is not an array, we must check if $querryarray is really an array
	$count = count( $queryarray );
	if ( $count > 0 && is_array( $queryarray ) )
		{
		$sql .= "AND ((term LIKE '%$queryarray[0]%' OR definition LIKE '%$queryarray[0]%' OR ref LIKE '%$queryarray[0]%')";
		for ( $i = 1; $i < $count; $i++ )
			{
			$sql .= " $andor ";
			$sql .= "(term LIKE '%$queryarray[$i]%' OR definition LIKE '%$queryarray[$i]%' OR ref LIKE '%$queryarray[$i]%')";
			} 
		$sql .= ") ";
		} 
	$sql .= "ORDER BY entryID DESC";
	$result = $xoopsDB -> query( $sql, $limit, $offset );
	$i = 0;

	while ( $myrow = $xoopsDB -> fetchArray( $result ) )
		{
		$ret[$i]['image'] = "images/wb.png";
		$ret[$i]['link'] = "entry.php?entryID=" . $myrow['entryID'];
		$ret[$i]['title'] = $myrow['term'];
		$ret[$i]['time'] = $myrow['datesub'];
		$ret[$i]['uid'] = $myrow['uid'];
		$i++;
		} 
	return $ret;
	} 
?>