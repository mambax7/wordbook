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

// -- General Stuff -- //
include("admin_header.php");

$op = '';

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

// -- Edit function -- //
/**
 * @param string $entryID
 */
function entryEdit($entryID = '')
{
    global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig, $xoopsModule, $XOOPS_URL;

    /**
     * Clear all variables before we start
     */
    if (!isset($block)) {
        $block = 1;
    }
    if (!isset($html)) {
        $html = 1;
    }
    if (!isset($smiley)) {
        $smiley = 1;
    }
    if (!isset($xcodes)) {
        $xcodes = 1;
    }
    if (!isset($breaks)) {
        $breaks = 1;
    }
    if (!isset($offline)) {
        $offline = 0;
    }
    if (!isset($submit)) {
        $submit = 0;
    }
    if (!isset($request)) {
        $request = 0;
    }
    if (!isset($notifypub)) {
        $notifypub = 1;
    }
    if (!isset($categoryID)) {
        $categoryID = 1;
    }
    if (!isset($term)) {
        $term = "";
    }
    if (!isset($definition)) {
        $definition = _AM_WB_WRITEHERE;
    }
    if (!isset($ref)) {
        $ref = "";
    }
    if (!isset($url)) {
        $url = "";
    }

    // If there is a parameter, and the id exists, retrieve data: we're editing an entry
    if ($entryID) {
        $result = $xoopsDB->query("SELECT categoryID, term, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub, request FROM " . $xoopsDB->prefix("wbentries") . " WHERE entryID = '$entryID'");
        list($categoryID, $term, $definition, $ref, $url, $uid, $submit, $datesub, $html, $smiley, $xcodes, $breaks, $block, $offline, $notifypub, $request) = $xoopsDB->fetchrow($result);

        if (!$xoopsDB->getRowsNum($result)) {
            redirect_header("index.php", 1, _AM_WB_NOENTRYTOEDIT);
            exit();
        }
        // wb_adminMenu(2, _AM_WB_ENTRIES);

        echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_WB_ADMINENTRYMNGMT . "</h3>";
        $sform = new XoopsThemeForm(_AM_WB_MODENTRY . ": $term", "op", xoops_getenv('PHP_SELF'));
    } else {
        // there's no parameter, so we're adding an entry

        $result01 = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("wbcategories") . " ");
        list($totalcats) = $xoopsDB->fetchRow($result01);
        if ($totalcats == 0 && $xoopsModuleConfig['multicats'] == 1) {
            redirect_header("index.php", 1, _AM_WB_NEEDONECOLUMN);
            exit();
        }
        // wb_adminMenu(2, _AM_WB_ENTRIES);
        $uid = $xoopsUser->getVar('uid');
        echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_WB_ADMINENTRYMNGMT . "</h3>";
        $sform = new XoopsThemeForm(_AM_WB_NEWENTRY, "op", xoops_getenv('PHP_SELF'));
    }

    $sform->setExtra('enctype="multipart/form-data"');

    // Category selector
    if ($xoopsModuleConfig['multicats'] == 1) {
        $mytree = new XoopsTree($xoopsDB->prefix("wbcategories"), "categoryID", "0");

        ob_start();
        //$sform -> addElement( new XoopsFormHidden( 'categoryID', $categoryID ) );
        $sform->addElement(new XoopsFormHidden('$categoryID', $categoryID));//v.1.17
        $mytree->makeMySelBox("name", "name", $categoryID);
        $sform->addElement(new XoopsFormLabel(_AM_WB_CATNAME, ob_get_contents()));
        ob_end_clean();
    }

    // Author selector
    ob_start();
    getuserForm((int)($uid));
    $sform->addElement(new XoopsFormLabel(_AM_WB_AUTHOR, ob_get_contents()));
    ob_end_clean();

    // Term, definition, reference and related URL
    $sform->addElement(new XoopsFormText(_AM_WB_ENTRYTERM, 'term', 50, 80, $term), true);

    $def_block = new XoopsFormDhtmlTextArea(_AM_WB_ENTRYDEF, 'definition', $definition, 15, 60);
    if ($definition == _MD_WB_WRITEHERE) {
        $def_block->setExtra('onfocus="this.select()"');
    }
    $sform->addElement($def_block);
    $sform->addElement(new XoopsFormTextArea(_AM_WB_ENTRYREFERENCE, 'ref', $ref, 5, 60), false);
    $sform->addElement(new XoopsFormText(_AM_WB_ENTRYURL, 'url', 50, 80, $url), false);

    // Code to take entry offline, for maintenance purposes
    $offline_radio = new XoopsFormRadioYN(_AM_WB_SWITCHOFFLINE, 'offline', $offline, ' ' . _AM_WB_YES . '', ' ' . _AM_WB_NO . '');
    $sform->addElement($offline_radio);

    // Code to put entry in block
    $block_radio = new XoopsFormRadioYN(_AM_WB_BLOCK, 'block', $block, ' ' . _AM_WB_YES . '', ' ' . _AM_WB_NO . '');
    $sform->addElement($block_radio);

    // VARIOUS OPTIONS
    $options_tray = new XoopsFormElementTray(_AM_WB_OPTIONS, '<br />');

    $html_checkbox = new XoopsFormCheckBox('', 'html', $html);
    $html_checkbox->addOption(1, _AM_WB_DOHTML);
    $options_tray->addElement($html_checkbox);

    $smiley_checkbox = new XoopsFormCheckBox('', 'smiley', $smiley);
    $smiley_checkbox->addOption(1, _AM_WB_DOSMILEY);
    $options_tray->addElement($smiley_checkbox);

    $xcodes_checkbox = new XoopsFormCheckBox('', 'xcodes', $xcodes);
    $xcodes_checkbox->addOption(1, _AM_WB_DOXCODE);
    $options_tray->addElement($xcodes_checkbox);

    $breaks_checkbox = new XoopsFormCheckBox('', 'breaks', $breaks);
    $breaks_checkbox->addOption(1, _AM_WB_BREAKS);
    $options_tray->addElement($breaks_checkbox);

    $sform->addElement($options_tray);

    $sform->addElement(new XoopsFormHidden('entryID', $entryID));

    $button_tray = new XoopsFormElementTray('', '');
    $hidden      = new XoopsFormHidden('op', 'addentry');
    $button_tray->addElement($hidden);

    if (!$entryID) {
        // there's no entryID? Then it's a new entry

        $butt_create = new XoopsFormButton('', '', _AM_WB_CREATE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addentry\'"');
        $button_tray->addElement($butt_create);

        $butt_clear = new XoopsFormButton('', '', _AM_WB_CLEAR, 'reset');
        $button_tray->addElement($butt_clear);

        $butt_cancel = new XoopsFormButton('', '', _AM_WB_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    } else {
        // else, we're editing an existing entry

        $butt_create = new XoopsFormButton('', '', _AM_WB_MODIFY, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addentry\'"');
        $button_tray->addElement($butt_create);

        $butt_cancel = new XoopsFormButton('', '', _AM_WB_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    }

    $sform->addElement($button_tray);
    $sform->display();
    unset($hidden);
}

/**
 * @param string $entryID
 */
function entrySave($entryID = '')
{
    global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsDB, $myts;
    $entryID = isset($_POST['entryID']) ? (int)($_POST['entryID']) : (int)($_GET['entryID']);
    if ($xoopsModuleConfig['multicats'] == 1) {
        $categoryID = isset($_POST['categoryID']) ? (int)($_POST['categoryID']) : (int)($_GET['categoryID']);
    } else {
        $categoryID = '';
    }
    $block  = isset($_POST['block']) ? (int)($_POST['block']) : (int)($_GET['block']);
    $breaks = isset($_POST['breaks']) ? (int)($_POST['breaks']) : (int)($_GET['breaks']);

    $html    = isset($_POST['html']) ? (int)($_POST['html']) : (int)($_GET['html']);
    $smiley  = isset($_POST['smiley']) ? (int)($_POST['smiley']) : (int)($_GET['smiley']);
    $xcodes  = isset($_POST['xcodes']) ? (int)($_POST['xcodes']) : (int)($_GET['xcodes']);
    $offline = isset($_POST['offline']) ? (int)($_POST['offline']) : (int)($_GET['offline']);

    //$term = $myts->addSlashes($_POST['term']);//render HTML <>
    // v.1.17 take apostrophy ' sign in terms
    $term = $myts->addSlashes(wb_accent2text($_POST['term']));

    $init = substr($term, 0, 1);

    if (preg_match("/[a-zA-Z]/", $init)) {
        $init = strtoupper($init);
    } else {
        $init = '#';
    }

    // v.1.17 take apostrophy ' sign in definition
    $definition = $myts->xoopsCodeDecode(wb_accent2text($_POST['definition'], $allowimage = 1));
    //$definition = $myts -> xoopsCodeDecode($_POST['definition'], $allowimage = 1);
    $ref = isset($_POST['ref']) ? $myts->addSlashes($_POST['ref']) : '';
    $url = isset($_POST['url']) ? $myts->addSlashes($_POST['url']) : '';

    $date      = time();
    $submit    = 0;
    $notifypub = 0;
    $request   = 0;
    $uid       = isset($_POST['author']) ? (int)($_POST['author']) : $xoopsUser->uid();

    // Save to database
    if (!$entryID) {
        if ($xoopsDB->query("INSERT INTO " . $xoopsDB->prefix("wbentries") . " (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, block, offline, notifypub, request ) VALUES ('', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$date', '$html', '$smiley', '$xcodes', '$breaks', '$block', '$offline', '$notifypub', '$request' )")) {
            calculateTotals();
            redirect_header("index.php", 1, _AM_WB_ENTRYCREATEDOK);
        } else {
            redirect_header("index.php", 1, _AM_WB_ENTRYNOTCREATED);
        }
    } else {
        // That is, $entryID exists, thus we're editing an entry

        if ($xoopsDB->query("UPDATE " . $xoopsDB->prefix("wbentries") . " SET term = '$term', categoryID = '$categoryID', init = '$init', definition = '$definition', ref = '$ref', url = '$url', uid = '$uid', submit = '$submit', datesub = '$date', html = '$html', smiley = '$smiley', xcodes = '$xcodes', breaks = '$breaks', block = '$block', offline = '$offline', notifypub = '$notifypub', request = '$request' WHERE entryID = '$entryID'")) {
            calculateTotals();
            redirect_header("index.php", 1, _AM_WB_ENTRYMODIFIED);
        } else {
            redirect_header("index.php", 1, _AM_WB_ENTRYNOTUPDATED);
        }
    }
}

/**
 * @param string $entryID
 */
function entryDelete($entryID = '')
{
    global $xoopsDB, $xoopsModule;
    $entryID = isset($_POST['entryID']) ? (int)($_POST['entryID']) : (int)($_GET['entryID']);
    $ok      = isset($_POST['ok']) ? (int)($_POST['ok']) : 0;
    $result  = $xoopsDB->query("SELECT entryID, term FROM " . $xoopsDB->prefix("wbentries") . " WHERE entryID = $entryID");
    list($entryID, $term) = $xoopsDB->fetchrow($result);

    // confirmed, so delete
    if ($ok == 1) {
        $result = $xoopsDB->query("DELETE FROM " . $xoopsDB->prefix("wbentries") . " WHERE entryID = $entryID");
        // delete comments (mondarse)
        xoops_comment_delete($xoopsModule->getVar('mid'), $entryID);
        // delete comments (mondarse)
        redirect_header("index.php", 1, sprintf(_AM_WB_ENTRYISDELETED, $term));
    } else {
        xoops_cp_header();
        xoops_confirm(array('op' => 'del', 'entryID' => $entryID, 'ok' => 1, 'term' => $term), 'entry.php', _AM_WB_DELETETHISENTRY . "<br /><br>" . $term, _AM_WB_DELETE);
        xoops_cp_footer();
    }
    exit();
    //    break;
}

function entryDefault()
{
    xoops_cp_header();
    entryEdit();
}

/* -- Available operations -- */
switch ($op) {
    case "mod":
        xoops_cp_header();
        $entryID = (isset($_GET['entryID'])) ? (int)($_GET['entryID']) : (int)($_POST['entryID']);
        entryEdit($entryID);
        break;

    case "addentry":
        entrySave();
        exit();
        break;

    case "del":
        entryDelete();
        break;
    case "default":
    default:
        entryDefault();
        break;
}
xoops_cp_footer();
