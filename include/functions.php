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

/**
 * wb_getLinkedUnameFromId()
 *
 * @param integer $userid Userid of author etc
 * @param integer $name   :  0 Use Usenamer 1 Use realname
 * @return string
 */
function wb_getLinkedUnameFromId($userid = 0, $name = 0)
{
    if (!is_numeric($userid)) {
        return $userid;
    }

    $userid = (int)($userid);
    if ($userid > 0) {
        $member_handler =& xoops_gethandler('member');
        $user           =& $member_handler->getUser($userid);

        if (is_object($user)) {
            $ts        = MyTextSanitizer::getInstance();
            $username  = $user->getVar('uname');
            $usernameu = $user->getVar('name');

            if (($name) && !empty($usernameu)) {
                $username = $user->getVar('name');
            }
            if (!empty($usernameu)) {
                $linkeduser = "$usernameu [<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $userid . "'>" . $ts->htmlSpecialChars($username) . "</a>]";
            } else {
                $linkeduser = "<a href='" . XOOPS_URL . "/userinfo.php?uid=" . $userid . "'>" . ucfirst($ts->htmlSpecialChars($username)) . "</a>";
            }

            return $linkeduser;
        }
    }

    return $GLOBALS['xoopsConfig']['anonymous'];
}

/**
 * @param $user
 */
function getuserForm($user)
{
    global $xoopsDB, $xoopsConfig;

    echo "<select name='author'>";
    echo "<option value='-1'>------</option>";
    $result = $xoopsDB->query("SELECT uid, uname FROM " . $xoopsDB->prefix("users") . " ORDER BY uname");

    while (list($uid, $uname) = $xoopsDB->fetchRow($result)) {
        if ($uid == $user) {
            $opt_selected = "selected='selected'";
        } else {
            $opt_selected = "";
        }
        echo "<option value='" . $uid . "' $opt_selected>" . $uname . "</option>";
    }
    echo "</select></div>";
}

function calculateTotals()
{
    global $xoopsUser, $xoopsDB, $xoopsModule;
    $result01 = $xoopsDB->query("SELECT categoryID, total FROM " . $xoopsDB->prefix("wbcategories") . " ");
    list($totalcategories) = $xoopsDB->getRowsNum($result01);
    while (list($categoryID, $total) = $xoopsDB->fetchRow($result01)) {
        $newcount = countByCategory($categoryID);
        $xoopsDB->queryF("UPDATE " . $xoopsDB->prefix("wbcategories") . " SET total = '$newcount' WHERE categoryID = '$categoryID'");
    }
}

/**
 * @param $c
 * @return int
 */
function countByCategory($c)
{
    global $xoopsUser, $xoopsDB, $xoopsModule;
    $count = 0;
    $sql   = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit ='0' and offline = '0' AND categoryID = '$c'");
    while ($myrow = $xoopsDB->fetchArray($sql)) {
        $count++;
    }

    return $count;
}

function countCats()
{
    global $xoopsUser, $xoopsDB, $xoopsModule;
    $cats      = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbcategories") . "");
    $totalcats = $xoopsDB->getRowsNum($cats);

    return $totalcats;
}

function countWords()
{
    global $xoopsUser, $xoopsDB, $xoopsModule;
    $pubwords       = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbentries") . " WHERE submit = '0' AND offline ='0' AND request = '0' ");
    $publishedwords = $xoopsDB->getRowsNum($pubwords);

    return $publishedwords;
}

/**
 * @return array
 */
function alphaArray()
{
    global $xoopsUser, $xoopsDB, $xoopsModule;
    $alpha = array();
    for ($a = 65; $a < (65 + 26); $a++) {
        $letterlinks             = array();
        $initial                 = chr($a);
        $sql                     = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbentries") . " WHERE init = '$initial' ");
        $howmany                 = $xoopsDB->getRowsNum($sql);
        $letterlinks['total']    = $howmany;
        $letterlinks['id']       = chr($a);
        $letterlinks['linktext'] = chr($a);

        $alpha['initial'][] = $letterlinks;
    }

    return $alpha;
}

/**
 * @param $variable
 * @return string
 */
