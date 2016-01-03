<?php
// $Id: data.inc.php,v 1.3 2005/10/22 08:37:48 ohwada Exp $

// 2005-10-13 K.OHWADA
// term, definition, category, counter

//================================================================
// What's New Module
// get new entries from the module  <http://dev.xoops.org/modules/xfmod/project/?wordbook>
// for wordbook 1.17
// 2006-11-18 K.YERRES
//================================================================

/**
 * @param int $limit
 * @param int $offset
 * @return array
 */
function wordbook_new($limit = 0, $offset = 0)
{
    global $xoopsDB;
    $myts = &MyTextSanitizer:: getInstance();

    $URL_MOD = XOOPS_URL . "/modules/wordbook";

    $sql = "SELECT e.entryID, e.term, e.datesub, e.definition, e.html, e.smiley, e.xcodes, e.breaks, e.uid, e.counter, e.categoryID, c.name FROM " . $xoopsDB->prefix('wbentries') . " e, " . $xoopsDB->prefix('wbcategories') . " c WHERE e.categoryID = c.categoryID AND datesub < " . time() . " AND datesub > 0 AND submit = '0' AND offline = '0' ORDER BY e.entryID DESC";

    $result = $xoopsDB->query($sql, $limit, $offset);

    $i   = 0;
    $ret = array();

    while ($row = $xoopsDB->fetchArray($result)) {
        $id                  = $row['entryID'];
        $ret[$i]['link']     = $URL_MOD . "/entry.php?entryID=" . $id;
        $ret[$i]['cat_link'] = $URL_MOD . "/category.php?categoryID=" . $row['categoryID'];

        $ret[$i]['title']    = $row['term'];
        $ret[$i]['cat_name'] = $row['name'];

        $ret[$i]['time'] = $row['datesub'];
        $ret[$i]['uid']  = $row['uid'];
        $ret[$i]['hits'] = $row['counter'];
        $ret[$i]['id']   = $id;

        $html   = 0;
        $smiley = 0;
        $xcodes = 0;
        $image  = 1;
        $br     = 0;

        if ($row['html']) {
            $html = 1;
        }
        if ($row['smiley']) {
            $smiley = 1;
        }
        if ($row['xcodes']) {
            $xcodes = 1;
        }
        if ($row['breaks']) {
            $br = 1;
        }

        $ret[$i]['description'] = $myts->displayTarea($row['definition'], $html, $smiley, $xcodes, $image, $br);

        $i++;
    }

    return $ret;
}
