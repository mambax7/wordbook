<?php
/**
 * $Id: admin_header.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Wordbook - a multicategory glossary
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

include("../../../mainfile.php");
include '../../../include/cp_header.php';

if ( file_exists("../language/".$xoopsConfig['language']."/main.php") ) 
	{
	include "../language/".$xoopsConfig['language']."/main.php";
	}
else 
	{
	include "../language/english/main.php";
	}
include_once XOOPS_ROOT_PATH."/modules/wordbook/include/functions.php";
include_once XOOPS_ROOT_PATH."/class/xoopsmodule.php";
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$myts =& MyTextSanitizer::getInstance();

if ( is_object( $xoopsUser)  ) 
	{
	$xoopsModule = XoopsModule::getByDirname("wordbook");
	if ( !$xoopsUser->isAdmin($xoopsModule->mid()) ) 
		{
		redirect_header(XOOPS_URL."/",1,_NOPERM);
		exit();
		}
	}
else 
	{
	redirect_header(XOOPS_URL."/",1,_NOPERM);
	exit();
	}

?>