function serviceLinks($variable)
{
    global $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
    // Functional links
    $srvlinks = "";
    if ($xoopsUser) {
        if ($xoopsUser->isAdmin()) {
            $srvlinks .= "<a href=\"admin/entry.php?op=mod&entryID=" . $variable['id'] . "\" target=\"_blank\"><img src=\"assets/images/edit.gif\" border=\"0\" alt=\"" . _MD_WB_EDITTERM . "\" width=\"15\" height=\"11\"></a>&nbsp;<a href=\"admin/entry.php?op=del&entryID=" . $variable['id'] . "\" target=\"_self\"><img src=\"assets/images/delete.gif\" border=\"0\" alt=\"" . _MD_WB_DELTERM . "\" width=\"15\" height=\"11\"></a>&nbsp;";
        }
    }
    $srvlinks .= "<a href=\"print.php?entryID=" . $variable['id'] . "\" target=\"_blank\"><img src=\"assets/images/print.gif\" border=\"0\" alt=\"" . _MD_WB_PRINTTERM . "\" width=\"15\" height=\"11\"></a>&nbsp;<a href=\"mailto:?subject=" . sprintf(_MD_WB_INTENTRY, $xoopsConfig["sitename"]) . "&amp;body=" . sprintf(_MD_WB_INTENTRYFOUND, $xoopsConfig['sitename']) . ":  " . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/entry.php?entryID=" . $variable['id'] . " \" target=\"_blank\"><img src=\"assets/images/friend.gif\" border=\"0\" alt=\"" . _MD_WB_SENDTOFRIEND . "\" width=\"15\" height=\"11\"></a>&nbsp;";

    return $srvlinks;
}

/**
 * @return string
 */
function showSearchForm()
{
    global $xoopsUser, $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsConfig;
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

    return $searchform;
}

/**
 * @param $needle
 * @param $haystack
 * @param $hlS
 * @param $hlE
 * @return string
 */
function getHTMLHighlight($needle, $haystack, $hlS, $hlE)
{
    $parts = explode(">", $haystack);
    foreach ($parts as $key => $part) {
        $pL = "";
        $pR = "";

        if (($pos = strpos($part, "<")) === false) {
            $pL = $part;
        } elseif ($pos > 0) {
            $pL = substr($part, 0, $pos);
            $pR = substr($part, $pos, strlen($part));
        }
        if ($pL != "") {
            $parts[$key] = preg_replace('|(' . quotemeta($needle) . ')|iU', $hlS . '\\1' . $hlE, $pL) . $pR;
        }
    }

    return (implode(">", $parts));
}

/*
function wb_adminMenu ( $currentoption = 0, $breadcrumb = '' )
{
    global $xoopsModule, $xoopsConfig, $xoopsModuleConfig;
    $tblColors = Array();
    $tblColors[0]=$tblColors[1]=$tblColors[2]=$tblColors[3]=$tblColors[4]=$tblColors[5]=$tblColors[6]=$tblColors[7]=$tblColors[8]='#DDE';
    $tblColors[$currentoption] = '#FFF';
    if (file_exists(XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/language/'.$xoopsConfig['language'].'/modinfo.php')) {
        include_once '../language/'.$xoopsConfig['language'].'/modinfo.php';
    }
    else {
        include_once '../language/english/modinfo.php';
    }
    echo "<div style=\"font-size: 10px; text-align: right; color: #2F5376; margin: 0 0 8px 0; padding: 2px 6px; line-height: 18px; border: 1px solid #e7e7e7; \"><b>".$xoopsModule->name()._AM_WB_MODADMIN."</b> ".$breadcrumb."</div>";
    echo "<div id=\"navcontainer\"><ul style=\"padding: 3px 0; margin-left: 0; font: bold 12px Verdana, sans-serif; \">";
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"index.php\" style=\"padding: 3px 0.5em; margin-left: 0; border: 1px solid #778; background: ".$tblColors[0]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_INDEX."</a></li>";

    if ($xoopsModuleConfig['multicats'] == 1)
        {
        echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"category.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[1]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_CATS."</a></li>";
        }
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"entry.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[2]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_ENTRIES."</a></li>";
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"submissions.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[3]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_SUBMITS."</a></li>";
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"myblocksadmin.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[4]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_BLOCKS."</a></li>";
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=".$xoopsModule -> getVar( 'mid' )."\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[5]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_OPTS."</a></li>";
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"../index.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[6]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_GOMOD."</a></li>";
    //mondarse
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"importdictionary091.php\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[6]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_IMPORT."</a></li>";
    //mondarse
    echo "<li style=\"list-style: none; margin: 0; display: inline; \"><a href=\"../help/index.html\" target=\"_blank\" style=\"padding: 3px 0.5em; margin-left: 3px; border: 1px solid #778; background: ".$tblColors[7]."; text-decoration: none; white-space: nowrap; \">"._AM_WB_HELP."</a></li></ul></div>";
    }
*/
// v.1.17 thanks to smartfactory for this menu
/*
function wb_adminMenu ($currentoption = 0, $breadcrumb = '' ) {

    include_once XOOPS_ROOT_PATH . '/class/template.php';
    global $xoopsDB, $xoopsModule, $xoopsConfig;
    if (file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname(). "/language/" . $xoopsConfig['language'] . "/modinfo.php")) {
        include_once XOOPS_ROOT_PATH.'/modules/wordbook/' . 'language/' . $xoopsConfig['language'] . '/modinfo.php';
    } else {
        include_once XOOPS_ROOT_PATH.'/modules/wordbook/' . 'language/english/modinfo.php';
    }
    if (file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname() . "/language/" . $xoopsConfig['language'] . "/admin.php")) {
        include_once XOOPS_ROOT_PATH.'/modules/wordbook/' . 'language/' . $xoopsConfig['language'] . '/admin.php';
    } else {
        include_once XOOPS_ROOT_PATH.'/modules/wordbook/' . 'language/english/admin.php';
    }

    include 'menu.php';

    $tpl =& new XoopsTpl();
    $tpl->assign( array(
    'headermenu'    => $headermenu,
    'adminmenu'     => $adminmenu,
    'current'       => $currentoption,
    'breadcrumb'    => $breadcrumb,
    'headermenucount' => count($headermenu)
    ) );
    $tpl->display( 'db:wb_adminmenu.html' );
    echo "<br />\n";
}
*/

