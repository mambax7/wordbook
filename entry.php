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

include("header.php");
$xoopsOption['template_main'] = 'wb_entry.tpl';
include_once(XOOPS_ROOT_PATH . "/header.php");
include_once XOOPS_ROOT_PATH . "/class/module.textsanitizer.php";

global $xoTheme, $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $xoopsModuleConfig, $xoopsModule, $XOOPS_URL;
$myts =& MyTextSanitizer::getInstance();

$entryID = isset($_GET['entryID']) ? (int)($_GET['entryID']) : 0;

//$xoopsOption['template_main'] = 'wb_entry.tpl';

if ($xoopsModuleConfig['multicats'] == 1) {
    $xoopsTpl->assign('multicats', 1);
} else {
    $xoopsTpl->assign('multicats', 0);
}

// If there's no entries yet in the system...
$pubwords       = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '0' AND offline ='0' ");
$publishedwords = $xoopsDB->getRowsNum($pubwords);
$xoopsTpl->assign('publishedwords', $publishedwords);
if ($publishedwords == 0) {
    $xoopsTpl->assign('empty', '1');
    $xoopsTpl->assign('stillnothing', _MD_WB_STILLNOTHINGHERE);
}

// To display the linked letter list
$alpha = alphaArray();
$xoopsTpl->assign('alpha', $alpha);

$sql          = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbentries") . " WHERE init = '#' ");
$howmanyother = $xoopsDB->getRowsNum($sql);
$xoopsTpl->assign('totalother', $howmanyother);

if ($xoopsModuleConfig['multicats'] == 1) {
    // To display the list of categories
    $block0    = array();
    $resultcat = $xoopsDB->query("SELECT categoryID, name, total FROM " . $xoopsDB->prefix("wbcategories") . " ORDER BY name ASC");
    while (list($catID, $name, $total) = $xoopsDB->fetchRow($resultcat)) {
        $catlinks             = array();
        $xoopsModule          =& XoopsModule::getByDirname("wordbook");
        $catlinks['id']       = $catID;
        $catlinks['total']    = (int)($total);
        $catlinks['linktext'] = $myts->htmlSpecialChars($name);

        $block0['categories'][] = $catlinks;
    }
    $xoopsTpl->assign('block0', $block0);
}

if (!$entryID) {
    $result = $xoopsDB->query("SELECT entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND (submit = 0) ORDER BY datesub DESC", 1, 0);
} else {
    if (!$xoopsUser || ($xoopsUser->isAdmin($xoopsModule->mid()) && $xoopsModuleConfig['adminhits'] == 1) || ($xoopsUser && !$xoopsUser->isAdmin($xoopsModule->mid()))) {
        $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix("wbentries") . " SET counter = counter+1 WHERE entryID = $entryID ");
    }

    $result = $xoopsDB->query("SELECT entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, counter, html, smiley, xcodes, breaks, block, offline, notifypub FROM " . $xoopsDB->prefix("wbentries") . " WHERE entryID = $entryID");
}

