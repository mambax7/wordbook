<?php 
/**
 * $Id: index.php v 1.0 8 May 2004 hsalazar Exp $
 * Module: Wordbook - a multicategory glossary
 * Version: v 1.00
 * Release Date: 8 May 2004
 * Author: hsalazar
 * Licence: GNU
 */

$rform = new XoopsThemeForm(_MD_WB_REQUESTFORM, "requestform", "request.php");

if ( !$xoopsUser )  
	{
	$username_v = _MD_WB_ANONYMOUS;
	}

$name_text = new XoopsFormText(_MD_WB_USERNAME, 'username', 35, 100, $username_v);
$rform->addElement($name_text, false);

$email_text = new XoopsFormText(_MD_WB_USERMAIL, "usermail", 40, 100, $usermail_v);
$rform->addElement($email_text, false);

$reqterm_text = new XoopsFormText(_MD_WB_REQTERM, 'reqterm', 30, 150);
$rform->addElement($reqterm_text, true);

if ( is_object( $xoopsUser ) )
	{
	$notify_checkbox = new XoopsFormCheckBox( '', 'notifypub', $notifypub );
	$notify_checkbox -> addOption( 1, _MD_WB_NOTIFY );
	$rform -> addElement( $notify_checkbox );
	} 

$submit_button = new XoopsFormButton("", "submit", _MD_WB_SUBMIT, "submit");
$rform->addElement($submit_button);

?>