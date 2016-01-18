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
 * @param $options
 * @return array
 */

function b_entries_new_show($options)
{
    global $xoopsDB, $xoopsModule, $xoopsModuleConfig, $xoopsUser;
    $myts = &MyTextSanitizer:: getInstance();

    $words      = $xoopsDB->query("SELECT * FROM " . $xoopsDB->prefix("wbentries") . "");
    $totalwords = $xoopsDB->getRowsNum($words);

    $block  = array();
    $sql    = "SELECT entryID, term, datesub FROM " . $xoopsDB->prefix("wbentries") . " WHERE datesub < " . time() . " AND datesub > 0 AND submit = '0' AND offline = '0' ORDER BY " . $options[0] . " DESC";
    $result = $xoopsDB->query($sql, $options[1], 0);

    $hModule     =& xoops_gethandler('module');
    $hModConfig  =& xoops_gethandler('config');
    $wbModule    =& $hModule->getByDirname('wordbook');
    $module_id   = $wbModule->getVar('mid');
    $module_name = $wbModule->getVar('dirname');
    $wbConfig    =& $hModConfig->getConfigsByCat(0, $wbModule->getVar('mid'));

    if ($totalwords > 0) {
        // If there are definitions

        while (list($entryID, $term, $datesub) = $xoopsDB->fetchRow($result)) {
            $newentries             = array();
            $xoopsModule            =& XoopsModule::getByDirname("wordbook");
            $linktext               = ucfirst($myts->htmlSpecialChars($term));
            $newentries['dir']      = $xoopsModule->dirname();
            $newentries['linktext'] = $linktext;
            $newentries['id']       = $entryID;
            $newentries['date']     = formatTimestamp($datesub, $wbConfig['dateformat']);

            $block['newstuff'][] = $newentries;
        }
    }

    return $block;
}

/**
 * @param $options
 * @return string
 */
function b_entries_new_edit($options)
{
    $form = "" . _MB_WB_ORDER . "&nbsp;<select name='options[]'>";

    $form .= "<option value='datesub'";
    if ($options[0] === "datesub") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_WB_DATE . "</option>\n";

    $form .= "<option value='counter'";
    if ($options[0] === "counter") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_WB_HITS . "</option>\n";

    $form .= "<option value='weight'";
    if ($options[0] === "weight") {
        $form .= " selected='selected'";
    }
    $form .= ">" . _MB_WB_WEIGHT . "</option>\n";

    $form .= "</select>\n";
    $form .= "&nbsp;" . _MB_WB_DISP . "&nbsp;<input type='text' name='options[]' value='" . $options[1] . "' />&nbsp;" . _MB_WB_TERMS . "";

    return $form;
}
