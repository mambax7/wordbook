<?php
/**
 * $Id: storyform.inc.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Wordbook
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

global $term, $definition, $ref, $url, $xoopsUser, $xoopsModule, $xoopsModuleConfig;

include_once XOOPS_ROOT_PATH . "/class/xoopstree.php";
include XOOPS_ROOT_PATH . "/class/xoopslists.php";
include XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

$mytree = new XoopsTree( $xoopsDB -> prefix( "wbcategories" ), "categoryID", "0" );
$sform = new XoopsThemeForm( _MD_WB_SUB_SMNAME, "storyform", xoops_getenv( 'PHP_SELF' ) );

if ($xoopsModuleConfig['multicats'] == '1')
	{

		ob_start();
		$sform -> addElement( new XoopsFormHidden( 'categoryID', $categoryID ) );
		$mytree -> makeMySelBox( "name", "name", $categoryID );
		$sform -> addElement( new XoopsFormLabel( _MD_WB_CATEGORY, ob_get_contents() ) );
		ob_end_clean();

	}
// This part is common to edit/add
$sform -> addElement( new XoopsFormText( _MD_WB_ENTRY, 'term', 50, 80, $term ), true );

$def_block = new XoopsFormDhtmlTextArea( _MD_WB_DEFINITION, 'definition', _MD_WB_WRITEHERE, 15, 50 );
$def_block -> setExtra( 'onfocus="this.select()"' );
$sform -> addElement ( $def_block );

$sform -> addElement( new XoopsFormTextArea( _MD_WB_REFERENCE, 'ref', $ref, 5, 50 ), false );
$sform -> addElement( new XoopsFormText( _MD_WB_URL, 'url', 50, 80, $url ), false );

if ( is_object( $xoopsUser ) )
	{
	$uid = $xoopsUser->getVar('uid');
	$sform -> addElement( new XoopsFormHidden( 'uid', $uid ) );

	$notify_checkbox = new XoopsFormCheckBox( '', 'notifypub', $notifypub );
	$notify_checkbox -> addOption( 1, _MD_WB_NOTIFY );
	$sform -> addElement( $notify_checkbox );
	} 

$button_tray = new XoopsFormElementTray( '', '' );
$hidden = new XoopsFormHidden( 'op', 'post' );
$button_tray -> addElement( $hidden );
$button_tray -> addElement( new XoopsFormButton( '', 'post', _MD_WB_CREATE, 'submit' ) );

$sform -> addElement( $button_tray );
//mondarse $sform -> display();
unset( $hidden );

?>