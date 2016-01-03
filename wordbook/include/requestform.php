<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 or later (http://www.gnu.org/licenses/gpl-2.0.html)
 * @package      Wordbook - a multicategory glossary
 * @since        8 May 2004
 * @author       hsalazar, XOOPS Development Team
 * @version      $Id $
 */

$rform = new XoopsThemeForm(_MD_WB_REQUESTFORM, "requestform", "request.php");

if (!$xoopsUser) {
    $username_v = _MD_WB_ANONYMOUS;
}

$name_text = new XoopsFormText(_MD_WB_USERNAME, 'username', 35, 100, $username_v);
$rform->addElement($name_text, false);

$email_text = new XoopsFormText(_MD_WB_USERMAIL, "usermail", 40, 100, $usermail_v);
$rform->addElement($email_text, false);

$reqterm_text = new XoopsFormText(_MD_WB_REQTERM, 'reqterm', 30, 150);
$rform->addElement($reqterm_text, true);

if (is_object($xoopsUser)) {
    $notify_checkbox = new XoopsFormCheckBox('', 'notifypub', $notifypub);
    $notify_checkbox->addOption(1, _MD_WB_NOTIFY);
    $rform->addElement($notify_checkbox);
}

$submit_button = new XoopsFormButton("", "submit", _MD_WB_SUBMIT, "submit");
$rform->addElement($submit_button);
