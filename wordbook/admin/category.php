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

/**
 * @param string $categoryID
 */
function categoryEdit($categoryID = '')
{
    $weight      = 1;
    $name        = '';
    $description = '';

    global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $xoopsModuleConfig, $xoopsModule;

    // If there is a parameter, and the id exists, retrieve data: we're editing a column
    if ($categoryID) {
        $result = $xoopsDB->query("SELECT categoryID, name, description, total, weight FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = '$categoryID'");
        list($categoryID, $name, $description, $total, $weight) = $xoopsDB->fetchrow($result);

        if ($xoopsDB->getRowsNum($result) == 0) {
            redirect_header("category.php", 1, _AM_WB_NOCATTOEDIT);
            exit();
        }
        xoops_cp_header();
        // wb_adminMenu(1, _AM_WB_CATS);

        echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_WB_CATSHEADER . "</h3>";
        $sform = new XoopsThemeForm(_AM_WB_MODCAT . ": $name", "op", xoops_getenv('PHP_SELF'));
    } else {
        xoops_cp_header();
        // wb_adminMenu(1, _AM_WB_CATS);

        echo "<h3 style=\"color: #2F5376; margin-top: 6px; \">" . _AM_WB_CATSHEADER . "</h3>";
        $sform = new XoopsThemeForm(_AM_WB_NEWCAT, "op", xoops_getenv('PHP_SELF'));
    }

    $sform->setExtra('enctype="multipart/form-data"');
    $sform->addElement(new XoopsFormText(_AM_WB_CATNAME, 'name', 50, 80, $name), true);
    $sform->addElement(new XoopsFormTextArea(_AM_WB_CATDESCRIPT, 'description', $description, 7, 60));
    $sform->addElement(new XoopsFormText(_AM_WB_CATPOSIT, 'weight', 4, 4, $weight), true);
    $sform->addElement(new XoopsFormHidden('categoryID', $categoryID));

    $button_tray = new XoopsFormElementTray('', '');
    $hidden      = new XoopsFormHidden('op', 'addcat');
    $button_tray->addElement($hidden);

    // No ID for column -- then it's new column, button says 'Create'
    if (!$categoryID) {
        $butt_create = new XoopsFormButton('', '', _AM_WB_CREATE, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcat\'"');
        $button_tray->addElement($butt_create);

        $butt_clear = new XoopsFormButton('', '', _AM_WB_CLEAR, 'reset');
        $button_tray->addElement($butt_clear);

        $butt_cancel = new XoopsFormButton('', '', _AM_WB_CANCEL, 'button');
        $butt_cancel->setExtra('onclick="history.go(-1)"');
        $button_tray->addElement($butt_cancel);
    } else {
        // button says 'Update'

        $butt_create = new XoopsFormButton('', '', _AM_WB_MODIFY, 'submit');
        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addcat\'"');
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
 * @param string $categoryID
 */
function categoryDelete($categoryID = '')
{
    global $xoopsDB;
    $categoryID = isset($_POST['categoryID']) ? (int)($_POST['categoryID']) : (int)($_GET['categoryID']);
    $ok         = isset($_POST['ok']) ? (int)($_POST['ok']) : 0;
    $result     = $xoopsDB->query("SELECT categoryID, name FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = $categoryID");
    list($categoryID, $name) = $xoopsDB->fetchrow($result);

    // confirmed, so delete
    if ($ok == 1) {
        //get all entries in the category
        $result3 = $xoopsDB->query("select entryID from " . $xoopsDB->prefix("wbentries") . " where categoryID = $categoryID");
        //now for each entry, delete the coments
        while (list($entryID) = $xoopsDB->fetchRow($result3)) {
            xoops_comment_delete($xoopsModule->getVar('mid'), $entryID);
        }
        $result  = $xoopsDB->query("DELETE FROM " . $xoopsDB->prefix("wbcategories") . " WHERE categoryID = $categoryID");
        $result2 = $xoopsDB->query("DELETE FROM " . $xoopsDB->prefix("wbentries") . " WHERE categoryID = $categoryID");
        redirect_header("index.php", 1, sprintf(_AM_WB_CATISDELETED, $name));
    } else {
        xoops_cp_header();
        xoops_confirm(array('op' => 'del', 'categoryID' => $categoryID, 'ok' => 1, 'name' => $name), 'category.php', _AM_WB_DELETETHISCAT . "<br /><br>" . $name, _AM_WB_DELETE);
        xoops_cp_footer();
    }
}

/**
 * @param string $categoryID
 */
function categorySave($categoryID = '')
{
    global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $modify, $myts, $categoryID;
    $categoryID  = isset($_POST['categoryID']) ? (int)($_POST['categoryID']) : (int)($_GET['categoryID']);
    $weight      = isset($_POST['weight']) ? (int)($_POST['weight']) : (int)($_GET['weight']);
    $name        = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : htmlspecialchars($_GET['name']);
    $description = isset($_POST['description']) ? htmlspecialchars($_POST['description']) : htmlspecialchars($_GET['description']);
    //$description = $myts->xoopsCodeDecode($description, $allowimage = 0);
    //v.1.17 code to take apostrophy ' sign in descrciption and name
    $definition = $myts->xoopsCodeDecode($description, $allowimage = 0);
    //$definition = $myts -> xoopsCodeDecode(wb_accent2text($_POST['description'], $allowimage = 0));
    $description = $myts->addSlashes(wb_accent2text($_POST['description']));//v.117 render HTML <> and '
    //$description = $myts->addSlashes($_POST['description']);  //v.117 render HTML <>

    $name = $myts->addSlashes(wb_accent2text($_POST['name']));// 1.17 added HTML <> and '

    // Run the query and update the data
    if (!$_POST['categoryID']) {
        if ($xoopsDB->query("INSERT INTO " . $xoopsDB->prefix("wbcategories") . " (categoryID, name, description, weight) VALUES ('', '$name', '$description', '$weight')")) {
            redirect_header("index.php", 1, _AM_WB_CATCREATED);
        } else {
            redirect_header("index.php", 1, _AM_WB_NOTUPDATED);
        }
    } else {
        if ($xoopsDB->queryF("UPDATE " . $xoopsDB->prefix("wbcategories") . " SET name = '$name', description = '$description', weight = '$weight' WHERE categoryID = '$categoryID'")) {
            redirect_header("index.php", 1, _AM_WB_CATMODIFIED);
        } else {
            redirect_header("index.php", 1, _AM_WB_NOTUPDATED);
        }
    }
}

switch ($op) {
    case "mod":
        $categoryID = isset($_POST['categoryID']) ? (int)($_POST['categoryID']) : (int)($_GET['categoryID']);
        categoryEdit($categoryID);
        break;

    case "addcat":
        categorySave();
        exit();
        break;

    case "del":
        categoryDelete();
        exit();
        break;

    case "cancel":
        redirect_header("index.php", 1, sprintf(_AM_WB_BACK2IDX, ''));
        exit();

    case "default":
    default:
        global $xoopsUser, $xoopsUser, $xoopsConfig, $xoopsDB, $xoopsModuleConfig;
        if ($xoopsModuleConfig['multicats'] != 1) {
            redirect_header("index.php", 1, sprintf(_AM_WB_SINGLECAT, ''));
            exit();
        }
        categoryEdit();
        break;
}
xoops_cp_footer();
