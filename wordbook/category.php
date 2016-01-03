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
$xoopsOption['template_main'] = 'wb_category.tpl';
include_once(XOOPS_ROOT_PATH . "/header.php");

global $xoTheme, $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $xoopsModuleConfig, $xoopsModule, $XOOPS_URL, $indexp;
$myts =& MyTextSanitizer::getInstance();

$categoryID = isset($_GET['categoryID']) ? (int)($_GET['categoryID']) : 0;

include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/cleantags.php";

$start = isset($_GET['start']) ? (int)($_GET['start']) : 0;

$xoopsTpl->assign('multicats', (int)($xoopsModuleConfig['multicats']));

// If there's no entries yet in the system...
$pubwords       = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '0' AND offline ='0' ");
$publishedwords = $xoopsDB->getRowsNum($pubwords);
if ($publishedwords == 0) {
    redirect_header(XOOPS_URL, 1, _MD_WB_STILLNOTHINGHERE);
    exit();
}
$xoopsTpl->assign('publishedwords', $publishedwords);

// To display the list of linked initials
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

// No ID of category: we need to see all categories descriptions
if (!$categoryID) {
    // How many categories are there?
    $resultcats = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbcategories") . " ORDER BY weight");
    $totalcats  = $xoopsDB->getRowsNum($resultcats);
    if ($totalcats == 0) {
        redirect_header("javascript:history.go(-1)", 1, _MD_WB_NOCATSINSYSTEM);
        exit();
    }

    // If there's no $categoryID, we want to show just the categories with their description
    $catsarray = array();

    // How many categories will we show in this page?
    $queryA  = "SELECT * FROM " . $xoopsDB->prefix('wbcategories') . " ORDER BY name ASC";
    $resultA = $xoopsDB->query($queryA, $xoopsModuleConfig['indexperpage'], $start);

    while (list($categoryID, $name, $description, $total) = $xoopsDB->fetchRow($resultA)) {
        $eachcat         = array();
        $xoopsModule     =& XoopsModule::getByDirname("wordbook");
        $eachcat['dir']  = $xoopsModule->dirname();
        $eachcat['id']   = $categoryID;
        $eachcat['name'] = $myts->htmlSpecialChars($name);
        //$eachcat['name'] = $myts -> addslashes( $name );
        $eachcat['description'] = $myts->htmlSpecialChars($description);
        //v.1.l7 show HTML in desc
        $eachcat['description'] = $myts->addSlashes($description);
        //$eachcat['description'] = wb_html2text( $description );// xoops2.2

        // Total entries in this category
        $entriesincat     = countByCategory($categoryID);
        $eachcat['total'] = (int)($entriesincat);

        $catsarray['single'][] = $eachcat;
    }
    $pagenav             = new XoopsPageNav($totalcats, $xoopsModuleConfig['indexperpage'], $start, 'start');
    $catsarray['navbar'] = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

    $xoopsTpl->assign('catsarray', $catsarray);
    $xoopsTpl->assign('pagetype', '0');
    // v.1.17
    wb_create_pagetitle($myts->htmlSpecialChars(_MD_WB_ALLCATS));
} else {
    // There IS a $categoryID, thus we show only that category's description
    $catdata = $xoopsDB->query("SELECT categoryID, name, description, total FROM " . $xoopsDB->prefix('wbcategories') . " WHERE categoryID = '$categoryID' ");
    while (list($categoryID, $name, $description, $total) = $xoopsDB->fetchRow($catdata)) {
        if ($total == 0) {
            redirect_header("javascript:history.go(-1)", 1, _MD_WB_NOENTRIESINCAT);
            exit();
        }
        $singlecat         = array();
        $singlecat['dir']  = $xoopsModule->dirname();
        $singlecat['id']   = $categoryID;
        $singlecat['name'] = $myts->htmlSpecialChars($name);
        //$singlecat['name'] = $myts -> addslashes( $name );
        $singlecat['description'] = $myts->htmlSpecialChars($description);
        //v.1.l7 show HTML in desc
        $singlecat['description'] = $myts->addSlashes($description);
        //$singlecat['description'] = $myts -> addSlashes(wb_cleantags( $description ));//xoops 2.2 + 2.0 but reduced HTML

        // Total entries in this category
        $entriesincat = countByCategory($categoryID);

        $singlecat['total'] = (int)($entriesincat);
        $xoopsTpl->assign('singlecat', $singlecat);

        // Entries to show in current page
        $entriesarray = array();

        // Now we retrieve a specific number of entries according to start variable
        //$queryB = "SELECT entryID, term, definition FROM " . $xoopsDB -> prefix( 'wbentries' ) . " WHERE categoryID = '$categoryID' AND submit ='0' AND offline = '0' ORDER BY term ASC";
        // load entry and HTML
        $queryB  = "SELECT entryID, term, definition, html, smiley, xcodes, breaks FROM " . $xoopsDB->prefix('wbentries') . " WHERE categoryID = '$categoryID' AND submit ='0' AND offline = '0' ORDER BY term ASC";
        $resultB = $xoopsDB->query($queryB, $xoopsModuleConfig['indexperpage'], $start);

        //while (list( $entryID, $term, $definition ) = $xoopsDB->fetchRow($resultB))
        while (list($entryID, $term, $definition, $html, $smiley, $xcodes, $breaks) = $xoopsDB->fetchRow($resultB)) {
            $eachentry         = array();
            $xoopsModule       =& XoopsModule::getByDirname("wordbook");
            $eachentry['dir']  = $xoopsModule->dirname();
            $eachentry['id']   = $entryID;
            $eachentry['term'] = ucfirst($myts->htmlSpecialChars($term));
            if (!XOOPS_USE_MULTIBYTES) {
                //$deftemp = $myts -> displayTarea ( substr ( $definition, 0, ( $xoopsModuleConfig['rndlength'] -1 ))) . "...";
                $deftemp = $myts->displayTarea(substr($definition, 0, ($xoopsModuleConfig['rndlength'] - 1)), $html, $smiley, $xcodes, $breaks) . "...";
                //$deftemp = wb_cleanTags( $deftemp );
                //$deftemp = wb_html2text($deftemp);// 1.17 NO HTML
                $deftemp                 = $myts->addslashes($deftemp);// 1.17 show HTML
                $eachentry['definition'] = $deftemp;
            }

            // Functional links
            $microlinks               = serviceLinks($eachentry);
            $eachentry['microlinks']  = $microlinks;
            $entriesarray['single'][] = $eachentry;
        }
    }
    $navstring              = "categoryID=" . $singlecat['id'] . "&start";
    $pagenav                = new XoopsPageNav($entriesincat, $xoopsModuleConfig['indexperpage'], $start, $navstring);
    $entriesarray['navbar'] = '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';

    $xoopsTpl->assign('entriesarray', $entriesarray);
    $xoopsTpl->assign('pagetype', '1');
    //v.1.17
    wb_create_pagetitle($myts->htmlSpecialChars(_MD_WB_ENTRYCATEGORY . ' ' . $singlecat['name']));
}

$xoopsTpl->assign('lang_modulename', $xoopsModule->name());
$xoopsTpl->assign('lang_moduledirname', $xoopsModule->dirname());

// This will let us include the module's styles in the theme
$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');

include(XOOPS_ROOT_PATH . "/footer.php");
