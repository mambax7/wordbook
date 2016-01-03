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

include("header.php"); // to include function create pagetitle
include("../../mainfile.php");
$xoopsOption['template_main'] = 'wb_submit.tpl';
include(XOOPS_ROOT_PATH . "/header.php");

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

global $xoTheme, $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;

$result = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbcategories") . "");
if ($xoopsDB->getRowsNum($result) == '0' && $xoopsModuleConfig['multicats'] == '1') {
    redirect_header("index.php", 1, _AM_WB_NOCOLEXISTS);
    exit();
}

if (!is_object($xoopsUser) && $xoopsModuleConfig['anonpost'] == 0) {
    redirect_header("index.php", 1, _NOPERM);
    exit();
}
if (is_object($xoopsUser) && $xoopsModuleConfig['allowsubmit'] == 0) {
    redirect_header("index.php", 1, _NOPERM);
    exit();
}

$op = 'form';

if (isset($_POST['post'])) {
    $op = trim('post');
} elseif (isset($_POST['edit'])) {
    $op = trim('edit');
}

if (!isset($HTTP_POST_VARS['suggest'])) {
    $suggest = isset($HTTP_GET_VARS['suggest']) ? (int)($HTTP_GET_VARS['suggest']) : 0;
} else {
    $suggest = (int)($HTTP_POST_VARS['suggest']);
}
if ($suggest > 0) {
    $terminosql = $xoopsDB->query("SELECT term FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND request = '1' AND entryID = '" . $suggest . "'");
    list($termino) = $xoopsDB->fetchRow($terminosql);
} else {
    $termino = '';
}