while (list($entryID, $categoryID, $term, $init, $definition, $ref, $url, $uid, $submit, $datesub, $counter, $html, $smiley, $xcodes, $breaks, $block, $offline, $notifypub) = $xoopsDB->fetchRow($result)) {
    $thisterm       = array();
    $xoopsModule    =& XoopsModule::getByDirname("wordbook");
    $thisterm['id'] = (int)($entryID);

    if ($xoopsModuleConfig['multicats'] == 1) {
        $thisterm['categoryID'] = (int)($categoryID);
        $catname                = $xoopsDB->query("SELECT name FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = $categoryID ");
        while (list($name) = $xoopsDB->fetchRow($catname)) {
            $thisterm['catname'] = $myts->htmlSpecialChars($name);
        }
    }

    $glossaryterm     = $myts->htmlSpecialChars($term);
    $thisterm['term'] = ucfirst($myts->htmlSpecialChars($term));
    if ($init === '#') {
        $thisterm['init'] = _MD_WB_OTHER;
    } else {
        $thisterm['init'] = ucfirst($init);
    }

    if ($xoopsModuleConfig['linkterms'] == 1) {
        // Code to make links out of glossary terms
        $parts = explode(">", $definition);

        // First, retrieve all terms from the glossary...
        //$allterms = $xoopsDB -> query( "SELECT entryID, term FROM " . $xoopsDB -> prefix( "wbentries" ));
        $allterms = $xoopsDB->query("SELECT entryID, term FROM " . $xoopsDB->prefix("wbentries") . " WHERE offline ='0' "); // v.1.17

        while (list($entryID, $term) = $xoopsDB->fetchrow($allterms)) {
            foreach ($parts as $key => $part) {
                if ($term != $glossaryterm) {
                    // singular
                    $term_q       = preg_quote($term, '/');
                    $search_term  = "/\b$term_q\b/i";
                    $replace_term = "<span><b><a style='color: #2F5376; text-decoration: underline; ' href='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/entry.php?entryID=" . ucfirst($entryID) . "'>" . $term . "</a></b></span>";
                    $parts[$key]  = preg_replace($search_term, $replace_term, $parts[$key]);

                    // plural
                    $term         = $term . "s";
                    $term_q       = preg_quote($term, '/');
                    $search_term  = "/\b$term_q\b/i";
                    $replace_term = "<span><b><a style='color: #2F5376; text-decoration: underline; ' href='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/entry.php?entryID=" . ucfirst($entryID) . "'>" . $term . "</a></b></span>";
                    $parts[$key]  = preg_replace($search_term, $replace_term, $parts[$key]);

                    // plural with e
                    $term         = $term . "es";
                    $term_q       = preg_quote($term, '/');
                    $search_term  = "/\b$term_q\b/i";
                    $replace_term = "<span><b><a style='color: #2F5376; text-decoration: underline; ' href='" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/entry.php?entryID=" . ucfirst($entryID) . "'>" . $term . "</a></b></span>";
                    $parts[$key]  = preg_replace($search_term, $replace_term, $parts[$key]);
                }
            }
        }
        $definition = implode(">", $parts);
    }

    $thisterm['definition'] = $myts->displayTarea($definition, $html, $smiley, $xcodes, 1, $breaks);
    $thisterm['ref']        = $myts->htmlSpecialChars($ref);
    $thisterm['url']        = $myts->makeClickable($url, $allowimage = 0);
    $thisterm['submitter']  = XoopsUserUtility::getUnameFromId($uid);
    $thisterm['submit']     = (int)($submit);
    $thisterm['datesub']    = formatTimestamp($datesub, $xoopsModuleConfig['dateformat']);
    $thisterm['counter']    = (int)($counter);
    $thisterm['block']      = (int)($block);
    $thisterm['offline']    = (int)($offline);
    $thisterm['notifypub']  = (int)($notifypub);
    $thisterm['dir']        = $xoopsModule->dirname();
}
$xoopsTpl->assign('thisterm', $thisterm);

$microlinks = serviceLinks($thisterm);

$xoopsTpl->assign('microlinks', $microlinks);

$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->dirname());

$xoopsTpl->assign('entryID', $entryID);
$xoopsTpl->assign('submitted', sprintf(_MD_WB_SUBMITTED, $thisterm['submitter'], $thisterm['datesub']));
$xoopsTpl->assign('counter', sprintf(_MD_WB_COUNT, $thisterm['counter']));

/*
 * pagetitle
 */
if ($xoopsModuleConfig['multicats'] == 0) {
    wb_create_pagetitle($thisterm['term']);
} else {
    wb_create_pagetitle($thisterm['term'] . ' - ' . $thisterm['catname']);
}
/* title End */

$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');

//Mondarse
include XOOPS_ROOT_PATH . '/include/comment_view.php';
//Mondarse
include_once XOOPS_ROOT_PATH . '/footer.php';
