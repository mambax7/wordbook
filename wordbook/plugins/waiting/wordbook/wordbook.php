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

function b_waiting_wordbook()
{
    $xoopsDB =& XoopsDatabaseFactory::getDatabaseConnection();
    $ret     = array();

    // Waiting
    $block = array();
    //  $result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("wbentries")." WHERE submit=1 AND categoryID>0");
    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit=1");
    if ($result) {
        //      $block['adminlink'] = XOOPS_URL."/modules/wordbook/admin/index.php#esp." ;
        $block['adminlink'] = XOOPS_URL . "/modules/wordbook/admin/submissions.php";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_WAITINGS;
    }
    $ret[] = $block;

    // Request
    //  $result = $xoopsDB->query("SELECT COUNT(*) FROM ".$xoopsDB->prefix("wbentries")." WHERE submit=1 AND categoryID=0");
    $result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit=1");
    if ($result) {
        //      $block['adminlink'] = XOOPS_URL."/modules/wordbook/admin/index.php#sol." ;
        $block['adminlink'] = XOOPS_URL . "/modules/wordbook/admin/submissions.php";
        list($block['pendingnum']) = $xoopsDB->fetchRow($result);
        $block['lang_linkname'] = _PI_WAITING_REQUESTS;
    }
    $ret[] = $block;

    return $ret;
}