switch ($op) {
    case 'post':
        global $xoTheme, $xoopsUser, $xoopsUxer, $xoopsConfig, $xoopsModule, $xoopsModuleConfig, $myts, $xoopsDB;

        include_once XOOPS_ROOT_PATH . "/modules/" . $xoopsModule->dirname() . "/include/functions.php";
        $myts = &MyTextSanitizer:: getInstance();

        $html = 1;
        if ($xoopsUser) {
            $uid = $xoopsUser->getVar('uid');
            if ($xoopsUser->isAdmin($xoopsModule->mid())) {
                $html = empty($html) ? 0 : 1;
            }
        } else {
            if ($xoopsModuleConfig['anonpost'] == 1) {
                $uid = 0;
            } else {
                redirect_header("index.php", 3, _NOPERM);
                exit();
            }
        }

        $block     = isset($block) ? (int)($block) : 1;
        $smiley    = isset($smiley) ? (int)($smiley) : 1;
        $xcodes    = isset($xcodes) ? (int)($xcodes) : 1;
        $breaks    = isset($breaks) ? (int)($breaks) : 1;
        $notifypub = isset($notifypub) ? (int)($notifypub) : 1;

        if ($xoopsModuleConfig['multicats'] == 1) {
            $categoryID = (int)($_POST['categoryID']);
        } else {
            $categoryID = 0;
        }
        $term       = $myts->htmlSpecialChars($_POST['term']);
        $init       = substr($term, 0, 1);
        $definition = $myts->addSlashes($_POST['definition']);
        $ref        = $myts->addSlashes($_POST['ref']);
        $url        = $myts->addSlashes($_POST['url']);

        if (empty($url)) {
            $url = "";
        }

        $datesub = time();

        $submit  = 1;
        $offline = 1;
        $request = 0;

        if ($xoopsModuleConfig['autoapprove'] == 1) {
            $submit  = 0;
            $offline = 0;
        }

        $result  = $xoopsDB->query("INSERT INTO " . $xoopsDB->prefix("wbentries") . " (entryID, categoryID, term, init, definition, ref, url, uid, submit, datesub, html, smiley, xcodes, breaks, offline, notifypub ) VALUES ('', '$categoryID', '$term', '$init', '$definition', '$ref', '$url', '$uid', '$submit', '$datesub', '$html', '$smiley', '$xcodes', '$breaks', '$offline', '$notifypub')");
        $entryID = $xoopsDB->getInsertId();

        if ($result) {
            if (!is_object($xoopsUser)) {
                $username = _MD_WB_GUEST;
                $usermail = '';
            } else {
                $username = $xoopsUser->getVar("uname", "E");
                $result   = $xoopsDB->query("select email from " . $xoopsDB->prefix("users") . " where uname='$username'");
                list($usermail) = $xoopsDB->fetchRow($result);
            }

            if ($xoopsModuleConfig['mailtoadmin'] == 1) {
                $adminMessage = sprintf(_MD_WB_WHOSUBMITTED, $username);
                $adminMessage .= "<b>" . $term . "</b>\n";
                $adminMessage .= "" . _MD_WB_EMAILLEFT . " $usermail\n";
                $adminMessage .= "\n";
                if ($notifypub == '1') {
                    $adminMessage .= _MD_WB_NOTIFYONPUB;
                }
                $adminMessage .= "\n" . $_SERVER['HTTP_USER_AGENT'] . "\n";
                $subject     = $xoopsConfig['sitename'] . " - " . _MD_WB_DEFINITIONSUB;
                $xoopsMailer =& getMailer();
                $xoopsMailer->useMail();
                $xoopsMailer->setToEmails($xoopsConfig['adminmail']);
                $xoopsMailer->setFromEmail($usermail);
                $xoopsMailer->setFromName($xoopsConfig['sitename']);
                $xoopsMailer->setSubject($subject);
                $xoopsMailer->setBody($adminMessage);
                $xoopsMailer->send();
                $messagesent = sprintf(_MD_WB_MESSAGESENT, $xoopsConfig['sitename']) . "<br />" . _MD_WB_THANKS1 . "";
            }

            if ($xoopsModuleConfig['autoapprove'] == 1) {
                redirect_header("index.php", 2, _MD_WB_RECEIVEDANDAPPROVED);
            } else {
                redirect_header("index.php", 2, _MD_WB_RECEIVED);
            }
        } else {
            redirect_header("submit.php", 2, _MD_WB_ERRORSAVINGDB);
        }
        exit();
        break;

    case 'form':
    default:
        global $xoopsUser, $_SERVER;
        if (!is_object($xoopsUser)) {
            $name = _MD_WB_GUEST;
        } else {
            $name = ucfirst($xoopsUser->getVar("uname"));
        }

        //echo "<table id=\"moduleheader\"><tr>";
        //echo "<td width=\"100%\"><span class=\"leftheader\"><a href=".XOOPS_URL.">"._MD_WB_HOME."</a> > <a href=\"".XOOPS_URL."/modules/".$xoopsModule->dirname()."/index.php\">".ucfirst($xoopsModule->name())."</a> > "._MD_WB_SUBMITART."</span></td>";
        //echo "<td width=\"100\"><span class=\"rightheader\">".$xoopsModule->name()."</span></td></tr></table>";

        //echo "<h3 class='cat'>" . sprintf(_MD_WB_SUB_SNEWNAME,ucfirst($xoopsModule->name())) . "</h3>";
        //echo "<p class='intro'>" . _MD_WB_GOODDAY . "<b>$name</b>, " . _MD_WB_SUB_SNEWNAMEDESC . "</p>";
        //$xoopsOption['template_main'] = 'wb_submit.tpl';
        $xoopsTpl->assign('send_def_to', sprintf(_MD_WB_SUB_SNEWNAME, ucfirst($xoopsModule->name())));
        $xoopsTpl->assign('send_def_g', sprintf(_MD_WB_SUB_SNEWNAME, ucfirst($xoopsModule->name())));
        $xoopsTpl->assign('wb_user_name', $name);

        $block      = 1;
        $html       = 1;
        $smiley     = 1;
        $xcodes     = 1;
        $breaks     = 1;
        $categoryID = 0;
        $notifypub  = 1;
        $term       = $termino;
        $definition = '';
        $ref        = '';
        $url        = '';

        include_once 'include/storyform.inc.php';

        $xoopsTpl->assign('modulename', $xoopsModule->dirname());

        $sform->assign($xoopsTpl);

        $xoopsTpl->assign('lang_modulename', $xoopsModule->name());
        $xoopsTpl->assign('lang_moduledirname', $xoopsModule->dirname());
        // v 1.17
        $xoopsTpl->assign('xoops_pagetitle', $myts->htmlSpecialChars($xoopsModule->name()) . ' - ' . _MD_WB_SUBMITART);
        $xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="style.css" />');

        include XOOPS_ROOT_PATH . '/footer.php';
        break;
}
