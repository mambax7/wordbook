<?php
// comment callback functions

/**
 * @param $entry_ID
 * @param $total_num
 */
function wordbook_com_update($entry_ID, $total_num)
{
    $db  =& XoopsDatabaseFactory::getDatabaseConnection();
    $sql = 'UPDATE ' . $db->prefix('wbentries') . ' SET comments = ' . $total_num . ' WHERE entryID = ' . $entry_ID;
    $db->query($sql);
}

/**
 * @param $comment
 */
function wordbook_com_approve(&$comment)
{
    // notification mail here
}