/*
 * Create pagetitles @package Wordbook
 * modified; orig. @author, @copyright Herve Thouzard
 */
/**
 * @param string $article
 * @param string $topic
 */
function wb_create_pagetitle($article = '', $topic = '')
{
    global $xoopsModule, $xoopsTpl;
    $myts    = MyTextSanitizer::getInstance();
    $content = '';
    if (!empty($article)) {
        $content .= strip_tags($myts->displayTarea($article));
    }
    if (!empty($topic)) {
        if (xoops_trim($content) != '') {
            $content .= ' - ' . strip_tags($myts->displayTarea($topic));
        } else {
            $content .= strip_tags($myts->displayTarea($topic));// htmlSpecialChars
        }
    }
    if (is_object($xoopsModule) && xoops_trim($xoopsModule->name()) != '') {
        if (xoops_trim($content) != '') {
            $content .= ' - ' . strip_tags($myts->displayTarea($xoopsModule->name()));
        } else {
            $content .= strip_tags($myts->displayTarea($xoopsModule->name()));
        }
    }
    if ($content != '') {
        $xoopsTpl->assign('xoops_pagetitle', $content);
    }
}

/*
 * Clean definitions and term off HTML-tags
 * Package wordbook
 */
/**
 * @param $document
 * @return mixed
 */
function wb_html2text($document)
{
    // PHP Manual:: function preg_replace $document should contain an HTML document.
    // This will remove HTML tags, javascript sections and white space. It will also
    // convert some common HTML entities to their text equivalent.

    $search = array("'<script[^>]*?>.*?</script>'si",  // Strip out javascript
                    "'<[\/\!]*?[^<>]*?>'si",          // Strip out HTML tags
                    "'([\r\n])[\s]+'",                // Strip out white space
                    "'&(quot|#34);'i",                // Replace HTML entities
                    "'&(amp|#38);'i",
                    "'&(lt|#60);'i",
                    "'&(gt|#62);'i",
                    "'&(nbsp|#160);'i",
                    "'&(iexcl|#161);'i",
                    "'&(cent|#162);'i",
                    "'&(pound|#163);'i",
                    "'&(copy|#169);'i");

    $replace = array("",
                     "",
                     "\\1",
                     "\"",
                     "&",
                     "<",
                     ">",
                     " ",
                     chr(161),
                     chr(162),
                     chr(163),
                     chr(169));

    $text = preg_replace($search, $replace, $document);

    $text = preg_replace_callback("&#(\d+)&", create_function('$matches', "return chr(\$matches[1]);"), $text);

    return $text;
}

/* Accept apostrophy sign (') in terms, definitions, categories , descriptions and names
 * @package wordbook
 * @author , @copyright yerres 2006
 */
/**
 * @param $document
 * @return mixed
 */
function wb_accent2text($document)
{
    $search = array(
        "'&#39;'i",
        "'\''i");

    $replace = array(//"&#44;",
                     "�",   // set here the replacement for ' (�,`,�,...)
                     "�");

    $text = preg_replace($search, $replace, $document);

    return $text;
}
