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

include("admin_header.php");
$myts = MyTextSanitizer::getInstance();
$op   = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

/* -- Available operations -- */
switch ($op) {
    case "default":
    default:
        include_once XOOPS_ROOT_PATH . "/class/xoopslists.php";
        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

        $startentry = isset($_GET['startentry']) ? (int)($_GET['startentry']) : 0;
        $startcat   = isset($_GET['startcat']) ? (int)($_GET['startcat']) : 0;
        $startsub   = isset($_GET['startsub']) ? (int)($_GET['startsub']) : 0;
        $datesub    = isset($_GET['datesub']) ? (int)($_GET['datesub']) : 0;
        $entryID    =

            xoops_cp_header();
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $entryID;

        $myts = MyTextSanitizer::getInstance();
        // wb_adminMenu(0, _AM_WB_INDEX);
        $result01 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbcategories") . " ");
        list($totalcategories) = $xoopsDB->fetchRow($result01);
        $result02 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = 0");
        list($totalpublished) = $xoopsDB->fetchRow($result02);
        $result03 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '1' AND request = '0' ");
        list($totalsubmitted) = $xoopsDB->fetchRow($result03);
        $result04 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '1' AND request = '1' ");
        list($totalrequested) = $xoopsDB->fetchRow($result04);
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_WB_MODULEHEADMULTI . "</h3>";
        } else {
            echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_WB_MODULEHEADSINGLE . "</h3>";
        }
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WB_INVENTORY . "</legend>";
        echo "<div style='padding: 12px;'>" . _AM_WB_TOTALENTRIES . " <b>$totalpublished</b> | ";
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo _AM_WB_TOTALCATS . " <b>$totalcategories</b> | ";
        }
        echo _AM_WB_TOTALSUBM . " <b>$totalsubmitted</b> | ";
        echo _AM_WB_TOTALREQ . " <b>$totalrequested</b></div>";
        echo "</fieldset><br />";

        /* -- Code to show existing terms -- */
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WB_SHOWENTRIES . "</legend><br />";
        echo "<a style='border: 1px solid #5E5D63; color: #000000; font-family: verdana, tahoma, arial, helvetica, sans-serif; font-size: 1em; padding: 4px 8px; text-align:center;' href='entry.php'>" . _AM_WB_CREATEENTRY . "</a><br /><br />";
        // To create existing terms table
        $resultA1 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = 0");
        list($numrows) = $xoopsDB->fetchRow($resultA1);

        $sql      = "SELECT entryID, categoryID, term, uid, datesub, offline FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = 0 ORDER BY entryID DESC";
        $resultA2 = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $startentry);

        echo "<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>";
        echo "<tr>";
        echo "<td width='40' class='bg3' align='center'><b>" . _AM_WB_ENTRYID . "</b></td>";
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<td width='20%' class='bg3' align='center'><b>" . _AM_WB_ENTRYCATNAME . "</b></td>";
        }
        echo "<td class='bg3' align='center'><b>" . _AM_WB_ENTRYTERM . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_WB_SUBMITTER . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_WB_ENTRYCREATED . "</b></td>";
        echo "<td width='30' class='bg3' align='center'><b>" . _AM_WB_STATUS . "</b></td>";
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_WB_ACTION . "</b></td>";
        echo "</tr>";

        if ($numrows > 0) {
            // That is, if there ARE entries in the system

            while (list($entryID, $categoryID, $term, $uid, $created, $offline) = $xoopsDB->fetchrow($resultA2)) {
                $resultA3 = $xoopsDB->query("SELECT name FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = '$categoryID'");
                list($name) = $xoopsDB->fetchrow($resultA3);

                $sentby = XoopsUserUtility::getUnameFromId($uid);

                $catname = $myts->htmlSpecialChars($name);
                $term    = $myts->htmlSpecialChars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='entry.php?op=mod&entryID=" . $entryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/edit.gif ALT='" . _AM_WB_EDITENTRY . "'></a>";
                $delete  = "<a href='entry.php?op=del&entryID=" . $entryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/delete.gif ALT='" . _AM_WB_DELETEENTRY . "'></a>";

                if ($offline == 0) {
                    $status = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/on.gif alt='" . _AM_WB_ENTRYISON . "'>";
                } else {
                    $status = "<img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/off.gif alt='" . _AM_WB_ENTRYISOFF . "'>";
                }

                echo "<tr>";
                echo "<td class='head' align='center'>" . $entryID . "</td>";
                if ($xoopsModuleConfig['multicats'] == 1) {
                    echo "<td class='even' align='left'>" . $catname . "</td>";
                }
                //echo "<td class='even' align='left'>" . $term . "</td>";
                echo "<td class='even' align='left'><a href='../entry.php?entryID=" . $entryID . "'>" . $term . "</td>";
                echo "<td class='even' align='center'>" . $sentby . "</td>";
                echo "<td class='even' align='center'>" . $created . "</td>";
                echo "<td class='even' align='center'>" . $status . "</td>";
                echo "<td class='even' align='center'> $modify $delete </td>";
                echo "</tr>";
            }
        } else {
            // that is, $numrows = 0, there's no entries yet

            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_WB_NOTERMS . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        $pagenav = new XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startentry, 'startentry', 'entryID =' . $entryID);
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo "</fieldset>";
        echo "<br />\n";

        if ($xoopsModuleConfig['multicats'] == 1) {
            /* -- Code to show existing categories -- */
            echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WB_SHOWCATS . "</legend><br />";
            echo "<a style='border: 1px solid #5E5D63; color: #000000; font-family: verdana, tahoma, arial, helvetica, sans-serif; font-size: 1em; padding: 4px 8px; text-align:center;' href='category.php'>" . _AM_WB_CREATECAT . "</a><br /><br />";
            // To create existing columns table
            $resultC1 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbcategories") . " ");
            list($numrows) = $xoopsDB->fetchRow($resultC1);

            $sql      = "SELECT * FROM " . $xoopsDB->prefix("wbcategories") . " ORDER BY weight";
            $resultC2 = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $startcat);

            echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
            echo "<tr>";
            echo "<td width='40' class='bg3' align='center'><b>" . _AM_WB_ID . "</b></td>";
            echo "<td class='bg3' align='center'><b>" . _AM_WB_WEIGHT . "</b></td>";
            echo "<td width='20%' class='bg3' align='center'><b>" . _AM_WB_CATNAME . "</b></td>";
            echo "<td class='bg3' align='center'><b>" . _AM_WB_DESCRIP . "</b></td>";
            echo "<td width='60' class='bg3' align='center'><b>" . _AM_WB_ACTION . "</b></td>";
            echo "</tr>";

            if ($numrows > 0) {
                // That is, if there ARE columns in the system

                while (list($categoryID, $name, $description, $total, $weight,) = $xoopsDB->fetchrow($resultC2)) {
                    $name = $myts->htmlSpecialChars($name);
                    //$description = $myts -> htmlSpecialChars( $description );
                    // v.1.17 NO HTML
                    $description = $myts->htmlSpecialChars(wb_html2text($description));
                    //$description = $myts -> addslashes( $description ); // show HTML
                    $modify = "<a href='category.php?op=mod&categoryID=" . $categoryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/edit.gif ALT='" . _AM_WB_EDITCAT . "'></a>";
                    $delete = "<a href='category.php?op=del&categoryID=" . $categoryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/delete.gif ALT='" . _AM_WB_DELETECAT . "'></a>";

                    echo "<tr>";
                    echo "<td class='head' align='center'>" . $categoryID . "</td>";
                    echo "<td class='even' align='center'>" . $weight . "</td>";
                    echo "<td class='even' align='lefet'>" . $name . "</td>";
                    echo "<td class='even' align='left'>" . $description . "</td>";
                    echo "<td class='even' align='center'> $modify $delete </td>";
                    echo "</tr>";
                }
            } else {
                // that is, $numrows = 0, there's no columns yet

                echo "<tr>";
                echo "<td class='head' align='center' colspan= '7'>" . _AM_WB_NOCATS . "</td>";
                echo "</tr>";
                $categoryID = '0';
            }
            echo "</table>\n";
            $pagenav = new XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startcat, 'startcat', 'categoryID=' . $categoryID);
            echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
            echo "</fieldset>";
            echo "<br />\n";
        }

        /* -- Code to show submitted entries -- */
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WB_SHOWSUBMISSIONS . "</legend><br />";
        $resultS1 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '1' AND request = '0' ");
        list($numrows) = $xoopsDB->fetchRow($resultS1);

        $sql      = "SELECT entryID, categoryID, term, uid, datesub FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '1' AND request = '0' ORDER BY datesub DESC";
        $resultS2 = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $startsub);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td width='40' class='bg3' align='center'><b>" . _AM_WB_ENTRYID . "</b></td>";
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<td width='20%' class='bg3' align='center'><b>" . _AM_WB_ENTRYCATNAME . "</b></td>";
        }
        echo "<td class='bg3' align='center'><b>" . _AM_WB_ENTRYTERM . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_WB_SUBMITTER . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_WB_ENTRYCREATED . "</b></td>";
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_WB_ACTION . "</b></td>";
        echo "</tr>";

        if ($numrows > 0) {
            // That is, if there ARE submitted entries in the system

            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchrow($resultS2)) {
                $resultS3 = $xoopsDB->query("SELECT name FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = '$categoryID'");
                list($name) = $xoopsDB->fetchrow($resultS3);

                $sentby = XoopsUserUtility::getUnameFromId($uid);

                $catname = $myts->htmlSpecialChars($name);
                $term    = $myts->htmlSpecialChars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='submissions.php?op=mod&entryID=" . $entryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/edit.gif ALT='" . _AM_WB_EDITSUBM . "'></a>";
                $delete  = "<a href='submissions.php?op=del&entryID=" . $entryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/delete.gif ALT='" . _AM_WB_DELETESUBM . "'></a>";

                echo "<tr>";
                echo "<td class='head' align='center'>" . $entryID . "</td>";
                if ($xoopsModuleConfig['multicats'] == 1) {
                    echo "<td class='even' align='left'>" . $catname . "</td>";
                }
                echo "<td class='even' align='left'>" . $term . "</td>";
                echo "<td class='even' align='center'>" . $sentby . "</td>";
                echo "<td class='even' align='center'>" . $created . "</td>";
                echo "<td class='even' align='center'> $modify $delete </td>";
                echo "</tr>";
            }
        } else {
            // that is, $numrows = 0, there's no columns yet

            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_WB_NOSUBMISSYET . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        $pagenav = new XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startsub, 'startsub', 'entryID =' . $entryID);
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo "</fieldset>";
        echo "<br />\n";

        /* -- Code to show requested entries -- */
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WB_SHOWREQUESTS . "</legend><br />";
        $resultS2 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '1' and request = '1'");
        list($numrowsX) = $xoopsDB->fetchRow($resultS2);

        $sql4     = "SELECT entryID, categoryID, term, uid, datesub FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '1' AND request = '1' ORDER BY datesub DESC";
        $resultS4 = $xoopsDB->query($sql4, $xoopsModuleConfig['perpage'], $startsub);

        echo "<table width='100%' cellspacing=1 cellpadding=3 border=0 class = outer>";
        echo "<tr>";
        echo "<td width='40' class='bg3' align='center'><b>" . _AM_WB_ENTRYID . "</b></td>";
        if ($xoopsModuleConfig['multicats'] == 1) {
            echo "<td width='20%' class='bg3' align='center'><b>" . _AM_WB_ENTRYCATNAME . "</b></td>";
        }
        echo "<td class='bg3' align='center'><b>" . _AM_WB_ENTRYTERM . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_WB_SUBMITTER . "</b></td>";
        echo "<td width='90' class='bg3' align='center'><b>" . _AM_WB_ENTRYCREATED . "</b></td>";
        echo "<td width='60' class='bg3' align='center'><b>" . _AM_WB_ACTION . "</b></td>";
        echo "</tr>";

        if ($numrowsX > 0) {
            // That is, if there ARE unauthorized articles in the system

            while (list($entryID, $categoryID, $term, $uid, $created) = $xoopsDB->fetchrow($resultS4)) {
                $resultS3 = $xoopsDB->query("SELECT name FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = '$categoryID'");
                list($name) = $xoopsDB->fetchrow($resultS3);

                $sentby = XoopsUserUtility::getUnameFromId($uid);

                $catname = $myts->htmlSpecialChars($name);
                $term    = $myts->htmlSpecialChars($term);
                $created = formatTimestamp($created, 's');
                $modify  = "<a href='submissions.php?op=mod&entryID=" . $entryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/edit.gif ALT='" . _AM_WB_EDITSUBM . "'></a>";
                $delete  = "<a href='submissions.php?op=del&entryID=" . $entryID . "'><img src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/assets/images/icon/delete.gif ALT='" . _AM_WB_DELETESUBM . "'></a>";

                echo "<tr>";
                echo "<td class='head' align='center'>" . $entryID . "</td>";
                if ($xoopsModuleConfig['multicats'] == 1) {
                    echo "<td class='even' align='left'>" . $catname . "</td>";
                }
                echo "<td class='even' align='left'>" . $term . "</td>";
                echo "<td class='even' align='center'>" . $sentby . "</td>";
                echo "<td class='even' align='center'>" . $created . "</td>";
                echo "<td class='even' align='center'> $modify $delete </td>";
                echo "</tr>";
            }
        } else {
            // that is, $numrows = 0, there's no columns yet

            echo "<tr>";
            echo "<td class='head' align='center' colspan= '7'>" . _AM_WB_NOREQSYET . "</td>";
            echo "</tr>";
        }
        echo "</table>\n";
        $pagenav = new XoopsPageNav($numrows, $xoopsModuleConfig['perpage'], $startsub, 'startsub', 'entryID =' . $entryID);
        echo '<div style="text-align:right;">' . $pagenav->renderNav() . '</div>';
        echo "</fieldset>";
        echo "<br />\n";

        break;
}
xoops_cp_footer();
