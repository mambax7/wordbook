<?php
/**
* $Id: menu.php,v 1.7 2006/03/03 11:52:53 malanciault Exp $
* Module: wordbook. adapted from:
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

$i = 0;
// main navigation
// Index
$adminmenu[$i]['title'] = _MI_WB_ADMENU1;
$adminmenu[$i]['link'] = "admin/index.php";
$i++;
// Category
$adminmenu[$i]['title'] = _MI_WB_ADMENU2;
$adminmenu[$i]['link'] = "admin/category.php";
$i++;
// Entry
$adminmenu[$i]['title'] = _MI_WB_ADMENU3;
$adminmenu[$i]['link'] = "admin/entry.php";
$i++;
// Submits
//$adminmenu[$i]['title'] = _AM_WB_SUBMITS;
$adminmenu[$i]['title'] = _MI_WB_ADMENU8;
$adminmenu[$i]['link'] = "admin/submissions.php";
$i++;
// Blocks only show up in xoops version 2.0.xx
if (strstr(XOOPS_VERSION, "XOOPS 2.0")){
$adminmenu[$i]['title'] = _MI_WB_ADMENU4;
$adminmenu[$i]['link'] = "admin/myblocksadmin.php";
$i++;
}
// About
$adminmenu[$i]['title'] = _MI_WB_ADMENU10;
$adminmenu[$i]['link'] = "admin/about.php";
$i++;

// top bar
if (isset($xoopsModule)) {
	$i = 0;
	$headermenu[$i]['title'] = _AM_WB_OPTS;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');
	$i++;


/*	$headermenu[$i]['title'] = _AM_WB_BLOCKS;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/wordbook/admin/myblocksadmin.php";
	$i++;
*/
	$headermenu[$i]['title'] = _AM_WB_GOMOD;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/wordbook";
	$i++;
 
	$headermenu[$i]['title'] = _AM_WB_IMPORT;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/wordbook/admin/importdictionary091.php";
	$i++;

	$headermenu[$i]['title'] = _AM_WB_UPDATEMODULE;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');
	$i++;

   	$headermenu[$i]['title'] = _AM_WB_HELP;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/wordbook/" . "admin/about.php?op=readme";
    //$i++;

}
?>
