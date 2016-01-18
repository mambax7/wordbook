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

global $xoTheme, $xoopsUser, $xoopsDB, $xoopsConfig, $myts, $xoopsModuleConfig;

$op = '';

$xoopsOption['template_main'] = 'wb_index.tpl';
include_once(XOOPS_ROOT_PATH . "/header.php");
include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/cleantags.php";

$start = isset($_GET['start']) ? (int)($_GET['start']) : 0;

$columna = array();

// Options
switch ($op) {
    case "default":
    default:

        global $xoopsUser, $xoopsConfig, $xoopsDB, $myts, $xoopsModuleConfig, $xoopsModule;
        calculateTotals();
        $xoopsTpl->assign('multicats', (int)($xoopsModuleConfig['multicats']));

        // Counts
        if ($xoopsModuleConfig['multicats'] == 1) {
            $totalcats = countCats();
            $xoopsTpl->assign('totalcats', $totalcats);
        }
        $publishedwords = countWords();
        $xoopsTpl->assign('publishedwords', $publishedwords);

        if ($xoopsModuleConfig['multicats'] == 1) {
            $xoopsTpl->assign('multicats', 1);
        } else {
            $xoopsTpl->assign('multicats', 0);
        }

        // If there's no entries yet in the system...
        if ($publishedwords == 0) {
            $xoopsTpl->assign('empty', '1');
        }

        // To display the search form
        $searchform = "<table width=\"100%\">";
        $searchform .= "<form name=\"op\" id=\"op\" action=\"search.php\" method=\"post\">";
        $searchform .= "<tr><td style=\"text-align: right; line-height: 200%;\" width=\"150\">";
        $searchform .= _MD_WB_LOOKON . "</td><td width=\"10\">&nbsp;</td><td style=\"text-align: left;\">";
        $searchform .= "<select name=\"type\"><option value=\"1\">" . _MD_WB_TERMS . "</option><option value=\"2\">" . _MD_WB_DEFINS . "</option>";
        $searchform .= "<option value=\"3\">" . _MD_WB_TERMSDEFS . "</option></select></td></tr>";
        if ($xoopsModuleConfig['multicats'] == 1) {
            $searchform .= "<tr><td style=\"text-align: right; line-height: 200%;\">" . _MD_WB_CATEGORY . "</td>";
            $searchform .= "<td>&nbsp;</td><td style=\"text-align: left;\">";
            $resultcat = $xoopsDB->query("SELECT categoryID, name FROM " . $xoopsDB->prefix("wbcategories") . " ORDER BY categoryID");
            $searchform .= "<select name=\"categoryID\">";
            $searchform .= "<option value=\"0\">" . _MD_WB_ALLOFTHEM . "</option>";
            while (list($categoryID, $name) = $xoopsDB->fetchRow($resultcat)) {
                $searchform .= "<option value=\"$categoryID\">$categoryID : $name</option>";
            }
            $searchform .= "</select></td></tr>";
        }
        $searchform .= "<tr><td style=\"text-align: right; line-height: 200%;\">";
        $searchform .= _MD_WB_TERM . "</td><td>&nbsp;</td><td style=\"text-align: left;\">";
        $searchform .= "<input type=\"text\" name=\"term\" class=\"searchBox\" /></td></tr><tr>";
        $searchform .= "<td>&nbsp;</td><td>&nbsp;</td><td><input type=\"submit\" value=\"" . _MD_WB_SEARCH . "\" />";
        $searchform .= "</td></tr></form></table>";
        $xoopsTpl->assign('searchform', $searchform);

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

        // To display the recent entries block
        $block1   = array();
        $result05 = $xoopsDB->query("SELECT entryID, term, datesub FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND submit = '0' AND offline = '0' AND request = '0' ORDER BY datesub DESC", $xoopsModuleConfig['indexperpage'], 0);

        if ($publishedwords > 0) {
            // If there are definitions

            while (list($entryID, $term, $datesub) = $xoopsDB->fetchRow($result05)) {
                $newentries             = array();
                $xoopsModule            =& XoopsModule::getByDirname("wordbook");
                $linktext               = ucfirst($myts->htmlSpecialChars($term));
                $newentries['linktext'] = $linktext;
                $newentries['id']       = $entryID;
                $newentries['date']     = formatTimestamp($datesub, "s");

                $block1['newstuff'][] = $newentries;
            }
            $xoopsTpl->assign('block', $block1);
        }

        // To display the most read entries block
        $block2   = array();
        $result06 = $xoopsDB->query("SELECT entryID, term, counter FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND submit = '0' AND offline = '0' AND request = '0' ORDER BY counter DESC", $xoopsModuleConfig['indexperpage'], 0);

        if ($publishedwords > 0) {
            // If there are definitions

            while (list($entryID, $term, $counter) = $xoopsDB->fetchRow($result06)) {
                $popentries             = array();
                $xoopsModule            =& XoopsModule::getByDirname("wordbook");
                $linktext               = ucfirst($myts->htmlSpecialChars($term));
                $popentries['linktext'] = $linktext;
                $popentries['id']       = $entryID;
                $popentries['counter']  = (int)($counter);

                $block2['popstuff'][] = $popentries;
            }
            $xoopsTpl->assign('block2', $block2);
        }

        // To display the random term block
        list($numrows) = $xoopsDB->fetchRow($xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = 'O' AND offline = '0'"));
        if ($numrows > 1) {
            --$numrows;
            mt_srand((double)microtime() * 1000000);
            $entrynumber = mt_rand(0, $numrows);
        } else {
            $entrynumber = 0;
        }

        $resultZ = $xoopsDB->query("SELECT categoryID, entryID, term, definition FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = 'O' AND offline = '0' LIMIT $entrynumber, 1");

        $zerotest = $xoopsDB->getRowsNum($resultZ);
        if ($zerotest != 0) {
            while ($myrow = $xoopsDB->fetchArray($resultZ)) {
                $random            = array();
                $random['entryID'] = $myrow['entryID'];
                $random['term']    = ucfirst($myrow['term']);

                if (!XOOPS_USE_MULTIBYTES) {
                    $deftemp              = substr($myrow['definition'], 0, ($xoopsModuleConfig['rndlength'] - 1));
                    $deftemp              = $myts->displayTarea($deftemp, 1, 1, 1, 1) . "...";
                    $deftemp              = wb_cleanTags($deftemp);//1.17
                    $random['definition'] = $deftemp;
                }

                if ($xoopsModuleConfig['multicats'] == 1) {
                    $random['categoryID'] = $myrow['categoryID'];

                    $resultY = $xoopsDB->query("SELECT categoryID, name FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = " . $myrow['categoryID'] . " ");
                    list($categoryID, $name) = $xoopsDB->fetchRow($resultY);
                    $random['categoryname'] = $myts->displayTarea($name);
                }
            }
            $microlinks           = serviceLinks($random['entryID']);//erroneous 10,11,12->1
            $random['microlinks'] = $microlinks;
            $xoopsTpl->assign('random', $random);
        }
        if ($xoopsUser && $xoopsUser->isAdmin()) {

            // To display the submitted and requested terms box
            $xoopsTpl->assign('userisadmin', 1);

            $blockS      = array();
            $resultS     = $xoopsDB->query("SELECT entryID, term FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND submit = '1' AND offline = '1' AND request = '0' ORDER BY term");
            $totalSwords = $xoopsDB->getRowsNum($resultS);

            if ($totalSwords > 0) {
                // If there are definitions

                while (list($entryID, $term) = $xoopsDB->fetchRow($resultS)) {
                    $subentries             = array();
                    $xoopsModule            =& XoopsModule::getByDirname("wordbook");
                    $linktext               = ucfirst($myts->htmlSpecialChars($term));
                    $subentries['linktext'] = $linktext;
                    $subentries['id']       = $entryID;

                    $blockS['substuff'][] = $subentries;
                }
                $xoopsTpl->assign('blockS', $blockS);
                $xoopsTpl->assign('wehavesubs', 1);
            } else {
                $xoopsTpl->assign('wehavesubs', 0);
            }

            $blockR      = array();
            $resultR     = $xoopsDB->query("SELECT entryID, term FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND request = '1' ORDER BY term");
            $totalRwords = $xoopsDB->getRowsNum($resultR);

            if ($totalRwords > 0) {
                // If there are definitions

                while (list($entryID, $term) = $xoopsDB->fetchRow($resultR)) {
                    $reqentries             = array();
                    $xoopsModule            =& XoopsModule::getByDirname("wordbook");
                    $linktext               = ucfirst($myts->htmlSpecialChars($term));
                    $reqentries['linktext'] = $linktext;
                    $reqentries['id']       = $entryID;

                    $blockR['reqstuff'][] = $reqentries;
                }
                $xoopsTpl->assign('blockR', $blockR);
                $xoopsTpl->assign('wehavereqs', 1);
            } else {
                $xoopsTpl->assign('wehavereqs', 0);
            }
        } else {
            $xoopsTpl->assign('userisadmin', 0);
            $blockR      = array();
            $resultR     = $xoopsDB->query("SELECT entryID, term FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND request = '1' ORDER BY term");
            $totalRwords = $xoopsDB->getRowsNum($resultR);

            if ($totalRwords > 0) {
                // If there are definitions

                while (list($entryID, $term) = $xoopsDB->fetchRow($resultR)) {
                    $reqentries             = array();
                    $xoopsModule            =& XoopsModule::getByDirname("wordbook");
                    $linktext               = ucfirst($myts->htmlSpecialChars($term));
                    $reqentries['linktext'] = $linktext;
                    $reqentries['id']       = $entryID;

                    $blockR['reqstuff'][] = $reqentries;
                }
                $xoopsTpl->assign('blockR', $blockR);
                $xoopsTpl->assign('wehavereqs', 1);
            } else {
                $xoopsTpl->assign('wehavereqs', 0);
            }
        }
        // Various strings
        $xoopsTpl->assign('lang_modulename', $xoopsModule->name());
        $xoopsTpl->assign('lang_moduledirname', $xoopsModule->dirname());
        $xoopsTpl->assign('microlinks', $microlinks);
        $xoopsTpl->assign('alpha', $alpha);
}
// 1.17
//$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()). ' - ' ._MI_WB_MD_DESC);
$xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()));

$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');

include(XOOPS_ROOT_PATH . "/footer.php");
